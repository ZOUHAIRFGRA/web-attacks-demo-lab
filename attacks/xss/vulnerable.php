<?php
session_start();

// Initialize comments array in session if it doesn't exist
if (!isset($_SESSION['comments'])) {
    $_SESSION['comments'] = [];
}

// Handle new comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    // Vulnerable: Directly storing user input without sanitization
    $_SESSION['comments'][] = [
        'text' => $_POST['comment'],
        'user' => $_POST['username'] ?? 'Anonymous',
        'time' => date('Y-m-d H:i:s')
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulnerable XSS Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">Vulnerable XSS Example</h1>
        <div class="alert alert-danger">
            <strong>Warning:</strong> This page is intentionally vulnerable to XSS attacks!
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Search Products</h5>
                        <form method="GET" action="">
                            <div class="mb-3">
                                <label for="search" class="form-label">Search Term:</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       placeholder="Try: <script>alert('XSS!')</script>">
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>

                        <?php if (isset($_GET['search'])): ?>
                            <!-- Vulnerable: Directly outputting user input -->
                            <div class="mt-3">
                                <h6>Search Results for: <?php echo $_GET['search']; ?></h6>
                                <p>No products found matching your search.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Comment Section</h5>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       placeholder="Try: <b>Bold Name</b>">
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">Comment:</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3"
                                          placeholder="Try: <script>document.body.style.backgroundColor='red';</script>"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Post Comment</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Comments</h5>
                        <?php foreach ($_SESSION['comments'] as $comment): ?>
                            <div class="border-bottom mb-3 pb-3">
                                <!-- Vulnerable: Directly outputting user input -->
                                <strong><?php echo $comment['user']; ?></strong>
                                <small class="text-muted"> - <?php echo $comment['time']; ?></small>
                                <p><?php echo $comment['text']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h3>How these XSS vulnerabilities work:</h3>
            <ul>
                <li>The search form reflects user input without sanitization</li>
                <li>The comment system stores and displays raw HTML/JavaScript</li>
                <li>Try entering <code>&lt;script&gt;alert('XSS!')&lt;/script&gt;</code></li>
                <li>Try entering <code>&lt;img src="x" onerror="alert('XSS')"&gt;</code></li>
                <li>Try entering <code>&lt;style&gt;body { background: red; }&lt;/style&gt;</code></li>
            </ul>
        </div>

        <div class="mt-4">
            <a href="../../index.php" class="btn btn-secondary">Back to Index</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
