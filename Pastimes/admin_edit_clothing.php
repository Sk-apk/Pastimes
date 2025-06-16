<?php
session_start();
if (!isset($_SESSION["admin_ID"])) {
    header("Location: admin_login.php");
    exit();
}

include 'DBConn.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$item_id = null;
$clothing_Brand = '';
$clothing_Size = '';
$clothing_Colour = '';
$clothing_Price = '';
$clothing_Description = '';
$clothing_Image = '';
$current_image_path = ''; // To display current image and handle deletion

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $item_id = $_GET['id'];

    $sqlSelect = "SELECT clothing_Brand, clothing_Size, clothing_Colour, clothing_Price, clothing_Description, clothing_Image FROM clothingitem WHERE item_ID = ?";
    $stmtSelect = $connect->prepare($sqlSelect);

    if ($stmtSelect) {
        $stmtSelect->bind_param("i", $item_id);
        $stmtSelect->execute();
        $resultSelect = $stmtSelect->get_result();

        if ($resultSelect->num_rows == 1) {
            $row = $resultSelect->fetch_assoc();
            $clothing_Brand = htmlspecialchars($row["clothing_Brand"]);
            $clothing_Size = htmlspecialchars($row["clothing_Size"]);
            $clothing_Colour = htmlspecialchars($row["clothing_Colour"]);
            $clothing_Price = htmlspecialchars($row["clothing_Price"]);
            $clothing_Description = htmlspecialchars($row["clothing_Description"]);
            $clothing_Image = htmlspecialchars($row["clothing_Image"]);
            
            // Set current image path for display
            if (!empty($clothing_Image) && file_exists('uploads/' . $clothing_Image)) {
                $current_image_path = 'uploads/' . $clothing_Image;
            }

            $stmtSelect->close();
        } else {
            echo "<p style='color:red;'>Clothing item not found.</p>";
            $connect->close();
            exit();
        }
    } else {
        echo "<p style='color:red;'>Error preparing select statement: " . $connect->error . "</p>";
        $conn->close();
        exit();
    }
} else {
    echo "<p style='color:red;'>Invalid item ID provided.</p>";
    $connect->close();
    exit();
}
?>


<title>Edit Clothing Item</title>


<h1>Edit Clothing Item</h1>
        <?php if (isset($_GET['error'])) { ?>
            <p style="color: red; text-align: center;"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php } ?>
        <?php if (isset($_GET['success'])) { ?>
            <p style="color: green; text-align: center;">Clothing item updated successfully!</p>
        <?php } ?>

        <form method="POST" action="admin_edit_clothing_process.php" enctype="multipart/form-data">
            <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item_id); ?>">
            <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($clothing_Image); ?>">

            <div>
                <label for="brand">Brand:</label>
                <input type="text" id="brand" name="brand" value="<?php echo $clothing_Brand; ?>" required>
            </div>
            <div>
                <label for="size">Size:</label>
                <input type="text" id="size" name="size" value="<?php echo $clothing_Size; ?>" required>
            </div>
            <div>
                <label for="colour">Colour:</label>
                <input type="text" id="colour" name="colour" value="<?php echo $clothing_Colour; ?>" required>
            </div>
            <div>
                <label for="price">Price (R):</label>
                <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo $clothing_Price; ?>" required>
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?php echo $clothing_Description; ?></textarea>
            </div>

            <div class="current-image-section">
                <label>Current Image:</label><br>
                <?php if (!empty($current_image_path)): ?>
                    <img src="<?php echo htmlspecialchars($current_image_path); ?>" alt="Current Clothing Image">
                    <br><input type="checkbox" name="delete_current_image" id="delete_current_image"> <label for="delete_current_image">Delete current image</label>
                <?php else: ?>
                    <p>No image uploaded.</p>
                <?php endif; ?>
            </div>

            <div>
                <label for="new_image">Upload New Image (optional):</label>
                <input type="file" id="new_image" name="new_image" accept="image/*">
            </div>
            <button type="submit">Update Item</button>
        </form>
        <a href="admin_dashboard.php" class="back-link">Back to Dashboard</a>

        <?php $connect->close(); ?>