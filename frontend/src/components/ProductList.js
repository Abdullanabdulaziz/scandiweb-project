import React, { useState, useEffect } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';

const ProductList = () => {
  const [products, setProducts] = useState([]);
  const [selectedIds, setSelectedIds] = useState([]); // Track selected products by SKU
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Fetch product data from the backend
  useEffect(() => {
    fetch('https://scandiwebproject.wuaze.com/index.php')
      .then((response) => {
        if (!response.ok) {
          throw new Error('Failed to fetch products');
        }
        return response.json();
      })
      .then((data) => {
        console.log('Fetched data:', data); // Log the fetched data for debugging
        if (data && data.products) {
          setProducts(data.products); // Set products if they exist in the response
        } else {
          setError('No products found.');
        }
      })
      .catch((error) => {
        setError(error.message); // Set error if the fetch fails
        console.error('Error fetching products:', error);
      })
      .finally(() => setLoading(false)); // Stop loading indicator
  }, []);

  // Handle checkbox change (product selection)
  const handleCheckboxChange = (sku) => {
    setSelectedIds((prev) =>
      prev.includes(sku) ? prev.filter((id) => id !== sku) : [...prev, sku]
    );
  };

  // Handle mass deletion
  const handleMassDelete = () => {
    if (selectedIds.length === 0) {
      alert('Please select products to delete.');
      return;
    }

    fetch('https://scandiwebproject.wuaze.com/delete-product.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ ids: selectedIds }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          setProducts(products.filter((product) => !selectedIds.includes(product.sku)));
          setSelectedIds([]);
        } else {
          setError('Failed to delete selected products.');
        }
      })
      .catch((error) => setError(error.message));
  };

  // Render product-specific attributes (e.g., Size, Weight, Dimensions)
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

      {/* Error message */}
      {error && <div className="alert alert-danger text-center">{error}</div>}

      {/* Buttons: Add Product and Mass Delete */}
      <div className="d-flex justify-content-between mb-3">
        <button className="btn btn-primary" onClick={() => window.location.href = '/add-product'}>
          ADD
        </button>
        <button className="btn btn-danger" onClick={handleMassDelete}>
          MASS DELETE
        </button>
      </div>

      {/* Loading state */}
      {loading ? (
        <p className="text-center">Loading products...</p>
      ) : (
        <div className="row">
          {products.length > 0 ? (
            products.map((product) => (
              <div key={product.sku} className="col-md-4 mb-4">
                <div className="card">
                  <div className="card-body">
                    {/* Checkbox for product selection */}
                    <input
                      type="checkbox"
                      className="delete-checkbox"
                      checked={selectedIds.includes(product.sku)} // Check if the SKU is in the selectedIds
                      onChange={() => handleCheckboxChange(product.sku)} // Toggle selection
                    />
                    <h5>SKU: {product.sku}</h5>
                    <p>Name: {product.name}</p>
                    <p>Price: {product.price}</p>
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
