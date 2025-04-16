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
        
        <div class="alert alert-info mb-4">
            <h4>Understanding Web Security Vulnerabilities</h4>
            <p>This demonstration showcases common web security vulnerabilities and how to prevent them. Each example includes both a vulnerable and secure implementation.</p>
        </div>

        <div class="row">
            <!-- RFI (Remote File Inclusion) -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="h5 mb-0">Remote File Inclusion (RFI)</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">RFI allows attackers to include remote files from external servers. This can lead to:</p>
                        <ul class="small mb-3">
                            <li>Code execution from malicious remote files</li>
                            <li>Server compromise</li>
                            <li>Data theft</li>
                        </ul>
                        <a href="attacks/rfi/vulnerable.php" class="btn btn-danger mb-2">Vulnerable Example</a>
                        <a href="attacks/rfi/secure.php" class="btn btn-success">Secure Example</a>
                    </div>
                </div>
            </div>
            
            <!-- SQL Injection -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="h5 mb-0">SQL Injection</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">SQL injection allows attackers to manipulate database queries. This can result in:</p>
                        <ul class="small mb-3">
                            <li>Unauthorized data access</li>
                            <li>Data manipulation or deletion</li>
                            <li>Authentication bypass</li>
                        </ul>
                        <a href="attacks/sqli/vulnerable.php" class="btn btn-danger mb-2">Vulnerable Example</a>
                        <a href="attacks/sqli/secure.php" class="btn btn-success">Secure Example</a>
                    </div>
                </div>
            </div>
            
            <!-- XSS (Cross-Site Scripting) -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="h5 mb-0">Cross-Site Scripting (XSS)</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">XSS allows injection of malicious scripts into web pages. This enables:</p>
                        <ul class="small mb-3">
                            <li>Cookie theft and session hijacking</li>
                            <li>Defacement of websites</li>
                            <li>Malicious redirects</li>
                        </ul>
                        <a href="attacks/xss/vulnerable.php" class="btn btn-danger mb-2">Vulnerable Example</a>
                        <a href="attacks/xss/secure.php" class="btn btn-success">Secure Example</a>
                    </div>
                </div>
            </div>

            <!-- CSRF (Cross-Site Request Forgery) -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="h5 mb-0">Cross-Site Request Forgery (CSRF)</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">CSRF tricks users into performing unwanted actions. Can lead to:</p>
                        <ul class="small mb-3">
                            <li>Unauthorized transactions</li>
                            <li>Account modifications</li>
                            <li>Data manipulation</li>
                        </ul>
                        <a href="attacks/csrf/vulnerable.php" class="btn btn-danger mb-2">Vulnerable Example</a>
                        <a href="attacks/csrf/secure.php" class="btn btn-success">Secure Example</a>
                    </div>
                </div>
            </div>

            <!-- File Upload Vulnerabilities -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="h5 mb-0">File Upload Vulnerabilities</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">Insecure file uploads can lead to:</p>
                        <ul class="small mb-3">
                            <li>Execution of malicious files</li>
                            <li>Server-side code execution</li>
                            <li>Web shell uploads</li>
                        </ul>
                        <a href="attacks/fileupload/vulnerable.php" class="btn btn-danger mb-2">Vulnerable Example</a>
                        <a href="attacks/fileupload/secure.php" class="btn btn-success">Secure Example</a>
                    </div>
                </div>
            </div>

            <!-- Command Injection -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="h5 mb-0">Command Injection</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">Command injection allows execution of system commands. Risks include:</p>
                        <ul class="small mb-3">
                            <li>Server compromise</li>
                            <li>File system access</li>
                            <li>System command execution</li>
                        </ul>
                        <a href="attacks/cmdi/vulnerable.php" class="btn btn-danger mb-2">Vulnerable Example</a>
                        <a href="attacks/cmdi/secure.php" class="btn btn-success">Secure Example</a>
                    </div>
                </div>
            </div>

            <!-- Local File Inclusion (LFI) -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="h5 mb-0">Local File Inclusion (LFI)</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">LFI vulnerabilities allow access to local files. This can expose:</p>
                        <ul class="small mb-3">
                            <li>Sensitive configuration files</li>
                            <li>System files and logs</li>
                            <li>Application source code</li>
                        </ul>
                        <a href="attacks/lfi/vulnerable.php" class="btn btn-danger mb-2">Vulnerable Example</a>
                        <a href="attacks/lfi/secure.php" class="btn btn-success">Secure Example</a>
                    </div>
                </div>
            </div>

            <!-- Path Traversal -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="h5 mb-0">Path Traversal Attack</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">Path traversal allows access to files outside intended directories. Risks include:</p>
                        <ul class="small mb-3">
                            <li>Access to sensitive files</li>
                            <li>Configuration file exposure</li>
                            <li>Source code disclosure</li>
                        </ul>
                        <a href="attacks/pathtraversal/vulnerable.php" class="btn btn-danger mb-2">Vulnerable Example</a>
                        <a href="attacks/pathtraversal/secure.php" class="btn btn-success">Secure Example</a>
                    </div>
                </div>
            </div>

            <!-- Denial of Service (DoS) -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="h5 mb-0">Denial of Service (DoS)</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">DoS vulnerabilities can overwhelm server resources. Impacts include:</p>
                        <ul class="small mb-3">
                            <li>Server performance degradation</li>
                            <li>Resource exhaustion</li>
                            <li>Service unavailability</li>
                        </ul>
                        <a href="attacks/dos/vulnerable.php" class="btn btn-danger mb-2">Vulnerable Example</a>
                        <a href="attacks/dos/secure.php" class="btn btn-success">Secure Example</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-warning mt-4">
            <h4>⚠️ Educational Purpose Only</h4>
            <p class="mb-0">These examples are for educational purposes only. Never implement vulnerable code in production environments. Always follow security best practices and keep your systems updated.</p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
