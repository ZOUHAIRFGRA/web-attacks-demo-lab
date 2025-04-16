<?php
$title = "Vulnerable RFI Example";
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
            <strong>Warning:</strong> This page is intentionally vulnerable to RFI attacks for demonstration purposes!
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Remote File Inclusion Test</h5>
                <p class="card-text">This form allows you to include any remote file without validation:</p>
                
                <form method="GET" action="">
                    <div class="mb-3">
                        <label for="page" class="form-label">Enter file path to include:</label>
                        <input type="text" class="form-control" id="page" name="page" 
                               placeholder="Enter a file path (e.g., header.php or http://evil.com/malicious.php)">
                    </div>
                    <button type="submit" class="btn btn-primary">Include File</button>
                </form>

                <?php
                if (isset($_GET['page'])) {
                    // Vulnerable: No validation before including the file
                    include($_GET['page']);
                }
                ?>
            </div>
        </div>

        <div class="mt-4">
            <h3>How this vulnerability works:</h3>
            <ul>
                <li>The page accepts any file path through the GET parameter 'page'</li>
                <li>It uses PHP's include() function without any validation</li>
                <li>Attackers can include both local and remote files</li>
                <li>Remote files could contain malicious PHP code that will be executed</li>
            </ul>
        </div>

        <div class="mt-4">
            <a href="../../index.php" class="btn btn-secondary">Back to Index</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
