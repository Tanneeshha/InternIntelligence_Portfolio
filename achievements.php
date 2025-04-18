<?php
// Include database configuration
require_once 'config.php';

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

// GET - Retrieve all achievements or a specific achievement
if ($method === 'GET') {
    // Check if requesting a specific achievement by ID
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        
        // Prepare SQL statement
        $stmt = $conn->prepare("SELECT * FROM achievements WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $achievement = $result->fetch_assoc();
            send_json_response('success', 'Achievement retrieved successfully', $achievement);
        } else {
            send_json_response('error', 'Achievement not found');
        }
    } 
    // Get all achievements
    else {
        $query = "SELECT * FROM achievements ORDER BY date DESC";
        
        $result = $conn->query($query);
        $achievements = [];
        
        while ($row = $result->fetch_assoc()) {
            $achievements[] = $row;
        }
        
        send_json_response('success', 'Achievements retrieved successfully', $achievements);
    }
}

// POST - Create a new achievement
else if ($method === 'POST') {
    // Require authentication
    require_authentication();
    
    // Get and sanitize input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (!isset($input['title']) || !isset($input['description'])) {
        send_json_response('error', 'Title and description are required');
    }
    
    $title = sanitize_input($input['title']);
    $description = sanitize_input($input['description']);
    $date = isset($input['date']) ? sanitize_input($input['date']) : null;
    $issuer = isset($input['issuer']) ? sanitize_input($input['issuer']) : null;
    $url = isset($input['url']) ? sanitize_input($input['url']) : null;
    
    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO achievements (title, description, date, issuer, url) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $date, $issuer, $url);
    
    if ($stmt->execute()) {
        $new_id = $stmt->insert_id;
        send_json_response('success', 'Achievement created successfully', ['id' => $new_id]);
    } else {
        send_json_response('error', 'Failed to create achievement: ' . $stmt->error);
    }
}

// PUT - Update an existing achievement
else if ($method === 'PUT') {
    // Require authentication
    require_authentication();
    
    // Check if ID is provided
    if (!isset($_GET['id'])) {
        send_json_response('error', 'Achievement ID is required');
    }
    
    $id = intval($_GET['id']);
    
    // Get and sanitize input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (!isset($input['title']) || !isset($input['description'])) {
        send_json_response('error', 'Title and description are required');
    }
    
    $title = sanitize_input($input['title']);
    $description = sanitize_input($input['description']);
    $date = isset($input['date']) ? sanitize_input($input['date']) : null;
    $issuer = isset($input['issuer']) ? sanitize_input($input['issuer']) : null;
    $url = isset($input['url']) ? sanitize_input($input['url']) : null;
    
    // Prepare SQL statement
    $stmt = $conn->prepare("UPDATE achievements SET title = ?, description = ?, date = ?, issuer = ?, url = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $title, $description, $date, $issuer, $url, $id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            send_json_response('success', 'Achievement updated successfully');
        } else {
            send_json_response('error', 'Achievement not found or no changes made');
        }
    } else {
        send_json_response('error', 'Failed to update achievement: ' . $stmt->error);
    }
}

// DELETE - Delete an achievement
else if ($method === 'DELETE') {
    // Require authentication
    require_authentication();
    
    // Check if ID is provided
    if (!isset($_GET['id'])) {
        send_json_response('error', 'Achievement ID is required');
    }
    
    $id = intval($_GET['id']);
    
    // Prepare SQL statement
    $stmt = $conn->prepare("DELETE FROM achievements WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            send_json_response('success', 'Achievement deleted successfully');
        } else {
            send_json_response('error', 'Achievement not found');
        }
    } else {
        send_json_response('error', 'Failed to delete achievement: ' . $stmt->error);
    }
}

// Invalid method
else {
    send_json_response('error', 'Invalid request method');
}
?>