<?php
// Include header
include_once 'includes/header.php';

// Require login
requireLogin();

// Process form submission
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Store form data in session for later use
    $_SESSION['energy_data'] = [
        'electricity_kwh' => floatval($_POST['electricity_kwh']),
        'natural_gas_therms' => floatval($_POST['natural_gas_therms']),
        'fuel_oil_gallons' => floatval($_POST['fuel_oil_gallons']),
        'propane_gallons' => floatval($_POST['propane_gallons'])
    ];
    
    // Redirect to transportation form
    header("Location: transportation_form.php");
    exit;
}
?>

<div class="form-container">
    <h1 class="mb-4">Energy Consumption</h1>
    
    <div class="progress mb-4">
        <div class="progress-bar bg-success" role="progressbar" style="width: 33%" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100">Step 1 of 3</div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3>Enter Your Energy Consumption Data</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <?php echo alert($error, 'danger'); ?>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <?php echo alert($success, 'success'); ?>
            <?php endif; ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-3">
                    <label for="electricity_kwh" class="form-label">Electricity (kWh per month)</label>
                    <input type="number" step="0.01" min="0" class="form-control" id="electricity_kwh" name="electricity_kwh" required>
                    <div class="form-text">Average Indian household uses about 250 kWh per month.</div>
                </div>
                
                <div class="mb-3">
                    <label for="natural_gas_therms" class="form-label">LPG (cylinders per month)</label>
                    <input type="number" step="0.01" min="0" class="form-control" id="natural_gas_therms" name="natural_gas_therms" required>
                    <div class="form-text">Average Indian household uses about 1 LPG cylinder per month (14.2 kg).</div>
                </div>
                
                <div class="mb-3">
                    <label for="fuel_oil_gallons" class="form-label">Kerosene (litres per month)</label>
                    <input type="number" step="0.01" min="0" class="form-control" id="fuel_oil_gallons" name="fuel_oil_gallons" required>
                    <div class="form-text">If you don't use kerosene, enter 0.</div>
                </div>
                
                <button type="submit" class="btn btn-primary">Next: Transportation</button>
            </form>
        </div>
    </div>
</div>

<?php
// Include footer
include_once 'includes/footer.php';
?> 