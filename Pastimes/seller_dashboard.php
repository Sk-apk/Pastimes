<?php
session_start();
if (!isset($_SESSION["seller_ID"]) || $_SESSION["user_type"] !== "seller") {
    header("Location: login.php"); // Redirect if not a logged-in seller
    exit();
}

$username = $_SESSION["seller_Name"];
?>


    <title>Seller Dashboard</title>
    <link rel="stylesheet" href="s_dashboard.css"> 

<body class="dashboard-body">

<div class="dashboard-container">
        <h1>Hello, <?php echo htmlspecialchars($username); ?>! (Seller)</h1>
        <p>This is your seller dashboard. Here you can manage your listings and sales.</p>

        <h2>Seller Actions</h2>
        <ul>
            <li><a href="seller_list_product.php">List New Product</a></li>
            <li><a href="#">Manage Your Products</a></li> 
            <li><a href="#">View Sales History</a></li> 
            <li><a href="#">Update Your Profile</a></li> 
        </ul>

        <div class="logout-container">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
    
