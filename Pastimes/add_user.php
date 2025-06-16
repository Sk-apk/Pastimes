<h1 style="text-align: center;"  >Add New User</h1>
<link rel="stylesheet" href="style.css">

    <form method="POST" action="add_user_process.php" style="text-align: center;"  >
        <div class="input-group">
            <label for="name">Name:</label> <br>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="input-group">
            <label for="surname">Surname:</label> <br>
            <input type="text" id="surname" name="surname" required>
        </div>
        <div class="input-group">
            <label for="email">Email:</label> <br>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="input-group">
            <label for="password">Password:</label> <br>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="input-group">
            <label for="role">Role:</label>
            <select id="role" name="role">
                <option value="buyer">Buyer</option>
                <option value="seller">Seller</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit">Add User</button>
        <p><a href="admin_dashboard.php">Back to Dashboard</a></p>
    </form>