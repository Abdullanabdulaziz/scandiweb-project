// src/index.js
import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './App';
import './index.css'; // Custom CSS for styling
import 'bootstrap/dist/css/bootstrap.min.css'; // Bootstrap CSS globally
import reportWebVitals from './reportWebVitals';

// Create a root element for React 18
const root = ReactDOM.createRoot(document.getElementById('root'));

// Render the app inside React.StrictMode
root.render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);

// Optional: Report web vitals for performance tracking
if (process.env.NODE_ENV === 'production') {
  reportWebVitals(console.log);
}
