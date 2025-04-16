<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Security Demonstration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">Web Security Demonstrations</h1>
        <div class="row">
            <!-- RFI (Remote File Inclusion) -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="h5 mb-0">Remote File Inclusion (RFI)</h3>
                    </div>
                    <div class="card-body">
                        <p>Demonstrate RFI vulnerabilities and prevention techniques.</p>
                        <a href="attacks/rfi/vulnerable.php" class="btn btn-danger mb-2">Vulnerable Example</a>
                        <a href="attacks/rfi/secure.php" class="btn btn-success">Secure Example</a>
                    </div>
                </div>
            </div>
            
            <!-- SQL Injection -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="h5 mb-0">SQL Injection</h3>
                    </div>
                    <div class="card-body">
                        <p>Demonstrate SQL injection attacks and prevention.</p>
                        <a href="attacks/sqli/vulnerable.php" class="btn btn-danger mb-2">Vulnerable Example</a>
                        <a href="attacks/sqli/secure.php" class="btn btn-success">Secure Example</a>
                    </div>
                </div>
            </div>
            
            <!-- XSS (Cross-Site Scripting) -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="h5 mb-0">Cross-Site Scripting (XSS)</h3>
                    </div>
                    <div class="card-body">
                        <p>Demonstrate XSS vulnerabilities and prevention.</p>
                        <a href="attacks/xss/vulnerable.php" class="btn btn-danger mb-2">Vulnerable Example</a>
                        <a href="attacks/xss/secure.php" class="btn btn-success">Secure Example</a>
                    </div>
                </div>
            </div>

            <!-- CSRF (Cross-Site Request Forgery) -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="h5 mb-0">Cross-Site Request Forgery (CSRF)</h3>
                    </div>
                    <div class="card-body">
                        <p>Demonstrate CSRF attacks and prevention.</p>
                        <a href="attacks/csrf/vulnerable.php" class="btn btn-danger mb-2">Vulnerable Example</a>
                        <a href="attacks/csrf/secure.php" class="btn btn-success">Secure Example</a>
                    </div>
                </div>
            </div>

            <!-- File Upload Vulnerabilities -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="h5 mb-0">File Upload Vulnerabilities</h3>
                    </div>
                    <div class="card-body">
                        <p>Demonstrate file upload vulnerabilities and secure handling.</p>
                        <a href="attacks/fileupload/vulnerable.php" class="btn btn-danger mb-2">Vulnerable Example</a>
                        <a href="attacks/fileupload/secure.php" class="btn btn-success">Secure Example</a>
                    </div>
                </div>
            </div>

            <!-- Command Injection -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="h5 mb-0">Command Injection</h3>
                    </div>
                    <div class="card-body">
                        <p>Demonstrate command injection vulnerabilities and prevention.</p>
                        <a href="attacks/cmdi/vulnerable.php" class="btn btn-danger mb-2">Vulnerable Example</a>
                        <a href="attacks/cmdi/secure.php" class="btn btn-success">Secure Example</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
