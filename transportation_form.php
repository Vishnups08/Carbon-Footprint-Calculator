<?php
// Include header
include_once 'includes/header.php';

// Require login
requireLogin();

// Check if energy data exists
if (!isset($_SESSION['energy_data'])) {
    header("Location: energy_form.php");
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Store form data in session for later use
    $_SESSION['transportation_data'] = [
        'vehicle_type' => $_POST['vehicle_type'],
        'fuel_type' => $_POST['fuel_type'],
        'distance_miles' => floatval($_POST['distance_miles'])
    ];
    
    // Redirect to waste form
    header("Location: waste_form.php");
    exit;
}
?>

<div class="form-container">
    <h1 class="mb-4">Transportation</h1>
    
    <div class="progress mb-4">
        <div class="progress-bar bg-success" role="progressbar" style="width: 66%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100">Step 2 of 3</div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3>Enter Your Transportation Data</h3>
        </div>
        <div class="card-body">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-3">
                    <label for="vehicle_type" class="form-label">Vehicle Type</label>
                    <select class="form-select" id="vehicle_type" name="vehicle_type" required>
                        <option value="">Select vehicle type</option>
                        <option value="car">Car</option>
                        <option value="motorcycle">Two-Wheeler</option>
                        <option value="auto">Auto-Rickshaw</option>
                        <option value="bus">Bus</option>
                        <option value="train">Local Train/Metro</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="fuel_type" class="form-label">Fuel Type</label>
                    <select class="form-select" id="fuel_type" name="fuel_type" required>
                        <option value="">Select fuel type</option>
                        <option value="petrol">Petrol</option>
                        <option value="diesel">Diesel</option>
                        <option value="cng">CNG</option>
                        <option value="electric">Electric</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="distance_miles" class="form-label">Average Distance Traveled (kilometers per month)</label>
                    <input type="number" step="0.01" min="0" class="form-control" id="distance_miles" name="distance_miles" required>
                    <div class="form-text">Average Indian urban commuter travels about 500-600 km per month.</div>
                </div>
                
                <button type="submit" class="btn btn-primary">Next: Waste</button>
            </form>
        </div>
    </div>
</div>

<?php
// Include footer
include_once 'includes/footer.php';
?> 