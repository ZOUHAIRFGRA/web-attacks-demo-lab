<?php
$title = "Vulnerable DoS Example";

function fibonacci($n) {
    if ($n <= 1) return $n;
    return fibonacci($n - 1) + fibonacci($n - 2);
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
        <div class="alert alert-danger">
            <strong>Warning:</strong> This page is intentionally vulnerable to DoS attacks!
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Intensive Operation Test</h5>
                <p class="card-text">This form performs CPU-intensive calculations without any limits:</p>
                
                <form method="GET" action="">
                    <div class="mb-3">
                        <label for="n" class="form-label">Calculate Fibonacci number (try large numbers):</label>
                        <input type="number" class="form-control" id="n" name="n" 
                               placeholder="Enter a number (e.g., 40)">
                    </div>
                    <button type="submit" class="btn btn-primary">Calculate</button>
                </form>

                <?php
                if (isset($_GET['n'])) {
                    $n = intval($_GET['n']);
                    $startTime = microtime(true);
                    
                    // Vulnerable: No limits on computation
                    $result = fibonacci($n);
                    
                    $endTime = microtime(true);
                    $executionTime = ($endTime - $startTime);
                    
                    echo "<div class='mt-4'>";
                    echo "<h6>Results:</h6>";
                    echo "<p>Fibonacci($n) = $result</p>";
                    echo "<p>Execution time: " . number_format($executionTime, 4) . " seconds</p>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <div class="mt-4">
            <h3>How this DoS vulnerability works:</h3>
            <ul>
                <li>The page performs CPU-intensive calculations without any limits</li>
                <li>No rate limiting or request throttling</li>
                <li>Large input values can cause excessive server load</li>
                <li>Multiple concurrent requests can overwhelm the server</li>
                <li>Try entering large numbers (e.g., 40 or higher) to see the impact</li>
            </ul>
        </div>

        <div class="mt-4">
            <a href="../../index.php" class="btn btn-secondary">Back to Index</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
