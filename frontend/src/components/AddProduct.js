import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import 'bootstrap/dist/css/bootstrap.min.css';

const AddProduct = () => {
  const navigate = useNavigate();

  const [formData, setFormData] = useState({
    sku: '',
    name: '',
    price: '',
    productType: 'DVD', // Default product type
    attributes: {}
  });

  const [error, setError] = useState(null);

  // Handle input changes for regular fields (sku, name, price, productType)
  const handleChange = (event) => {
    const { name, value } = event.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
    setError(null); // Clear any previous errors
  };

  // Handle product-specific attributes (size, weight, dimensions) based on product type
  const handleAttributesChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({
      ...prev,
      attributes: { ...prev.attributes, [name]: value }
    }));
  };

  // Handle form submission
  const handleSubmit = async (event) => {
    event.preventDefault(); // Prevent default form submission

    // Ensure required fields are filled
    if (!formData.sku || !formData.name || !formData.price || !formData.productType) {
      setError("Please fill in all the required fields.");
      return;
    }

    const payload = {
      sku: formData.sku,
      name: formData.name,
      price: parseFloat(formData.price),
      productType: formData.productType,
      attributes: formData.attributes // Attributes as object
    };

    try {
      const response = await fetch('https://scandiwebproject.wuaze.com/save-product.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json', // Ensure the backend knows we're sending JSON
        },
        body: JSON.stringify(payload) // Send the correct payload
      });

      const data = await response.json();
      console.log('Backend response:', data);  // Log the response to debug

      if (response.ok && data.success) {
        // Reset the form on successful submission
        setFormData({
          sku: '',
          name: '',
          price: '',
          productType: 'DVD', // Reset to default type
          attributes: {}
        });

        // Ensure this happens after the save is complete
        console.log('Product saved successfully. Redirecting to homepage.');
        navigate('/'); // Redirect to the homepage or product list
      } else {
        setError(data.error || 'Failed to save product');
      }
    } catch (error) {
      setError('Network error, please try again');
      console.error('Error during fetch:', error);  // Log the error for debugging
    }
  };

  // Dynamically render product-specific attributes
  const renderAttributeFields = () => {
    switch (formData.productType) {
      case 'DVD':
        return (
          <div className="form-group mb-3">
            <label htmlFor="size">Size (MB)</label>
            <input
              type="number"
              id="size"
              name="size"
              value={formData.attributes.size || ''}
              onChange={handleAttributesChange}
              className="form-control"
              placeholder="Enter size in MB"
              min="0"
            />
          </div>
        );
      case 'Book':
        return (
          <div className="form-group mb-3">
            <label htmlFor="weight">Weight (KG)</label>
            <input
              type="number"
              id="weight"
              name="weight"
              value={formData.attributes.weight || ''}
              onChange={handleAttributesChange}
              className="form-control"
              placeholder="Enter weight in KG"
              min="0"
            />
          </div>
        );
      case 'Furniture':
        return (
          <>
            <div className="form-group mb-3">
              <label htmlFor="height">Height (CM)</label>
              <input
                type="number"
                id="height"
                name="height"
                value={formData.attributes.height || ''}
                onChange={handleAttributesChange}
                className="form-control"
                placeholder="Enter height in CM"
                min="0"
              />
            </div>
            <div className="form-group mb-3">
              <label htmlFor="width">Width (CM)</label>
              <input
                type="number"
                id="width"
                name="width"
                value={formData.attributes.width || ''}
                onChange={handleAttributesChange}
                className="form-control"
                placeholder="Enter width in CM"
                min="0"
              />
            </div>
            <div className="form-group mb-3">
              <label htmlFor="length">Length (CM)</label>
              <input
                type="number"
                id="length"
                name="length"
                value={formData.attributes.length || ''}
                onChange={handleAttributesChange}
                className="form-control"
                placeholder="Enter length in CM"
                min="0"
              />
            </div>
          </>
        );
      default:
        return null;
    }
  };

  return (
    <div className="container">
      <h2>Add Product</h2>
      <form id="product_form" onSubmit={handleSubmit}>
        {/* SKU Field */}
        <div className="form-group">
          <label htmlFor="sku">SKU</label>
          <input
            type="text"
            id="sku"
            name="sku"
            value={formData.sku}
            onChange={handleChange}
            required
            className="form-control"
          />
        </div>

        {/* Name Field */}
        <div className="form-group">
          <label htmlFor="name">Name</label>
          <input
            type="text"
            id="name"
            name="name"
            value={formData.name}
            onChange={handleChange}
            required
            className="form-control"
          />
        </div>

        {/* Price Field */}
        <div className="form-group">
          <label htmlFor="price">Price</label>
          <input
            type="number"
            id="price"
            name="price"
            value={formData.price}
            onChange={handleChange}
            required
            className="form-control"
          />
        </div>

        {/* Product Type Selector */}
        <div className="form-group">
          <label htmlFor="productType">Product Type</label>
          <select
            id="productType"
            name="productType"
            value={formData.productType}
            onChange={handleChange}
            required
            className="form-control"
          >
            <option value="DVD">DVD</option>
            <option value="Book">Book</option>
            <option value="Furniture">Furniture</option>
          </select>
        </div>

        {/* Dynamic Attributes based on Product Type */}
        {renderAttributeFields()}

        {/* Submit Button */}
        <button type="submit" className="btn btn-primary">
          Save Product
        </button>

        {/* Cancel Button */}
        <button type="button" className="btn btn-secondary ml-2" onClick={() => window.location.href = '/'}>Cancel</button>
      </form>

      {/* Display error message if there's an issue */}
      {error && <div className="alert alert-danger mt-3">{error}</div>}
    </div>
  );
};

export default AddProduct;
