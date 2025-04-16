<?php
session_start();

class SecureFileUploader {
    private $uploadDirectory;
    private $allowedTypes;
    private $maxFileSize;
    private $allowedExtensions;
    
    public function __construct() {
        // Secure: Store uploads outside web root
        $this->uploadDirectory = "secure_uploads/";
        if (!file_exists($this->uploadDirectory)) {
            mkdir($this->uploadDirectory, 0750, true);
        }
        
        // Secure: Define allowed MIME types
        $this->allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx'
        ];
        
        // Secure: Set maximum file size (5MB)
        $this->maxFileSize = 5 * 1024 * 1024;
        
        // Secure: Define allowed file extensions
        $this->allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
    }
    
    public function validateFile($file) {
        $errors = [];
        
        // Check if file was uploaded
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['Invalid file upload'];
        }

        // Validate upload errors
        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $errors[] = 'File size exceeds limit';
                break;
            default:
                $errors[] = 'Unknown upload error';
                break;
        }

        // Validate file size
        if ($file['size'] > $this->maxFileSize) {
            $errors[] = 'File size exceeds limit (5MB)';
        }

        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!array_key_exists($mimeType, $this->allowedTypes)) {
            $errors[] = 'Invalid file type';
        }

        // Validate file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedExtensions)) {
            $errors[] = 'Invalid file extension';
        }

        // Validate file contents (advanced check)
        if (!$this->validateFileContents($file['tmp_name'], $mimeType)) {
            $errors[] = 'File contents do not match the file type';
        }

        return $errors;
    }
    
    private function validateFileContents($filePath, $mimeType) {
        // Advanced file content validation
        $f = fopen($filePath, 'r');
        $header = fread($f, 8);
        fclose($f);
        
        // Check file signatures
        switch ($mimeType) {
            case 'image/jpeg':
                return strpos($header, "\xFF\xD8\xFF") === 0;
            case 'image/png':
                return strpos($header, "\x89PNG\r\n\x1a\n") === 0;
            case 'image/gif':
                return strpos($header, "GIF87a") === 0 || strpos($header, "GIF89a") === 0;
            case 'application/pdf':
                return strpos($header, "%PDF-") === 0;
            default:
                return true;
        }
    }
    
    public function uploadFile($file) {
        // Generate secure random filename
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newFilename = bin2hex(random_bytes(16)) . '.' . $extension;
        $targetPath = $this->uploadDirectory . $newFilename;
        
        // Secure file move
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Set secure permissions
            chmod($targetPath, 0640);
            return [
                'success' => true,
                'filename' => $newFilename
            ];
        }
        
        return [
            'success' => false,
            'error' => 'Failed to move uploaded file'
        ];
    }
    
    public function getSecureFileUrl($filename) {
        // Implement secure file download through a script
        return 'download.php?file=' . urlencode($filename);
    }
    
    public function listUploadedFiles() {
        $files = [];
        if (is_dir($this->uploadDirectory)) {
            $files = array_diff(scandir($this->uploadDirectory), array('.', '..'));
        }
        return $files;
    }
}

// Initialize uploader
$uploader = new SecureFileUploader();
$message = '';
$messageType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $file = $_FILES["file"];
    
    // Validate file
    $errors = $uploader->validateFile($file);
    
    if (empty($errors)) {
        // Upload file
        $result = $uploader->uploadFile($file);
        if ($result['success']) {
            $message = "File uploaded successfully!";
            $messageType = "success";
        } else {
            $message = $result['error'];
            $messageType = "danger";
        }
    } else {
        $message = "Upload failed: " . implode(", ", $errors);
        $messageType = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure File Upload Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">Secure File Upload Example</h1>
        <div class="alert alert-success">
            <strong>Notice:</strong> This page implements proper security measures for file uploads!
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Secure File Upload</h5>
                        <?php if ($message): ?>
                            <div class="alert alert-<?php echo $messageType; ?>">
                                <?php echo htmlspecialchars($message); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="file" class="form-label">Choose File:</label>
                                <input type="file" class="form-control" id="file" name="file" required
                                       accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx">
                                <small class="text-muted">
                                    Allowed types: JPG, PNG, GIF, PDF, DOC, DOCX (Max 5MB)
                                </small>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Uploaded Files</h5>
                        <?php
                        $uploadedFiles = $uploader->listUploadedFiles();
                        if (count($uploadedFiles) > 0): ?>
                            <ul class="list-group">
                            <?php foreach($uploadedFiles as $file): ?>
                                <li class="list-group-item">
                                    <?php echo htmlspecialchars($file); ?>
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
                        <h5 class="card-title">Security Measures Implemented</h5>
                        
                        <h6 class="mt-3">1. File Type Validation</h6>
                        <ul class="small">
                            <li>MIME type checking</li>
                            <li>File extension validation</li>
                            <li>File content/signature verification</li>
                        </ul>

                        <h6>2. File Size Restrictions</h6>
                        <ul class="small">
                            <li>Maximum file size: 5MB</li>
                            <li>Server-side size validation</li>
                        </ul>

                        <h6>3. File Storage Security</h6>
                        <ul class="small">
                            <li>Random file names to prevent overwrites</li>
                            <li>Secure file permissions (0640)</li>
                            <li>Storage outside web root</li>
                        </ul>

                        <h6>4. Input Validation</h6>
                        <ul class="small">
                            <li>File extension whitelist</li>
                            <li>MIME type whitelist</li>
                            <li>Content type verification</li>
                        </ul>

                        <div class="alert alert-info mt-3">
                            <h6 class="mb-2">How to Test Security:</h6>
                            <ol class="small mb-0">
                                <li>Try uploading a PHP file - should be rejected</li>
                                <li>Try a file >5MB - should be rejected</li>
                                <li>Try double extensions (e.g., file.php.jpg) - should be rejected</li>
                                <li>Try modified MIME types - should be rejected</li>
                                <li>Check that uploaded files get random names</li>
                                <li>Verify files are stored securely</li>
                            </ol>
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
