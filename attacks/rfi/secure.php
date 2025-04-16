<?php
$title = "Secure RFI Example";

// Define a whitelist of allowed files
$allowed_files = [
    'header.php' => true,
    'footer.php' => true,
    'content.php' => true
];

function securePath($file) {
    // Remove any directory traversal attempts
    $file = str_replace(['../', '..\\', '..'], '', $file);
    // Ensure the file has .php extension
    return preg_match('/^[a-zA-Z0-9_-]+\.php$/', $file) ? $file : '';
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
            <strong>Notice:</strong> This page implements proper security measures against RFI attacks!
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Secure File Inclusion Example</h5>
                <p class="card-text">This form only allows inclusion of whitelisted local files:</p>
                
                <form method="GET" action="">
                    <div class="mb-3">
                        <label for="page" class="form-label">Select file to include:</label>
                        <select class="form-control" id="page" name="page">
                            <option value="">Select a file...</option>
                            <?php foreach(array_keys($allowed_files) as $file): ?>
                                <option value="<?php echo htmlspecialchars($file); ?>">
                                    <?php echo htmlspecialchars($file); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Include File</button>
                </form>

                <?php
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                    
                    // Security measures:
                    // 1. Check if file is in whitelist
                    // 2. Clean the path
                    // 3. Verify file exists locally
                    if (isset($allowed_files[$page])) {
                        $safe_page = securePath($page);
                        if ($safe_page && file_exists($safe_page)) {
                            include($safe_page);
                        } else {
                            echo '<div class="alert alert-warning">Invalid file selected.</div>';
                        }
                    } else {
                        echo '<div class="alert alert-warning">Selected file is not in the allowed list.</div>';
                    }
                }
                ?>
            </div>
        </div>

        <div class="mt-4">
            <h3>Security Measures Implemented:</h3>
            <ul>
                <li>Whitelist of allowed files</li>
                <li>Directory traversal prevention</li>
                <li>File extension validation</li>
                <li>Local file existence check</li>
                <li>Dropdown menu instead of free text input</li>
                <li>Input sanitization and validation</li>
            </ul>
        </div>

        <div class="mt-4">
            <a href="../../index.php" class="btn btn-secondary">Back to Index</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
