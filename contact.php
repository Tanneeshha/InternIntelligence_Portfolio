<?php
require_once 'config.php';
require_once 'validation.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!Validator::validateToken($input['csrf_token'] ?? '')) {
        send_json_response('error', 'Invalid CSRF token');
    }
    if (empty($input['name']) || empty($input['email']) || empty($input['message'])) {
        send_json_response('error', 'Name, email, and message are required');
    }
    $name = Validator::sanitizeText($input['name']);
    $email = Validator::sanitizeText($input['email']);
    $subject = $input['subject'] ?? '';
    $message = Validator::sanitizeText($input['message']);
    $ip_address = $_SERVER['REMOTE_ADDR'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        send_json_response('error', 'Invalid email format');
    }

    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM contact_submissions WHERE ip_address = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 MINUTE)");
    $stmt->bind_param("s", $ip_address);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 3) {
        send_json_response('error', 'Too many submissions. Please try again later.');
    }

    $stmt = $conn->prepare("INSERT INTO contact_submissions (name, email, subject, message, ip_address) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $subject, $message, $ip_address);

    if ($stmt->execute()) {
        send_json_response('success', 'Message sent successfully');
    } else {
        send_json_response('error', 'Failed to send message');
    }
} elseif ($method === 'GET') {
    require_authentication();
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("SELECT * FROM contact_submissions WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $submission = $result->fetch_assoc();
        if ($submission) {
            send_json_response('success', 'Submission retrieved', $submission);
        } else {
            send_json_response('error', 'Submission not found');
        }
    } else {
        $result = $conn->query("SELECT * FROM contact_submissions ORDER BY created_at DESC");
        $submissions = [];
        while ($row = $result->fetch_assoc()) {
            $submissions[] = $row;
        }
        send_json_response('success', 'Submissions retrieved', $submissions);
    }
} else {
    send_json_response('error', 'Unsupported HTTP method');
}
?>
