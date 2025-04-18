<?php
// Include database configuration
require_once 'config.php';

// Start session
session_start();

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

// Login endpoint
if ($method === 'POST' && isset($_GET['action']) && $_GET['action'] === 'login') {
    // Get and sanitize input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['username']) || !isset($input['password'])) {
        send_json_response('error', 'Username and password are required');
    }
    
    $username = sanitize_input($input['username']);
    $password = $input['password'];
    
    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, create session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Update last login time
            $update_stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $update_stmt->bind_param("i", $user['id']);
            $update_stmt->execute();
            
            send_json_response('success', 'Login successful', ['username' => $user['username']]);
        } else {
            send_json_response('error', 'Invalid password');
        }
    } else {
        send_json_response('error', 'User not found');
    }
}

// Logout endpoint
else if ($method === 'POST' && isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Destroy session
    session_unset();
    session_destroy();
    
    send_json_response('success', 'Logout successful');
}

// Check authentication status endpoint
else if ($method === 'GET' && isset($_GET['action']) && $_GET['action'] === 'status') {
    if (is_authenticated()) {
        send_json_response('success', 'User is authenticated', ['username' => $_SESSION['username']]);
    } else {
        send_json_response('error', 'User is not authenticated');
    }
}

// Change password endpoint
else if ($method === 'POST' && isset($_GET['action']) && $_GET['action'] === 'change-password') {
    // Require authentication
    require_authentication();
    
    // Get and sanitize input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['current_password']) || !isset($input['new_password'])) {
        send_json_response('error', 'Current password and new password are required');
    }
    
    $current_password = $input['current_password'];
    $new_password = $input['new_password'];
    
    // Validate new password
    if (strlen($new_password) < 8) {
        send_json_response('error', 'New password must be at least 8 characters long');
    }
    
    // Get user's current password hash
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    // Verify current password
    if (!password_verify($current_password, $user['password'])) {
        send_json_response('error', 'Current password is incorrect');
    }
    
    // Hash new password
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update password
    $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $update_stmt->bind_param("si", $new_password_hash, $_SESSION['user_id']);
    
    if ($update_stmt->execute()) {
        send_json_response('success', 'Password updated successfully');
    } else {
        send_json_response('error', 'Failed to update password');
    }
}

// Invalid request
else {
    send_json_response('error', 'Invalid request');
}
?>