import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom'; // Import useNavigate for redirect
import 'bootstrap/dist/css/bootstrap.min.css';

const AddProduct = () => {
  const navigate = useNavigate(); // Use navigate hook for redirection
  const [formData, setFormData] = useState({
    sku: '',
    name: '',
    price: '',
    productType: 'DVD',
    size: '',
    weight: '',
    height: '',
    width: '',
    length: '',
  });

  const [error, setError] = useState(null);

  const handleChange = (event) => {
    const { name, value } = event.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
    setError(null); // Clear error on input change
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    // Construct the payload
    const payload = {
      sku: formData.sku,
      name: formData.name,
      price: parseFloat(formData.price),
      productType: formData.productType,
      attributes: {
        size: formData.size ? parseInt(formData.size, 10) : null,
        weight: formData.weight ? parseFloat(formData.weight) : 0, // Default weight to 0 if not provided
        height: formData.height ? parseFloat(formData.height) : 0, // Default to 0 if not provided
        width: formData.width ? parseFloat(formData.width) : 0, // Default to 0 if not provided
        length: formData.length ? parseFloat(formData.length) : 0, // Default to 0 if not provided
      },
    };

    try {
      const response = await fetch('https://scandiwebproject.wuaze.com/save-product.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload), // Convert payload to JSON string
      });

      if (!response.ok) {
        throw new Error('Failed to save product');
      }

      const data = await response.json();

      if (data.success) {
        setFormData({
          sku: '',
          name: '',
          price: '',
          productType: 'DVD',
          size: '',
          weight: '',
          height: '',
          width: '',
          length: '',
        });
        // Redirect to home page immediately after successful product save
        navigate('/'); // Redirect to the home page using navigate
      } else {
        setError(`Error: ${data.message}`);
      }
    } catch (error) {
      console.error('Error:', error.message);
      setError('Failed to save product');
    }
  };

  return (
    <div className="container">
      <h1 className="text-center my-4">Add Product</h1>
      {error && <div className="alert alert-danger">{error}</div>}
      <form onSubmit={handleSubmit}>
        <div className="form-group mb-3">
          <label htmlFor="sku">SKU</label>
          <input
            type="text"
            id="sku"
            name="sku"
            value={formData.sku}
            onChange={handleChange}
            className="form-control"
            placeholder="Enter SKU"
            required
          />
        </div>

        <div className="form-group mb-3">
          <label htmlFor="name">Product Name</label>
          <input
            type="text"
            id="name"
            name="name"
            value={formData.name}
            onChange={handleChange}
            className="form-control"
            placeholder="Enter product name"
            required
          />
        </div>

        <div className="form-group mb-3">
          <label htmlFor="price">Price</label>
          <input
            type="number"
            id="price"
            name="price"
            value={formData.price}
            onChange={handleChange}
            className="form-control"
            placeholder="Enter price"
            min="0"
            required
          />
        </div>

        <div className="form-group mb-3">
          <label htmlFor="productType">Product Type</label>
          <select
            id="productType"
            name="productType"
            value={formData.productType}
            onChange={handleChange}
            className="form-control"
          >
            <option value="DVD">DVD</option>
            <option value="Book">Book</option>
            <option value="Furniture">Furniture</option>
          </select>
        </div>

        {formData.productType === 'DVD' && (
          <div className="form-group mb-3">
            <label htmlFor="size">Size (MB)</label>
            <input
              type="number"
              id="size"
              name="size"
              value={formData.size}
              onChange={handleChange}
              className="form-control"
              placeholder="Enter size in MB"
              min="0"
              required
            />
          </div>
        )}

        {formData.productType === 'Book' && (
          <div className="form-group mb-3">
            <label htmlFor="weight">Weight (KG)</label>
            <input
              type="number"
              id="weight"
              name="weight"
              value={formData.weight}
              onChange={handleChange}
              className="form-control"
              placeholder="Enter weight in KG"
              min="0"
              required
            />
          </div>
        )}

        {formData.productType === 'Furniture' && (
          <div>
            <div className="form-group mb-3">
              <label htmlFor="height">Height (CM)</label>
              <input
                type="number"
                id="height"
                name="height"
                value={formData.height}
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
                value={formData.width}
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
                value={formData.length}
                onChange={handleChange}
                className="form-control"
                placeholder="Enter length in CM"
                min="0"
                required
              />
            </div>
          </div>
        )}

        <button type="submit" className="btn btn-primary">Save Product</button>
        <button type="button" className="btn btn-secondary ml-2" onClick={() => window.location.href = '/'}>
          Cancel
        </button>
      </form>
    </div>
  );
};

export default AddProduct;
