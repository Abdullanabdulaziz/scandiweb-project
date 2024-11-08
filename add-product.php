<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <h1>Add Product</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message" style="color: red;">
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <form id="product_form" action="save-product.php" method="POST">
        <label for="sku">SKU</label>
        <input type="text" id="sku" name="sku" required>

        <label for="name">Name</label>
        <input type="text" id="name" name="name" required>

        <label for="price">Price ($)</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="productType">Type</label>
        <select id="productType" name="productType" required>
            <option value="DVD">DVD</option>
            <option value="Book">Book</option>
            <option value="Furniture">Furniture</option>
        </select>

        <div id="dvd-fields" style="display:none;">
            <label for="size">Size (MB)</label>
            <input type="number" id="size" name="size" min="0">
            <p>Please provide the size in MB.</p>
        </div>

        <div id="book-fields" style="display:none;">
            <label for="weight">Weight (KG)</label>
            <input type="number" id="weight" name="weight" min="0">
            <p>Please provide the weight in KG.</p>
        </div>

        <div id="furniture-fields" style="display:none;">
            <label for="height">Height (CM)</label>
            <input type="number" id="height" name="height" min="0">
            <label for="width">Width (CM)</label>
            <input type="number" id="width" name="width" min="0">
            <label for="length">Length (CM)</label>
            <input type="number" id="length" name="length" min="0">
            <p>Please provide dimensions in CM (HxWxL).</p>
        </div>

        <button type="submit">Save</button>
        <button type="button" onclick="window.location.href='index.php'">Cancel</button>
    </form>

    <script src="js/script.js"></script>
</body>
</html>
