<title>Add New Clothing Item</title>
 <link rel="stylesheet" href="add_clothing.css">


 <div class="left-container">
<h1>Add New Clothing Item</h1>
        <?php if (isset($_GET['error'])) { ?>
            <p style="color: red; text-align: center;"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php } ?>
        <?php if (isset($_GET['success'])) { ?>
            <p style="color: green; text-align: center;">Clothing item added successfully!</p>
        <?php } ?>

        <form method="POST" action="admin_add_clothing_process.php" enctype="multipart/form-data">
            <div class="input-group" >
                <label for="brand">Brand:</label>
                <input type="text" id="brand" name="brand" required>
            </div>

            <div class="input-group" >
                <label for="size">Size:</label>
                <input type="text" id="size" name="size" required>
            </div>

            <div class="input-group" >
                <label for="colour">Colour:</label>
                <input type="text" id="colour" name="colour" required>
            </div>

            <div class="input-group" >
                <label for="price">Price (R):</label>
                <input type="number" id="price" name="price" step="0.01" min="0" required>
            </div>

            <div class="input-group" >
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div class="input-group" >
                <label for="image">Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <button type="submit">Add Item</button>
        </form>
        <a href="admin_dashboard.php" class="back-link">Back to Dashboard</a>
    


 </div>

