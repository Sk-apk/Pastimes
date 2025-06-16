<?php
include 'DBConn.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"]) && isset($_GET["type"])) {
    $id = $_GET["id"];
    $type = $_GET["type"];


    $table="";
    $insertColumns ="";
    $bindParamsType = "";


    // Determine the target table and ID column based on user type.
    if ($type == "buyer") {
        $table = "buyer";
        $insertColumns = "buyer_Name ,buyer_Surname ,buyer_Email ,buyer_Password";
        $bindParamsType = "ssss";
             
        
    } elseif ($type == "seller") {
        $table = "seller";
       $insertColumns = "seller_Name ,seller_Surname ,seller_Email ,seller_Password";
       $bindParamsType = "ssss"; 
    } else {
        // Handle invalid user type.  Important
        echo "Invalid user type  encountered during approval process.";
        $connect->close();
        exit();
    }

    // 1. Get user data from pending_registrations
    $sqlSelect = "SELECT pen_Name, pen_Email, pen_Password FROM pending_registrations WHERE pen_ID = ?";
    $stmtSelect = $connect->prepare($sqlSelect);

    if ($stmtSelect) {
        $stmtSelect->bind_param("i", $id);
        $stmtSelect->execute();
        $resultSelect = $stmtSelect->get_result();

        if ($resultSelect->num_rows == 1) {
            $row = $resultSelect->fetch_assoc();
            $username = $row["pen_Name"];
            $surname = "pen_Surname"; 
            $email = $row["pen_Email"];
            $password_hash = $row["pen_Password"];
            $stmtSelect->close();



             $sqlInsert = "INSERT INTO $table ($insertColumns) VALUES (?, ?, ?, ?)";
            $stmtInsert = $connect->prepare($sqlInsert);


           /* // 2. Insert into buyers or sellers table
            if($type = "buyer"){
                $sqlInsert = "INSERT INTO $table (buyer_Name, buyer_Surname, buyer_Email, buyer_Password) VALUES (?, ?, ?, ?)";
            $stmtInsert = $connect->prepare($sqlInsert);


            }elseif($type="seller"){
                 $sqlInsert = "INSERT INTO $targetTable (seller_Name, seller_Surname, seller_Email, seller_Password) VALUES (?, ?, ?, ?)";
            $stmtInsert = $connect->prepare($sqlInsert);
            }
           */

            if ($stmtInsert) {
                $stmtInsert->bind_param("ssss", $username, $surname, $email, $password_hash);
                $insertResult = $stmtInsert->execute(); // Store the result of execute

                if ($insertResult) {
                    $stmtInsert->close();
                    // 3. Delete from pending_registrations
                    $sqlDelete = "DELETE FROM pending_registrations WHERE pen_ID = ?";
                    $stmtDelete = $connect->prepare($sqlDelete);

                    if ($stmtDelete) {
                        $stmtDelete->bind_param("i", $id);
                        $stmtDelete->execute();
                        $stmtDelete->close();
                        header("Location: admin_dashboard.php?approved=1"); // success
                        $connect->close();
                        exit();
                    } else {
                        echo "Error deleting from pending_registrations: " . $connect->error;
                        $connect->close();
                        exit();
                    }
                } else {
                    echo "Error inserting into $targetTable: " . $stmtInsert->error;
                    $stmtInsert->close();
                    $connect->close();
                    exit();
                }
                
            } else {
                echo "Error inserting into $table: " . $connect->error;
                $connect->close();
                exit();
            }
        } else {
            echo "Error: User not found in pending_registrations.";
            $stmtSelect->close();
            $connect->close();
            exit();
        }
    } else {
        echo "Error preparing select statement: " . $connect->error;
        $connect->close();
        exit();
    }
} else {
    header("Location: admin_dashboard.php"); //  invalid access
    exit();
}
?>