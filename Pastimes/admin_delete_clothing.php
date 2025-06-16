<?php
session_start();
if (!isset($_SESSION["admin_ID"])) {
    header("Location: admin_login.php");
    exit();
}

include 'DBConn.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $item_id = $_GET['id'];
    $target_dir = "uploads/";

    //  getting the image filename to delete it from the server
    $sqlGetImage = "SELECT clothing_Image FROM clothingitem WHERE item_ID = ?";
    $stmtGetImage = $connect->prepare($sqlGetImage);

    if ($stmtGetImage) {
        $stmtGetImage->bind_param("i", $item_id);
        $stmtGetImage->execute();
        $resultGetImage = $stmtGetImage->get_result();

        if ($resultGetImage->num_rows == 1) {
            $row = $resultGetImage->fetch_assoc();
            $image_to_delete = $row["clothing_Image"];

            // Deleting image file from server if it exists
            if (!empty($image_to_delete) && file_exists($target_dir . $image_to_delete)) {
                unlink($target_dir . $image_to_delete);
            }
        }
        $stmtGetImage->close();
    } else {
        
        error_log("Error preparing statement to get image for deletion: " . $connect->error);
    }

    //  deleting the record from the database
    $sqlDelete = "DELETE FROM clothingitem WHERE item_ID = ?";
    $stmtDelete = $connect->prepare($sqlDelete);

    if ($stmtDelete) {
        $stmtDelete->bind_param("i", $item_id);
        if ($stmtDelete->execute()) {
            $stmtDelete->close();
            header("Location: admin_dashboard.php?clothing_deleted=1");
            exit();
        } else {
            echo "Error deleting clothing item: " . $stmtDelete->error;
            $stmtDelete->close();
            $connect->close();
            exit();
        }
    } else {
        echo "Error preparing delete statement for clothing item: " . $connect->error;
        $connect->close();
        exit();
    }
} else {
    header("Location: admin_dashboard.php?error=Invalid item ID for deletion.");
    exit();
}

$connect->close();
?>
