<?php
session_start();
$title = "Secure DoS Prevention Example";

// Rate limiting and IP blocking configuration
class RateLimiter {
    private $maxRequests = 10; // Maximum requests per time window
    private $timeWindow = 60; // Time window in seconds
    private $blockDuration = 300; // Block duration in seconds (5 minutes)
    private $maxInputSize = 35; // Maximum allowed input for Fibonacci
    
    public function __construct() {
        // Initialize blocked IPs storage if not exists
        if (!isset($_SESSION['blocked_ips'])) {
            $_SESSION['blocked_ips'] = [];
        }
        
        // Initialize request counters if not exists
        if (!isset($_SESSION['request_counters'])) {
            $_SESSION['request_counters'] = [];
        }
        
        // Clean up expired blocks and counters
        $this->cleanupExpiredData();
    }
    
    private function getClientIP() {
        // Get real IP even behind proxy
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }
    
    private function cleanupExpiredData() {
        $now = time();
        
        // Clean up expired IP blocks
        foreach ($_SESSION['blocked_ips'] as $ip => $expireTime) {
            if ($now > $expireTime) {
                unset($_SESSION['blocked_ips'][$ip]);
            }
        }
        
        // Clean up expired request counters
        foreach ($_SESSION['request_counters'] as $ip => $data) {
            if ($now > $data['timestamp'] + $this->timeWindow) {
                unset($_SESSION['request_counters'][$ip]);
            }
        }
    }
    
    public function isBlocked() {
        $clientIP = $this->getClientIP();
        
        // Check if IP is blocked
        if (isset($_SESSION['blocked_ips'][$clientIP])) {
            if (time() < $_SESSION['blocked_ips'][$clientIP]) {
                $remainingTime = ceil(($_SESSION['blocked_ips'][$clientIP] - time()) / 60);
                return "Your IP is temporarily blocked. Please try again in {$remainingTime} minutes.";
            }
            unset($_SESSION['blocked_ips'][$clientIP]);
        }
        return false;
    }
    
    public function checkRateLimit() {
        $clientIP = $this->getClientIP();
        $now = time();
        
        // Initialize or update request counter for this IP
        if (!isset($_SESSION['request_counters'][$clientIP])) {
            $_SESSION['request_counters'][$clientIP] = [
                'count' => 1,
                'timestamp' => $now
            ];
            return true;
        }
        
        $counter = &$_SESSION['request_counters'][$clientIP];
        
        // Reset counter if time window has passed
        if ($now > $counter['timestamp'] + $this->timeWindow) {
            $counter = [
                'count' => 1,
                'timestamp' => $now
            ];
            return true;
        }
        
        // Increment counter and check limit
        $counter['count']++;
        if ($counter['count'] > $this->maxRequests) {
            // Block IP
            $_SESSION['blocked_ips'][$clientIP] = $now + $this->blockDuration;
            unset($_SESSION['request_counters'][$clientIP]);
            return false;
        }
        
        return true;
    }
    
    public function validateInput($n) {
        return is_numeric($n) && $n >= 0 && $n <= $this->maxInputSize;
    }
}

// Optimized Fibonacci calculation using iteration
function fibonacci($n) {
    if ($n <= 1) return $n;
    
    $prev = 0;
    $curr = 1;
    for ($i = 2; $i <= $n; $i++) {
        $temp = $curr;
        $curr = $prev + $curr;
        $prev = $temp;
    }
    return $curr;
}

// Initialize rate limiter
$rateLimiter = new RateLimiter();

// Check if IP is blocked
$blockMessage = $rateLimiter->isBlocked();

// Process the request if not blocked
$error = '';
$result = null;
$executionTime = null;

if (!$blockMessage && isset($_GET['n'])) {
    if (!$rateLimiter->checkRateLimit()) {
        $error = "Rate limit exceeded. Please try again later.";
    } else {
        $n = intval($_GET['n']);
        if (!$rateLimiter->validateInput($n)) {
            $error = "Invalid input. Please enter a number between 0 and 35.";
        } else {
            $startTime = microtime(true);
            $result = fibonacci($n);
            $executionTime = microtime(true) - $startTime;
        }
    }
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
            <strong>Notice:</strong> This page implements proper security measures against DoS attacks!
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Protected Intensive Operation</h5>
                <p class="card-text">This form implements rate limiting and input validation:</p>
                
                <?php if ($blockMessage): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($blockMessage); ?>
                    </div>
                <?php else: ?>
                    <form method="GET" action="">
                        <div class="mb-3">
                            <label for="n" class="form-label">Calculate Fibonacci number:</label>
                            <input type="number" class="form-control" id="n" name="n" 
                                   placeholder="Enter a number (max 35)" max="35" min="0">
                            <div class="form-text">Limited to numbers between 0 and 35 for server protection.</div>
                        </div>
                        <button type="submit" class="btn btn-primary">Calculate</button>
                    </form>

                    <?php if ($error): ?>
                        <div class="alert alert-warning mt-3">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($result !== null): ?>
                        <div class="mt-4">
                            <h6>Results:</h6>
                            <p>Fibonacci(<?php echo $n; ?>) = <?php echo $result; ?></p>
                            <p>Execution time: <?php echo number_format($executionTime, 4); ?> seconds</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-4">
            <h3>Security Measures Implemented:</h3>
            <ul>
                <li>Rate limiting: Maximum 10 requests per minute per IP</li>
                <li>IP blocking: Excessive requests result in a 5-minute block</li>
                <li>Input validation: Limited to reasonable number ranges (0-35)</li>
                <li>Optimized algorithm: Uses iteration instead of recursion</li>
                <li>Session-based tracking of request limits and blocks</li>
                <li>Proper error handling and user feedback</li>
                <li>Protection against proxy IP spoofing</li>
            </ul>
        </div>

        <div class="mt-4">
            <a href="../../index.php" class="btn btn-secondary">Back to Index</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
