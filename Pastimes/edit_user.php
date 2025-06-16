<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

include 'DBConn.php';

if (isset($_GET["id"]) && isset($_GET["role"])) {
    $id = $_GET["id"];
    $role = $_GET["role"];
    $tableName = ($role === 'buyer') ? 'buyer' : (($role === 'seller') ? 'seller' : (($role === 'admin') ? 'admin' : ''));

    if (empty($tableName)) {
        header("Location: admin_dashboard.php?error=Invalid user role for edit");
        exit();
    }

    $primaryKey = ($role === 'buyer') ? 'buyer_ID' : (($role === 'seller') ? 'seller_ID' : 'admin_ID');
    $sqlSelect = "SELECT name, email FROM $tableName WHERE $primaryKey = ?";
    $stmtSelect = $conn->prepare($sqlSelect);
    $stmtSelect->bind_param("i", $id);
    $stmtSelect->execute();
    $resultSelect = $stmtSelect->get_result();

    if ($resultSelect->num_rows == 1) {
        $userData = $resultSelect->fetch_assoc();
    } else {
        header("Location: admin_dashboard.php?error=User not found for edit");
        exit();
    }
    $stmtSelect->close();
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?>



    <h1 style="text-align: center;"  >Edit User</h1>
    <form method="POST" action="edit_user_process.php" style="text-align: center;"  >
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="role" value="<?php echo $role; ?>">
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $userData["name"]; ?>" required>
        </div>
        <div>
            <label for="surname">Surname:</label>
            <input type="text" id="surname" name="surname" value="<?php echo $userData["surname"]; ?>" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $userData["email"]; ?>" required>
        </div>
        <div>
            <label for="password">New Password (leave blank to keep current):</label>
            <input type="password" id="password" name="password"   >
        </div>
        <button type="submit">Update User</button>
        <p style="text-align: center;"  ><a href="admin_dashboard.php">Back to Dashboard</a></p>
    </form>
