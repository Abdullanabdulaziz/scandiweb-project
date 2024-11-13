// src/components/AddProduct.js
import React, { useState } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';

const AddProduct = () => {
  const [product, setProduct] = useState({
    sku: '',
    name: '',
    price: '',
    productType: 'DVD',
    size: '',
    weight: '',
    height: '',
    width: '',
    length: ''
  });

  const [error, setError] = useState(null);

  const handleChange = (e) => {
    setProduct({ ...product, [e.target.name]: e.target.value });
    setError(null); // Clear error on input change
  };

  const validateProductData = () => {
    if (!product.sku || !product.name || !product.price) {
      setError('Please fill out all required fields.');
      return false;
    }

    if (parseFloat(product.price) <= 0) {
      setError('Price must be positive.');
      return false;
    }

    if (product.productType === 'DVD' && parseInt(product.size) <= 0) {
      setError('Size must be a positive integer for DVD.');
      return false;
    } else if (product.productType === 'Book' && parseFloat(product.weight) <= 0) {
      setError('Weight must be positive for Book.');
      return false;
    } else if (product.productType === 'Furniture') {
      if (parseFloat(product.height) <= 0 || parseFloat(product.width) <= 0 || parseFloat(product.length) <= 0) {
        setError('Dimensions must be positive for Furniture.');
        return false;
      }
    }

    return true;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(null);

    if (!validateProductData()) return;

    const productData = {
      sku: product.sku,
      name: product.name,
      price: parseFloat(product.price),
      productType: product.productType,
      ...(product.productType === 'DVD' && { size: parseInt(product.size) }),
      ...(product.productType === 'Book' && { weight: parseFloat(product.weight) }),
      ...(product.productType === 'Furniture' && {
        height: parseFloat(product.height),
        width: parseFloat(product.width),
        length: parseFloat(product.length)
      })
    };

    try {
      const response = await fetch('https://scandiwebproject.wuaze.com/save-product.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(productData),
      });

      if (response.status === 409) throw new Error('SKU must be unique.');
      if (!response.ok) throw new Error(`Unexpected error: ${response.status}`);

      const data = await response.json();
      if (data.success) {
        window.location.href = '/';
      } else {
        setError(data.message || 'Failed to save product.');
      }
    } catch (err) {
      console.error('Error:', err.message);
      setError(err.message);
    }
  };

  return (
    <div className="container">
      <h1 className="text-center my-4">Add Product</h1>
      {error && <div className="alert alert-danger">{error}</div>}

      <form id="product_form" onSubmit={handleSubmit} className="needs-validation">
        {/* SKU, Name, Price Fields */}
        <div className="form-group mb-3">
          <label htmlFor="sku">SKU</label>
          <input
            type="text"
            id="sku"
            name="sku"
            value={product.sku}
            onChange={handleChange}
            className="form-control"
            placeholder="Enter SKU"
            required
          />
        </div>

        <div className="form-group mb-3">
          <label htmlFor="name">Name</label>
          <input
            type="text"
            id="name"
            name="name"
            value={product.name}
            onChange={handleChange}
            className="form-control"
            placeholder="Enter product name"
            required
          />
        </div>

        <div className="form-group mb-3">
          <label htmlFor="price">Price ($)</label>
          <input
            type="number"
            id="price"
            name="price"
            value={product.price}
            onChange={handleChange}
            className="form-control"
            placeholder="Enter price"
            step="0.01"
            required
          />
        </div>

        {/* Product Type Selection */}
        <div className="form-group mb-3">
          <label htmlFor="productType">Type</label>
          <select
            id="productType"
            name="productType"
            value={product.productType}
            onChange={handleChange}
            className="form-control"
            required
          >
            <option value="DVD">DVD</option>
            <option value="Book">Book</option>
            <option value="Furniture">Furniture</option>
          </select>
        </div>

        {/* Product Type-Specific Fields */}
        {product.productType === 'DVD' && (
          <div className="form-group mb-3">
            <label htmlFor="size">Size (MB)</label>
            <input
              type="number"
              id="size"
              name="size"
              value={product.size}
              onChange={handleChange}
              className="form-control"
              placeholder="Enter size in MB"
              min="0"
              required
            />
          </div>
        )}

        {product.productType === 'Book' && (
          <div className="form-group mb-3">
            <label htmlFor="weight">Weight (KG)</label>
            <input
              type="number"
              id="weight"
              name="weight"
              value={product.weight}
              onChange={handleChange}
              className="form-control"
              placeholder="Enter weight in KG"
              min="0"
              required
            />
          </div>
        )}

        {product.productType === 'Furniture' && (
          <div>
            <div className="form-group mb-3">
              <label htmlFor="height">Height (CM)</label>
              <input
                type="number"
                id="height"
                name="height"
                value={product.height}
                onChange={handleChange}
                className="form-control"
                placeholder="Enter height in CM"
                min="0"
                required
              />
            </div>
            <div className="form-group mb-3">
              <label htmlFor="width">Width (CM)</label>
              <input
                type="number"
                id="width"
                name="width"
                value={product.width}
                onChange={handleChange}
                className="form-control"
                placeholder="Enter width in CM"
                min="0"
                required
              />
            </div>
            <div className="form-group mb-3">
              <label htmlFor="length">Length (CM)</label>
              <input
                type="number"
                id="length"
                name="length"
                value={product.length}
                onChange={handleChange}
                className="form-control"
                placeholder="Enter length in CM"
                min="0"
                required
              />
            </div>
          </div>
        )}

        {/* Save and Cancel Buttons */}
        <button type="submit" className="btn btn-primary">Save</button>
        <button type="button" className="btn btn-secondary ml-2" onClick={() => window.location.href = '/'}>
          Cancel
        </button>
      </form>
    </div>
  );
};

export default AddProduct;
