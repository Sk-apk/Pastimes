<?php
include 'DBConn.php';

$adminUsername = 'superadmin';
$adminSurname = 'root';
$adminEmail = 'admin@example.com';
$adminPassword = 'Super@1234';
$hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);

$setupCompletedFile = 'admin_setup_completed.txt'; // File to indicate setup is done

if (!file_exists($setupCompletedFile)) { // Check if setup has been completed
    $sqlCheck = "SELECT admin_ID FROM admin WHERE admin_Name = ?";
    $stmtCheck = $connect->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $adminUsername);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows == 0) {
        $sqlInsert = "INSERT INTO admin (admin_Name, admin_Surname, admin_Email, admin_Password) VALUES (?, ?, ?, ?)";
        $stmtInsert = $connect->prepare($sqlInsert);
        $stmtInsert->bind_param("ssss", $adminUsername, $adminSurname, $adminEmail, $hashedPassword);

        if ($stmtInsert->execute()) {
            echo "Initial admin user created successfully!<br>";
             // Create a file to indicate setup is complete
            file_put_contents($setupCompletedFile, 'Setup completed on ' . date('Y-m-d H:i:s'));
            echo "Setup completion marker file created.<br>";
        } else {
            echo "Error creating initial admin user: " . $conn->error . "<br>";
        }
        $stmtInsert->close();
    } else {
        echo "Admin user with username '$adminUsername' already exists.<br>";
        echo "Setup was likely completed previously.<br>";
    }
    $stmtCheck->close();
} else {
    echo "Initial admin setup has already been completed.<br>";
    echo "This script should not be run again.<br>";
}

$connect->close();





?>