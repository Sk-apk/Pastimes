<?php
session_start();
// Check if a buyer is logged in. If not, redirect to login page.
// You might want a public browse page for non-logged-in users,
// but for now, we'll assume login is required to browse.
if (!isset($_SESSION["buyer_ID"]) || $_SESSION["user_type"] !== "buyer") {
    header("Location: login.php"); // Redirect if not a logged-in buyer
    exit();
}

include 'DBConn.php';

//error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetching all clothing items
$sqlClothing = "SELECT item_ID, clothing_Brand, clothing_Size, clothing_Colour, clothing_Price, clothing_Description, clothing_Image FROM clothingitem ORDER BY item_ID";
$resultClothing = $connect->query($sqlClothing);

?>

<title>Browse Products</title>
<link rel="stylesheet" href="browse.css">

<div>
        <div class="top-links">
            <a href="cart.php">View Cart</a>
            <a href="buyer_dashboard.php">My Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>

        <h1>Available Clothing Items</h1>

        <?php if (isset($_GET['success_add'])) { ?>
            <p class="message success-message">Item added to cart successfully!</p>
        <?php } ?>
        <?php if (isset($_GET['error_add'])) { ?>
            <p class="message error-message">Error adding item to cart.</p>
        <?php } ?>

        <?php if ($resultClothing && $resultClothing->num_rows > 0): ?>
            <div class="product-container">
                <?php while ($row = $resultClothing->fetch_assoc()): ?>
                    <div class="product-card">
                        <?php
                        $image_path = 'uploads/' . htmlspecialchars($row["clothing_Image"]);
                        if (!empty($row["clothing_Image"]) && file_exists($image_path)) {
                            echo '<img src="' . $image_path . '" alt="' . htmlspecialchars($row["clothing_Brand"]) . '">';
                        } else {
                            echo '<img src="https://placehold.co/250x200/cccccc/333333?text=No+Image" alt="No Image">';
                        }
                        ?>
                        <h3><?php echo htmlspecialchars($row["clothing_Brand"]); ?></h3>
                        <p>Size: <?php echo htmlspecialchars($row["clothing_Size"]); ?></p>
                        <p>Colour: <?php echo htmlspecialchars($row["clothing_Colour"]); ?></p>
                        <p><?php echo htmlspecialchars(substr($row["clothing_Description"], 0, 100)) . (strlen($row["clothing_Description"]) > 100 ? '...' : ''); ?></p>
                        <div >R<?php echo htmlspecialchars(number_format($row["clothing_Price"], 2)); ?></div>
                        <a href="add_to_cart.php?item_id=<?php echo $row["item_ID"]; ?>" class="add-to-cart-btn">Add to Cart</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p style="text-align: center;">No clothing items available yet.</p>
        <?php endif; ?>
    </div>



<?php $connect->close(); ?>