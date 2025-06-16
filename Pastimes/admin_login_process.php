<?php
session_start();
include 'DBConn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["name"];
    $password = $_POST["password"];

    $sqlAdmin = "SELECT admin_ID, admin_Name, admin_Surname , admin_Password FROM  admin  WHERE admin_Name = ?";
    $stmtAdmin = $connect->prepare($sqlAdmin);
    $stmtAdmin->bind_param("s", $username);
    $stmtAdmin->execute();
    $resultAdmin = $stmtAdmin->get_result();

    if ($resultAdmin->num_rows == 1) {
        $rowAdmin = $resultAdmin->fetch_assoc();
        if (password_verify($password, $rowAdmin["admin_Password"])) {
            $_SESSION["admin_ID"] = $rowAdmin["admin_ID"];
            $_SESSION["admin_Name"] = $rowAdmin["admin_Name"];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            header("Location: admin_login.php?error=Invalid username or password");
            exit();
        }
    } else {
        header("Location: admin_login.php?error=Invalid username or password");
        exit();
    }
    $stmtAdmin->close();
}

$conn->close();
?>