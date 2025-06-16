</!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Pastimes</h1>
    <h2>ADMIN LOGIN</h2>

    <?php if (isset($_GET['error'])) { ?>
        <p><?php echo $_GET['error']; ?></p>
    <?php } ?>

    <form method="POST" action="admin_login_process.php">
        <div class="input-group">
            <input type="text" id="name" name="name" Placeholder="Admin Name" required>
        </div>

        <div class="input-group">
            <input type="password" id="password" name="password" Placeholder="Password" required>
        </div>
        
            <button type="submit">Login</button>
    </form>
    </div>
</body>
</html>