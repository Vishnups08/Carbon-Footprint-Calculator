<?php
// Include header
include_once 'includes/header.php';
require_once 'api/ibm_api.php';

// Remove login requirement
// requireLogin();

// Check if transportation data exists
if (!isset($_SESSION['transportation_data'])) {
    header("Location: transportation_form.php");
    exit;
}

// Check if energy data exists
if (!isset($_SESSION['energy_data'])) {
    header("Location: energy_form.php");
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Store form data in session
    $_SESSION['waste_data'] = [
        'waste_type' => $_POST['waste_type'],
        'amount_kg' => floatval($_POST['amount_kg'])
    ];
    
    // Calculate emissions using IBM API
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    
    // Energy emissions using IBM API
    $energy_emissions = calculateEnergyEmissionsIBM($_SESSION['energy_data']);
    
    // Transportation emissions using IBM API
    $transportation_emissions = calculateTransportationEmissionsIBM($_SESSION['transportation_data']);
    
    // Waste emissions using IBM API
    $waste_emissions = calculateWasteEmissionsIBM($_SESSION['waste_data']);
    
    // Store calculation results in session for all users
    $_SESSION['calculation_results'] = [
        'energy_emissions' => $energy_emissions,
        'transportation_emissions' => $transportation_emissions,
        'waste_emissions' => $waste_emissions,
        'total_emissions' => $energy_emissions + $transportation_emissions + $waste_emissions,
        'calculation_date' => date('Y-m-d H:i:s'),
        'calculation_method' => 'IBM_API'
    ];
    
    // If user is logged in, save to database
    if (isLoggedIn()) {
        global $conn;
        
        $stmt = $conn->prepare("INSERT INTO carbon_footprint (user_id, energy_emissions, transportation_emissions, waste_emissions, total_emissions) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("idddd", 
                $_SESSION['user_id'],
                $energy_emissions,
                $transportation_emissions,
                $waste_emissions,
                $energy_emissions + $transportation_emissions + $waste_emissions
            );
            $stmt->execute();
            $stmt->close();
        }
    }
    
    // Clear form data from session
    unset($_SESSION['energy_data']);
    unset($_SESSION['transportation_data']);
    unset($_SESSION['waste_data']);
    
    // Redirect to results page
    header("Location: results.php");
    exit;
}
?>

<div class="form-container">
    <h1 class="mb-4">Waste Generation</h1>
    
    <div class="progress mb-4">
        <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Step 3 of 3</div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3>Enter Your Waste Data</h3>
        </div>
        <div class="card-body">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-3">
                    <label for="waste_type" class="form-label">Primary Waste Disposal Method</label>
                    <select class="form-select" id="waste_type" name="waste_type" required>
                        <option value="">Select waste disposal method</option>
                        <option value="landfill">Municipal Waste Collection</option>
                        <option value="recycled">Segregated Recycling</option>
                        <option value="composted">Home Composting</option>
                        <option value="incinerated">Incineration</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="amount_kg" class="form-label">Average Waste Generated (kg per month)</label>
                    <input type="number" step="0.01" min="0" class="form-control" id="amount_kg" name="amount_kg" required>
                    <div class="form-text">Average Indian urban resident generates about 15-20 kg of waste per month.</div>
                </div>
                
                <button type="submit" class="btn btn-primary">Calculate Carbon Footprint</button>
            </form>
        </div>
    </div>
</div>

<?php
// Include footer
include_once 'includes/footer.php';
?> 