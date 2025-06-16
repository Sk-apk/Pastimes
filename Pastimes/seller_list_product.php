<title>List New Product</title>
<link rel="stylesheet" href="add_clothing.css">

<div class="left-container">
        <h1>List New Product</h1>

        <?php if (isset($_GET['error'])) { ?>
            <p class="message error-message"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php } ?>
        <?php if (isset($_GET['success'])) { ?>
            <p class="message success-message">Product listed successfully! It will appear on the browse page.</p>
        <?php } ?>

        <form method="POST" action="seller_list_product_process.php" enctype="multipart/form-data">
            <div class="input-group">
                <label for="brand">Brand:</label>
                <input type="text" id="brand" name="brand" required>
            </div>
            <div class="input-group">
                <label for="size">Size:</label>
                <input type="text" id="size" name="size" required>
            </div>
            <div class="input-group">
                <label for="colour">Colour:</label>
                <input type="text" id="colour" name="colour" required>
            </div>
            <div class="input-group">
                <label for="price">Price (R):</label>
                <input type="number" id="price" name="price" step="0.01" min="0" required>
            </div>
            <div class="input-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="input-group">
                <label for="image">Product Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            <button type="submit">Add Product</button>
        </form>
        <a href="seller_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>