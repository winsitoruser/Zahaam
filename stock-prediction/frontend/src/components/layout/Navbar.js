import React, { useState } from 'react';
import { Link, useLocation } from 'react-router-dom';

const Navbar = () => {
  const location = useLocation();
  const [language, setLanguage] = useState('id');
  const [darkMode, setDarkMode] = useState(false);
  
  const isActive = (path) => {
    return location.pathname === path;
  };

  return (
    <nav className="navbar navbar-expand-lg navbar-dark navbar-zahaam fixed-top">
      <div className="container">
        <Link className="navbar-brand" to="/">ZAHAAM</Link>
        <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span className="navbar-toggler-icon"></span>
        </button>
        <div className="collapse navbar-collapse" id="navbarNav">
          <ul className="navbar-nav me-auto">
            <li className="nav-item">
              <Link className={`nav-link ${isActive('/') ? 'active' : ''}`} to="/" data-translate="home">Beranda</Link>
            </li>
            <li className="nav-item">
              <Link className={`nav-link ${isActive('/dashboard') ? 'active' : ''}`} to="/dashboard" data-translate="dashboard">Dashboard</Link>
            </li>
            <li className="nav-item">
              <Link className={`nav-link ${isActive('/stocks') ? 'active' : ''}`} to="/stocks" data-translate="stocks">Saham</Link>
            </li>
            <li className="nav-item">
              <Link className={`nav-link ${isActive('/prediction') ? 'active' : ''}`} to="/prediction" data-translate="ai_prediction">Prediksi AI</Link>
            </li>
            <li className="nav-item">
              <Link className={`nav-link ${isActive('/strategy') ? 'active' : ''}`} to="/strategy" data-translate="trading_strategy">Strategi Trading</Link>
            </li>
          </ul>
          <div className="d-flex align-items-center">
            {/* Language Selector */}
            <div className="me-3">
              <select 
                id="languageSelector" 
                className="form-select form-select-sm"
                value={language}
                onChange={(e) => setLanguage(e.target.value)}
              >
                <option value="id">ID</option>
                <option value="en">EN</option>
              </select>
            </div>
            
            {/* Dark Mode Toggle */}
            <div className="dark-mode-toggle me-3">
              <input 
                type="checkbox" 
                id="darkModeToggle"
                checked={darkMode}
                onChange={() => setDarkMode(!darkMode)}
              />
              <span className="slider"></span>
            </div>
            
            {/* Notification Component */}
            <div className="dropdown me-3">
              <a className="btn btn-outline-light position-relative" href="#" role="button" id="notification-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i className="bi bi-bell"></i>
                <span className="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  3
                  <span className="visually-hidden">unread notifications</span>
                </span>
              </a>
              <div className="dropdown-menu dropdown-menu-end notification-dropdown p-0 shadow-lg" aria-labelledby="notification-toggle">
                <div className="notification-header d-flex justify-content-between align-items-center p-3">
                  <h6 className="m-0">Notifications</h6>
                  <a href="#" className="text-decoration-none small">Mark all as read</a>
                </div>
                <div className="notification-body">
                  <a href="#" className="notification-item unread d-flex p-3 border-bottom">
                    <div className="notification-icon bg-primary text-white me-3">
                      <i className="bi bi-graph-up"></i>
                    </div>
                    <div className="notification-details flex-grow-1">
                      <p className="mb-1 fw-bold">Price Alert: BBCA has increased by 5%</p>
                      <span className="text-muted small">2 minutes ago</span>
                    </div>
                  </a>
                  <a href="#" className="notification-item d-flex p-3 border-bottom">
                    <div className="notification-icon bg-success text-white me-3">
                      <i className="bi bi-check-circle"></i>
                    </div>
                    <div className="notification-details flex-grow-1">
                      <p className="mb-1">Strategy execution successful</p>
                      <span className="text-muted small">1 hour ago</span>
                    </div>
                  </a>
                  <a href="#" className="notification-item d-flex p-3 border-bottom">
                    <div className="notification-icon bg-warning text-white me-3">
                      <i className="bi bi-exclamation-triangle"></i>
                    </div>
                    <div className="notification-details flex-grow-1">
                      <p className="mb-1">System update scheduled</p>
                      <span className="text-muted small">Today, 14:00</span>
                    </div>
                  </a>
                </div>
                <div className="notification-footer text-center p-2">
                  <a href="notifications.html" className="text-decoration-none">View all notifications</a>
                </div>
              </div>
            </div>
            
            {/* User Profile */}
            <div className="dropdown">
              <a className="dropdown-toggle d-flex align-items-center text-decoration-none text-light" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div className="avatar avatar-sm me-2">
                  <img src="https://ui-avatars.com/api/?name=User&background=random" alt="User" className="rounded-circle" width="32" height="32" />
                </div>
                <span className="d-none d-sm-inline-block">User</span>
              </a>
              <ul className="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userMenu">
                <li><a className="dropdown-item" href="profile.html"><i className="bi bi-person me-2"></i> Profile</a></li>
                <li><a className="dropdown-item" href="settings.html"><i className="bi bi-gear me-2"></i> Settings</a></li>
                <li><hr className="dropdown-divider" /></li>
                <li><a className="dropdown-item" href="login.html"><i className="bi bi-box-arrow-right me-2"></i> Logout</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </nav>
  );
};

export default Navbar;
