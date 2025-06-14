import React, { useState, useEffect, useContext, useCallback } from 'react';
import { Container, Row, Col, Card, Table, Button, Form, Badge, Modal, Spinner, Alert } from 'react-bootstrap';
import Plot from 'react-plotly.js';
import { AuthContext } from '../contexts/AuthContext';
import * as api from '../services/apiIntegration';
import { batchApi } from '../services/batchApi';
import { useNavigate } from 'react-router-dom';

const Portfolio = () => {
  const [portfolio, setPortfolio] = useState([]);
  const [showAddModal, setShowAddModal] = useState(false);
  const [loading, setLoading] = useState(true);
  const [formData, setFormData] = useState({
    ticker: '',
    name: '',
    buyPrice: '',
    quantity: '',
    buyDate: ''
  });
  const [error, setError] = useState('');
  const [marketData, setMarketData] = useState([]);
  const [refreshTrigger, setRefreshTrigger] = useState(0);
  const { isAuthenticated, currentUser } = useContext(AuthContext);
  const navigate = useNavigate();
  
  const loadPortfolio = useCallback(async () => {
    try {
      setLoading(true);
      setError('');
      
      // Get portfolio data from API with caching support
      // This uses the enhanced API integration service with token refresh
      const result = await api.fetchPortfolio({
        useCache: true,
        cacheTTL: 300, // Cache for 5 minutes
        priorityFresh: true // Prioritize fresh data
      });
      
      if (result && result.portfolio) {
        // Transform API data to match our component's expected format
        const transformedPortfolio = result.portfolio.map((item, index) => ({
          id: index + 1,
          ticker: item.symbol,
          name: item.name,
          buyPrice: item.avgPrice,
          currentPrice: item.currentPrice,
          quantity: item.shares,
          buyDate: item.buyDate || new Date().toISOString().split('T')[0], // Use API date or today as fallback
          value: item.value,
          gain: item.gain,
          gainPercent: item.gainPercent
        }));
        
        setPortfolio(transformedPortfolio);
        
        // If portfolio has stocks, fetch additional market data using batch API
        if (transformedPortfolio.length > 0) {
          fetchMarketData(transformedPortfolio.map(item => item.ticker));
        }
      } else {
        // Handle empty portfolio case
        setPortfolio([]);
      }
    } catch (error) {
      console.error('Error loading portfolio:', error);
      setError('Failed to load portfolio data. Please try again.');
      // Defensive programming: set empty array on error
      setPortfolio([]);
    } finally {
      setLoading(false);
    }
  }, []);
  
  // Fetch additional market data for portfolio stocks using batch API
  const fetchMarketData = async (symbols) => {
    if (!Array.isArray(symbols) || symbols.length === 0) return;
    
    try {
      // Use batch API to efficiently fetch data for multiple stocks in one request
      const batchResponse = await batchApi.batchFetchMultipleStocks(symbols);
      
      if (batchResponse && Object.keys(batchResponse).length > 0) {
        const marketDataArray = [];
        
        for (const symbol of symbols) {
          if (batchResponse[symbol] && batchResponse[symbol].data) {
            marketDataArray.push({
              symbol,
              ...batchResponse[symbol].data
            });
          }
        }
        
        setMarketData(marketDataArray);
      }
    } catch (error) {
      console.error('Error fetching market data:', error);
    }
  };
  
  // Check authentication and load portfolio on component mount
  useEffect(() => {
    if (!isAuthenticated) {
      navigate('/login', { state: { from: '/portfolio' } });
      return;
    }
    loadPortfolio();
    
    // Set up a refresh interval for market data (every 1 minute if tab is visible)
    const refreshInterval = setInterval(() => {
      if (!document.hidden && isAuthenticated) {
        setRefreshTrigger(prev => prev + 1);
      }
    }, 60000); // 1 minute refresh
    
    return () => clearInterval(refreshInterval); // Cleanup on unmount
  }, [isAuthenticated, navigate, loadPortfolio]);
  
  // Refresh data when refreshTrigger changes
  useEffect(() => {
    if (isAuthenticated && portfolio.length > 0) {
      // Only update market data, not full portfolio
      fetchMarketData(portfolio.map(item => item.ticker));
    }
  }, [refreshTrigger, isAuthenticated, portfolio]);
  
  // Update UI when portfolio changes
  useEffect(() => {
    // This would be a good place to sync with backend if needed
    // But for now we just update the UI
  }, [portfolio]);
  
  // Handle form input changes
  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value
    });
  };
  
  // Handle form submission
  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // Validate form
    if (!formData.ticker || !formData.name || !formData.buyPrice || !formData.quantity || !formData.buyDate) {
      setError('Please fill all required fields');
      return;
    }
    
    try {
      setLoading(true);
      
      // Create transaction data for API
      const transaction = {
        symbol: formData.ticker,
        name: formData.name,
        price: parseFloat(formData.buyPrice),
        shares: parseInt(formData.quantity),
        date: formData.buyDate,
        type: 'buy',
        userId: currentUser?.id // Include user ID if available
      };
      
      // Call API to add transaction to portfolio with authentication
      // The enhanced API service will automatically handle authentication and token refresh
      const result = await api.addPortfolioTransaction(transaction);
      
      if (result && result.success) {
        // Invalidate portfolio cache to ensure fresh data
        api.clearCacheByPattern('portfolio');
        
        // Refresh portfolio data from API to get updated portfolio
        await loadPortfolio();
        
        // Reset form and close modal
        setFormData({
          ticker: '',
          name: '',
          buyPrice: '',
          quantity: '',
          buyDate: ''
        });
        setShowAddModal(false);
        setError('');
      } else {
        setError(result?.message || 'Failed to add transaction. Please try again.');
      }
    } catch (error) {
      console.error('Error adding portfolio item:', error);
      setError('Failed to add stock to portfolio. Please try again.');
    } finally {
      setLoading(false);
    }
  };
  
  // Handle delete portfolio item
  const handleDelete = async (id) => {
    try {
      const itemToDelete = portfolio.find(item => item.id === id);
      if (!itemToDelete) return;
      
      // Create transaction for deletion (sell all shares)
      const transaction = {
        symbol: itemToDelete.ticker,
        name: itemToDelete.name,
        price: itemToDelete.currentPrice,
        shares: itemToDelete.quantity,
        date: new Date().toISOString().split('T')[0],
        type: 'sell',
        userId: currentUser?.id // Include user ID if available
      };
      
      // Call API to add sell transaction with authentication
      // The enhanced API service will automatically handle authentication and token refresh
      const result = await api.addPortfolioTransaction(transaction);
      
      if (result && result.success) {
        // Invalidate portfolio cache
        api.clearCacheByPattern('portfolio');
        
        // Refresh portfolio data
        await loadPortfolio();
      } else {
        setError('Failed to delete portfolio item. Please try again.');
      }
    } catch (error) {
      console.error('Error deleting portfolio item:', error);
      setError('Failed to delete stock from portfolio. Please try again.');
    }
  };
  
  // Calculate portfolio summary
  const calculateSummary = () => {
    if (portfolio.length === 0) return { totalInvestment: 0, currentValue: 0, profit: 0, profitPercent: 0 };
    
    const totalInvestment = portfolio.reduce((total, item) => total + (item.buyPrice * item.quantity), 0);
    const currentValue = portfolio.reduce((total, item) => total + (item.currentPrice * item.quantity), 0);
    const profit = currentValue - totalInvestment;
    const profitPercent = (profit / totalInvestment) * 100;
    
    return {
      totalInvestment,
      currentValue,
      profit,
      profitPercent
    };
  };
  
  const summary = calculateSummary();
  
  // Process data for charts
  const stockLabels = portfolio.map(item => item.name);
  const stockValues = portfolio.map(item => item.currentPrice * item.quantity);
  const stockReturns = portfolio.map(item => {
    return parseFloat(((item.currentPrice - item.buyPrice) / item.buyPrice * 100).toFixed(2));
  });
  const stockColors = portfolio.map(item => {
    const returnPercent = ((item.currentPrice - item.buyPrice) / item.buyPrice) * 100;
    return returnPercent >= 0 ? '#4caf50' : '#f44336';
  });
  // Stock colors with opacity for hover effect
  const stockColorsWithOpacity = portfolio.map(item => {
    const returnPercent = ((item.currentPrice - item.buyPrice) / item.buyPrice) * 100;
    return returnPercent >= 0 ? 'rgba(76, 175, 80, 0.7)' : 'rgba(244, 67, 54, 0.7)';
  });
  
  if (loading) {
    return (
      <div className="text-center py-5">
        <Spinner animation="border" variant="primary" />
        <p className="mt-3">Loading portfolio data...</p>
      </div>
    );
  }
  
  return (
    <Container>
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h3>My Portfolio</h3>
        <Button variant="primary" onClick={() => setShowAddModal(true)}>
          <i className="bx bx-plus me-1"></i> Add Stock
        </Button>
      </div>
      
      {error && (
        <Alert variant="danger" className="mb-4" onClose={() => setError('')} dismissible>
          {error}
        </Alert>
      )}
      
      {/* Portfolio Summary */}
      <Row className="mb-4">
        <Col md={3} className="mb-3">
          <Card className="h-100 dashboard-card">
            <Card.Body className="d-flex flex-column align-items-center justify-content-center p-4">
              <div className="dashboard-label">Total Investment</div>
              <div className="dashboard-value">{formatCurrency(summary.totalInvestment, 'IDR')}</div>
            </Card.Body>
          </Card>
        </Col>
        <Col md={3} className="mb-3">
          <Card className="h-100 dashboard-card">
            <Card.Body className="d-flex flex-column align-items-center justify-content-center p-4">
              <div className="dashboard-label">Current Value</div>
              <div className="dashboard-value">{formatCurrency(summary.currentValue, 'IDR')}</div>
            </Card.Body>
          </Card>
        </Col>
        <Col md={3} className="mb-3">
          <Card className="h-100 dashboard-card">
            <Card.Body className="d-flex flex-column align-items-center justify-content-center p-4">
              <div className="dashboard-label">Profit/Loss</div>
              <div className={`dashboard-value ${summary.profit >= 0 ? 'text-success' : 'text-danger'}`}>
                {formatCurrency(summary.profit, 'IDR')}
              </div>
            </Card.Body>
          </Card>
        </Col>
        <Col md={3} className="mb-3">
          <Card className="h-100 dashboard-card">
            <Card.Body className="d-flex flex-column align-items-center justify-content-center p-4">
              <div className="dashboard-label">Return</div>
              <div className={`dashboard-value ${summary.profitPercent >= 0 ? 'text-success' : 'text-danger'}`}>
                {summary.profitPercent.toFixed(2)}%
              </div>
            </Card.Body>
          </Card>
        </Col>
      </Row>
      
      <Row className="mb-4">
        {/* Portfolio Distribution */}
        <Col lg={6} className="mb-4">
          <Card className="h-100 dashboard-card">
            <Card.Header className="bg-white">
              <h5 className="mb-0">Portfolio Distribution</h5>
            </Card.Header>
            <Card.Body>
              {portfolio.length > 0 ? (
                <Plot
                  data={[
                    {
                      type: 'pie',
                      labels: stockLabels,
                      values: stockValues,
                      textinfo: 'percent',
                      hoverinfo: 'label+percent+value',
                      marker: {
                        colors: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#5a5c69', '#858796']
                      },
                      hovertemplate: '%{label}: %{percent} (%{value:,.0f} IDR)<extra></extra>'
                    }
                  ]}
                  layout={{
                    autosize: true,
                    margin: { l: 10, r: 10, t: 10, b: 10 },
                    height: 350,
                    showlegend: true,
                    legend: { orientation: 'h', y: -0.1 },
                    paper_bgcolor: 'rgba(0,0,0,0)',
                    plot_bgcolor: 'rgba(0,0,0,0)'
                  }}
                  config={{
                    displayModeBar: false,
                    responsive: true
                  }}
                  style={{ width: '100%' }}
                />
              ) : (
                <div className="text-center py-5 text-muted">
                  <i className="bx bx-pie-chart-alt-2 fs-1 mb-3"></i>
                  <p>No stocks in your portfolio yet.</p>
                </div>
              )}
            </Card.Body>
          </Card>
        </Col>
        
        {/* Performance by Stock */}
        <Col lg={6} className="mb-4">
          <Card className="h-100 dashboard-card">
            <Card.Header className="bg-white">
              <h5 className="mb-0">Performance by Stock</h5>
            </Card.Header>
            <Card.Body>
              {portfolio.length > 0 ? (
                <Plot
                  data={[
                    {
                      type: 'bar',
                      x: portfolio.map(item => item.ticker),
                      y: stockReturns,
                      marker: {
                        color: stockColors
                      },
                      hovertemplate: '%{x}: %{y:.2f}%<extra></extra>'
                    }
                  ]}
                  layout={{
                    autosize: true,
                    height: 350,
                    margin: { l: 50, r: 20, t: 10, b: 50 },
                    xaxis: {
                      title: 'Stock',
                      titlefont: { size: 12 }
                    },
                    yaxis: {
                      title: 'Return (%)',
                      titlefont: { size: 12 },
                      ticksuffix: '%',
                      gridcolor: '#e7e7e7'
                    },
                    paper_bgcolor: 'rgba(0,0,0,0)',
                    plot_bgcolor: 'rgba(0,0,0,0)'
                  }}
                  config={{
                    displayModeBar: false,
                    responsive: true
                  }}
                  style={{ width: '100%' }}
                />
              ) : (
                <div className="text-center py-5 text-muted">
                  <i className="bx bx-bar-chart-alt-2 fs-1 mb-3"></i>
                  <p>No performance data available.</p>
                </div>
              )}
            </Card.Body>
          </Card>
        </Col>
      </Row>
      
      {/* Portfolio Holdings */}
      <Card className="dashboard-card mb-4">
        <Card.Header className="bg-white">
          <h5 className="mb-0">Portfolio Holdings</h5>
        </Card.Header>
        <Card.Body className="p-0">
          {portfolio.length > 0 ? (
            <Table responsive className="market-overview-table mb-0">
              <thead>
                <tr>
                  <th>Stock</th>
                  <th>Quantity</th>
                  <th>Buy Price</th>
                  <th>Current Price</th>
                  <th>Current Value</th>
                  <th>Profit/Loss</th>
                  <th>Return %</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                {portfolio.map((item) => {
                  const profit = (item.currentPrice - item.buyPrice) * item.quantity;
                  const returnPercent = ((item.currentPrice - item.buyPrice) / item.buyPrice) * 100;
                  
                  return (
                    <tr key={item.id}>
                      <td>
                        <div className="fw-bold">{item.ticker}</div>
                        <div className="small text-muted">{item.name}</div>
                      </td>
                      <td>{formatNumber(item.quantity)}</td>
                      <td>{formatCurrency(item.buyPrice, 'IDR')}</td>
                      <td>{formatCurrency(item.currentPrice, 'IDR')}</td>
                      <td>{formatCurrency(item.currentPrice * item.quantity, 'IDR')}</td>
                      <td className={api.getValueColor(profit)}>
                        {api.formatCurrency(profit, 'IDR')}
                      </td>
                      <td className={api.getValueColor(returnPercent)}>
                        {returnPercent.toFixed(2)}%
                      </td>
                      <td>
                        <Button variant="outline-danger" size="sm" onClick={() => handleDelete(item.id)}>
                          <i className="bx bx-trash"></i>
                        </Button>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </Table>
          ) : (
            <div className="text-center py-5 text-muted">
              <i className="bx bx-briefcase fs-1 mb-3"></i>
              <p>Your portfolio is empty. Add stocks to track your investments.</p>
              <Button variant="primary" onClick={() => setShowAddModal(true)}>
                <i className="bx bx-plus me-1"></i> Add Stock
              </Button>
            </div>
          )}
        </Card.Body>
      </Card>
      
      {/* Add Stock Modal */}
      <Modal show={showAddModal} onHide={() => setShowAddModal(false)}>
        <Modal.Header closeButton>
          <Modal.Title>Add Stock to Portfolio</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Form onSubmit={handleSubmit}>
            <Form.Group className="mb-3">
              <Form.Label>Stock Symbol</Form.Label>
              <Form.Control 
                type="text" 
                name="ticker" 
                value={formData.ticker} 
                onChange={handleInputChange} 
                placeholder="e.g., BBCA.JK"
                required
              />
            </Form.Group>
            
            <Form.Group className="mb-3">
              <Form.Label>Company Name</Form.Label>
              <Form.Control 
                type="text" 
                name="name" 
                value={formData.name} 
                onChange={handleInputChange} 
                placeholder="e.g., Bank Central Asia"
                required
              />
            </Form.Group>
            
            <Form.Group className="mb-3">
              <Form.Label>Buy Price (IDR)</Form.Label>
              <Form.Control 
                type="number" 
                name="buyPrice" 
                value={formData.buyPrice} 
                onChange={handleInputChange} 
                placeholder="e.g., 9500"
                required
              />
            </Form.Group>
            
            <Form.Group className="mb-3">
              <Form.Label>Quantity</Form.Label>
              <Form.Control 
                type="number" 
                name="quantity" 
                value={formData.quantity} 
                onChange={handleInputChange} 
                placeholder="e.g., 100"
                required
              />
            </Form.Group>
            
            <Form.Group className="mb-3">
              <Form.Label>Buy Date</Form.Label>
              <Form.Control 
                type="date" 
                name="buyDate" 
                value={formData.buyDate} 
                onChange={handleInputChange} 
                required
              />
            </Form.Group>
            
            {error && <Alert variant="danger">{error}</Alert>}
            
            <div className="d-flex justify-content-end gap-2 mt-4">
              <Button variant="secondary" onClick={() => setShowAddModal(false)}>
                Cancel
              </Button>
              <Button variant="primary" type="submit">
                Add Stock
              </Button>
            </div>
          </Form>
        </Modal.Body>
      </Modal>
    </Container>
  );
};

export default Portfolio;
