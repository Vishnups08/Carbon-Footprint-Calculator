<?php
// Start session
session_start();

// Include necessary files
include_once 'includes/header.php';
include_once 'config/db_config.php';

$error = '';

// Check if user is already logged in (Remove if `isLoggedIn()` is not defined)
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if all fields are filled
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password";
    } else {
        // Prepare SQL statement to fetch user data
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Check if a user was found
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Regenerate session ID for security
                session_regenerate_id(true);
                
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = htmlspecialchars($user['username']);

                // Redirect to dashboard
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
        
        $stmt->close();
    }
}
?>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header">
                <h2>Login</h2>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                <div class="mt-3">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include_once 'includes/footer.php';
?>
