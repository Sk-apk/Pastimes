<?php
// edit_user_process.php
include 'DBConn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $role = $_POST["role"];
    $username = $_POST["name"];
    $surname = $_POST["surname"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $tableName = "";
    if ($role === 'buyer') {
        $tableName = "buyer";
    } elseif ($role === 'seller') {
        $tableName = "seller";
    } elseif ($role === 'admin') {
        $tableName = "admin";
    } else {
        header("Location: admin_dashboard.php?error=Invalid user role for update");
        exit();
    }

    $primaryKey = "";
    if ($role === 'buyer') {
        $primaryKey = "buyer_id";
    } elseif ($role === 'seller') {
        $primaryKey = "seller_id";
    } elseif ($role === 'admin') {
        $primaryKey = "admin_id";
    }

    $sqlUpdate = "UPDATE $tableName SET name = ?, email = ?";
    $params = [$username, $email];

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sqlUpdate .= ", password_hash = ?";
        $params[] = $hashedPassword;
    }

    $sqlUpdate .= " WHERE $primaryKey = ?";
    $params[] = $id;

    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param(str_repeat("s", count($params)), ...$params); 

    if ($stmtUpdate->execute()) {
        header("Location: admin_dashboard.php?user_updated=1");
        exit();
    } else {
        header("Location: edit_user.php?id=$id&role=$role&error=Error updating user: " . $conn->error);
        exit();
    }
    $stmtUpdate->close();
} else {
    header("Location: admin_dashboard.php");
    exit();
}

$conn->close();
?>