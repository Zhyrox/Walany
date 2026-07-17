<?php
// DYNAMIC BASE URL ENGINE
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseDir = preg_replace('#/modules/.*|/core.*#', '', $scriptDir);
$baseUrl = rtrim($baseDir, '/') . '/';
define('BASE_URL', $baseUrl);

// Container for our parsed .env values to bypass broken system global arrays
$ENV_DATA = [];
$envPath = 'C:\xampp\htdocs\Walany\.env';

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        
        // Skip comment lines
        if (empty($line) || strpos($line, '#') === 0 || strpos($line, '//') === 0) {
            continue;
        }
        
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            // Handle inline comments
            if (($hashPos = strpos($value, '#')) !== false) {
                $value = substr($value, 0, $hashPos);
            }
            if (($slashPos = strpos($value, '//')) !== false) {
                $value = substr($value, 0, $slashPos);
            }
            
            // Sanitize quotes and Windows carriage returns (\r)
            $value = trim(trim($value), '"\'');
            $value = str_replace(["\r", "\n"], '', $value);
            
            // Store directly in our own reliable custom array
            $ENV_DATA[$name] = $value;
        }
    }
} else {
    error_log("CRITICAL: .env file missing at: " . $envPath);
}

// UNIFIED CONSTANT ASSIGNMENTS (Pulls directly from $ENV_DATA)

define('DB_HOST',   ($ENV_DATA['DB_HOST']   ?? ''));
define('DB_NAME',   ($ENV_DATA['DB_NAME']   ?? ''));
define('DB_PORT',   ($ENV_DATA['DB_PORT']   ?? ''));
define('DB_CHAR',   ($ENV_DATA['DB_CHAR']   ?? ''));
define('DB_USER',   ($ENV_DATA['DB_USER']   ?? ''));
define('DB_PASS',   ($ENV_DATA['DB_PASS']   ?? ''));

// API Keys - Falls back to blank string if missing from your file
define('PAYMONGO_SECRET_KEY', ($ENV_DATA['PAYMONGO_SECRET_KEY'] ?? ''));
define('GEMINI_CHATBOT_KEY',  ($ENV_DATA['GEMINI_CHATBOT_KEY']  ?? ''));

// SMTP Settings - Falls back to standard defaults if missing
define('SMTP_HOST', ($ENV_DATA['SMTP_HOST'] ?? ''));
define('SMTP_USER', ($ENV_DATA['SMTP_USER'] ?? ''));
define('SMTP_PASS', ($ENV_DATA['SMTP_PASS'] ?? ''));
?>