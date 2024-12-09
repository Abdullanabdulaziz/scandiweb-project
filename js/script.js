document.addEventListener("DOMContentLoaded", function () {
    const productTypeSelect = document.getElementById("productType");
    const dvdFields = document.getElementById("dvd-fields");
    const bookFields = document.getElementById("book-fields");
    const furnitureFields = document.getElementById("furniture-fields");
    const form = document.getElementById("product_form");

    function displayInlineError(message) {
        let errorContainer = document.querySelector(".error-message");
        if (!errorContainer) {
            errorContainer = document.createElement("div");
            errorContainer.className = "error-message";
            errorContainer.style.color = "red";
            form.prepend(errorContainer);
        }
        errorContainer.textContent = message;
    }

    function toggleFields(type) {
        dvdFields.style.display = "none";
        bookFields.style.display = "none";
        furnitureFields.style.display = "none";

        if (type === "DVD") {
            dvdFields.style.display = "block";
        } else if (type === "Book") {
            bookFields.style.display = "block";
        } else if (type === "Furniture") {
            furnitureFields.style.display = "block";
        }
    }

    function validateProductFields() {
        const productType = productTypeSelect.value;
        displayInlineError("");

        if (productType === "DVD" && !document.getElementById("size").value) {
            displayInlineError("Please provide the size for the DVD.");
            return false;
        }
        if (productType === "Book" && !document.getElementById("weight").value) {
            displayInlineError("Please provide the weight for the Book.");
            return false;
        }
        if (productType === "Furniture") {
            const height = document.getElementById("height").value;
            const width = document.getElementById("width").value;
            const length = document.getElementById("length").value;
            if (!height || !width || !length) {
                displayInlineError("Please provide all dimensions (Height, Width, Length) for the Furniture.");
                return false;
            }
        }
        return true;
    }

    if (productTypeSelect) {
        toggleFields(productTypeSelect.value);

        productTypeSelect.addEventListener("change", function () {
            toggleFields(this.value);
        });

        form.addEventListener("submit", function (e) {
            if (!validateProductFields()) {
                e.preventDefault();
            }
        });
    }

    const deleteButton = document.getElementById("delete-product-btn");
    if (deleteButton) {
        deleteButton.addEventListener("click", function () {
            const checkboxes = document.querySelectorAll(".delete-checkbox:checked");
            if (checkboxes.length === 0) {
                displayInlineError("Please select products to delete.");
                return;
            }

            const idsToDelete = Array.from(checkboxes).map((checkbox) => checkbox.value);

            fetch("delete-product.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ ids: idsToDelete }),
            })
                .then((response) => {
                    if (!response.ok) throw new Error("Network response was not ok");
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        checkboxes.forEach((checkbox) => {
                            const card = checkbox.closest(".card");
                            if (card) card.remove();
                        });
                        displayInlineError(""); // Clear previous errors
                    } else {
                        displayInlineError("Failed to delete products: " + (data.message || "Unknown error"));
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    displayInlineError("An error occurred while trying to delete products. Please try again.");
                });
        });
    }
});
