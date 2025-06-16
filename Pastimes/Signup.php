
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

    <h1>Pastimes</h1>
    <h2>USER REGISTRATION</h2>

    <form method="POST" action="Signup_process.php">
        <div class="input-group">
            <input type="text" id="name" name="name" placeholder="Name" required>
        </div>

        <div class="input-group">
            <input type="text" id="surname" name="surname" placeholder="Surame" required>
        </div>

        <div class="input-group">
            <input type="text" id="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-group">
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>

        <div class="input-group">
            <select id="user_type" name="user_type">
                <option value="buyer">Buyer</option>
                <option value="seller">Seller</option>
            </select>
        </div>
        <button type="submit">Register</button>
        <p>Already have an account? <a href="Login.php">Login here</a></p>
    </form>

</body>
</html>