<?php
$title = "Secure Command Injection Prevention Example";

// Whitelist of allowed hosts for ping
$allowed_hosts = [
    'localhost' => true,
    '127.0.0.1' => true,
    'google.com' => true,
    'github.com' => true
];

// Whitelist of allowed domains for DNS lookup
$allowed_domains = [
    'google.com' => true,
    'github.com' => true,
    'microsoft.com' => true,
    'example.com' => true
];

// Secure command execution function
function secureExecuteCommand($type, $input, $allowedList) {
    if (!isset($allowedList[$input])) {
        return "Error: Input not in allowed list";
    }
    
    // Validate input format
    if ($type === 'ping') {
        if (!filter_var($input, FILTER_VALIDATE_DOMAIN) && 
            !filter_var($input, FILTER_VALIDATE_IP)) {
            return "Error: Invalid host format";
        }
    } else if ($type === 'dns') {
        if (!filter_var($input, FILTER_VALIDATE_DOMAIN)) {
            return "Error: Invalid domain format";
        }
    }
    
    // Escape shell arguments properly
    $escapedInput = escapeshellarg($input);
    
    // Use full path to commands for security
    $command = $type === 'ping' 
        ? "C:\\Windows\\System32\\ping.exe " . $escapedInput
        : "C:\\Windows\\System32\\nslookup.exe " . $escapedInput;
    
    // Capture command output safely
    $output = [];
    $returnVar = 0;
    exec($command, $output, $returnVar);
    
    return implode("\n", $output);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4"><?php echo htmlspecialchars($title); ?></h1>
        <div class="alert alert-success">
            <strong>Notice:</strong> This page implements proper security measures against command injection attacks!
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Secure Ping Tool</h5>
                <p class="card-text">Select a host to ping from the allowed list:</p>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="host" class="form-label">Host:</label>
                        <select class="form-control" id="host" name="host" required>
                            <option value="">Select a host...</option>
                            <?php foreach(array_keys($allowed_hosts) as $host): ?>
                                <option value="<?php echo htmlspecialchars($host); ?>">
                                    <?php echo htmlspecialchars($host); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Ping Host</button>
                </form>

                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['host'])) {
                    $host = $_POST['host'];
                    $output = secureExecuteCommand('ping', $host, $allowed_hosts);
                    
                    echo "<div class='mt-3'>";
                    echo "<h6>Output:</h6>";
                    echo "<pre class='bg-light p-2'>" . htmlspecialchars($output) . "</pre>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Secure DNS Lookup</h5>
                <p class="card-text">Select a domain from the allowed list:</p>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="domain" class="form-label">Domain:</label>
                        <select class="form-control" id="domain" name="domain" required>
                            <option value="">Select a domain...</option>
                            <?php foreach(array_keys($allowed_domains) as $domain): ?>
                                <option value="<?php echo htmlspecialchars($domain); ?>">
                                    <?php echo htmlspecialchars($domain); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Lookup Domain</button>
                </form>

                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['domain'])) {
                    $domain = $_POST['domain'];
                    $output = secureExecuteCommand('dns', $domain, $allowed_domains);
                    
                    echo "<div class='mt-3'>";
                    echo "<h6>Output:</h6>";
                    echo "<pre class='bg-light p-2'>" . htmlspecialchars($output) . "</pre>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <div class="mt-4">
            <h3>Security Measures Implemented:</h3>
            <ul>
                <li>Whitelist of allowed hosts and domains</li>
                <li>Input validation and sanitization</li>
                <li>Use of escapeshellarg() for command arguments</li>
                <li>Full paths to system commands</li>
                <li>Dropdown menus instead of free text input</li>
                <li>Use of exec() with proper output capturing</li>
                <li>Domain and IP format validation</li>
                <li>Output encoding using htmlspecialchars()</li>
            </ul>
        </div>

        <div class="mt-4">
            <a href="../../index.php" class="btn btn-secondary">Back to Index</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
