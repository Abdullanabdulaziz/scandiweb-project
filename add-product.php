<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Add Product</h1>
        <form id="addProductForm">
            <div class="form-group">
                <label for="sku">SKU</label>
                <input type="text" class="form-control" id="sku" name="sku" required />
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required />
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required />
            </div>
            <div class="form-group">
                <label for="productType">Product Type</label>
                <select class="form-control" id="productType" name="productType" required>
                    <option value="DVD">DVD</option>
                    <option value="Book">Book</option>
                    <option value="Furniture">Furniture</option>
                </select>
            </div>
            <div id="productAttributes"></div> <!-- Product type specific fields -->

            <button type="submit" class="btn btn-primary">Save Product</button>
            <button type="button" class="btn btn-secondary" onclick="window.location.href='/'">Cancel</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#productType').on('change', function() {
                const productType = $(this).val();
                let fields = '';

                if (productType === 'DVD') {
                    fields = `
                        <div class="form-group">
                            <label for="size">Size (MB)</label>
                            <input type="number" class="form-control" id="size" name="size" required />
                        </div>
                    `;
                } else if (productType === 'Book') {
                    fields = `
                        <div class="form-group">
                            <label for="weight">Weight (KG)</label>
                            <input type="number" class="form-control" id="weight" name="weight" required />
                        </div>
                    `;
                } else if (productType === 'Furniture') {
                    fields = `
                        <div class="form-group">
                            <label for="height">Height (CM)</label>
                            <input type="number" class="form-control" id="height" name="height" required />
                        </div>
                        <div class="form-group">
                            <label for="width">Width (CM)</label>
                            <input type="number" class="form-control" id="width" name="width" required />
                        </div>
                        <div class="form-group">
                            <label for="length">Length (CM)</label>
                            <input type="number" class="form-control" id="length" name="length" required />
                        </div>
                    `;
                }

                $('#productAttributes').html(fields);
            });

            $('#addProductForm').on('submit', function(event) {
                event.preventDefault();

                const formData = {
                    sku: $('#sku').val(),
                    name: $('#name').val(),
                    price: $('#price').val(),
                    productType: $('#productType').val(),
                    size: $('#size').val(),
                    weight: $('#weight').val(),
                    height: $('#height').val(),
                    width: $('#width').val(),
                    length: $('#length').val()
                };

                $.ajax({
                    url: 'https://scandiwebproject.wuaze.com/save-product.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(formData),
                    success: function(response) {
                        if (response.success) {
                            alert('Product added successfully!');
                            window.location.href = '/'; // Redirect to the home page
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while adding the product.');
                    }
                });
            });
        });
    </script>
</body>
</html>
