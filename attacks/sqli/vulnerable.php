<?php
require_once '../../config/database.php';
$title = "Vulnerable SQL Injection Example";
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
            <strong>Warning:</strong> This page is intentionally vulnerable to SQL injection attacks!
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">User Login (Vulnerable)</h5>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" 
                               placeholder="Try: admin' OR '1'='1">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Try: anything">
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>

                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    
                    // Vulnerable query
                    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
                    $result = $conn->query($query);
                    
                    echo "<div class='mt-3'>";
                    echo "<p>Query executed: <code>" . htmlspecialchars($query) . "</code></p>";
                    
                    if ($result && $result->num_rows > 0) {
                        echo "<div class='alert alert-success'>Login successful!</div>";
                        echo "<h6>Retrieved user data:</h6>";
                        echo "<table class='table table-bordered'>";
                        echo "<tr><th>ID</th><th>Username</th><th>Email</th></tr>";
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<div class='alert alert-warning'>Login failed!</div>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Product Search (Vulnerable)</h5>
                <form method="GET" action="">
                    <div class="mb-3">
                        <label for="search" class="form-label">Search Products:</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Try: ' UNION SELECT id, username, password, email FROM users; -- ">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>

                <?php
                if (isset($_GET['search'])) {
                    $search = $_GET['search'];
                    
                    // Vulnerable query
                    $query = "SELECT * FROM products WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
                    $result = $conn->query($query);
                    
                    echo "<div class='mt-3'>";
                    echo "<p>Query executed: <code>" . htmlspecialchars($query) . "</code></p>";
                    
                    if ($result && $result->num_rows > 0) {
                        echo "<table class='table table-bordered'>";
                        echo "<tr><th>ID</th><th>Name</th><th>Description</th></tr>";
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<div class='alert alert-warning'>No products found!</div>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <div class="mt-4">
            <h3>How these vulnerabilities work:</h3>
            <ul>
                <li>The login form is vulnerable to SQL injection through unescaped input</li>
                <li>The search form allows UNION-based attacks to extract data from other tables</li>
                <li>Try entering <code>admin' OR '1'='1</code> in the username field with any password</li>
                <li>Try entering <code>' UNION SELECT id, username, password FROM users; -- </code> in the search field</li>
            </ul>
        </div>

        <div class="mt-4">
            <a href="../../index.php" class="btn btn-secondary">Back to Index</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
