<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 * 
 * @return bool True if logged in, false otherwise
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Redirect to login page if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Format number with commas and decimal places
 * 
 * @param float $number Number to format
 * @param int $decimals Number of decimal places
 * @return string Formatted number
 */
function formatNumber($number, $decimals = 2) {
    return number_format($number, $decimals);
}

/**
 * Convert date to readable format
 * 
 * @param string $date Date in MySQL format
 * @return string Formatted date
 */
function formatDate($date) {
    return date("F j, Y, g:i a", strtotime($date));
}

/**
 * Display alert message
 * 
 * @param string $message Message to display
 * @param string $type Alert type (success, danger, warning, info)
 * @return string HTML for alert
 */
function alert($message, $type = 'info') {
    return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
                ' . $message . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
}

/**
 * Get carbon footprint history for a user
 * 
 * @param int $user_id User ID
 * @return array Array of carbon footprint records
 */
function getCarbonFootprintHistory($user_id) {
    global $conn;
    
    $history = array();
    
    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT * FROM carbon_footprint WHERE user_id = ? ORDER BY calculation_date DESC");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $history[] = $row;
        }
        
        $stmt->close();
    }
    
    return $history;
}
?> 