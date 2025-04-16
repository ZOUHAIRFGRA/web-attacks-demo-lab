<?php
session_start();

// Simulate a user authentication system
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id' => 1,
        'username' => 'demo_user',
        'balance' => 1000
    ];
}

// Vulnerable money transfer function
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amount'], $_POST['recipient'])) {
    $amount = floatval($_POST['amount']);
    $recipient = $_POST['recipient'];
    
    // Vulnerable: No CSRF token verification
    if ($amount > 0 && $amount <= $_SESSION['user']['balance']) {
        $_SESSION['user']['balance'] -= $amount;
        $message = "Successfully transferred $${amount} to ${recipient}";
    } else {
        $message = "Invalid amount or insufficient funds";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulnerable CSRF Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">Vulnerable CSRF Example</h1>
        <div class="alert alert-danger">
            <strong>Warning:</strong> This page is intentionally vulnerable to CSRF attacks!
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Your Account</h5>
                        <p>Username: <?php echo htmlspecialchars($_SESSION['user']['username']); ?></p>
                        <p>Balance: $<?php echo htmlspecialchars($_SESSION['user']['balance']); ?></p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Money Transfer</h5>
                        <?php if (isset($message)): ?>
                            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="recipient" class="form-label">Recipient:</label>
                                <input type="text" class="form-control" id="recipient" name="recipient" required>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount ($):</label>
                                <input type="number" class="form-control" id="amount" name="amount" 
                                       min="1" step="0.01" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Transfer Money</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">CSRF Attack Demo</h5>
                        <p>This is an example of a malicious form that could be hosted on another website:</p>
                        <div class="bg-light p-3 rounded">
                            <pre class="mb-0"><code>&lt;form action="http://yoursite/vulnerable.php" method="POST" id="csrf-form"&gt;
    &lt;input type="hidden" name="recipient" value="attacker"&gt;
    &lt;input type="hidden" name="amount" value="500"&gt;
&lt;/form&gt;
&lt;script&gt;
    document.getElementById('csrf-form').submit();
&lt;/script&gt;</code></pre>
                        </div>
                        <p class="mt-3">When a logged-in user visits the malicious site, it will automatically submit the form and transfer money without their consent.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h3>How this CSRF vulnerability works:</h3>
            <ul>
                <li>The form doesn't include any CSRF token for verification</li>
                <li>It relies only on session cookies for authentication</li>
                <li>Any site can submit a form to this endpoint</li>
                <li>The user's browser will automatically include their session cookie</li>
                <li>The server has no way to verify if the request came from the legitimate form</li>
            </ul>
        </div>

        <div class="mt-4">
            <a href="../../index.php" class="btn btn-secondary">Back to Index</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
