import React, { useState, useEffect } from 'react';
import { Container, Row, Col, Card, Alert, Button, Spinner, Badge } from 'react-bootstrap';
import { 
  FaServer, 
  FaDatabase, 
  FaCalendarAlt, 
  FaMemory, 
  FaUsers, 
  FaChartLine, 
  FaBell, 
  FaSearch, 
  FaCog, 
  FaSignOutAlt, 
  FaTachometerAlt,
  FaTable,
  FaTasks,
  FaChartBar
} from 'react-icons/fa';
import SystemHealth from './components/SystemHealth';
import DatabaseManagement from './components/DatabaseManagement';
import SchedulerManagement from './components/SchedulerManagement';
import CacheManagement from './components/CacheManagement';
import UserManagement from './components/UserManagement';
import StockManagement from './components/StockManagement';
import './AdminPanel.css';

const AdminPanel = () => {
  const [activeTab, setActiveTab] = useState('system');
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState(null);
  const [lastRefresh, setLastRefresh] = useState(null);

  const refreshData = async () => {
    setIsLoading(true);
    setError(null);
    try {
      // Trigger refresh logic for the active component
      setLastRefresh(new Date());
      // After 1 second, disable loading indicator (actual data loading happens in child components)
      setTimeout(() => setIsLoading(false), 1000);
    } catch (err) {
      console.error('Error refreshing data:', err);
      setError('Gagal memperbarui data. Silakan coba lagi.');
      setIsLoading(false);
    }
  };

  useEffect(() => {
    // Initial data load
    refreshData();
    
    // Set up periodic refresh every 30 seconds for system health
    const refreshInterval = setInterval(() => {
      if (activeTab === 'system') {
        refreshData();
      }
    }, 30000);
    
    return () => clearInterval(refreshInterval);
  }, [activeTab]);

  const renderContent = () => {
    switch (activeTab) {
      case 'system':
        return <SystemHealth lastRefresh={lastRefresh} />;
      case 'database':
        return <DatabaseManagement lastRefresh={lastRefresh} />;
      case 'scheduler':
        return <SchedulerManagement lastRefresh={lastRefresh} />;
      case 'cache':
        return <CacheManagement lastRefresh={lastRefresh} />;
      case 'users':
        return <UserManagement lastRefresh={lastRefresh} />;
      case 'stocks':
        return <StockManagement lastRefresh={lastRefresh} />;
      default:
        return <SystemHealth lastRefresh={lastRefresh} />;
    }
  };
  
  // Get current date and time
  const currentDate = new Date().toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });

  return (
    <div className="wrapper">
      {/* Sidebar */}
      <div className="sidebar">
        <div className="sidebar-brand">
          <FaServer />
          <div className="sidebar-logo">ZAHAAM</div>
        </div>

        <div className="sidebar-menu">
          <div className="menu-title">MANAJEMEN</div>
          <div 
            className={`sidebar-menu-item ${activeTab === 'system' ? 'active' : ''}`}
            onClick={() => setActiveTab('system')}
          >
            <FaTachometerAlt />
            <span>Status Sistem</span>
          </div>
          <div 
            className={`sidebar-menu-item ${activeTab === 'database' ? 'active' : ''}`}
            onClick={() => setActiveTab('database')}
          >
            <FaDatabase />
            <span>Manajemen Database</span>
          </div>
          <div 
            className={`sidebar-menu-item ${activeTab === 'scheduler' ? 'active' : ''}`}
            onClick={() => setActiveTab('scheduler')}
          >
            <FaCalendarAlt />
            <span>Scheduler</span>
          </div>
          
          <div className="menu-title">ADMINISTRASI</div>
          <div 
            className={`sidebar-menu-item ${activeTab === 'cache' ? 'active' : ''}`}
            onClick={() => setActiveTab('cache')}
          >
            <FaMemory />
            <span>Cache Management</span>
          </div>
          <div 
            className={`sidebar-menu-item ${activeTab === 'users' ? 'active' : ''}`}
            onClick={() => setActiveTab('users')}
          >
            <FaUsers />
            <span>User Management</span>
          </div>
          <div 
            className={`sidebar-menu-item ${activeTab === 'stocks' ? 'active' : ''}`}
            onClick={() => setActiveTab('stocks')}
          >
            <FaChartLine />
            <span>Stock Management</span>
          </div>
          
          <div className="menu-title">SHORTCUTS</div>
          <div className="sidebar-menu-item">
            <FaTable />
            <span>Data Tables</span>
          </div>
          <div className="sidebar-menu-item">
            <FaTasks />
            <span>Task Manager</span>
          </div>
          <div className="sidebar-menu-item">
            <FaChartBar />
            <span>Statistics</span>
          </div>
        </div>
      </div>

      {/* Main Content */}
      <div className="page-content">
        {/* Topbar */}
        <div className="topbar">
          <div className="d-flex align-items-center">
            <h4 className="mb-0 me-3">Admin Dashboard</h4>
            <div className="live-indicator">Live</div>
          </div>
          
          <div className="d-flex align-items-center">
            <Button 
              variant="light" 
              className="me-3 shadow-sm"
              onClick={refreshData} 
              disabled={isLoading}
            >
              {isLoading ? (
                <>
                  <Spinner as="span" animation="border" size="sm" className="me-1" />
                  Memperbarui...
                </>
              ) : (
                'Refresh Data'
              )}
            </Button>
            
            <div className="d-flex align-items-center">
              <FaBell className="me-4" style={{color: '#6c757d'}} />
              <FaSearch className="me-4" style={{color: '#6c757d'}} />
              <FaCog className="me-4" style={{color: '#6c757d'}} />
              <FaSignOutAlt style={{color: '#6c757d'}} />
            </div>
          </div>
        </div>

        {/* Main container */}
        <div className="container-fluid">
          {/* Alert for errors */}
          {error && (
            <Alert variant="danger" onClose={() => setError(null)} dismissible>
              {error}
            </Alert>
          )}

          {/* Welcome header */}
          <div className="welcome-header">
            <h4 className="welcome-title">Welcome to ZAHAAM Admin</h4>
            <p className="welcome-subtitle">{currentDate}</p>
          </div>
          
          {/* Tab navigation */}
          <div className="page-tabs">
            <div className={`page-tab ${activeTab === 'system' ? 'active' : ''}`} onClick={() => setActiveTab('system')}>
              Status Sistem
            </div>
            <div className={`page-tab ${activeTab === 'database' ? 'active' : ''}`} onClick={() => setActiveTab('database')}>
              Database
            </div>
            <div className={`page-tab ${activeTab === 'scheduler' ? 'active' : ''}`} onClick={() => setActiveTab('scheduler')}>
              Scheduler
            </div>
            <div className={`page-tab ${activeTab === 'cache' ? 'active' : ''}`} onClick={() => setActiveTab('cache')}>
              Cache
            </div>
            <div className={`page-tab ${activeTab === 'users' ? 'active' : ''}`} onClick={() => setActiveTab('users')}>
              Users
            </div>
            <div className={`page-tab ${activeTab === 'stocks' ? 'active' : ''}`} onClick={() => setActiveTab('stocks')}>
              Stocks
            </div>
          </div>

          {/* Summary Cards */}
          <Row className="mb-4">
            <Col xl={3} md={6} className="mb-3">
              <Card className="stats-card">
                <Card.Body>
                  <div className="d-flex">
                    <div className="flex-grow-1">
                      <p className="stats-label">Total Users</p>
                      <h3 className="stats-value">9,526</h3>
                      <Badge className="badge-success-lighten">+8.1% <i className="bx bx-up-arrow-alt"></i></Badge>
                    </div>
                    <div className="avatar-md bg-soft-primary rounded">
                      <FaUsers className="fs-32 text-primary" />
                    </div>
                  </div>
                </Card.Body>
              </Card>
            </Col>
            
            <Col xl={3} md={6} className="mb-3">
              <Card className="stats-card">
                <Card.Body>
                  <div className="d-flex">
                    <div className="flex-grow-1">
                      <p className="stats-label">System Health</p>
                      <h3 className="stats-value">98.2%</h3>
                      <Badge className="badge-success-lighten">Healthy <i className="bx bx-check-circle"></i></Badge>
                    </div>
                    <div className="avatar-md bg-soft-primary rounded">
                      <FaServer className="fs-32 text-primary" />
                    </div>
                  </div>
                </Card.Body>
              </Card>
            </Col>
            
            <Col xl={3} md={6} className="mb-3">
              <Card className="stats-card">
                <Card.Body>
                  <div className="d-flex">
                    <div className="flex-grow-1">
                      <p className="stats-label">Database Records</p>
                      <h3 className="stats-value">123.6k</h3>
                      <Badge className="badge-danger-lighten">-0.9% <i className="bx bx-down-arrow-alt"></i></Badge>
                    </div>
                    <div className="avatar-md bg-soft-primary rounded">
                      <FaDatabase className="fs-32 text-primary" />
                    </div>
                  </div>
                </Card.Body>
              </Card>
            </Col>
            
            <Col xl={3} md={6} className="mb-3">
              <Card className="stats-card">
                <Card.Body>
                  <div className="d-flex">
                    <div className="flex-grow-1">
                      <p className="stats-label">Scheduled Tasks</p>
                      <h3 className="stats-value">976</h3>
                      <Badge className="badge-warning-lighten">Pending 12 <i className="bx bx-time"></i></Badge>
                    </div>
                    <div className="avatar-md bg-soft-primary rounded">
                      <FaCalendarAlt className="fs-32 text-primary" />
                    </div>
                  </div>
                </Card.Body>
              </Card>
            </Col>
          </Row>
          
          {/* Main Content Area */}
          <Row>
            <Col lg={12}>
              <Card className="mb-4">
                <Card.Body>
                  {renderContent()}
                </Card.Body>
                <Card.Footer className="text-muted">  
                  {lastRefresh && (
                    <small>
                      Last updated: {lastRefresh.toLocaleTimeString()}
                    </small>
                  )}
                </Card.Footer>
              </Card>
            </Col>
          </Row>
        </div>
      </div>
    </div>
  );
};

export default AdminPanel;
