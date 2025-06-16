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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST["item_id"];
    $brand = $_POST["brand"];
    $size = $_POST["size"];
    $colour = $_POST["colour"];
    $price = $_POST["price"];
    $description = $_POST["description"];
    $old_image = $_POST["old_image"]; // Path to the old image filename
    $delete_current_image = isset($_POST["delete_current_image"]); // Checking if checkbox is ticked

    $image_name = $old_image; 

    $target_dir = "uploads/"; // folder where images will be uploaded

    // Handling image deletion request
    if ($delete_current_image && !empty($old_image)) {
        $old_image_path = $target_dir . $old_image;
        if (file_exists($old_image_path)) {
            unlink($old_image_path); // Delete the file from the server
        }
        $image_name = null; // Set image name to null in DB
    }

    // Handling new image upload
    if (isset($_FILES["new_image"]) && $_FILES["new_image"]["error"] == 0) {
        //  deleting the old image if a new one is being uploaded and it's not already marked for deletion
        if (!empty($old_image) && !$delete_current_image) {
            $old_image_path = $target_dir . $old_image;
            if (file_exists($old_image_path)) {
                unlink($old_image_path); // Deleting the old file
            }
        }

       
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $image_original_name = basename($_FILES["new_image"]["name"]);
        $imageFileType = strtolower(pathinfo($image_original_name, PATHINFO_EXTENSION));

        $unique_image_name = uniqid('img_', true) . '.' . $imageFileType;
        $target_file_unique = $target_dir . $unique_image_name;

        $check = getimagesize($_FILES["new_image"]["tmp_name"]);
        if($check !== false) {
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                header("Location: admin_edit_clothing.php?id=$item_id&error=Sorry, only JPG, JPEG, PNG & GIF files are allowed for new image.");
                exit();
            }
            if ($_FILES["new_image"]["size"] > 5000000) { // 5MB
                header("Location: admin_edit_clothing.php?id=$item_id&error=Sorry, your new image file is too large (max 5MB).");
                exit();
            }
            if (move_uploaded_file($_FILES["new_image"]["tmp_name"], $target_file_unique)) {
                $image_name = $unique_image_name; // Store the new unique filename
            } else {
                header("Location: admin_edit_clothing.php?id=$item_id&error=Sorry, there was an error uploading your new file.");
                exit();
            }
        } else {
            header("Location: admin_edit_clothing.php?id=$item_id&error=New file is not a valid image.");
            exit();
        }
    }

    // Update clothing item in the database
    $sqlUpdate = "UPDATE clothingitem SET clothing_Brand = ?, clothing_Size = ?, clothing_Colour = ?, clothing_Price = ?, clothing_Description = ?, clothing_Image = ? WHERE item_ID = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);

    if ($stmtUpdate) {
        // 'sssdssi' for string, string, string, decimal, string, string, integer (item_ID)
        $stmtUpdate->bind_param("sssdssi", $brand, $size, $colour, $price, $description, $image_name, $item_id);

        if ($stmtUpdate->execute()) {
            $stmtUpdate->close();
            header("Location: admin_edit_clothing.php?id=$item_id&success=1");
            exit();
        } else {
            echo "Error updating clothing item: " . $stmtUpdate->error;
            $stmtUpdate->close();
            $connect->close();
            exit();
        }
    } else {
        echo "Error preparing update statement for clothing item: " . $connect->error;
        $connect->close();
        exit();
    }
} else {
    header("Location: admin_dashboard.php"); 
    exit();
}

$connect->close();
?>
