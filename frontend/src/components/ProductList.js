// src/components/ProductList.js
import React, { useState, useEffect } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
// import './ProductList.css'; // For additional custom styling

const ProductList = () => {
  const [products, setProducts] = useState([]);
  const [selectedIds, setSelectedIds] = useState([]); // Track selected products by SKU
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    // Fetch product data from backend
    fetch('https://scandiwebproject.wuaze.com/index.php')
      .then(response => {
        if (!response.ok) {
          throw new Error('Failed to fetch products');
        }
        return response.json();
      })
      .then(data => setProducts(data))
      .catch(error => setError(error.message))
      .finally(() => setLoading(false));
  }, []);

  // Handle checkbox change: add/remove SKU to/from selectedIds
  const handleCheckboxChange = (sku) => {
    setSelectedIds(prev => prev.includes(sku) ? prev.filter(id => id !== sku) : [...prev, sku]);
  };

  // Handle mass deletion
  const handleMassDelete = () => {
    if (selectedIds.length === 0) {
      alert("Please select products to delete.");
      return;
    }

    // Send DELETE request to backend with selected SKUs
    fetch('https://scandiwebproject.wuaze.com/delete-product.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ ids: selectedIds }),  // Send selected SKUs for deletion
    })
      .then(response => {
        if (!response.ok) {
          throw new Error('Failed to delete products');
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          // Remove deleted products from UI and clear selected checkboxes
          setProducts(products.filter(product => !selectedIds.includes(product.sku)));
          setSelectedIds([]);
        } else {
          setError('Failed to delete selected products.');
        }
      })
      .catch(error => setError(error.message));
  };

  // Render product attributes based on type (DVD, Book, Furniture)
  const renderProductAttribute = (product) => {
    switch (product.type) {
      case 'DVD':
        return `Size: ${product.size} MB`;
      case 'Book':
        return `Weight: ${product.weight} KG`;
      case 'Furniture':
        return `Dimensions: ${product.height}x${product.width}x${product.length} CM`;
      default:
        return '';
    }
  };

  return (
    <div className="container">
      <h1 className="text-center my-4">Product List</h1>

      {error && <div className="alert alert-danger text-center">{error}</div>}

      {/* Buttons: Add Product and Mass Delete */}
      <div className="d-flex justify-content-between mb-3">
        <button className="btn btn-primary" onClick={() => window.location.href='/add-product'}>
          ADD
        </button>
        <button id="delete-product-btn" className="btn btn-danger" onClick={handleMassDelete}>
          MASS DELETE
        </button>
      </div>

      {/* Loading state */}
      {loading ? (
        <p className="text-center">Loading products...</p>
      ) : (
        <div className="row">
          {products.length > 0 ? (
            products.map(product => (
              <div key={product.sku} className="col-md-4 mb-4">
                <div className="card">
                  <div className="card-body">
                    {/* Checkbox for product selection */}
                    <input
                      type="checkbox"
                      className="delete-checkbox"
                      checked={selectedIds.includes(product.sku)}  // Check if the SKU is in the selectedIds
                      onChange={() => handleCheckboxChange(product.sku)} // Toggle selection
                    />
                    <h5>SKU: {product.sku}</h5>
                    <p>Name: {product.name}</p>
                    <p>Price: {product.price} $</p>
                    <p>{renderProductAttribute(product)}</p>
                  </div>
                </div>
              </div>
            ))
          ) : (
            <p className="text-center">No products found.</p>
          )}
        </div>
      )}
    </div>
  );
};

export default ProductList;
