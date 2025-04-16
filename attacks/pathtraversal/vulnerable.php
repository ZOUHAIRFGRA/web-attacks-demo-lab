<?php
$title = "Vulnerable Path Traversal Example";

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
            <strong>Warning:</strong> This page is intentionally vulnerable to path traversal attacks!
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">File Download System</h5>
                <p class="card-text">Enter the name of the file you want to download:</p>
                
                <form method="GET" action="">
                    <div class="mb-3">
                        <label for="file" class="form-label">File name:</label>
                        <input type="text" class="form-control" id="file" name="file" 
                               placeholder="Try: ../config/database.php or ../../windows/win.ini" required>
                        <small class="text-muted">Try path traversal: ../../../windows/win.ini</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Download File</button>
                </form>

                <?php
                if (isset($_GET['file'])) {
                    $requestedFile = $_GET['file'];
                    $filePath = $downloadsDir . $requestedFile;
                    
                    echo "<div class='mt-3'>";
                    echo "<h6>Attempting to access:</h6>";
                    echo "<pre class='bg-light p-2'>" . htmlspecialchars($filePath) . "</pre>";
                    
                    if (file_exists($filePath)) {
                        echo "<div class='alert alert-info'>File contents:</div>";
                        echo "<pre class='bg-light p-2'>";
                        readfile($filePath);
                        echo "</pre>";
                    } else {
                        echo "<div class='alert alert-warning'>File not found!</div>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Image Viewer</h5>
                <p class="card-text">View images from the images directory:</p>
                
                <form method="GET" action="">
                    <div class="mb-3">
                        <label for="image" class="form-label">Image path:</label>
                        <input type="text" class="form-control" id="image" name="image" 
                               placeholder="Try: ../config/database.php">
                        <small class="text-muted">Try viewing sensitive files using relative paths</small>
                    </div>
                    <button type="submit" class="btn btn-primary">View Image</button>
                </form>

                <?php
                if (isset($_GET['image'])) {
                    $imagePath = "images/" . $_GET['image'];
                    
                    echo "<div class='mt-3'>";
                    echo "<h6>Attempting to access:</h6>";
                    echo "<pre class='bg-light p-2'>" . htmlspecialchars($imagePath) . "</pre>";
                    
                    if (file_exists($imagePath)) {
                        // Vulnerable: No content-type checking
                        readfile($imagePath);
                    } else {
                        echo "<div class='alert alert-warning'>Image not found!</div>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <div class="mt-4">
            <h3>How Path Traversal Works:</h3>
            <ul>
                <li>The application directly uses user input in file paths</li>
                <li>No validation against directory traversal sequences (../)</li>
                <li>Can access files outside the intended directory</li>
                <li>Try these examples:
                    <ul>
                        <li>../config/database.php</li>
                        <li>../../windows/win.ini</li>
                        <li>../index.php</li>
                        <li>%2e%2e%2f%2e%2e%2f (URL encoded ../)</li>
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
