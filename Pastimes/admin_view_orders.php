<?php
session_start();

//error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//checking if admin is still in session 
echo "DEBUG: Session Admin ID: " . (isset($_SESSION["admin_ID"]) ?
$_SESSION["admin_ID"] : "NOT SET") . "<br>";

echo "DEBUG: Session User Type: " .  (isset($_SESSION["user_type"]) ?
$_SESSION["user_type"] : "NOT SET") . "<br>";

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

include 'DBConn.php';



// Fetching all deliveries along with  the buyers information
$sqlDeliveries = "
    SELECT
        d.delivery_ID,
        d.delivery_Time,
        d.delivery_Date,
        d.delivery_Location,
        d.delivery_Price,
        b.buyer_Name,
        b.buyer_Surname,
        b.buyer_Email
    FROM
        delivery d
    JOIN
        buyers b ON d.buyer_ID = b.buyer_ID
    ORDER BY
        d.delivery_Date DESC, d.delivery_Time DESC
";
$resultDeliveries = $connect->query($sqlDeliveries);

?>

<title>Admin - View All Orders</title>


<div >
        <h1>All Customer Orders (Deliveries)</h1>
        <p>This view helps the administrator track deliveries and communicate with buyers.</p>

        <?php if ($resultDeliveries) :
               if ($resultDeliveries->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Delivery ID</th>
                        <th>Buyer Name</th>
                        <th>Buyer Email</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Total Price</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultDeliveries->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["delivery_ID"]); ?></td>
                            <td><?php echo htmlspecialchars($row["buyer_Name"]) . " " . htmlspecialchars($row["buyer_Surname"]); ?></td>
                            <td><?php echo htmlspecialchars($row["buyer_Email"]); ?></td>
                            <td><?php echo htmlspecialchars($row["delivery_Location"]); ?></td>
                            <td><?php echo htmlspecialchars($row["delivery_Date"]); ?></td>
                            <td><?php echo htmlspecialchars($row["delivery_Time"]); ?></td>
                            <td>R<?php echo htmlspecialchars(number_format($row["delivery_Price"], 2)); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center;">No orders found yet.</p>
        <?php endif;
        else:
          echo "<p class='error-message'>Error fetching deliveries: " . $connect->error . "</p>";
        endif;
        ?>

        <a href="admin_dashboard.php" class="back-link">Back to Admin Dashboard</a>
    </div>

    <?php $connect->close(); ?>