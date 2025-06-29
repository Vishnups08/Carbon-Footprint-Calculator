<?php
// Include header
include_once 'includes/header.php';
//include_once 'api/ibm_api.php';

// Require login
requireLogin();

// Get user's carbon footprint history
//$history = getCarbonFootprintHistory($_SESSION['user_id']);

// Get latest footprint data
$latest = !empty($history) ? $history[0] : null;

// Prepare data for historical chart
$dates = [];
$emissions = [];
//foreach ($history as $entry) {
//   $dates[] = date('M d', strtotime($entry['calculation_date']));
//    $emissions[] = $entry['total_emissions'];
//}
// Reverse arrays to show chronological order
$dates = array_reverse($dates);
$emissions = array_reverse($emissions);

// Average US carbon footprint per person (in kg CO2e)
$avg_emissions = 16000; // This is a placeholder value
?>

<h1 class="mb-4">Your Carbon Footprint Dashboard</h1>

<?php if (empty($history)): ?>
    <div class="alert alert-info">
        <h4>Welcome to your Carbon Footprint Dashboard!</h4>
        <p>You haven't calculated your carbon footprint yet. Start by entering your energy consumption, transportation habits, and waste generation data.</p>
        <a href="energy_form.php" class="btn btn-primary">Start Calculation</a>
    </div>
<?php else: ?>
    <div class="dashboard-summary">
        <div class="summary-box energy-box">
            <h3>Energy Emissions</h3>
            <div class="value"><?php echo formatNumber($latest['energy_emissions']); ?></div>
            <div class="unit">kg CO2e</div>
        </div>
        <div class="summary-box transportation-box">
            <h3>Transportation Emissions</h3>
            <div class="value"><?php echo formatNumber($latest['transportation_emissions']); ?></div>
            <div class="unit">kg CO2e</div>
        </div>
        <div class="summary-box waste-box">
            <h3>Waste Emissions</h3>
            <div class="value"><?php echo formatNumber($latest['waste_emissions']); ?></div>
            <div class="unit">kg CO2e</div>
        </div>
        <div class="summary-box total-box">
            <h3>Total Carbon Footprint</h3>
            <div class="value"><?php echo formatNumber($latest['total_emissions']); ?></div>
            <div class="unit">kg CO2e</div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Emissions Breakdown</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="emissionsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Comparison</h3>
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
                    <h3>Historical Emissions</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="historicalChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Eco Tips</h3>
                </div>
                <div class="card-body">
                    <div class="eco-tip">
                        <h4>Energy Saving Tip</h4>
                        <p>Switch to LED bulbs and save up to 80% on lighting energy costs. They also last 25 times longer than incandescent bulbs.</p>
                    </div>
                    <div class="eco-tip">
                        <h4>Transportation Tip</h4>
                        <p>Consider carpooling or using public transportation. Each gallon of fuel not burned prevents 20 pounds of CO2 from entering the atmosphere.</p>
                    </div>
                    <div class="eco-tip">
                        <h4>Waste Reduction Tip</h4>
                        <p>Composting food scraps can reduce your waste emissions and create nutrient-rich soil for your garden.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Create emissions breakdown chart
            createEmissionsChart('emissionsChart', 
                <?php echo $latest['energy_emissions']; ?>, 
                <?php echo $latest['transportation_emissions']; ?>, 
                <?php echo $latest['waste_emissions']; ?>
            );
            
            // Create historical emissions chart
            createHistoricalChart('historicalChart', 
                <?php echo json_encode($dates); ?>, 
                <?php echo json_encode($emissions); ?>
            );
            
            // Create comparison chart
            createComparisonChart('comparisonChart', 
                <?php echo $latest['total_emissions']; ?>, 
                <?php echo $avg_emissions; ?>
            );
        });
    </script>
<?php endif; ?>

<?php
// Include footer
include_once 'includes/footer.php';
?> 