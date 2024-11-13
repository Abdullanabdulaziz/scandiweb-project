// src/components/ProductList.js
import React, { useState, useEffect } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
// import './ProductList.css'; // For additional custom styling

const ProductList = () => {
  const [products, setProducts] = useState([]);
  const [selectedIds, setSelectedIds] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
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

  const handleCheckboxChange = (id) => {
    setSelectedIds(prev => prev.includes(id) ? prev.filter(i => i !== id) : [...prev, id]);
  };

  const handleMassDelete = () => {
    if (selectedIds.length === 0) {
      alert("Please select products to delete.");
      return;
    }

    fetch('https://scandiwebproject.wuaze.com/delete-product.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ ids: selectedIds }),
    })
      .then(response => {
        if (!response.ok) {
          throw new Error('Failed to delete products');
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          setProducts(products.filter(product => !selectedIds.includes(product.id)));
          setSelectedIds([]);
        } else {
          setError('Failed to delete selected products.');
        }
      })
      .catch(error => setError(error.message));
  };

  const renderProductAttribute = (product) => {
    switch (product.type) {
      case 'DVD':
        return `Size: ${product.size} MB`;
      case 'Book':
        return `Weight: ${product.weight} KG`;
      case 'Furniture':
        return `Dimensions: ${product.height}x${product.width}x${product.length} CM`;
      default:
        return null;
    }
  };

  return (
    <div className="container">
      <h1 className="text-center my-4">Product List</h1>

      {error && <div className="alert alert-danger text-center">{error}</div>}

      <div className="d-flex justify-content-between mb-3">
        <button className="btn btn-primary" onClick={() => window.location.href='/add-product'}>
          ADD
        </button>
        <button id="delete-product-btn" className="btn btn-danger" onClick={handleMassDelete}>
          MASS DELETE
        </button>
      </div>

      {loading ? (
        <p className="text-center">Loading products...</p>
      ) : (
        <div className="row">
          {products.length > 0 ? (
            products.map(product => (
              <div key={product.id} className="col-md-4 mb-4">
                <div className="card">
                  <div className="card-body">
                    <input
                      type="checkbox"
                      className="delete-checkbox"
                      checked={selectedIds.includes(product.id)}
                      onChange={() => handleCheckboxChange(product.id)}
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
