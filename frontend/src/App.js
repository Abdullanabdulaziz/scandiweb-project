// src/App.js
import React from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import ProductList from './components/ProductList'; // The Product List component
import AddProduct from './components/AddProduct';   // The Add Product component

function App() {
  return (
    <Router>
      <Routes>
        {/* Route for the Product List Page */}
        <Route path="/" element={<ProductList />} />
        
        {/* Route for the Add Product Page */}
        <Route path="/add-product" element={<AddProduct />} />
        
        {/* Fallback Route for undefined paths */}
        <Route path="*" element={<h2>404 - Page Not Found</h2>} />
      </Routes>
    </Router>
  );
}

export default App;
