<?php
session_start();
include 'DBConn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["name"];
    $password = $_POST["password"];
 //  $hashedPasswordToCheck = $password ; // The specific hash to compare

    // Check in the buyers table
    $sqlBuyer = "SELECT buyer_ID, buyer_Name, buyer_Password FROM buyer WHERE buyer_Name = ?";
    $stmtBuyer = $connect->prepare($sqlBuyer);
    $stmtBuyer->bind_param("s", $username);
    $stmtBuyer->execute();
    $resultBuyer = $stmtBuyer->get_result();

    if ($resultBuyer->num_rows == 1) {
        $rowBuyer = $resultBuyer->fetch_assoc();
        if ($password === trim($rowBuyer["buyer_Password"])) { // Direct comparison for the fixed hash
            $_SESSION["buyer_ID"] = $rowBuyer["buyer_ID"];
            $_SESSION["buyer_Name"] = $rowBuyer["buyer_Name"];
            $_SESSION["user_type"] = "buyer";
            header("Location: buyer_dashboard.php"); // Redirect to buyer dashboard
            exit();
        }
    }

    // Check in the sellers table 
    $sqlSeller = "SELECT seller_ID, seller_Name, seller_Password FROM seller WHERE seller_Name = ?";
    $stmtSeller = $connect->prepare($sqlSeller);
    $stmtSeller->bind_param("s", $username);
    $stmtSeller->execute();
    $resultSeller = $stmtSeller->get_result();

    if ($resultSeller->num_rows == 1) {
        $rowSeller = $resultSeller->fetch_assoc();
        if ($password === trim($rowSeller["seller_Password"])) { // Direct comparison for the fixed hash
            $_SESSION["seller_ID"] = $rowSeller["seller_ID"];
            $_SESSION["seller_Name"] = $rowSeller["seller_Name"];
            $_SESSION["user_type"] = "seller";
            header("Location: seller_dashboard.php"); // Redirect to seller dashboard
            exit();
        }
    }

    // Login failed
    header("Location: login.php?error=Invalid username or password");
    exit();
}

$conn->close();
?>