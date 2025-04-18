<?php
// Include necessary files
require_once 'config.php';
require_once 'validation.php';
require_once 'auth.php';

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

// GET - Retrieve all projects or a specific one
if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $project = $result->fetch_assoc();

        if ($project) {
            send_json_response('success', 'Project retrieved successfully', $project);
        } else {
            send_json_response('error', 'Project not found');
        }
    } else {
        $query = "SELECT * FROM projects";
        if (isset($_GET['featured']) && $_GET['featured'] === 'true') {
            $query .= " WHERE featured = 1";
        }
        $query .= " ORDER BY created_at DESC";
        $result = $conn->query($query);
        $projects = [];

        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }

        send_json_response('success', 'Projects retrieved successfully', $projects);
    }
}

// POST - Create a new project
elseif ($method === 'POST') {
    require_authentication();
    $input = json_decode(file_get_contents('php://input'), true);

    if (!Validator::validateToken($input['csrf_token'] ?? '')) {
        send_json_response('error', 'Invalid CSRF token');
    }

    if (empty($input['title']) || empty($input['description'])) {
        send_json_response('error', 'Title and description are required');
    }

    $title = Validator::sanitizeText($input['title']);
    $description = Validator::sanitizeText($input['description']);
    $image_url = isset($input['image_url']) ? Validator::sanitizeText($input['image_url']) : null;
    $project_url = isset($input['project_url']) ? Validator::sanitizeText($input['project_url']) : null;
    $github_url = isset($input['github_url']) ? Validator::sanitizeText($input['github_url']) : null;
    $technologies = isset($input['technologies']) ? Validator::sanitizeText($input['technologies']) : null;
    $featured = !empty($input['featured']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO projects (title, description, image_url, project_url, github_url, technologies, featured) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $title, $description, $image_url, $project_url, $github_url, $technologies, $featured);

    if ($stmt->execute()) {
        send_json_response('success', 'Project created successfully', ['id' => $stmt->insert_id]);
    } else {
        send_json_response('error', 'Failed to create project: ' . $stmt->error);
    }
}

// PUT - Update an existing project
elseif ($method === 'PUT') {
    require_authentication();

    if (!isset($_GET['id'])) {
        send_json_response('error', 'Project ID is required');
    }

    $id = intval($_GET['id']);
    $input = json_decode(file_get_contents('php://input'), true);

    if (!Validator::validateToken($input['csrf_token'] ?? '')) {
        send_json_response('error', 'Invalid CSRF token');
    }

    if (empty($input['title']) || empty($input['description'])) {
        send_json_response('error', 'Title and description are required');
    }

    $title = Validator::sanitizeText($input['title']);
    $description = Validator::sanitizeText($input['description']);
    $image_url = isset($input['image_url']) ? Validator::sanitizeText($input['image_url']) : null;
    $project_url = isset($input['project_url']) ? Validator::sanitizeText($input['project_url']) : null;
    $github_url = isset($input['github_url']) ? Validator::sanitizeText($input['github_url']) : null;
    $technologies = isset($input['technologies']) ? Validator::sanitizeText($input['technologies']) : null;
    $featured = !empty($input['featured']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE projects SET title = ?, description = ?, image_url = ?, project_url = ?, github_url = ?, technologies = ?, featured = ? WHERE id = ?");
    $stmt->bind_param("ssssssii", $title, $description, $image_url, $project_url, $github_url, $technologies, $featured, $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            send_json_response('success', 'Project updated successfully');
        } else {
            send_json_response('error', 'Project not found or no changes made');
        }
    } else {
        send_json_response('error', 'Failed to update project: ' . $stmt->error);
    }
}

// DELETE - Delete a project
elseif ($method === 'DELETE') {
    require_authentication();

    if (!isset($_GET['id'])) {
        send_json_response('error', 'Project ID is required');
    }

    $id = intval($_GET['id']);
    $input = json_decode(file_get_contents('php://input'), true);

    if (!Validator::validateToken($input['csrf_token'] ?? '')) {
        send_json_response('error', 'Invalid CSRF token');
    }

    $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            send_json_response('success', 'Project deleted successfully');
        } else {
            send_json_response('error', 'Project not found');
        }
    } else {
        send_json_response('error', 'Failed to delete project: ' . $stmt->error);
    }
}

// Invalid request
else {
    send_json_response('error', 'Invalid request method');
}
?>
