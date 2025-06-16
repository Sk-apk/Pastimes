<?php
session_start();
// Ensure only logged-in sellers can list products
if (!isset($_SESSION["seller_ID"]) || $_SESSION["user_type"] !== "seller") {
    header("Location: login.php");
    exit();
}

include 'DBConn.php';

// error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seller_id = $_SESSION["user_ID"]; // Get seller_ID from session
    $brand = $_POST["brand"];
    $size = $_POST["size"];
    $colour = $_POST["colour"];
    $price = $_POST["price"];
    $description = $_POST["description"];

    $image_name = null; // Default to null if no image is uploaded or if upload fails

    // Handling image upload 
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $target_dir = "uploads/"; 

        
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $image_original_name = basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($image_original_name, PATHINFO_EXTENSION));

        // Generating a unique filename to prevent overwriting and security issues
        $unique_image_name = uniqid('prod_', true) . '.' . $imageFileType; // Prefix for product images
        $target_file_unique = $target_dir . $unique_image_name;

        // Checking if image file is an actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            // Allowing certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                header("Location: seller_list_product.php?error=" . urlencode("Sorry, only JPG, JPEG, PNG & GIF files are allowed."));
                exit();
            }

            // Checking file size (e.g., 5MB limit)
            if ($_FILES["image"]["size"] > 5000000) { // 5MB
                header("Location: seller_list_product.php?error=" . urlencode("Sorry, your file is too large (max 5MB)."));
                exit();
            }

            // Move the uploaded file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file_unique)) {
                $image_name = $unique_image_name; // Store the unique filename in the database
            } else {
                header("Location: seller_list_product.php?error=" . urlencode("Sorry, there was an error uploading your file. Check folder permissions."));
                exit();
            }
        } else {
            header("Location: seller_list_product.php?error=" . urlencode("File is not a valid image."));
            exit();
        }
    } else {
        // If no image was uploaded or there was an error, redirect with an error
        header("Location: seller_list_product.php?error=" . urlencode("Please upload a product image."));
        exit();
    }

    // Inserting clothing items into the database
    $sqlInsert = "INSERT INTO clothingitem (clothing_Brand, clothing_Size, clothing_Colour, clothing_Price, clothing_Description, clothing_Image) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsert = $connect->prepare($sqlInsert);

    if ($stmtInsert) {
        // 'sssdss' for string, string, string, decimal, string, string  
        $stmtInsert->bind_param("sssdss", $brand, $size, $colour, $price, $description, $image_name);

        if ($stmtInsert->execute()) {
            $stmtInsert->close();
            header("Location: seller_list_product.php?success=1"); // Redirect with success
            exit();
        } else {
            // Debugging: Output specific database error
            header("Location: seller_list_product.php?error=" . urlencode("Database error: " . $stmtInsert->error));
            $stmtInsert->close();
            $connect->close();
            exit();
        }
    } else {
        // Debugging: Output specific database error for prepare
        header("Location: seller_list_product.php?error=" . urlencode("Database prepare error: " . $conn->error));
        $connect->close();
        exit();
    }
} else {
    // If not a POST request, redirect to the form
    header("Location: seller_list_product.php");
    exit();
}

$connect->close();
?>
