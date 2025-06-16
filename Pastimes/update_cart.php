<?php
session_start();
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

// Handle item removal
if (isset($_GET['remove_item']) && is_numeric($_GET['remove_item'])) {
    $item_id_to_remove = (int)$_GET['remove_item'];
    if (isset($_SESSION['cart'][$item_id_to_remove])) {
        unset($_SESSION['cart'][$item_id_to_remove]);
        header("Location: cart.php?success=Item removed.");
        exit();
    } else {
        header("Location: cart.php?error=Item not found in cart.");
        exit();
    }
}

// Handle quantity updates from the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['quantity'])) {
    foreach ($_POST['quantity'] as $item_id => $new_quantity) {
        $item_id = (int)$item_id;
        $new_quantity = (int)$new_quantity;

        if (isset($_SESSION['cart'][$item_id])) {
            if ($new_quantity > 0) {
                $_SESSION['cart'][$item_id]['quantity'] = $new_quantity;
            } else {
                // If quantity is 0 or less, remove the item
                unset($_SESSION['cart'][$item_id]);
            }
        }
    }
    header("Location: cart.php?success=Cart updated.");
    exit();
}

// If no specific action, redirect back to cart
header("Location: cart.php");
exit();
?>
