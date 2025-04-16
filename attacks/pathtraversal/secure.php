<?php
$title = "Secure Path Traversal Prevention Example";

// Create a directory for storing downloadable files
$downloadsDir = "downloads/";
if (!file_exists($downloadsDir)) {
    mkdir($downloadsDir, 0777, true);
}

// Create some sample files if they don't exist
$sampleFiles = [
    'public_doc.txt' => 'This is a public document that anyone can access.',
    'product_list.csv' => "Product,Price\nLaptop,999\nPhone,499",
];

foreach ($sampleFiles as $file => $content) {
    $filePath = $downloadsDir . $file;
    if (!file_exists($filePath)) {
        file_put_contents($filePath, $content);
    }
}

// Whitelist of allowed files and their display names
$allowed_files = [
    'public_doc.txt' => 'Public Document',
    'product_list.csv' => 'Product List',
];

// Whitelist of allowed image files
$allowed_images = [
    'logo.png' => 'Company Logo',
    'banner.jpg' => 'Website Banner',
];

// Secure file access function
function secureFileAccess($filename, $baseDir, $allowedFiles) {
    // Convert to real path to resolve any ../ sequences
    $requestedPath = realpath($baseDir . $filename);
    $basePath = realpath($baseDir);
    
    // Security checks
    if (!$requestedPath || // File doesn't exist
        !is_file($requestedPath) || // Not a regular file
        !str_starts_with($requestedPath, $basePath) || // Outside base directory
        !isset($allowedFiles[$filename])) { // Not in whitelist
        return false;
    }
    
    return $requestedPath;
}

// Secure file type validation
function validateFileType($filepath, $allowedTypes) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $filepath);
    finfo_close($finfo);
    
    return in_array($mimeType, $allowedTypes);
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
            <strong>Notice:</strong> This page implements proper security measures against path traversal attacks!
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Secure File Download System</h5>
                <p class="card-text">Select a file from the allowed list to download:</p>
                
                <form method="GET" action="">
                    <div class="mb-3">
                        <label for="file" class="form-label">Select file:</label>
                        <select class="form-control" id="file" name="file" required>
                            <option value="">Choose a file...</option>
                            <?php foreach($allowed_files as $filename => $display): ?>
                                <option value="<?php echo htmlspecialchars($filename); ?>">
                                    <?php echo htmlspecialchars($display); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Download File</button>
                </form>

                <?php
                if (isset($_GET['file'])) {
                    $requestedFile = $_GET['file'];
                    $filePath = secureFileAccess($requestedFile, $downloadsDir, $allowed_files);
                    
                    if ($filePath !== false) {
                        echo "<div class='mt-3'>";
                        echo "<div class='alert alert-info'>File contents:</div>";
                        echo "<pre class='bg-light p-2'>";
                        echo htmlspecialchars(file_get_contents($filePath));
                        echo "</pre>";
                        echo "</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Access denied or file not found.</div>";
                    }
                }
                ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Secure Image Viewer</h5>
                <p class="card-text">Select an image from the allowed list:</p>
                
                <form method="GET" action="">
                    <div class="mb-3">
                        <label for="image" class="form-label">Select image:</label>
                        <select class="form-control" id="image" name="image" required>
                            <option value="">Choose an image...</option>
                            <?php foreach($allowed_images as $filename => $display): ?>
                                <option value="<?php echo htmlspecialchars($filename); ?>">
                                    <?php echo htmlspecialchars($display); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">View Image</button>
                </form>

                <?php
                if (isset($_GET['image'])) {
                    $requestedImage = $_GET['image'];
                    $imagePath = secureFileAccess($requestedImage, 'images/', $allowed_images);
                    
                    if ($imagePath !== false && validateFileType($imagePath, ['image/jpeg', 'image/png', 'image/gif'])) {
                        $mimeType = mime_content_type($imagePath);
                        header("Content-Type: " . $mimeType);
                        readfile($imagePath);
                        exit;
                    } else {
                        echo "<div class='alert alert-danger'>Invalid image or access denied.</div>";
                    }
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
                <li>MIME type validation</li>
                <li>Dropdown menu instead of free text input</li>
                <li>Prevention of directory traversal</li>
                <li>Proper error handling</li>
                <li>Content-Type validation for images</li>
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
