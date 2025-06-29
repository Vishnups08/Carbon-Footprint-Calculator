<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
include_once 'includes/header.php';
include_once 'includes/functions.php';
include_once 'config/db_config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Remove login requirement
// requireLogin();

$latest = null;

// Check for calculation results
if (isLoggedIn() && function_exists('getCarbonFootprintHistory')) {
    // For logged-in users, try to get data from database
    $history = getCarbonFootprintHistory($_SESSION['user_id']);
    if (!empty($history)) {
        $latest = $history[0];
    }
}

// If no database results, check session data
if (empty($latest) && isset($_SESSION['calculation_results'])) {
    $latest = $_SESSION['calculation_results'];
}

// If still no results, show error message
if (empty($latest)) {
    echo '<div class="alert alert-danger">
            <h4>No Results Found</h4>
            <p>Could not find calculation results. Please ensure you have completed all steps:</p>
            <ol>
                <li>Energy consumption data</li>
                <li>Transportation data</li>
                <li>Waste generation data</li>
            </ol>
            <a href="energy_form.php" class="btn btn-primary">Start New Calculation</a>
          </div>';
    include_once 'includes/footer.php';
    exit;
}

// Calculate total emissions
$total = $latest['total_emissions'];

// Calculate comparison with average American (16000 kg CO2e per year)
$avg_american = 16000;
$comparison = round(($total / $avg_american) * 100);

// Calculate percentage breakdown
$energy_percent = ($total > 0) ? round(($latest['energy_emissions'] / $total) * 100) : 0;
$transportation_percent = ($total > 0) ? round(($latest['transportation_emissions'] / $total) * 100) : 0;
$waste_percent = ($total > 0) ? round(($latest['waste_emissions'] / $total) * 100) : 0;

// Add this after storing calculation results in session
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate form data
    if (!isset($_POST['waste_type']) || !isset($_POST['amount_kg'])) {
        die('Error: Missing form data');
    }
    
    // Validate energy data
    if (!isset($_SESSION['energy_data'])) {
        die('Error: Missing energy data');
    }
    
    // Validate transportation data
    if (!isset($_SESSION['transportation_data'])) {
        die('Error: Missing transportation data');
    }
    
    // ... existing calculation code ...

    // Debug session data
    $_SESSION['calculation_results'] = [
        'energy_emissions' => $energy_emissions,
        'transportation_emissions' => $transportation_emissions,
        'waste_emissions' => $waste_emissions,
        'total_emissions' => $energy_emissions + $transportation_emissions + $waste_emissions,
        'calculation_date' => date('Y-m-d H:i:s')
    ];

    // Add debug logging
    error_log('Calculation Results: ' . print_r($_SESSION['calculation_results'], true));

    // Ensure session data exists before redirect
    if (!isset($_SESSION['calculation_results'])) {
        die('Error: Calculation results not stored in session');
    }

    // Redirect to results page
    header("Location: results.php");
    exit;
}
?>

<div class="row">
    <div class="col-md-12 text-center mb-4">
        <h1>Your Carbon Footprint Results</h1>
        <p class="lead">Calculation completed on <?php echo formatDate($latest['calculation_date']); ?></p>
    </div>
</div>

<div class="result-box">
    <h3>Total Carbon Footprint</h3>
    <div class="result-value"><?php echo formatNumber($total); ?> <span class="result-unit">kg CO2e</span></div>
    <p>This is approximately <?php echo $comparison; ?>% of the average American's carbon footprint.</p>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3>Emissions Breakdown</h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="result-category energy-category">
                            <h4>Energy Emissions</h4>
                            <div class="result-value"><?php echo formatNumber($latest['energy_emissions']); ?> <span class="result-unit">kg CO2e</span></div>
                            <div class="result-percent"><?php echo $energy_percent; ?>% of your total</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="result-category transportation-category">
                            <h4>Transportation Emissions</h4>
                            <div class="result-value"><?php echo formatNumber($latest['transportation_emissions']); ?> <span class="result-unit">kg CO2e</span></div>
                            <div class="result-percent"><?php echo $transportation_percent; ?>% of your total</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="result-category waste-category">
                            <h4>Waste Emissions</h4>
                            <div class="result-value"><?php echo formatNumber($latest['waste_emissions']); ?> <span class="result-unit">kg CO2e</span></div>
                            <div class="result-percent"><?php echo $waste_percent; ?>% of your total</div>
                        </div>
                    </div>
                </div>
                <div class="chart-container-centered">
                    <canvas id="emissionsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3>Monthly Trend</h3>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3>Comparison with Averages</h3>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="comparisonChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3>Impact Level</h3>
            </div>
            <div class="card-body">
                <div class="gauge-container">
                    <canvas id="gaugeChart"></canvas>
                </div>
                <div class="text-center mt-3">
                    <div class="impact-scale">
                        <span class="low">Low Impact</span>
                        <span class="medium">Medium Impact</span>
                        <span class="high">High Impact</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3>Recommendations</h3>
            </div>
            <div class="card-body">
                <h4>Energy Saving Tips</h4>
                <ul>
                    <li>Switch to LED bulbs to reduce electricity consumption.</li>
                    <li>Improve home insulation to reduce heating and cooling needs.</li>
                    <li>Use a programmable thermostat to optimize energy use.</li>
                </ul>
                
                <h4>Transportation Tips</h4>
                <ul>
                    <li>Consider carpooling or using public transportation when possible.</li>
                    <li>Maintain your vehicle properly for optimal fuel efficiency.</li>
                    <li>Combine errands to reduce total miles driven.</li>
                </ul>
                
                <h4>Waste Reduction Tips</h4>
                <ul>
                    <li>Recycle paper, plastic, glass, and metal whenever possible.</li>
                    <li>Compost food scraps and yard waste.</li>
                    <li>Reduce single-use items and packaging.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php if (!isLoggedIn()): ?>
