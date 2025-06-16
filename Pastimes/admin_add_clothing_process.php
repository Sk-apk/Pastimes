<?php
session_start();
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION["admin_ID"])) {
    
    header("Location: admin_login.php");
    exit();
}

include 'DBConn.php';




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand = $_POST["brand"];
    $size = $_POST["size"];
    $colour = $_POST["colour"];
    $price = $_POST["price"];
    $description = $_POST["description"];

    $image_name = null; // Default to null if no image is uploaded or if upload fails

    // Handling image upload
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $target_dir = "uploads/"; // Make sure this directory exists and is writable!
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Generating  a unique file name to prevent overwriting and security issues
        $unique_image_name = uniqid() . '.' . $imageFileType;
        $target_file_unique = $target_dir . $unique_image_name;

        // Checking if the  image file is a actually a image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            // Allowing  certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                header("Location: admin_add_clothing.php?error=Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                exit();
            }

            // Checking the  file size (e.g., 5MB limit)
            if ($_FILES["image"]["size"] > 5000000) {
                header("Location: admin_add_clothing.php?error=Sorry, your file is too large (max 5MB).");
                exit();
            }

            // Moving the uploaded file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file_unique)) {
                $image_name = $unique_image_name; // Store the unique filename in the database
            } else {
                header("Location: admin_add_clothing.php?error=Sorry, there was an error uploading your file.");
                exit();
            }
        } else {
            header("Location: admin_add_clothing.php?error=File is not an image.");
            exit();
        }
    }

    // Insert clothing item into the database
    $sqlInsert = "INSERT INTO clothingitem (clothing_Brand, clothing_Size, clothing_Colour, clothing_Price, clothing_Description, clothing_Image) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsert = $connect->prepare($sqlInsert);

    if ($stmtInsert) {
        // 'ssdss' for string, string, decimal (price), string, string
       
        $stmtInsert->bind_param("sssdss", $brand, $size, $colour, $price, $description, $image_name);

        if ($stmtInsert->execute()) {
            $stmtInsert->close();
            header("Location: admin_add_clothing.php?success=1");
            exit();
        } else {
            echo "Error adding clothing item: " . $stmtInsert->error;
            $stmtInsert->close();
            $connect->close();
            exit();
        }
    } else {
        echo "Error preparing insert statement for clothing item: " . $connect->error;
        $connect->close();
        exit();
    }
} else {
    header("Location: admin_add_clothing.php");
    exit();
}

$connect->close();
?>