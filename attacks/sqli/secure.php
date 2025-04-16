<?php
require_once '../../config/database.php';
$title = "Secure SQL Injection Prevention Example";

// Initialize error/success messages
$message = '';
$messageType = '';
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
            <strong>Notice:</strong> This page implements proper security measures against SQL injection attacks!
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Secure User Login</h5>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" 
                               required pattern="[a-zA-Z0-9]+" title="Only alphanumeric characters allowed">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary">Login</button>
                </form>

                <?php
                if (isset($_POST['login'])) {
                    // Validate input
                    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                    $password = $_POST['password'];
                    
                    if (preg_match('/^[a-zA-Z0-9]+$/', $username)) {
                        // Prepare statement
                        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
                        $stmt->bind_param("ss", $username, $password);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        echo "<div class='mt-3'>";
                        echo "<p>Secure query using prepared statement - SQL injection is prevented!</p>";
                        
                        if ($result->num_rows > 0) {
                            echo "<div class='alert alert-success'>Login successful!</div>";
                            $user = $result->fetch_assoc();
                            echo "<div class='alert alert-info'>Welcome " . htmlspecialchars($user['username']) . "!</div>";
                        } else {
                            echo "<div class='alert alert-warning'>Invalid credentials!</div>";
                        }
                        echo "</div>";
                        $stmt->close();
                    } else {
                        echo "<div class='alert alert-danger'>Invalid username format!</div>";
                    }
                }
                ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Secure Product Search</h5>
                <form method="GET" action="">
                    <div class="mb-3">
                        <label for="search" class="form-label">Search Products:</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               maxlength="50" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>

                <?php
                if (isset($_GET['search'])) {
                    // Sanitize and validate input
                    $search = filter_var($_GET['search'], FILTER_SANITIZE_STRING);
                    
                    // Prepare statement
                    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
                    $searchParam = "%" . $search . "%";
                    $stmt->bind_param("ss", $searchParam, $searchParam);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    echo "<div class='mt-3'>";
                    echo "<p>Secure query using prepared statement - SQL injection is prevented!</p>";
                    
                    if ($result->num_rows > 0) {
                        echo "<table class='table table-bordered'>";
                        echo "<tr><th>Name</th><th>Price</th><th>Description</th></tr>";
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>$" . htmlspecialchars($row['price']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<div class='alert alert-warning'>No products found!</div>";
                    }
                    echo "</div>";
                    $stmt->close();
                }
                ?>
            </div>
        </div>

        <div class="mt-4">
            <h3>Security Measures Implemented:</h3>
            <ul>
                <li>Prepared Statements to prevent SQL injection</li>
                <li>Input validation and sanitization</li>
                <li>Parameter binding</li>
                <li>HTML escaping of output data</li>
                <li>Input length restrictions</li>
                <li>Proper error handling</li>
            </ul>
        </div>

        <div class="mt-4">
            <a href="../../index.php" class="btn btn-secondary">Back to Index</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
