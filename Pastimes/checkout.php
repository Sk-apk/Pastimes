<?php
// error reporting for debugging
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION["buyer_ID"]) || $_SESSION["user_type"] !== "buyer") {
    header("Location: login.php");
    exit();
}

include 'DBConn.php';


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize cart if it doesn't exist or is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php?error=Your cart is empty. Please add items before checking out.");
    exit();
}

$cart_items = $_SESSION['cart'];
$total_cart_price = 0;

// Calculating total price
foreach ($cart_items as $item_id => $item_data) {
    $total_cart_price += $item_data['details']['clothing_Price'] * $item_data['quantity'];
}

// Handling the  form submission for checkout
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buyer_id = $_SESSION["user_id"];
    $delivery_location = $_POST["delivery_location"];
    $delivery_date = $_POST["delivery_date"];
    $delivery_time = $_POST["delivery_time"];
    // delivery_Price is the total_cart_price

    // validation
    if (empty($delivery_location) || empty($delivery_date) || empty($delivery_time)) {
        header("Location: checkout.php?error=Please fill in all delivery details.");
        exit();
    }

    // Inserting into delivery table
    $sqlInsertDelivery = "INSERT INTO delivery (buyer_ID, delivery_Location, delivery_Date, delivery_Time, delivery_Price) VALUES (?, ?, ?, ?, ?)";
    $stmtInsertDelivery = $connect->prepare($sqlInsertDelivery);

    if ($stmtInsertDelivery) {
        $stmtInsertDelivery->bind_param("isssi", $buyer_id, $delivery_location, $delivery_date, $delivery_time, $total_cart_price);

        if ($stmtInsertDelivery->execute()) {
            $stmtInsertDelivery->close();
            // Clearing the cart after successful checkout
            unset($_SESSION['cart']);
            header("Location: buyer_dashboard.php?order_success=1"); // Redirecting to buyer dashboard
            exit();
        } else {
            echo "Error processing order: " . $stmtInsertDelivery->error;
            $stmtInsertDelivery->close();
            $connect->close();
            exit();
        }
    } else {
        echo "Error preparing delivery insert statement: " . $connect->error;
        $connect->close();
        exit();
    }
}

$connect->close();
?>

<title>Checkout</title>
<link rel="stylesheet" href="checkout.css">


<div class="checkout-container" >
        <h1 style="text-align: center;"  >Checkout</h1>

        <?php if (isset($_GET['error'])) { ?>
            <p class="message error-message"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php } ?>

        <div class="order-summary">
            <h2 >Order Summary</h2>
            <ul>
                <?php foreach ($cart_items as $item_id => $item_data): ?>
                    <li>
                        <?php echo htmlspecialchars($item_data['details']['clothing_Brand']); ?> (<?php echo htmlspecialchars($item_data['quantity']); ?>) - R<?php echo htmlspecialchars(number_format($item_data['details']['clothing_Price'] * $item_data['quantity'], 2)); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="total">Total: R<?php echo htmlspecialchars(number_format($total_cart_price, 2)); ?></div>
        </div>

        <form method="POST" action="checkout.php" class="delivery-form">
            <h2>Delivery Details</h2>
            <div class="form-group">
                <label for="delivery_location">Delivery Location:</label>
                <input type="text" id="delivery_location" name="delivery_location" required>
            </div>
            <div class="form-group">
                <label for="delivery_date">Delivery Date:</label>
                <input type="date" id="delivery_date" name="delivery_date" required>
            </div>
            <div class="form-group">
                <label for="delivery_time">Delivery Time:</label>
                <input type="time" id="delivery_time" name="delivery_time" required>
            </div>
            <button type="submit">Place Order</button>
        </form>
        <a href="cart.php" class="back-link">Back to Cart</a>
    </div>