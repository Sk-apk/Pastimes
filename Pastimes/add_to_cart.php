<?php
session_start();
// Ensure only logged-in buyers can add to cart
if (!isset($_SESSION["buyer_ID"]) || $_SESSION["user_type"] !== "buyer") {
    header("Location: login.php");
    exit();
}

// error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initializing cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['item_id']) && is_numeric($_GET['item_id'])) {
    $item_id = (int)$_GET['item_id'];
    $quantity = 1; // Default quantity to add

   
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    } else {
        // Fetch item details from DB to store in cart (for display later)
      include 'DBConn.php';
        $sql = "SELECT item_ID, clothing_Brand, clothing_Size, clothing_Colour, clothing_Price, clothing_Image FROM clothingitem WHERE item_ID = ?";
        $stmt = $connect->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $item_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $item_details = $result->fetch_assoc();
                $_SESSION['cart'][$item_id] = [
                    'details' => $item_details,
                    'quantity' => $quantity
                ];
                $stmt->close();
                $connect->close();
                header("Location: browse_products.php?success_add=1");
                exit();
            } else {
                $stmt->close();
                $connect->close();
                header("Location: browse_products.php?error_add=Item not found.");
                exit();
            }
        } else {
            $connect->close();
            header("Location: browse_products.php?error_add=Database error preparing statement.");
            exit();
        }
    }
    header("Location: browse_products.php?success_add=1"); // Redirect after adding/updating quantity
    exit();

} else {
    header("Location: browse_products.php?error_add=Invalid item ID.");
    exit();
}
?>