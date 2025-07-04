import React from 'react';
import Navbar from './Navbar';
import Sidebar from './Sidebar';
import Footer from './Footer';

const Layout = ({ children }) => {
  return (
    <div className="layout-container">
      <Navbar />
      <div className="main-layout">
        <Sidebar />
        <div className="main-content">
          {children}
        </div>
      </div>
      <Footer />
    </div>
  );
};

export default Layout;
