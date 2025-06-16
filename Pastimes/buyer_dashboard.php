<?php
session_start();
if (!isset($_SESSION["buyer_ID"]) || $_SESSION["user_type"] !== "buyer") {
    header("Location: login.php"); // Redirect if not a logged-in buyer
    exit();
}

$username = $_SESSION["buyer_Name"];
?>

<title style="text-align: center;" >Buyer Dashboard</title>
    <link rel="stylesheet" href="b_dashboard.css">

<div class="dashboard-container">
<h1 style="text-align: center;"  >Welcome, <?php echo htmlspecialchars($username); ?>! (Buyer)</h1>
    <p>This is your buyer dashboard.</p>

    
<h2>Your Activities</h2>
<ul>
    <li><a href="browse_products.php">Browse Products</a></li>
    <li><a href="#">View Your Orders</a></li> 
    <li><a href="#">Manage Your Wishlist</a></li>
    <li><a href="#">Update Your Profile</a></li>
</ul>
    <p style="text-align: center;"  ><a href="logout.php">Logout</a></p>


</div>
    