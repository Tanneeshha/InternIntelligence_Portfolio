<?php
require_once 'config.php';
require_once 'validation.php';
require_once 'auth.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("SELECT * FROM skills WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $skill = $result->fetch_assoc();
        if ($skill) {
            send_json_response('success', 'Skill retrieved successfully', $skill);
        } else {
            send_json_response('error', 'Skill not found');
        }
    } else {
        $query = "SELECT * FROM skills";
        if (isset($_GET['category'])) {
            $category = Validator::sanitizeText($_GET['category']);
            $query .= " WHERE category = '$category'";
        }
        $query .= " ORDER BY category, proficiency DESC";
        $result = $conn->query($query);
        $skills = [];
        while ($row = $result->fetch_assoc()) {
            $skills[] = $row;
        }
        send_json_response('success', 'Skills retrieved successfully', $skills);
    }
} elseif ($method === 'POST') {
    require_authentication();
    $input = json_decode(file_get_contents('php://input'), true);
    if (!Validator::validateToken($input['csrf_token'] ?? '')) {
        send_json_response('error', 'Invalid CSRF token');
    }
    if (empty($input['name']) || empty($input['category']) || empty($input['proficiency'])) {
        send_json_response('error', 'Name, category, and proficiency are required');
    }
    $name = Validator::sanitizeText($input['name']);
    $category = Validator::sanitizeText($input['category']);
    $proficiency = intval($input['proficiency']);
    $icon = $input['icon'] ?? null;

    if ($proficiency < 0 || $proficiency > 100) {
        send_json_response('error', 'Proficiency must be between 0 and 100');
    }

    $stmt = $conn->prepare("INSERT INTO skills (name, category, proficiency, icon) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $name, $category, $proficiency, $icon);

    if ($stmt->execute()) {
        send_json_response('success', 'Skill created successfully', ['id' => $stmt->insert_id]);
    } else {
        send_json_response('error', 'Failed to create skill');
    }
} elseif ($method === 'PUT') {
    require_authentication();
    if (!isset($_GET['id'])) {
        send_json_response('error', 'Skill ID is required');
    }
    $id = intval($_GET['id']);
    $input = json_decode(file_get_contents('php://input'), true);
    if (!Validator::validateToken($input['csrf_token'] ?? '')) {
        send_json_response('error', 'Invalid CSRF token');
    }
    if (empty($input['name']) || empty($input['category']) || empty($input['proficiency'])) {
        send_json_response('error', 'Name, category, and proficiency are required');
    }
    $name = Validator::sanitizeText($input['name']);
    $category = Validator::sanitizeText($input['category']);
    $proficiency = intval($input['proficiency']);
    $icon = $input['icon'] ?? null;

    $stmt = $conn->prepare("UPDATE skills SET name = ?, category = ?, proficiency = ?, icon = ? WHERE id = ?");
    $stmt->bind_param("ssisi", $name, $category, $proficiency, $icon, $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            send_json_response('success', 'Skill updated successfully');
        } else {
            send_json_response('error', 'Skill not found or no changes made');
        }
    } else {
        send_json_response('error', 'Failed to update skill');
    }
} elseif ($method === 'DELETE') {
    require_authentication();
    if (!isset($_GET['id'])) {
        send_json_response('error', 'Skill ID is required');
    }
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM skills WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            send_json_response('success', 'Skill deleted successfully');
        } else {
            send_json_response('error', 'Skill not found');
        }
    } else {
        send_json_response('error', 'Failed to delete skill');
    }
} else {
    send_json_response('error', 'Invalid request method');
}
?>
