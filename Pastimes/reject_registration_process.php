<?php
require_once 'DBConn.php';

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $sqlDelete = "DELETE FROM pending_registrations WHERE id = ?";
    $stmtDelete = $connect->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $id);

    if ($stmtDelete->execute()) {
        header("Location: admin_dashboard.php?rejection_success=1");
        exit();
    } else {
        header("Location: admin_dashboard.php?rejection_error=1");
        exit();
    }
    $stmtDelete->close();
} else {
    header("Location: admin_dashboard.php");
    exit();
}

$connect->close();
?>