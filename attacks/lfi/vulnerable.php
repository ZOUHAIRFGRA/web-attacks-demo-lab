<?php
$title = "Vulnerable LFI Example";
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
            <strong>Warning:</strong> This page is intentionally vulnerable to LFI attacks!
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Local File Inclusion Test</h5>
                <p class="card-text">This form allows reading any local file without proper validation:</p>
                
                <form method="GET" action="">
                    <div class="mb-3">
                        <label for="file" class="form-label">Enter file path to read:</label>
                        <input type="text" class="form-control" id="file" name="file" 
                               placeholder="Try: ../../../windows/win.ini or ../config/database.php">
                    </div>
                    <button type="submit" class="btn btn-primary">View File</button>
                </form>

                <?php
                if (isset($_GET['file'])) {
                    // Vulnerable: No validation before including the file
                    $file = $_GET['file'];
                    echo "<div class='mt-4'>";
                    echo "<h6>File Contents:</h6>";
                    echo "<pre class='bg-light p-3 mt-2'>";
                    // Vulnerable: Allows reading any file on the system
                    @readfile($file);
                    echo "</pre>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <div class="mt-4">
            <h3>How this LFI vulnerability works:</h3>
            <ul>
                <li>The page accepts any file path through the GET parameter 'file'</li>
                <li>No proper input validation or sanitization</li>
                <li>Allows directory traversal using ../ sequences</li>
                <li>Can read sensitive system files or configuration files</li>
                <li>Common targets:
                    <ul>
                        <li>/etc/passwd (on Linux)</li>
                        <li>../../windows/win.ini (on Windows)</li>
                        <li>../config/database.php (application files)</li>
                        <li>PHP configuration files</li>
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
