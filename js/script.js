document.addEventListener("DOMContentLoaded", function() {
    // ----- Form Handling for Product Add Page -----

    const productTypeSelect = document.getElementById('productType');
    const dvdFields = document.getElementById('dvd-fields');
    const bookFields = document.getElementById('book-fields');
    const furnitureFields = document.getElementById('furniture-fields');
    const form = document.getElementById('product_form');

    // Function to show/hide fields based on selected product type
    function toggleFields(type) {
        // Hide all specific fields initially
        dvdFields.style.display = 'none';
        bookFields.style.display = 'none';
        furnitureFields.style.display = 'none';

        // Display fields for the selected product type
        if (type === 'DVD') {
            dvdFields.style.display = 'block';
        } else if (type === 'Book') {
            bookFields.style.display = 'block';
        } else if (type === 'Furniture') {
            furnitureFields.style.display = 'block';
        }
    }

    // Initial form setup based on the default product type
    if (productTypeSelect) {
        toggleFields(productTypeSelect.value);

        // Update displayed fields when product type changes
        productTypeSelect.addEventListener('change', function() {
            toggleFields(this.value);
        });

        // Form validation before submission
        form.addEventListener('submit', function(e) {
            const productType = productTypeSelect.value;

            // Validation for DVD type
            if (productType === 'DVD' && !document.getElementById('size').value) {
                alert("Please provide the size for the DVD.");
                e.preventDefault();
            }

            // Validation for Book type
            else if (productType === 'Book' && !document.getElementById('weight').value) {
                alert("Please provide the weight for the Book.");
                e.preventDefault();
            }

            // Validation for Furniture type
            else if (productType === 'Furniture') {
                const height = document.getElementById('height').value;
                const width = document.getElementById('width').value;
                const length = document.getElementById('length').value;

                if (!height || !width || !length) {
                    alert("Please provide all dimensions (Height, Width, Length) for the Furniture.");
                    e.preventDefault();
                }
            }
        });
    }

    // ----- Mass Delete Handling for Product List Page -----

    const deleteButton = document.getElementById('delete-product-btn');
    if (deleteButton) {
        deleteButton.addEventListener('click', function() {
            // Collect all selected checkboxes
            const checkboxes = document.querySelectorAll('.delete-checkbox:checked');
            
            // If no checkboxes are selected, show an alert and exit
            if (checkboxes.length === 0) {
                alert("Please select products to delete.");
                return;
            }

            // Collect the selected product IDs
            const idsToDelete = Array.from(checkboxes).map(checkbox => checkbox.value);

            // Send the IDs to the server using fetch
            fetch('delete-product.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ ids: idsToDelete })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Check for success and reload the page to update the product list
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Failed to delete products: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while trying to delete products. Please try again.');
            });
        });
    }
});
