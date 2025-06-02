import React from 'react';
import { Link, useLocation } from 'react-router-dom';

const Sidebar = () => {
  const location = useLocation();
  
  const isActive = (path) => {
    return location.pathname === path;
  };

  return (
    <div className="sidebar">
      <div className="sidebar-sticky">
        <ul className="nav flex-column" id="sidebarMenu">
          <li className="nav-item">
            <Link className={`nav-link ${isActive('/dashboard') ? 'active' : ''}`} to="/dashboard">
              <i className="bi bi-speedometer2"></i> Dashboard
            </Link>
          </li>
          <li className="nav-item">
            <Link className={`nav-link ${isActive('/stock-details') ? 'active' : ''}`} to="/stock-details">
              <i className="bi bi-graph-up"></i> Stock Details
            </Link>
          </li>
          <li className="nav-item">
            <Link className={`nav-link ${isActive('/stock-list') ? 'active' : ''}`} to="/stock-list">
              <i className="bi bi-table"></i> Stock List
            </Link>
          </li>
          <li className="nav-item">
            <Link className={`nav-link ${isActive('/watchlist') ? 'active' : ''}`} to="/watchlist">
              <i className="bi bi-star"></i> Watchlist
            </Link>
          </li>
          <li className="nav-item">
            <Link className={`nav-link ${isActive('/prediction') ? 'active' : ''}`} to="/prediction">
              <i className="bi bi-robot"></i> AI Predictions
            </Link>
          </li>
          <li className="nav-item">
            <Link className={`nav-link ${isActive('/strategy') ? 'active' : ''}`} to="/strategy">
              <i className="bi bi-gear"></i> Strategies
            </Link>
          </li>
          <li className="nav-item">
            <Link className={`nav-link ${isActive('/alerts') ? 'active' : ''}`} to="/alerts">
              <i className="bi bi-bell"></i> Alerts
            </Link>
          </li>
          <li className="nav-item">
            <Link className={`nav-link ${isActive('/reports') ? 'active' : ''}`} to="/reports">
              <i className="bi bi-file-text"></i> Reports
            </Link>
          </li>
          <li className="nav-item">
            <Link className={`nav-link ${isActive('/profile') ? 'active' : ''}`} to="/profile">
              <i className="bi bi-person"></i> Profile
            </Link>
          </li>
          <li className="nav-item">
            <Link className={`nav-link ${isActive('/help') ? 'active' : ''}`} to="/help">
              <i className="bi bi-question-circle"></i> Help
            </Link>
          </li>
        </ul>
      </div>
    </div>
  );
};

export default Sidebar;