<div class="row mt-4">
    <div class="col-md-12">
        <div class="alert alert-info">
            <h4>Save Your Results</h4>
            <p>Create an account to save your results and track your carbon footprint over time.</p>
            <a href="register.php" class="btn btn-primary">Register Now</a>
            <a href="login.php" class="btn btn-outline-primary">Login</a>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row mt-4 mb-4">
    <div class="col-md-12 text-center">
        <a href="dashboard.php" class="btn btn-primary">Return to Dashboard</a>
        <a href="energy_form.php" class="btn btn-success">Calculate Again</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Create emissions breakdown chart
    var ctx = document.getElementById('emissionsChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Energy', 'Transportation', 'Waste'],
            datasets: [{
                data: [
                    <?php echo $latest['energy_emissions']; ?>,
                    <?php echo $latest['transportation_emissions']; ?>,
                    <?php echo $latest['waste_emissions']; ?>
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(255, 206, 86, 0.8)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Carbon Emissions by Category'
                }
            }
        }
    });

    // Create trend chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: ['Energy', 'Transportation', 'Waste'],
            datasets: [{
                label: 'Your Emissions',
                data: [
                    <?php echo $latest['energy_emissions']; ?>,
                    <?php echo $latest['transportation_emissions']; ?>,
                    <?php echo $latest['waste_emissions']; ?>
                ],
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Emissions by Category'
                }
            }
        }
    });

    // Create comparison chart
    const compCtx = document.getElementById('comparisonChart').getContext('2d');
    new Chart(compCtx, {
        type: 'bar',
        data: {
            labels: ['Your Footprint', 'Indian Average', 'Global Average'],
            datasets: [{
                label: 'Carbon Footprint (kg CO2e)',
                data: [
                    <?php echo $total; ?>,
                    1800, // Indian average (approximate)
                    4000  // Global average (approximate)
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(255, 99, 132, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Comparison with Averages'
                }
            }
        }
    });

    // Create gauge chart for Impact Level
    const gaugeCtx = document.getElementById('gaugeChart').getContext('2d');
    const percentage = <?php echo $comparison; ?>; // Get the comparison percentage

    new Chart(gaugeCtx, {
        type: 'doughnut',
        data: {
            labels: ['Impact', 'Remaining'],
            datasets: [{
                data: [percentage, 100 - Math.min(percentage, 100)],
                backgroundColor: [
                    getImpactColor(percentage),
                    'rgba(200, 200, 200, 0.2)'
                ],
                borderWidth: 0,
                circumference: 180,
                rotation: 270
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: false
                },
                title: {
                    display: true,
                    text: `Impact Level: ${getImpactText(percentage)}`,
                    font: {
                        size: 16
                    }
                }
            },
            layout: {
                padding: {
                    bottom: 30
                }
            }
        }
    });

    // Add center text to gauge
    const centerConfig = {
        id: 'centerText',
        afterDatasetsDraw(chart, args, pluginOptions) {
            const { ctx, data, chartArea: { top, bottom, left, right, width, height } } = chart;

            ctx.save();
            const xCoor = chart.getDatasetMeta(0).data[0].x;
            const yCoor = chart.getDatasetMeta(0).data[0].y;
            
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.font = '16px Arial';
            ctx.fillStyle = getImpactColor(percentage);
            ctx.fillText(`${Math.round(percentage)}%`, xCoor, yCoor + 20);
        }
    };

    Chart.register(centerConfig);
});

// Helper function to determine impact color
function getImpactColor(percentage) {
    if (percentage <= 50) {
        return 'rgba(75, 192, 192, 0.8)'; // Green - Low impact
    } else if (percentage <= 100) {
        return 'rgba(255, 206, 86, 0.8)'; // Yellow - Medium impact
    } else {
        return 'rgba(255, 99, 132, 0.8)'; // Red - High impact
    }
}

// Helper function to get impact text
function getImpactText(percentage) {
    if (percentage <= 50) {
        return 'Low Impact';
    } else if (percentage <= 100) {
        return 'Medium Impact';
    } else {
        return 'High Impact';
    }
}
</script>

<?php
// Include footer
include_once 'includes/footer.php';
?> 