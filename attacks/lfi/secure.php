<?php
$title = "Secure LFI Prevention Example";

// Define a whitelist of allowed files and their display names
$allowed_files = [
    'templates/header.php' => 'Header Template',
    'templates/footer.php' => 'Footer Template',
    'templates/menu.php' => 'Menu Template',
    'logs/access.log' => 'Access Log'
];

// Secure file reading function
function secureFileRead($filename, $allowedFiles) {
    // Convert to real path to resolve any ../ sequences
    $realPath = realpath($filename);
    
    // Get the application's base directory
    $baseDir = realpath(__DIR__);
    
    // Security checks
    if (!$realPath || // File doesn't exist
        !is_file($realPath) || // Not a regular file
        !str_starts_with($realPath, $baseDir) || // Outside base directory
        !isset($allowedFiles[$filename])) { // Not in whitelist
        return false;
    }
    
    return file_get_contents($realPath);
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
            <strong>Notice:</strong> This page implements proper security measures against LFI attacks!
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Secure File Access</h5>
                <p class="card-text">This form only allows access to specific whitelisted files:</p>
                
                <form method="GET" action="">
                    <div class="mb-3">
                        <label for="file" class="form-label">Select file to view:</label>
                        <select class="form-control" id="file" name="file" required>
                            <option value="">Choose a file...</option>
                            <?php foreach($allowed_files as $path => $name): ?>
                                <option value="<?php echo htmlspecialchars($path); ?>">
                                    <?php echo htmlspecialchars($name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">View File</button>
                </form>

                <?php
                if (isset($_GET['file'])) {
                    $file = $_GET['file'];
                    $contents = secureFileRead($file, $allowed_files);
                    
                    echo "<div class='mt-4'>";
                    if ($contents !== false) {
                        echo "<h6>Contents of: " . htmlspecialchars($allowed_files[$file]) . "</h6>";
                        echo "<pre class='bg-light p-3 mt-2'>";
                        echo htmlspecialchars($contents);
                        echo "</pre>";
                    } else {
                        echo "<div class='alert alert-danger'>Access denied or file not found.</div>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <div class="mt-4">
            <h3>Security Measures Implemented:</h3>
            <ul>
                <li>Whitelist of allowed files</li>
                <li>File path validation using realpath()</li>
                <li>Base directory restriction</li>
                <li>Proper error handling</li>
                <li>Input validation and sanitization</li>
                <li>Dropdown menu instead of free text input</li>
                <li>Prevention of directory traversal attacks</li>
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
