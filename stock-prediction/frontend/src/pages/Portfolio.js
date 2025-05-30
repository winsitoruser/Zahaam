import React, { useState, useEffect } from 'react';
import { Container, Row, Col, Card, Table, Button, Form, Modal, Spinner, Alert } from 'react-bootstrap';
import Chart from 'react-apexcharts';
import { formatCurrency, formatNumber, getValueColor } from '../services/api';

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
  
  // Load portfolio from localStorage on component mount
  useEffect(() => {
    const loadPortfolio = async () => {
      try {
        setLoading(true);
        
        // In a real app, this would come from an API or database
        // For now, we'll use localStorage
        const savedPortfolio = localStorage.getItem('stockPortfolio');
        
        if (savedPortfolio) {
          setPortfolio(JSON.parse(savedPortfolio));
        } else {
          // Sample portfolio data
          const samplePortfolio = [
            { id: 1, ticker: 'BBCA.JK', name: 'Bank Central Asia', buyPrice: 9200, currentPrice: 9525, quantity: 100, buyDate: '2025-01-15' },
            { id: 2, ticker: 'TLKM.JK', name: 'Telkom Indonesia', buyPrice: 4000, currentPrice: 4120, quantity: 250, buyDate: '2025-02-10' },
            { id: 3, ticker: 'ASII.JK', name: 'Astra International', buyPrice: 5600, currentPrice: 5475, quantity: 150, buyDate: '2025-03-05' },
            { id: 4, ticker: 'BMRI.JK', name: 'Bank Mandiri', buyPrice: 6100, currentPrice: 6250, quantity: 75, buyDate: '2025-04-20' }
          ];
          
          setPortfolio(samplePortfolio);
          localStorage.setItem('stockPortfolio', JSON.stringify(samplePortfolio));
        }
      } catch (error) {
        console.error('Error loading portfolio:', error);
        setError('Failed to load portfolio data. Please try again.');
      } finally {
        setLoading(false);
      }
    };
    
    loadPortfolio();
  }, []);
  
  // Save portfolio to localStorage whenever it changes
  useEffect(() => {
    if (portfolio.length > 0) {
      localStorage.setItem('stockPortfolio', JSON.stringify(portfolio));
    }
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
  const handleSubmit = (e) => {
    e.preventDefault();
    
    // Validate form
    if (!formData.ticker || !formData.name || !formData.buyPrice || !formData.quantity || !formData.buyDate) {
      setError('Please fill all required fields');
      return;
    }
    
    // Create new portfolio item
    const newItem = {
      id: Date.now(),
      ticker: formData.ticker,
      name: formData.name,
      buyPrice: parseFloat(formData.buyPrice),
      currentPrice: parseFloat(formData.buyPrice), // Initially set to buy price
      quantity: parseInt(formData.quantity),
      buyDate: formData.buyDate
    };
    
    // Add to portfolio
    setPortfolio([...portfolio, newItem]);
    
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
  };
  
  // Handle delete portfolio item
  const handleDelete = (id) => {
    setPortfolio(portfolio.filter(item => item.id !== id));
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
  
  // Prepare chart data
  const portfolioDistributionOptions = {
    chart: {
      type: 'donut',
      height: 350
    },
    labels: portfolio.map(item => item.name),
    legend: {
      position: 'bottom'
    },
    responsive: [{
      breakpoint: 480,
      options: {
        chart: {
          width: 300
        },
        legend: {
          position: 'bottom'
        }
      }
    }],
    colors: ['#3f51b5', '#f44336', '#ff9800', '#4caf50', '#2196f3', '#9c27b0', '#607d8b', '#e91e63', '#00bcd4', '#8bc34a']
  };
  
  const portfolioDistributionSeries = portfolio.map(item => item.currentPrice * item.quantity);
  
  // Prepare performance chart data
  const performanceChartOptions = {
    chart: {
      type: 'bar',
      height: 350,
      toolbar: {
        show: false
      }
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '55%',
        endingShape: 'rounded'
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      show: true,
      width: 2,
      colors: ['transparent']
    },
    xaxis: {
      categories: portfolio.map(item => item.ticker)
    },
    yaxis: {
      title: {
        text: 'Return (%)'
      },
      labels: {
        formatter: function (value) {
          return value.toFixed(2) + '%';
        }
      }
    },
    fill: {
      opacity: 1
    },
    tooltip: {
      y: {
        formatter: function (value) {
          return value.toFixed(2) + '%';
        }
      }
    },
    colors: portfolio.map(item => {
      const returnPercent = ((item.currentPrice - item.buyPrice) / item.buyPrice) * 100;
      return returnPercent >= 0 ? '#4caf50' : '#f44336';
    })
  };
  
  const performanceChartSeries = [
    {
      name: 'Return',
      data: portfolio.map(item => {
        return parseFloat(((item.currentPrice - item.buyPrice) / item.buyPrice * 100).toFixed(2));
      })
    }
  ];
  
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
                <Chart
                  options={portfolioDistributionOptions}
                  series={portfolioDistributionSeries}
                  type="donut"
                  height={350}
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
                <Chart
                  options={performanceChartOptions}
                  series={performanceChartSeries}
                  type="bar"
                  height={350}
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
                  <th>Total Value</th>
                  <th>Profit/Loss</th>
                  <th>Return</th>
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
                      <td className={getValueColor(profit)}>
                        {formatCurrency(profit, 'IDR')}
                      </td>
                      <td className={getValueColor(returnPercent)}>
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
