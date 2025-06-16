<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

//error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Debugging: Check if admin_id is set in session
 echo "DEBUG: delete_user.php - Session Admin ID: " . (isset($_SESSION["admin_ID"]) ? $_SESSION["admin_ID"] : "NOT SET") . "<br>";
 echo "DEBUG: delete_user.php - Session User Type: " . (isset($_SESSION["user_type"]) ? $_SESSION["user_type"] : "NOT SET") . "<br>";


if (!isset($_SESSION["admin_id"])) {
 echo "DEBUG: delete_user.php - Admin ID not set in session. Redirecting to admin_login.html.<br>";
    header("Location: admin_login.php");
    exit();
}

include 'DBConn.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['role'])) {
    $user_id = $_GET['id'];
    $role = $_GET['role'];

    $tableName = "";
    $idColumn = ""; 

    if ($role === 'buyer') {
        $tableName = "buyer";
        $idColumn = "buyer_ID";
    } elseif ($role === 'seller') {
        $tableName = "seller";
        $idColumn = "seller_ID";
    } elseif ($role === 'admin') {
        // Prevent deleting the currently logged-in admin
        if ($user_id == $_SESSION["admin_id"]) {
            header("Location: admin_dashboard.php?error=" . urlencode("Cannot delete yourself."));
            exit();
        }
        $tableName = "admin";
        $idColumn = "admin_ID"; 
    } else {
        header("Location: admin_dashboard.php?error=" . urlencode("Invalid user role specified for deletion."));
        exit();
    }

    // Preparing and executing the delete statement
    $sqlDelete = "DELETE FROM $tableName WHERE $idColumn = ?";
    $stmtDelete = $connect->prepare($sqlDelete);

    if ($stmtDelete) {
        $stmtDelete->bind_param("i", $user_id);
        if ($stmtDelete->execute()) {
            $stmtDelete->close();
            header("Location: admin_dashboard.php?user_deleted=1");
            exit();
        } else {
            // Debugging: If delete fails, output the error
            echo "Error deleting user from $tableName: " . $stmtDelete->error;
            $stmtDelete->close();
            $connect->close();
            exit();
        }
    } else {
        // Debugging: If prepare fails, output the error
        echo "Error preparing delete statement for $tableName: " . $connect->error;
        $connect->close();
        exit();
    }
} else {
    header("Location: admin_dashboard.php?error=" . urlencode("Invalid user ID or role for deletion."));
    exit();
}

$connect->close();
?>
