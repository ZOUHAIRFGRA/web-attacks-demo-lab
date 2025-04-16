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

// Generate CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error_message = '';
$success_message = '';

// Secure money transfer function
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error_message = "Invalid CSRF token!";
    } else if (isset($_POST['amount'], $_POST['recipient'])) {
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $error_message = "Token validation failed!";
        } else {
            $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
            $recipient = filter_input(INPUT_POST, 'recipient', FILTER_SANITIZE_STRING);
            
            if ($amount && $amount > 0 && $amount <= $_SESSION['user']['balance']) {
                $_SESSION['user']['balance'] -= $amount;
                $success_message = "Successfully transferred $${amount} to ${recipient}";
                
                // Generate new token after successful transaction
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            } else {
                $error_message = "Invalid amount or insufficient funds";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure CSRF Prevention Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">Secure CSRF Prevention Example</h1>
        <div class="alert alert-success">
            <strong>Notice:</strong> This page implements proper security measures against CSRF attacks!
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
                        <h5 class="card-title">Secure Money Transfer</h5>
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                        <?php endif; ?>
                        <?php if ($success_message): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" onsubmit="return validateForm()">
                            <!-- Hidden CSRF Token -->
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                            
                            <div class="mb-3">
                                <label for="recipient" class="form-label">Recipient:</label>
                                <input type="text" class="form-control" id="recipient" name="recipient" 
                                       pattern="[a-zA-Z0-9_-]+" maxlength="50" required
                                       title="Only alphanumeric characters, underscore, and hyphen allowed">
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount ($):</label>
                                <input type="number" class="form-control" id="amount" name="amount" 
                                       min="1" max="<?php echo $_SESSION['user']['balance']; ?>" step="0.01" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Transfer Money</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Security Measures</h5>
                        <p>This form is protected against CSRF attacks using multiple security measures:</p>
                        <ul>
                            <li>CSRF Token Generation and Validation</li>
                            <li>Synchronizer Token Pattern</li>
                            <li>Token Regeneration after Form Submission</li>
                            <li>Input Validation and Sanitization</li>
                            <li>Secure Token Comparison (hash_equals)</li>
                        </ul>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">Current CSRF Token:</p>
                            <code class="user-select-all"><?php echo htmlspecialchars($_SESSION['csrf_token']); ?></code>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h3>Security Measures Implemented:</h3>
            <ul>
                <li>Unique CSRF token generated for each session</li>
                <li>Token regeneration after each successful transaction</li>
                <li>Secure token generation using random_bytes()</li>
                <li>Constant-time string comparison with hash_equals()</li>
                <li>Input validation and sanitization</li>
                <li>Proper error handling and user feedback</li>
                <li>Maximum transfer amount limited to current balance</li>
            </ul>
        </div>

        <div class="mt-4">
            <a href="../../index.php" class="btn btn-secondary">Back to Index</a>
        </div>
    </div>

    <script nonce="random123">
        function validateForm() {
            const amount = parseFloat(document.getElementById('amount').value);
            const balance = <?php echo $_SESSION['user']['balance']; ?>;
            
            if (isNaN(amount) || amount <= 0) {
                alert('Please enter a valid amount');
                return false;
            }
            
            if (amount > balance) {
                alert('Insufficient funds');
                return false;
            }
            
            return true;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
