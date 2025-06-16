<?php
session_start();
if (!isset($_SESSION["admin_ID"])) {
    header("Location: admin_login.php");
    exit();
}

include 'DBConn.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch pending registrations from the database
$sqlPending = "SELECT pen_ID, pen_Name, pen_Surname, pen_Email, pen_User_type FROM pending_registrations";
$resultPending = $connect->query($sqlPending);

// Fetch all users (buyers and sellers)
$sqlUsers = "SELECT 'buyer' AS role, buyer_ID AS id, buyer_Name AS username, buyer_Surname AS surname, buyer_Email AS email FROM buyer
             UNION 
             SELECT 'seller' AS role, seller_ID AS id, seller_Name AS username, seller_Surname AS surname, seller_Email AS email FROM seller
             UNION 
             SELECT 'admin' AS role, admin_ID AS id, admin_Name AS username, admin_Surname AS surname, admin_Email FROM admin
             ORDER BY username";
$resultUsers = $connect->query($sqlUsers);


//fetch all clothing items
$sqlClothing = "SELECT item_ID, clothing_Brand, clothing_Size, clothing_Colour, clothing_Price, clothing_Description, clothing_Image
 FROM clothingitem ORDER BY item_ID";
$resultClothing = $connect->query($sqlClothing);
?>


   <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard">


<div class="dashboard-container">

 <h1 style="text-align: center;"  >Admin Dashboard</h1>
    <p  class="wlecome"style="text-align: center;"  >Welcome, Admin (<?php echo $_SESSION["admin_Name"]; ?>)! 
    <a href="admin_logout.php"  class="logout-btn">Logout</a></p>

    <h2 style="text-align: center;"  >Pending Registrations</h2>
    <section>

 <?php if ($resultPending) : // Check if $resultPending is valid
     if ($resultPending->num_rows > 0): ?>
        <table style="text-align: center;"  class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Email</th>
                    <th>Registered As</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultPending->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row["pen_Name"]; ?></td>
                        <td> <?php echo $row["pen_Surname"]?></td>
                        <td><?php echo $row["pen_Email"]; ?></td>
                        <td><?php echo $row["pen_User_type"]; ?></td>
                        <td>
                            <a class="action-link" href="approve_registration_process.php?id=<?php echo $row["pen_ID"]; ?>&type=<?php echo $row["pen_User_type"]; ?>">Approve</a> |
                            <a class="action-link" href="reject_registration_process.php?id=<?php echo $row["pen_ID"]; ?>"> Reject</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p classs="empty-msg" style="text-align: center;"  >No pending registrations.</p>
    <?php endif;
     else:
        echo "Error fetching pending registrations: " . $connect->error;
    endif;
    
    ?>

    </section>
   

    <section>


 <h2 style="text-align: center;"  >Manage Users</h2>
    <p class="button-link" style="text-align: center;"  ><a href="add_user.php">Add New User</a></p>
    <p class="button-link" style="text-align: center;" ><a href="admin_view_orders.php" style="margin-left: 20px;">View All Order</a></p>
    <?php if ($resultUsers) :  
    if ($resultUsers->num_rows > 0): ?>
        <table style="text-align: center;"  class="data-table">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Username</th>
                    <th>Surname</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultUsers->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo ucfirst($row["role"]); ?></td>
                        <td><?php echo $row["username"]; ?></td>
                        <td><?php echo $row["surname"]?></td>
                        <td><?php echo $row["email"]; ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $row["id"]; ?>&role=<?php echo $row["role"]; ?> "  class="action-link" >Edit</a> |
                            <a href="delete_user.php?id=<?php echo $row["id"]; ?>&role=<?php echo $row["role"]; ?>" class="action-link" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p  class="empty-msg" style="text-align: center;"  >No users found.</p>
    <?php endif; 
     else:
        echo "Error fetching users: " . $connect->error;
      endif;
    ?>

    </section>

   

<h2 style="text-align: center;" >Manage Clothing Items</h2>
    <p class="button-link"  style="text-align: center;" ><a href="admin_add_clothing.php">Add New Clothing Item</a></p>
    <?php if ($resultClothing) :
           if ($resultClothing->num_rows > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Brand</th>
                    <th>Size</th>
                    <th>Colour</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultClothing->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["item_ID"]); ?></td>
                        <td><?php echo htmlspecialchars($row["clothing_Brand"]); ?></td>
                        <td><?php echo htmlspecialchars($row["clothing_Size"]); ?></td>
                        <td><?php echo htmlspecialchars($row["clothing_Colour"]); ?></td>
                        <td>R<?php echo htmlspecialchars(number_format($row["clothing_Price"], 2)); ?></td>
                        <td><?php echo htmlspecialchars(substr($row["clothing_Description"], 0, 50)) . (strlen($row["clothing_Description"]) > 50 ? '...' : ''); ?></td>
                        <td>
                            <?php if (!empty($row["clothing_Image"]) && file_exists('uploads/' . $row["clothing_Image"])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($row["clothing_Image"]); ?>" alt="Clothing Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td >
                            <a href="admin_edit_clothing.php?id=<?php echo $row["item_ID"]; ?>"   class="action-link" >Edit</a> |
                            <a href="admin_delete_clothing.php?id=<?php echo $row["item_ID"]; ?>"  class="action-link" onclick="return confirm('Are you sure you want to delete this clothing item?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p  class="empty-msg" style="text-align: center;">No clothing items found.</p>
    <?php endif;
    else:
      echo "<p style='color:red;'>Error fetching clothing items: " . $connect->error . "</p>";
    endif;
    ?>

</div>

 

   

<?php $connect->close(); ?>