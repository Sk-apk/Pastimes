<?php
//including the DBConn helps with executing queries
include 'DBConn.php';

// Table names
$adminTable = "admin";
$buyersTable = "buyer";
$sellersTable = "seller";

// Drop tables if they exist
$conn->query("DROP TABLE IF EXISTS $adminTable");
$conn->query("DROP TABLE IF EXISTS $buyersTable");
$conn->query("DROP TABLE IF EXISTS $sellersTable");

// Create admin table
$sqlAdmin = "CREATE TABLE $adminTable (
    admin_ID INT AUTO_INCREMENT PRIMARY KEY,
    admin_Name VARCHAR(100) NOT NULL,
    admin_Surname VARCHAR(100) NOT NULL,
    admin_Email VARCHAR(100) NOT NULL,
    admin_Password VARCHAR(100) NOT NULL
)";



if ($conn->query($sqlAdmin) === TRUE) {
    echo "Admin table created successfully<br>";
} else {
    echo "Error creating admin table: " . $conn->error . "<br>";
}

// Create buyers table
$sqlBuyers = "CREATE TABLE $buyersTable (
    buyer_ID INT AUTO_INCREMENT PRIMARY KEY,
    buyer_Name VARCHAR(50) NOT NULL,
    buyer_Surname VARCHAR(100) NOT NULL,
    buyer_Email VARCHAR(100) NOT NULL,
    buyer_Password VARCHAR(255) NOT NULL
)";

if ($conn->query($sqlBuyers) === TRUE) {
    echo "Buyers table created successfully<br>";
} else {
    echo "Error creating buyers table: " . $conn->error . "<br>";
}

// Create sellers table
$sqlSellers = "CREATE TABLE $sellersTable (
    seller_ID INT AUTO_INCREMENT PRIMARY KEY,
    seller_Name VARCHAR(100) NOT NULL,
    seller_Surname VARCHAR(100),
    seller_Email VARCHAR(100) NOT NULL,
    seller_Password VARCHAR(100) NOT NULL
)";

if ($conn->query($sqlSellers) === TRUE) {
    echo "Sellers table created successfully<br>";
} else {
    echo "Error creating sellers table: " . $conn->error . "<br>";
}

// Load data from userData.txt into the buyers table
$file = fopen("userData.txt", "r");
if ($file) {
    while (($line = fgets($file)) !== false) {
        $data = explode(":", trim($line));
        if (count($data) === 4) {
            $name = $data[0];
            $surname = $data[1];
            $email = $data[2];
            $password = $data[3];

            $sqlInsertBuyer = "INSERT INTO $buyersTable (buyer_Name, buyer_Surname , buyer_Email, buyer_Password)
                             VALUES ('$name','$surname', '$email', '$password')";

            if ($conn->query($sqlInsertBuyer) === TRUE) {
                echo "Buyer data inserted for: $name<br>";
            } else {
                echo "Error inserting buyer data for $name: " . $conn->error . "<br>";
            }
        }
    }
    fclose($file);
} else {
    echo "Error opening userData.txt<br>";
}

$conn->close();
?>