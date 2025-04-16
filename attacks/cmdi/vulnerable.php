<?php
$title = "Vulnerable Command Injection Example";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4"><?php echo $title; ?></h1>
        <div class="alert alert-danger">
            <strong>Warning:</strong> This page is intentionally vulnerable to command injection attacks!
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Ping Tool</h5>
                <p class="card-text">Enter an IP address or hostname to ping:</p>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="host" class="form-label">Host:</label>
                        <input type="text" class="form-control" id="host" name="host" 
                               placeholder="Try: localhost & dir" required>
                        <small class="text-muted">Try command injection: localhost & dir or 127.0.0.1 | type c:\windows\win.ini</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Ping Host</button>
                </form>

                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['host'])) {
                    $host = $_POST['host'];
                    
                    // Vulnerable: Direct command execution without validation
                    $command = "ping " . $host;
                    
                    echo "<div class='mt-3'>";
                    echo "<h6>Command executed:</h6>";
                    echo "<pre class='bg-light p-2'>" . htmlspecialchars($command) . "</pre>";
                    echo "<h6>Output:</h6>";
                    echo "<pre class='bg-light p-2'>";
                    system($command);
                    echo "</pre>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">DNS Lookup</h5>
                <p class="card-text">Enter a domain to lookup:</p>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="domain" class="form-label">Domain:</label>
                        <input type="text" class="form-control" id="domain" name="domain" 
                               placeholder="Try: google.com ; net user" required>
                        <small class="text-muted">Try command injection: google.com ; ipconfig /all</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Lookup Domain</button>
                </form>

                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['domain'])) {
                    $domain = $_POST['domain'];
                    
                    // Vulnerable: Direct command execution without validation
                    $command = "nslookup " . $domain;
                    
                    echo "<div class='mt-3'>";
                    echo "<h6>Command executed:</h6>";
                    echo "<pre class='bg-light p-2'>" . htmlspecialchars($command) . "</pre>";
                    echo "<h6>Output:</h6>";
                    echo "<pre class='bg-light p-2'>";
                    system($command);
                    echo "</pre>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <div class="mt-4">
            <h3>How Command Injection Works:</h3>
            <ul>
                <li>User input is directly concatenated into system commands</li>
                <li>No input validation or sanitization</li>
                <li>Special characters (& | ; etc.) can chain multiple commands</li>
                <li>Attackers can execute arbitrary system commands</li>
                <li>Try these examples:
                    <ul>
                        <li>localhost & dir</li>
                        <li>127.0.0.1 | type c:\windows\win.ini</li>
                        <li>google.com ; ipconfig /all</li>
                        <li>example.com && net user</li>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="mt-4">
            <a href="../../index.php" class="btn btn-secondary">Back to Index</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
