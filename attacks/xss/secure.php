<?php
session_start();

// Initialize comments array in session if it doesn't exist
if (!isset($_SESSION['comments'])) {
    $_SESSION['comments'] = [];
}

// Handle new comment submission with proper validation and sanitization
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    // Sanitize and validate input
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    
    if (strlen($comment) > 0 && strlen($comment) <= 500) {
        $_SESSION['comments'][] = [
            'text' => $comment,
            'user' => $username ?: 'Anonymous',
            'time' => date('Y-m-d H:i:s')
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure XSS Prevention Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSP Header to prevent inline scripts -->
    <meta http-equiv="Content-Security-Policy" 
          content="default-src 'self' https://cdn.jsdelivr.net; 
                   script-src 'self' https://cdn.jsdelivr.net 'nonce-random123';
                   style-src 'self' https://cdn.jsdelivr.net;">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">Secure XSS Prevention Example</h1>
        <div class="alert alert-success">
            <strong>Notice:</strong> This page implements proper security measures against XSS attacks!
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Search Products (Secure)</h5>
                        <form method="GET" action="">
                            <div class="mb-3">
                                <label for="search" class="form-label">Search Term:</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       maxlength="50" pattern="[a-zA-Z0-9\s]+" 
                                       title="Only alphanumeric characters and spaces allowed">
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>

                        <?php if (isset($_GET['search'])): ?>
                            <div class="mt-3">
                                <!-- Secure: Properly escaped output -->
                                <h6>Search Results for: <?php echo htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8'); ?></h6>
                                <p>No products found matching your search.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Comment Section (Secure)</h5>
                        <form method="POST" action="" 
                              onsubmit="return validateForm()">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       maxlength="30" pattern="[a-zA-Z0-9\s]+" 
                                       title="Only alphanumeric characters and spaces allowed">
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">Comment:</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3"
                                          maxlength="500" required></textarea>
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
                                <!-- Secure: Properly escaped output -->
                                <strong><?php echo htmlspecialchars($comment['user'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                <small class="text-muted"> - <?php echo htmlspecialchars($comment['time'], ENT_QUOTES, 'UTF-8'); ?></small>
                                <p><?php echo nl2br(htmlspecialchars($comment['text'], ENT_QUOTES, 'UTF-8')); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h3>Security Measures Implemented:</h3>
            <ul>
                <li>Input validation using patterns and maxlength</li>
                <li>Output escaping using htmlspecialchars()</li>
                <li>Content Security Policy (CSP) headers</li>
                <li>Input sanitization using filter_input()</li>
                <li>Client-side validation with JavaScript</li>
                <li>Length restrictions on all inputs</li>
                <li>Proper character encoding settings</li>
            </ul>
        </div>

        <div class="mt-4">
            <a href="../../index.php" class="btn btn-secondary">Back to Index</a>
        </div>
    </div>

    <script nonce="random123">
        function validateForm() {
            const comment = document.getElementById('comment').value.trim();
            if (comment.length === 0) {
                alert('Please enter a comment');
                return false;
            }
            if (comment.length > 500) {
                alert('Comment is too long');
                return false;
            }
            return true;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
