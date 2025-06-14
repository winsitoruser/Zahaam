import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import { FaChartLine, FaTachometerAlt, FaChartBar, FaWallet, FaList, FaRegChartBar, FaTable, FaTasks, FaRegUser, FaServer, FaQuestionCircle } from 'react-icons/fa';

const Sidebar = () => {
  const location = useLocation();
  
  const isActive = (path) => {
    // Improve active state detection to work with nested routes
    if (path === '/dashboard') {
      return location.pathname === path;
    }
    // For stock analysis/detail pages
    if (path === '/stocks' && location.pathname.startsWith('/stocks')) {
      return true;
    }
    return location.pathname === path || location.pathname.startsWith(`${path}/`);
  };

  return (
    <div className="sidebar">
      <div className="sidebar-brand">
        <FaChartLine className="sidebar-brand-icon" />
        <div className="sidebar-logo">ZAHAAM</div>
      </div>
      
      <div className="sidebar-menu">
        <div className="menu-title">MAIN</div>
        <ul className="nav flex-column" id="sidebarMenu">
          <li className="sidebar-menu-item">
            <Link className={`nav-link ${isActive('/dashboard') ? 'active' : ''}`} to="/dashboard">
              <FaTachometerAlt />
              <span>Dashboard</span>
            </Link>
          </li>
          <li className="sidebar-menu-item">
            <Link className={`nav-link ${isActive('/stocks') ? 'active' : ''}`} to="/stocks">
              <FaChartBar />
              <span>Stock Analysis</span>
            </Link>
          </li>
          <li className="sidebar-menu-item">
            <Link className={`nav-link ${isActive('/portfolio') ? 'active' : ''}`} to="/portfolio">
              <FaWallet />
              <span>Portfolio</span>
            </Link>
          </li>
        </ul>
        
        <div className="menu-title">STOCKS</div>
        <ul className="nav flex-column">
          <li className="sidebar-menu-item">
            <Link className={`nav-link ${isActive('/watchlist') ? 'active' : ''}`} to="/watchlist">
              <FaList />
              <span>Watchlist</span>
            </Link>
          </li>
          <li className="sidebar-menu-item">
            <Link className={`nav-link ${isActive('/prediction') ? 'active' : ''}`} to="/prediction">
              <FaRegChartBar />
              <span>Predictions</span>
            </Link>
          </li>
          <li className="sidebar-menu-item">
            <Link className={`nav-link ${isActive('/market-data') ? 'active' : ''}`} to="/market-data">
              <FaTable />
              <span>Market Data</span>
            </Link>
          </li>
        </ul>
        
        <div className="menu-title">TOOLS</div>
        <ul className="nav flex-column">
          <li className="sidebar-menu-item">
            <Link className={`nav-link ${isActive('/strategies') ? 'active' : ''}`} to="/strategies">
              <FaTasks />
              <span>Strategies</span>
            </Link>
          </li>
          <li className="sidebar-menu-item">
            <Link className={`nav-link ${isActive('/profile') ? 'active' : ''}`} to="/profile">
              <FaRegUser />
              <span>Account</span>
            </Link>
          </li>
          <li className="sidebar-menu-item">
            <Link className={`nav-link ${isActive('/admin') ? 'active' : ''}`} to="/admin">
              <FaServer />
              <span>Admin</span>
            </Link>
          </li>
          <li className="sidebar-menu-item">
            <Link className={`nav-link ${isActive('/help') ? 'active' : ''}`} to="/help">
              <FaQuestionCircle />
              <span>Help</span>
            </Link>
          </li>
        </ul>
      </div>
    </div>
  );
};

export default Sidebar;
