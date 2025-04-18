// Create a validation.php utility file
<?php
class Validator {
    // Sanitize and validate regular text input
    public static function sanitizeText($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    // Validate email
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    // Validate URL
    public static function validateURL($url) {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
    
    // Validate form token (CSRF protection)
    public static function validateToken($token) {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            return false;
        }
        return true;
    }
    
    // Generate a CSRF token
    public static function generateToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}
?>