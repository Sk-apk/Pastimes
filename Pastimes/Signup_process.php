<?php
include 'DBConn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["name"];
    $surname = $_POST["surname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $userType = $_POST["user_type"];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password properly

    // Check if name and email already exists in  the buyer, seller, and pending  table
    $checkSql = "SELECT buyer_Name, buyer_Email FROM buyer WHERE buyer_Name = ? OR buyer_Email = ?
                 UNION
                 SELECT seller_Name, seller_Email FROM seller WHERE seller_Name = ? OR seller_Email = ?
                 UNION
                 SELECT pen_Name, pen_Email FROM pending_registrations WHERE pen_Name = ? OR pen_Email = ?";
    $checkStmt = $connect->prepare($checkSql);

    if($checkStmt) {
$checkStmt->bind_param("sssss", $username, $surname, $email, $password, $userType);
 $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

 if ($checkResult->num_rows > 0) {
    $checkStmt->close();
        header("Location: Signup.php?error=Username or email already exists");
        exit();
    }
    $checkStmt->close();

    } else {
        echo "Error preparing statement (check): " . $conn->error;
        $conn->close();
        exit();
    }
    
   

   

    // Insert into pending registrations
    $sqlInsertPending = "INSERT INTO pending_registrations (pen_Name, pen_Surname, pen_Email, pen_Password, pen_User_type)
                         VALUES (?, ?, ?, ?, ?)";
    $insertStmtPending = $connect->prepare($sqlInsertPending);

    if ($insertStmtPending){
  $insertStmtPending->bind_param("sssss", $username, $surname , $email, $password, $userType);

  if ($insertStmtPending->execute()){
    $insertStmtPending->close();
    header("Location: Login.php?registration_pending=1"); // Redirect with a message
    exit();
  }else {
    // Handle the error from execute()
    echo "Error inserting into pending_registrations: " . $insertStmtPending->error;
    $insertStmtPending->close();
    $conn->close();
    exit();
}
$insertStmtPending->close();

    }else {
        echo "Error preparing statement (insert pending): " . $conn->error;
        $conn->close();
        exit();


    }
   


    
}

$conn->close();
?>