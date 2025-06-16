<?php
session_start();
// Ensuring only logged-in buyers can view cart
if (!isset($_SESSION["buyer_ID"]) || $_SESSION["user_type"] !== "buyer") {
    header("Location: login.php");
    exit();
}

// error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart_items = $_SESSION['cart'];
$total_cart_price = 0;

// Calculating the total price
foreach ($cart_items as $item_id => $item_data) {
    $total_cart_price += $item_data['details']['clothing_Price'] * $item_data['quantity'];
}

?>


<title>Your Shopping Cart</title>
<link rel="stylesheet" href="cart.css">

<div>
        <h1>Your Shopping Cart</h1>

        <?php if (isset($_GET['success'])) { ?>
            <p class="message success-message">Cart updated successfully!</p>
        <?php } ?>
        <?php if (isset($_GET['error'])) { ?>
            <p class="message error-message">Error updating cart: <?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php } ?>

        <?php if (!empty($cart_items)): ?>
            <form action="update_cart.php" method="POST">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item_id => $item_data): ?>
                            <tr>
                                <td>
                                    <?php
                                    $image_path = 'uploads/' . htmlspecialchars($item_data['details']['clothing_Image']);
                                    if (!empty($item_data['details']['clothing_Image']) && file_exists($image_path)) {
                                        echo '<img src="' . $image_path . '" alt="' . htmlspecialchars($item_data['details']['clothing_Brand']) . '" class="cart-item-image">';
                                    } else {
                                        echo '<img src="https://placehold.co/80x80/cccccc/333333?text=No+Image" alt="No Image" class="cart-item-image">';
                                    }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($item_data['details']['clothing_Brand']); ?></td>
                                <td>R<?php echo htmlspecialchars(number_format($item_data['details']['clothing_Price'], 2)); ?></td>
                                <td>
                                    <input type="number" name="quantity[<?php echo $item_id; ?>]" value="<?php echo htmlspecialchars($item_data['quantity']); ?>" min="1" class="quantity-input">
                                </td>
                                <td>R<?php echo htmlspecialchars(number_format($item_data['details']['clothing_Price'] * $item_data['quantity'], 2)); ?></td>
                                <td>
                                    <a href="update_cart.php?remove_item=<?php echo $item_id; ?>" style="color: red; text-decoration: none;">Remove</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="total-row">
                            <td colspan="4" style="text-align: right;">Total:</td>
                            <td>R<?php echo htmlspecialchars(number_format($total_cart_price, 2)); ?></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <div style="text-align: center;">
                    <button type="submit">Update Cart</button>
                    <a href="browse_products.php">Continue Shopping</a>
                    <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
                </div>
            </form>
        <?php else: ?>
            <p style="text-align: center;">Your cart is empty. <a href="browse_products.php">Start shopping!</a></p>
        <?php endif; ?>
    </div>