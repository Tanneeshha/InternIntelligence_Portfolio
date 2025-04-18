<?php
// Database credentials
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'portfolio_db');

// Attempt to connect to MySQL database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to ensure proper handling of special characters
$conn->set_charset("utf8mb4");

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to generate JSON response
function send_json_response($status, $message, $data = null) {
    header('Content-Type: application/json');
    $response = [
        'status' => $status,
        'message' => $message
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit;
}

// Function to verify if user is authenticated
function is_authenticated() {
    session_start();
    return isset($_SESSION['user_id']);
}

// Function to require authentication
function require_authentication() {
    if (!is_authenticated()) {
        send_json_response('error', 'Authentication required', null);
    }
}
?>