<?php
// Start session
session_start();

// Include necessary files
include_once 'config/db_config.php';
include_once 'includes/header.php';

$error = '';
$success = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $email = trim($_POST['email']);

    // Check if all fields are filled
    if (empty($username) || empty($password) || empty($confirm_password) || empty($email)) {
        $error = "Please fill all fields.";
    }
    // Check if passwords match
    elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    }
    // Check password length
    elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    }
    // Check email format
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }
    else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "Username or Email already exists.";
        } else {
            // Hash the password before storing it
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into the database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                $success = "Registration successful! <a href='login.php'>Login here</a>";
            } else {
                $error = "Error registering user. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header">
                <h2>Register</h2>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
                <div class="mt-3">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include_once 'includes/footer.php';
?>
