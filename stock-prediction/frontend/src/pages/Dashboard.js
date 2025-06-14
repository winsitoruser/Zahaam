import React, { useState, useEffect } from 'react';
import { Container, Row, Col, Card, Button, Form, Badge, InputGroup } from 'react-bootstrap';
import { 
  FaChartLine, 
  FaTachometerAlt, 
  FaChartBar, 
  FaBell, 
  FaSearch, 
  FaCog, 
  FaRegNewspaper, 
  FaTable, 
  FaRegChartBar,
  FaGithub,
  FaTwitter,
  FaLinkedin,
  FaFilter,
  FaBolt,
  FaBroadcastTower
} from 'react-icons/fa';
import * as api from '../services/apiIntegration';
import { AuthContext } from '../contexts/AuthContext';
import MarketNews from '../components/MarketNews';

// Import dashboard components
import MarketOverview from '../components/dashboard/MarketOverview';
import SectorOverview from '../components/dashboard/SectorOverview';
import TopStocksWidget from '../components/dashboard/TopStocksWidget';
import StockTable from '../components/dashboard/StockTable';

// Import styles
import './larkon-dashboard.css';

// Using API service for helper functions

const Dashboard = () => {
  const [topGainers, setTopGainers] = useState([]);
  const [topLosers, setTopLosers] = useState([]);
  const [marketIndex, setMarketIndex] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [lastUpdated, setLastUpdated] = useState(null);
  
  // Authentication context
  const { isAuthenticated, authToken } = useContext(AuthContext);

  // State for sector data and stock lists
  const [sectors, setSectors] = useState([]);
  const [allStocks, setAllStocks] = useState([]);
  const [mostActive, setMostActive] = useState([]);
  const [ihsgData, setIhsgData] = useState([]);
  const [marketSummary, setMarketSummary] = useState({
    ihsg: 7025.36,
    change: 0.38,
    volume: 9827491200,
    value: 9327491000000,
    frequency: 983425,
    marketCap: 8452000000000000,
    advancing: 189,
    declining: 254,
    unchanged: 98
  });
  
  // Format the last updated time
  const getFormattedUpdateTime = () => {
    if (!lastUpdated) return 'Loading...';
    return lastUpdated.toLocaleTimeString('id-ID', { 
      hour: '2-digit', 
      minute: '2-digit',
      second: '2-digit'
    });
  };

  // Dashboard data loading function - extracted outside useEffect for reusability
  const loadDashboardData = async () => {
    try {
      setLoading(true);
      setError(null); // Reset error state before new load
      
      // Use batch API request for fetching all dashboard data at once
      // This optimizes network requests and leverages client-side caching
      const {
        stocks: stocksData,
        marketSummary: fetchedMarketSummary,
        sectors: sectorData
      } = await api.fetchDashboardData();
      
      // Set the lastUpdated timestamp
      setLastUpdated(new Date());
      
      // Process data for the dashboard with defensive checks
      if (stocksData && Array.isArray(stocksData)) {
        // Set all stocks
        setAllStocks(stocksData);
        
        // Set top gainers - sort by highest change percentage
        const gainers = [...stocksData]
          .filter(stock => stock && typeof stock.change === 'number' && stock.change > 0)
          .sort((a, b) => (b?.change || 0) - (a?.change || 0))
          .slice(0, 5);
        setTopGainers(gainers);
        
        // Set top losers - sort by lowest change percentage
        const losers = [...stocksData]
          .filter(stock => stock && typeof stock.change === 'number' && stock.change < 0)
          .sort((a, b) => (a?.change || 0) - (b?.change || 0))
          .slice(0, 5);
        setTopLosers(losers);
        
        // Set most active stocks by volume
        const active = [...stocksData]
          .filter(stock => stock && typeof stock.volume === 'number')
          .sort((a, b) => (b?.volume || 0) - (a?.volume || 0))
          .slice(0, 5);
        setMostActive(active);
      }
      
      // Set market summary data
      if (fetchedMarketSummary) {
        setMarketSummary(fetchedMarketSummary);
      }
      
      // Set sectors data if available
      if (sectorData && Array.isArray(sectorData)) {
        setSectors(sectorData);
      }
      
      // Set market index data if available in the response
      if (fetchedMarketSummary && fetchedMarketSummary.indices && Array.isArray(fetchedMarketSummary.indices)) {
        setMarketIndex(fetchedMarketSummary.indices);
      }
      
      // Set IHSG historical data if available
      if (fetchedMarketSummary && fetchedMarketSummary.ihsgData && Array.isArray(fetchedMarketSummary.ihsgData)) {
        setIhsgData(fetchedMarketSummary.ihsgData);
      }
      
      // If stocksData is invalid, log warning
      if (!stocksData || !Array.isArray(stocksData)) {
        console.warn('Unexpected API response format for stocks');
        setError('Unexpected data format received from server');
      }
      
      setLoading(false);
    } catch (error) {
      console.error('Error loading dashboard data', error);
      setError('Failed to load dashboard data. Using fallback data.');
      // In case of error, we can still display the UI with default values
      setLoading(false);
    }
  };

  useEffect(() => {
    // Load data on component mount
    loadDashboardData();
    
    // Set up automated refresh interval - refresh data every minute
    // Only if the component is mounted and visible
    const refreshInterval = setInterval(() => {
      // Check if document is visible before refreshing to save resources
      if (!document.hidden) {
        loadDashboardData();
      }
    }, 60000);
    
    // Clean up interval on component unmount
    return () => clearInterval(refreshInterval);
  }, []);
  
  // Optional effect to refresh data when authentication state changes
  // This ensures we get authorized data when a user logs in
  useEffect(() => {
    if (isAuthenticated) {
      // Reload data to get personalized/authenticated content
      loadDashboardData();
    }
  }, [isAuthenticated]);

  return (
    <Container fluid className="p-3">
      {/* Error alert if there's an error */}
      {error && (
        <div className="alert alert-warning alert-dismissible fade show mb-4" role="alert">
          <div className="d-flex align-items-center">
            <FaBell className="me-2" />
            <strong>{error}</strong>
          </div>
          <button type="button" className="btn-close" onClick={() => setError(null)} aria-label="Close"></button>
        </div>
      )}
      
      {/* Top bar with live indicator, search and actions */}
      <Row className="mb-4 align-items-center">
        <Col xs={12} md={6}>
          <div className="d-flex align-items-center">
            <div className="live-indicator me-3">
              <FaBroadcastTower className={`live-icon ${loading ? 'pulse' : ''}`} />
              <span className="ms-2">Live Data</span>
            </div>
            <Badge bg="primary" className="me-2">IHSG</Badge>
            <h3 className="mb-0">{marketSummary.ihsg}</h3>
            <h6 className={`mb-0 ms-2 ${getValueColor(marketSummary.change)}`}>
              {marketSummary.change > 0 ? '+' : ''}{marketSummary.change}%
            </h6>
            <div className="last-updated ms-3">
              <small className="text-muted">Updated: {getFormattedUpdateTime()}</small>
            </div>
          </div>
        </Col>
        <Col xs={12} md={6}>
          <div className="d-flex justify-content-md-end mt-3 mt-md-0">
            <InputGroup className="dashboard-search me-2">
              <InputGroup.Text><FaSearch /></InputGroup.Text>
              <Form.Control type="text" placeholder="Search stocks..." />
            </InputGroup>
            <Button variant="outline-primary" className="icon-button me-2">
              <FaBell />
            </Button>
            <Button variant="outline-primary" className="icon-button me-2">
              <FaCog />
            </Button>
            <Button 
              variant="primary" 
              className="icon-button"
              onClick={() => loadDashboardData()} 
              disabled={loading}
            >
              {loading ? <div className="spinner-border spinner-border-sm" role="status"></div> : <FaFilter />}
            </Button>
          </div>
        </Col>
      </Row>

      {/* Welcome Header */}
      <div className="welcome-header mb-4">
        <h4 className="welcome-title">Welcome to ZAHAAM Stock Platform</h4>
        <p className="welcome-subtitle">
          {new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
        </p>
      </div>

      {/* Main Dashboard Content */}
      {/* Market Overview Section - Full Width */}
      <Row className="g-4 mb-4">
        <Col xs={12}>
          <div className="futuristic-card primary-card">
            <div className="card-glow"></div>
            <div className="card-content h-100">
              <MarketOverview
                marketSummary={marketSummary}
                ihsgData={ihsgData}
                loading={loading}
              />
            </div>
          </div>
        </Col>
      </Row>

      {/* Sector Overview and Market Summary - Side by Side */}
      <Row className="g-4">
        {/* Sector Overview Section */}
        <Col md={6}>
          <Card className="shadow-sm h-100">
            <Card.Header className="bg-white">
              <div className="d-flex align-items-center justify-content-between">
                <div className="d-flex align-items-center">
                  <div className="header-icon-circle bg-success text-white me-2">
                    <FaChartBar />
                  </div>
                  <h5 className="mb-0">Sector Overview</h5>
                </div>
                <Badge bg="info" className="badge-interactive">
                  <FaBolt className="me-1" />
                  Live
                </Badge>
              </div>
            </Card.Header>
            <Card.Body>
              <SectorOverview 
                sectors={sectors}
                loading={loading}
              />
            </Card.Body>
          </Card>
        </Col>

        {/* Market Summary */}
        <Col md={6}>
          <Card className="shadow-sm h-100">
            <Card.Header className="bg-white">
              <div className="d-flex align-items-center justify-content-between">
                <div className="d-flex align-items-center">
                  <div className="header-icon-circle bg-primary text-white me-2">
                    <FaTachometerAlt />
                  </div>
                  <h5 className="mb-0">Market Summary</h5>
                </div>
              </div>
            </Card.Header>
            <Card.Body>
              <div className="market-summary">
                <div className="summary-item">
                  <span className="summary-label">Trading Volume</span>
                  <span className="summary-value">{formatNumber(marketSummary.volume)}</span>
                </div>
                <div className="summary-item">
                  <span className="summary-label">Trading Value</span>
                  <span className="summary-value">{formatCurrency(marketSummary.value)}</span>
                </div>
                <div className="summary-item">
                  <span className="summary-label">Market Cap</span>
                  <span className="summary-value">{formatCurrency(marketSummary.marketCap)}</span>
                </div>
                <div className="summary-item">
                  <span className="summary-label">Frequency</span>
                  <span className="summary-value">{formatNumber(marketSummary.frequency)}</span>
                </div>
                <div className="summary-chart">
                  <div className="d-flex justify-content-between">
                    <div className="chart-stat">
                      <span className="text-success">{marketSummary.advancing}</span>
                      <small>Advancing</small>
                    </div>
                    <div className="chart-stat">
                      <span className="text-danger">{marketSummary.declining}</span>
                      <small>Declining</small>
                    </div>
                    <div className="chart-stat">
                      <span className="text-secondary">{marketSummary.unchanged}</span>
                      <small>Unchanged</small>
                    </div>
                  </div>
                </div>
              </div>
            </Card.Body>
          </Card>
        </Col>
      </Row>

      {/* Market Leaders Section - Full Width with 3 cards */}
      <Row className="mt-4">
        <Col xs={12} className="mb-3">
          <div className="d-flex align-items-center justify-content-between">
            <h5 className="mb-0">Market Leaders</h5>
            <Button variant="outline-primary" size="sm">View All</Button>
          </div>
        </Col>
      </Row>
      
      <Row>
        {/* Top Gainers Card */}
        <Col md={4} className="mb-4">
          <Card className="shadow-sm h-100">
            <Card.Header className="bg-white">
              <div className="d-flex align-items-center justify-content-between">
                <div className="d-flex align-items-center">
                  <div className="header-icon-circle bg-success text-white me-2">
                    <FaRegChartBar />
                  </div>
                  <h5 className="mb-0">Top Gainers</h5>
                </div>
                <Badge bg="success" className="badge-interactive">
                  <FaBolt className="me-1" />
                  Rising
                </Badge>
              </div>
            </Card.Header>
            <Card.Body>
              {loading ? (
                <div className="text-center py-4">
                  <div className="spinner-border text-primary" role="status">
                    <span className="visually-hidden">Loading...</span>
                  </div>
                </div>
              ) : (
                <div className="stock-list">
                  {topGainers && topGainers.length > 0 ? (
                    topGainers.map((stock, index) => (
                      <div key={`gainer-${index}`} className="stock-item">
                        <div className="stock-name">
                          <strong>{stock.symbol}</strong>
                          <small>{stock.name}</small>
                        </div>
                        <div className="stock-price">
                          <div>{formatCurrency(stock.price)}</div>
                          <div className="text-success">+{stock.change}%</div>
                        </div>
                      </div>
                    ))
                  ) : (
                    <p className="text-center text-muted">No data available</p>
                  )}
                </div>
              )}
            </Card.Body>
          </Card>
        </Col>

        {/* Top Losers Card */}
        <Col md={4} className="mb-4">
          <Card className="shadow-sm h-100">
            <Card.Header className="bg-white">
              <div className="d-flex align-items-center justify-content-between">
                <div className="d-flex align-items-center">
                  <div className="header-icon-circle bg-danger text-white me-2">
                    <FaRegChartBar />
                  </div>
                  <h5 className="mb-0">Top Losers</h5>
                </div>
                <Badge bg="danger" className="badge-interactive">
                  <FaBolt className="me-1" />
                  Falling
                </Badge>
              </div>
            </Card.Header>
            <Card.Body>
              {loading ? (
                <div className="text-center py-4">
                  <div className="spinner-border text-primary" role="status">
                    <span className="visually-hidden">Loading...</span>
                  </div>
                </div>
              ) : (
                <div className="stock-list">
                  {topLosers && topLosers.length > 0 ? (
                    topLosers.map((stock, index) => (
                      <div key={`loser-${index}`} className="stock-item">
                        <div className="stock-name">
                          <strong>{stock.symbol}</strong>
                          <small>{stock.name}</small>
                        </div>
                        <div className="stock-price">
                          <div>{formatCurrency(stock.price)}</div>
                          <div className="text-danger">{stock.change}%</div>
                        </div>
                      </div>
                    ))
                  ) : (
                    <p className="text-center text-muted">No data available</p>
                  )}
                </div>
              )}
            </Card.Body>
          </Card>
        </Col>

        {/* Most Active Card */}
        <Col md={4} className="mb-4">
          <Card className="shadow-sm h-100">
            <Card.Header className="bg-white">
              <div className="d-flex align-items-center justify-content-between">
                <div className="d-flex align-items-center">
                  <div className="header-icon-circle bg-info text-white me-2">
                    <FaTable />
                  </div>
                  <h5 className="mb-0">Most Active</h5>
                </div>
                <Badge bg="info" className="badge-interactive">
                  <FaBolt className="me-1" />
                  Volume
                </Badge>
              </div>
            </Card.Header>
            <Card.Body>
              {loading ? (
                <div className="text-center py-4">
                  <div className="spinner-border text-primary" role="status">
                    <span className="visually-hidden">Loading...</span>
                  </div>
                </div>
              ) : (
                <div className="stock-list">
                  {mostActive && mostActive.length > 0 ? (
                    mostActive.map((stock, index) => (
                      <div key={`active-${index}`} className="stock-item">
                        <div className="stock-name">
                          <strong>{stock.symbol}</strong>
                          <small>{stock.name}</small>
                        </div>
                        <div className="stock-data">
                          <div>{api.formatCurrency(stock.price)}</div>
                          <div className={`${api.getValueColor(stock.change)}`}>
                            {stock.change > 0 ? '+' : ''}{stock.change}%
                          </div>
                        </div>
                      </div>
                    ))
                  ) : (
                    <p className="text-center text-muted">No data available</p>
                  )}
                </div>
              )}
            </Card.Body>
          </Card>
        </Col>
      </Row>

      {/* Stock List - Full Width */}
      <Row>
        <Col xs={12} className="mb-4">
          <Card className="shadow-sm">
            <Card.Header className="bg-white">
              <div className="d-flex align-items-center justify-content-between">
                <div className="d-flex align-items-center">
                  <div className="header-icon-circle bg-primary text-white me-2">
                    <FaTable />
                  </div>
                  <h5 className="mb-0">Stock List</h5>
                </div>
                <Badge bg="secondary" className="badge-interactive">
                  <FaBolt className="me-1" />
                  Interactive
                </Badge>
              </div>
            </Card.Header>
            <Card.Body>
              <StockTable 
                stocks={allStocks}
                loading={loading}
              />
            </Card.Body>
          </Card>
        </Col>
      </Row>

      {/* Market Intelligence */}
      <Row>
        <Col xs={12} className="mb-4">
          <Card className="shadow-sm">
            <Card.Header className="bg-white">
              <div className="d-flex align-items-center justify-content-between">
                <div className="d-flex align-items-center">
                  <div className="header-icon-circle bg-danger text-white me-2">
                    <FaRegNewspaper />
                  </div>
                  <h5 className="mb-0">Market Intelligence</h5>
                </div>
                <Badge bg="primary">
                  <FaBolt className="me-1" />
                  Updated
                </Badge>
              </div>
            </Card.Header>
            <Card.Body>
              <MarketNews loading={loading} />
            </Card.Body>
          </Card>
        </Col>
      </Row>

      {/* Footer */}
      <Row className="mt-4">
        <Col xs={12}>
          <div className="dashboard-footer">
            <div className="d-flex flex-column flex-md-row justify-content-between align-items-center">
              <div className="mb-3 mb-md-0">
                <p className="text-muted mb-0">© 2023 Zahaam Stock Prediction System. All rights reserved.</p>
              </div>
              <div className="d-flex">
                <a href="#github" className="footer-social-link me-3">
                  <FaGithub />
                </a>
                <a href="#twitter" className="footer-social-link me-3">
                  <FaTwitter />
                </a>
                <a href="#linkedin" className="footer-social-link">
                  <FaLinkedin />
                </a>
              </div>
            </div>
          </div>
        </Col>
      </Row>
    </Container>
  );
};

export default Dashboard;
