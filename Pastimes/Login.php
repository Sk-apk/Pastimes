
<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <link rel="stylesheet" href="login_style.css">
</head>
<body>

    <h1>Pastimes</h1>
    <h2>WELCOME</h2>

    <?php if (isset($_GET['error'])) { ?>
        <p style="color: red; text-align: center"><?php echo $_GET['error']; ?></p>
    <?php } ?>
    <form method="POST" action="Login_process.php">
        <div class="input-group">
            <img src="https://img.icons8.com/ios-filled/50/user.png" alt="User Icon">
            <input type="text" id="name" name="name" placeholder="Username" required>
    </div>
       
        <div class="input-group">
            <img src="https://img.icons8.com/ios-filled/50/lock-2.png" alt="Password Icon">
            <input type="password" id="password" name="password" placeholder="Password" required>
    </div>

        
        <button type="submit">Login</button>
        <a href="Signup.php">Don't have an account?</a>
    </form>
        <div class="footer">www.Pastimes.com</div>
 </body>
 </html>