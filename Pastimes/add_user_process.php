<?php
session_start();

//error reporting for debugging 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "DEBUG-START: Script initiated. <br>";

if(!isset($_SESSION["admin_ID"])){
      echo "DEBUG-SESSION: Admin ID not set. Redirecting to admin_login.php. <br>";
    header("Location: admin_login.php");
    exit();
}

 echo "DEBUG-SESSION: Admin sessoin active. User ID: " 
 . $_SESSION["admin_ID"] .
  "<br>";

include 'DBConn.php';

echo "DEBUG-DB: DBConn loaded. <br>";



if ($_SERVER["REQUEST_METHOD"] == "POST") {
     echo "DEBUG-POST: POST request detected.<br>"; 
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


     if (empty($role)) {
        echo "DEBUG-ROLE: Role is empty. Redirecting.<br>";
        header("Location: add_user.php?error=" . urlencode("User role not selected."));
        exit();
    }
    echo "DEBUG-ROLE: Role selected: " . htmlspecialchars($role) . "<br>";
    

    $table = "";
    $name="";
    $email="";
    $surname="";
    $password="";
   if ($role === 'buyer') {
        $table = "buyer";
        $name="buyer_Name";
        $surname="buyer_Surname";
        $email="buyer_Email";
        $password="buyer_Password";
    } elseif ($role === 'seller') {
        $table = "seller";
        $name="seller_Name";
        $surname="seller_Surname";
        $email="seller_Email";
        $password="seller_Password";
    } elseif ($role === 'admin') {
        $table = "admin";
        $name="admin_Name";
        $surname="admin_Surname";
        $email="admin_Email";
        $password="admin_Password";
    } else {
        echo "DEBUG-ROLE-INVALID: Invalid user role detected. Redirecting.<br>";
        header("Location: add_user.php?error=Invalid user role selected");
        exit();
    }
    

     if (empty($table)) {
        echo "DEBUG-TABLE: Table name not determined. Redirecting.<br>";
        header("Location: add_user.php?error=System error: Table not determined.");
        exit();
    }

   


    echo "DEBUG: Checking if user exists in: " . $table . "<br>";
     echo "DEBUG: name to check: " . $name . "<br>";
      echo "DEBUG: email to check: " . $email . "<br>";

    //Checking if the data being added already exists
    $checkSql="SELECT COUNT(*) FROM seller, buyer, admin WHERE $name = ? OR $email = ?";
     echo "DEBUG-CHECK-SQL: Check SQL query: " . htmlspecialchars($checkSql) . "<br>";
    $checkStmt = $connect->prepare($checkSql);

    if($checkStmt){
        $checkStmt->bind_param("ss",$name,$email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $row = $checkResult->fetch_row();
        $count = $row[0];
        $checkStmt->close();

        echo "DEBUG-CHECK-RESULT: Count found: " . $count . "<br>";
        
        if ($count > 0){
            echo "DEBUG-CHECK-EXISTS: User or email already exists. Redirecting.<br>"; // Debugging message
            header("Location: add_user.php?error=" . urlencode("Username or email already exists in " . ucfirst($role) . " table."));
            exit();
        }

    }else{
echo "DEBUG-CHECK-PREPARE-FAIL: Error preparing check statement: " . $connect->error;
        $connect->close();
        exit();
    }



//Insert new user
    $sqlInsert = "INSERT INTO $table ($name, $surname, $email, $password) VALUES (?, ?, ?, ?)";
    echo "DEBUG-INSERT-SQL: Insert SQL query: " . htmlspecialchars($sqlInsert) . "<br>";
    $stmtInsert = $connect->prepare($sqlInsert);

    if($stmtInsert){

        $stmtInsert->bind_param("ssss", $name, $surname, $email, $password);

    if($stmtInsert->execute()){
        $stmtInsert->close();
         echo "DEBUG-INSERT-SUCCESS: User added. Redirecting.<br>";
        header("Location: admin_dashboard.php?user_added=1");
        exit();
    }else{
        echo "DEBUG-INSERT-FAIL: Error adding user to $table: " . $stmtInsert->error;
        $stmtInsert->close();
        $connect->close();
        exit();
    }
    
    }
else{
        echo "DEBUG-INSERT-PREPARE-FAIL: Error preparing insert statement: " . $connect->error;
        $connect->close();
        exit();
    
    
    }
}else{
 echo "DEBUG-REQUEST: Not a POST request. Redirecting.<br>";
        header("Location: add_user.php");
        exit();
    }

   

$conn->close();
echo "DEBUG-END: Script finished successfully (unexpectedly, as a redirect should have happened).<br>";
?>