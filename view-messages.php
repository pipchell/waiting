<?php
// Simple admin page to view contact messages
// IMPORTANT: Change this password!
$admin_password = 'your_secure_password_here'; // CHANGE THIS!

session_start();

// Handle login
if (isset($_POST['password'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $error = 'Incorrect password';
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: view-messages.php');
    exit;
}

// Check if logged in
if (!isset($_SESSION['admin_logged_in'])) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
                background: #f5f5f5;
            }
            .login-box {
                background: white;
                padding: 2rem;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                width: 300px;
            }
            h2 { margin-top: 0; }
            input[type="password"] {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border: 1px solid #ddd;
                border-radius: 4px;
                box-sizing: border-box;
            }
            button {
                width: 100%;
                padding: 10px;
                background: #4696e5;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-weight: bold;
            }
            button:hover { background: #3089e2; }
            .error { color: red; margin-top: 10px; }
        </style>
    </head>
    <body>
        <div class="login-box">
            <h2>Contact Messages Admin</h2>
            <form method="POST">
                <input type="password" name="password" placeholder="Enter password" required>
                <button type="submit">Login</button>
            </form>
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Get all message files
$messages_dir = __DIR__ . '/contact_messages';
$messages = [];

if (is_dir($messages_dir)) {
    $files = glob($messages_dir . '/message_*.txt');
    rsort($files); // Newest first
    
    foreach ($files as $file) {
        $messages[] = [
            'filename' => basename($file),
            'content' => file_get_contents($file),
            'date' => filemtime($file)
        ];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact Messages</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 { color: #333; }
        .logout {
            padding: 8px 16px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .logout:hover { background: #c82333; }
        .message {
            background: white;
            padding: 1.5rem;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .message pre {
            white-space: pre-wrap;
            font-family: monospace;
            line-height: 1.6;
        }
        .no-messages {
            background: white;
            padding: 2rem;
            text-align: center;
            border-radius: 8px;
            color: #666;
        }
        .count {
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Contact Messages</h1>
                <p class="count"><?php echo count($messages); ?> total messages</p>
            </div>
            <a href="?logout=1" class="logout">Logout</a>
        </div>
        
        <?php if (empty($messages)): ?>
            <div class="no-messages">
                <h2>No messages yet</h2>
                <p>Contact form submissions will appear here.</p>
            </div>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <div class="message">
                    <pre><?php echo htmlspecialchars($msg['content']); ?></pre>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
