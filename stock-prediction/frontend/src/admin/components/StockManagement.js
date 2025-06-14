import React, { useState, useEffect, useRef } from 'react';
import { Card, Table, Button, Alert, Spinner, Form, Row, Col, Badge, Modal, Tab, Tabs } from 'react-bootstrap';
import { FaSearch, FaSync, FaPlus, FaEdit, FaChartLine, FaDownload, FaHistory } from 'react-icons/fa';
import axios from 'axios';
import Plot from 'react-plotly.js';

const StockManagement = ({ lastRefresh }) => {
  // Generate stock price history data for the chart
  const generatePriceHistory = (basePrice, days = 30) => {
    const data = [];
    const today = new Date();
    let price = basePrice;
    
    for (let i = days; i >= 0; i--) {
      const date = new Date();
      date.setDate(today.getDate() - i);
      
      // Add some random variation
      const change = price * (0.02 * Math.random() - 0.01);
      price = price + change;
      
      // Create OHLC data
      const open = price;
      const high = price * (1 + Math.random() * 0.01);
      const low = price * (1 - Math.random() * 0.01);
      const close = price * (1 + (Math.random() * 0.02 - 0.01));
      
      data.push({
        date: date.toISOString().split('T')[0],
        open,
        high,
        low,
        close,
        volume: Math.floor(Math.random() * 10000000) + 1000000
      });
      
      price = close; // For next iteration
    }
    
    return data;
  };
  const [stocks, setStocks] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [searchTerm, setSearchTerm] = useState('');
  const [sortBy, setSortBy] = useState('symbol');
  const [sortDirection, setSortDirection] = useState('asc');
  const [actionLoading, setActionLoading] = useState(false);
  const [actionStatus, setActionStatus] = useState(null);
  
  // Modal state
  const [showModal, setShowModal] = useState(false);
  const [modalMode, setModalMode] = useState('add'); // 'add', 'edit', or 'detail'
  const [currentStock, setCurrentStock] = useState(null);
  
  // Form data
  const [formData, setFormData] = useState({
    symbol: '',
    company_name: '',
    sector: '',
    is_active: true,
    description: '',
    outstanding_shares: 0
  });

  // Fetch stocks data
  const fetchStocks = async () => {
    setLoading(true);
    setError(null);
    
    try {
      // In a real implementation, this would connect to your stocks API endpoint
      // For demo purposes, we'll simulate data
      
      // This would be replaced with actual API call:
      // const response = await axios.get('/api/admin/stocks');
      // setStocks(response.data);
      
      // Simulated stock data
      setTimeout(() => {
        // Generate price history for each stock
        const mockStocks = [
          { 
            id: 1, 
            symbol: 'ASII', 
            company_name: 'Astra International Tbk.', 
            sector: 'Automotive',
            last_price: 4520,
            change_percent: 1.23,
            is_active: true,
            outstanding_shares: 40483553140,
            last_updated: '2025-06-12T04:30:00'
          },
          { 
            id: 2, 
            symbol: 'BBCA', 
            company_name: 'Bank Central Asia Tbk.', 
            sector: 'Banking',
            last_price: 9100,
            change_percent: 0.55,
            is_active: true,
            outstanding_shares: 24655010000,
            last_updated: '2025-06-12T04:30:00'
          },
          { 
            id: 3, 
            symbol: 'TLKM', 
            company_name: 'Telekomunikasi Indonesia Tbk.', 
            sector: 'Telecommunication',
            last_price: 3890,
            change_percent: -0.26,
            is_active: true,
            outstanding_shares: 99062216600,
            last_updated: '2025-06-12T04:30:00'
          },
          { 
            id: 4, 
            symbol: 'UNVR', 
            company_name: 'Unilever Indonesia Tbk.', 
            sector: 'Consumer Goods',
            last_price: 4180,
            change_percent: 0.48,
            is_active: true,
            outstanding_shares: 38150000000,
            last_updated: '2025-06-12T04:30:00'
          },
          { 
            id: 5, 
            symbol: 'GOTO', 
            company_name: 'GoTo Gojek Tokopedia', 
            sector: 'Technology',
            last_price: 71,
            change_percent: -2.74,
            is_active: true,
            outstanding_shares: 1184363173674,
            last_updated: '2025-06-12T04:30:00',
            price_history: generatePriceHistory(4520)
          }
        ];
        
        // Add price history to each stock
        mockStocks[1].price_history = generatePriceHistory(9100);
        mockStocks[2].price_history = generatePriceHistory(3890);
        mockStocks[3].price_history = generatePriceHistory(4180);
        mockStocks[4].price_history = generatePriceHistory(71);
        
        setStocks(mockStocks);
        setLoading(false);
      }, 800);
      
    } catch (err) {
      console.error('Error fetching stocks:', err);
      setError('Gagal mengambil data saham');
      setLoading(false);
    }
  };

  // Refresh data when component mounts or lastRefresh changes
  useEffect(() => {
    fetchStocks();
  }, [lastRefresh]);

  // Sort data
  const sortData = (data, key, direction) => {
    return [...data].sort((a, b) => {
      if (typeof a[key] === 'number') {
        return direction === 'asc' ? a[key] - b[key] : b[key] - a[key];
      }
      
      const valA = String(a[key]).toLowerCase();
      const valB = String(b[key]).toLowerCase();
      
      if (valA < valB) return direction === 'asc' ? -1 : 1;
      if (valA > valB) return direction === 'asc' ? 1 : -1;
      return 0;
    });
  };

  // Handle sort
  const handleSort = (field) => {
    if (sortBy === field) {
      setSortDirection(sortDirection === 'asc' ? 'desc' : 'asc');
    } else {
      setSortBy(field);
      setSortDirection('asc');
    }
  };

  // Filter and sort stocks
  const filteredStocks = stocks.filter(stock => 
    stock.symbol.toLowerCase().includes(searchTerm.toLowerCase()) ||
    stock.company_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
    stock.sector.toLowerCase().includes(searchTerm.toLowerCase())
  );
  
  const sortedStocks = sortData(filteredStocks, sortBy, sortDirection);

  // Reset form
  const resetForm = () => {
    setFormData({
      symbol: '',
      company_name: '',
      sector: '',
      is_active: true,
      description: '',
      outstanding_shares: 0
    });
  };

  // Open modal for adding stock
  const handleAddStock = () => {
    resetForm();
    setModalMode('add');
    setShowModal(true);
  };

  // Open modal for editing stock
  const handleEditStock = (stock) => {
    setCurrentStock(stock);
    setFormData({
      symbol: stock.symbol,
      company_name: stock.company_name,
      sector: stock.sector,
      is_active: stock.is_active,
      description: stock.description || '',
      outstanding_shares: stock.outstanding_shares
    });
    setModalMode('edit');
    setShowModal(true);
  };

  // Open modal for viewing stock details
  const handleViewStock = (stock) => {
    setCurrentStock(stock);
    setModalMode('detail');
    setShowModal(true);
  };

  // Handle form submission
  const handleSubmit = async (e) => {
    e.preventDefault();
    setActionLoading(true);
    
    try {
      if (modalMode === 'add') {
        // This would be replaced with actual API call:
        // await axios.post('/api/admin/stocks', formData);
        
        // Simulate successful API call
        setTimeout(() => {
          const newStock = {
            ...formData,
            id: Math.max(...stocks.map(s => s.id)) + 1,
            last_price: 0,
            change_percent: 0,
            last_updated: new Date().toISOString()
          };
          
          setStocks([...stocks, newStock]);
          setActionStatus({
            type: 'success',
            message: 'Stock added successfully'
          });
          setShowModal(false);
          setActionLoading(false);
        }, 1000);
      } else {
        // This would be replaced with actual API call:
        // await axios.put(`/api/admin/stocks/${currentStock.id}`, formData);
        
        // Simulate successful API call
        setTimeout(() => {
          const updatedStocks = stocks.map(stock => 
            stock.id === currentStock.id ? { 
              ...stock, 
              ...formData,
              last_updated: new Date().toISOString()
            } : stock
          );
          
          setStocks(updatedStocks);
          setActionStatus({
            type: 'success',
            message: 'Stock updated successfully'
          });
          setShowModal(false);
          setActionLoading(false);
        }, 1000);
      }
    } catch (err) {
      console.error('Error saving stock:', err);
      setActionStatus({
        type: 'danger',
        message: 'Failed to save stock data'
      });
      setActionLoading(false);
    }
  };

  // Handle updating data
  const handleUpdateStockData = async () => {
    setActionLoading(true);
    setActionStatus(null);
    
    try {
      // This would be replaced with actual API call:
      // await axios.post('/api/admin/stocks/update-data');
      
      // Simulate successful API call
      setTimeout(() => {
        setActionStatus({
          type: 'success',
          message: 'Stock data update has been scheduled'
        });
        setActionLoading(false);
      }, 1500);
    } catch (err) {
      console.error('Error updating stock data:', err);
      setActionStatus({
        type: 'danger',
        message: 'Failed to update stock data'
      });
      setActionLoading(false);
    }
  };

  // Format currency
  const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }).format(value);
  };

  // Format large numbers
  const formatNumber = (value) => {
    return new Intl.NumberFormat('id-ID').format(value);
  };

  return (
    <div className="stock-management">
      <h3 className="mb-4">Stock Management</h3>
      
      <Card className="mb-4">
        <Card.Header>
          <h5 className="mb-0">Stock Data Operations</h5>
        </Card.Header>
        <Card.Body>
          <Row>
            <Col md={8}>
              <p>
                Kelola data saham dan lakukan operasi pembaruan data. Anda dapat memperbarui semua 
                data saham sekaligus atau menambahkan saham baru secara manual.
              </p>
              {actionStatus && (
                <Alert 
                  variant={actionStatus.type} 
                  className="mb-3" 
                  onClose={() => setActionStatus(null)} 
                  dismissible
                >
                  {actionStatus.message}
                </Alert>
              )}
            </Col>
            <Col md={4}>
              <div className="action-buttons">
                <Button 
                  variant="primary" 
                  onClick={handleUpdateStockData} 
                  disabled={actionLoading}
                  className="mb-2 w-100"
                >
                  {actionLoading ? (
                    <>
                      <Spinner as="span" animation="border" size="sm" className="me-2" />
                      Processing...
                    </>
                  ) : (
                    <>
                      <FaDownload className="me-2" /> Update Stock Data
                    </>
                  )}
                </Button>
                
                <Button 
                  variant="success" 
                  onClick={handleAddStock}
                  className="w-100"
                >
                  <FaPlus className="me-2" /> Add New Stock
                </Button>
              </div>
            </Col>
          </Row>
        </Card.Body>
      </Card>
      
      <Card className="mb-4">
        <Card.Header className="d-flex justify-content-between align-items-center">
          <div className="d-flex align-items-center">
            <h5 className="mb-0">Stocks</h5>
            <Badge bg="primary" className="ms-2">{stocks.length}</Badge>
          </div>
          <div className="d-flex">
            <Form.Group className="mb-0 me-2">
              <Form.Control
                type="text"
                placeholder="Search stocks..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                size="sm"
              />
            </Form.Group>
            <Button 
              variant="light" 
              size="sm" 
              onClick={fetchStocks}
              disabled={loading}
            >
              <FaSync />
            </Button>
          </div>
        </Card.Header>
        <Card.Body>
          {error && (
            <Alert variant="danger" onClose={() => setError(null)} dismissible>
              {error}
            </Alert>
          )}
          
          {loading ? (
            <div className="text-center py-4">
              <Spinner animation="border" variant="primary" />
              <p className="mt-3">Loading stocks data...</p>
            </div>
          ) : (
            <div className="table-wrapper">
              <Table className="admin-table" hover responsive>
                <thead>
                  <tr>
                    <th onClick={() => handleSort('symbol')} style={{ cursor: 'pointer' }}>
                      Symbol {sortBy === 'symbol' && (sortDirection === 'asc' ? '▲' : '▼')}
                    </th>
                    <th onClick={() => handleSort('company_name')} style={{ cursor: 'pointer' }}>
                      Company Name {sortBy === 'company_name' && (sortDirection === 'asc' ? '▲' : '▼')}
                    </th>
                    <th onClick={() => handleSort('sector')} style={{ cursor: 'pointer' }}>
                      Sector {sortBy === 'sector' && (sortDirection === 'asc' ? '▲' : '▼')}
                    </th>
                    <th onClick={() => handleSort('last_price')} style={{ cursor: 'pointer' }}>
                      Last Price {sortBy === 'last_price' && (sortDirection === 'asc' ? '▲' : '▼')}
                    </th>
                    <th onClick={() => handleSort('change_percent')} style={{ cursor: 'pointer' }}>
                      Change {sortBy === 'change_percent' && (sortDirection === 'asc' ? '▲' : '▼')}
                    </th>
                    <th onClick={() => handleSort('is_active')} style={{ cursor: 'pointer' }}>
                      Status {sortBy === 'is_active' && (sortDirection === 'asc' ? '▲' : '▼')}
                    </th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {sortedStocks.length > 0 ? (
                    sortedStocks.map(stock => (
                      <tr key={stock.id}>
                        <td><strong>{stock.symbol}</strong></td>
                        <td>{stock.company_name}</td>
                        <td>{stock.sector}</td>
                        <td>{formatCurrency(stock.last_price)}</td>
                        <td>
                          <span className={stock.change_percent > 0 ? 'text-success' : 
                                         stock.change_percent < 0 ? 'text-danger' : ''}>
                            {stock.change_percent > 0 ? '+' : ''}{stock.change_percent}%
                          </span>
                        </td>
                        <td>
                          {stock.is_active ? (
                            <Badge bg="success">Active</Badge>
                          ) : (
                            <Badge bg="secondary">Inactive</Badge>
                          )}
                        </td>
                        <td>
                          <Button 
                            variant="outline-info" 
                            size="sm"
                            className="me-1"
                            onClick={() => handleViewStock(stock)}
                          >
                            <FaChartLine />
                          </Button>
                          <Button 
                            variant="outline-primary" 
                            size="sm"
                            onClick={() => handleEditStock(stock)}
                          >
                            <FaEdit />
                          </Button>
                        </td>
                      </tr>
                    ))
                  ) : (
                    <tr>
                      <td colSpan="7" className="text-center">No stocks found</td>
                    </tr>
                  )}
                </tbody>
              </Table>
            </div>
          )}
        </Card.Body>
      </Card>
      
      {/* Stock Modal */}
      <Modal show={showModal} onHide={() => setShowModal(false)} size="lg">
        <Modal.Header closeButton>
          <Modal.Title>
            {modalMode === 'add' ? 'Add New Stock' : 
             modalMode === 'edit' ? 'Edit Stock' : 
             `Stock Details: ${currentStock?.symbol}`}
          </Modal.Title>
        </Modal.Header>
        
        {modalMode === 'detail' ? (
          <Modal.Body>
            {currentStock && (
              <Tabs defaultActiveKey="overview" className="mb-3">
                <Tab eventKey="overview" title="Overview">
                  <Row className="mb-4">
                    <Col md={6}>
                      <h5>{currentStock.company_name}</h5>
                      <p className="lead">
                        <Badge bg="primary" className="me-2">{currentStock.symbol}</Badge>
                        <span className="text-muted">{currentStock.sector}</span>
                      </p>
                      
                      <Table bordered size="sm">
                        <tbody>
                          <tr>
                            <th>Last Price</th>
                            <td>{formatCurrency(currentStock.last_price)}</td>
                          </tr>
                          <tr>
                            <th>Change</th>
                            <td className={currentStock.change_percent > 0 ? 'text-success' : 
                                         currentStock.change_percent < 0 ? 'text-danger' : ''}>
                              {currentStock.change_percent > 0 ? '+' : ''}{currentStock.change_percent}%
                            </td>
                          </tr>
                          <tr>
                            <th>Outstanding Shares</th>
                            <td>{formatNumber(currentStock.outstanding_shares)}</td>
                          </tr>
                          <tr>
                            <th>Market Cap</th>
                            <td>{formatCurrency(currentStock.last_price * currentStock.outstanding_shares)}</td>
                          </tr>
                          <tr>
                            <th>Status</th>
                            <td>
                              {currentStock.is_active ? (
                                <Badge bg="success">Active</Badge>
                              ) : (
                                <Badge bg="secondary">Inactive</Badge>
                              )}
                            </td>
                          </tr>
                          <tr>
                            <th>Last Updated</th>
                            <td>{new Date(currentStock.last_updated).toLocaleString()}</td>
                          </tr>
                        </tbody>
                      </Table>
                    </Col>
                    <Col md={6}>
                      <Card>
                        <Card.Body className="p-2">
                          <div className="d-flex justify-content-between align-items-center mb-2">
                            <h6 className="mb-0">Price History</h6>
                            <Badge bg="info" pill className="pulse-badge">Interactive</Badge>
                          </div>
                          {currentStock && currentStock.price_history && (
                            <Plot
                              data={[
                                {
                                  type: 'candlestick',
                                  x: currentStock.price_history.map(item => item.date),
                                  open: currentStock.price_history.map(item => item.open),
                                  high: currentStock.price_history.map(item => item.high),
                                  low: currentStock.price_history.map(item => item.low),
                                  close: currentStock.price_history.map(item => item.close),
                                  increasing: {line: {color: '#26a69a'}},
                                  decreasing: {line: {color: '#ef5350'}},
                                  name: 'OHLC',
                                },
                                {
                                  type: 'bar',
                                  x: currentStock.price_history.map(item => item.date),
                                  y: currentStock.price_history.map(item => item.volume),
                                  marker: {color: 'rgba(100,100,200,0.4)'},
                                  name: 'Volume',
                                  yaxis: 'y2',
                                }
                              ]}
                              layout={{
                                autosize: true,
                                height: 450,
                                margin: {l: 50, r: 20, t: 35, b: 40},
                                title: {
                                  text: currentStock.symbol + ' Price Chart',
                                  font: { size: 14 }
                                },
                                dragmode: 'zoom',
                                showlegend: false,
                                xaxis: {
                                  rangeslider: { visible: false },
                                  type: 'category'
                                },
                                yaxis: {
                                  title: 'Price',
                                  autorange: true,
                                  fixedrange: false,
                                },
                                yaxis2: {
                                  title: 'Volume',
                                  overlaying: 'y',
                                  side: 'right',
                                  showgrid: false
                                },
                                paper_bgcolor: 'rgba(0,0,0,0)',
                                plot_bgcolor: 'rgba(0,0,0,0)',
                                font: { 
                                  family: '"Poppins", sans-serif'
                                },
                                modebar: {
                                  orientation: 'v',
                                  bgcolor: 'rgba(255,255,255,0.9)',
                                  color: '#2b3947',
                                  activecolor: '#007bff'
                                },
                                hovermode: 'closest'
                              }}
                              config={{
                                responsive: true,
                                displaylogo: false,
                                modeBarButtonsToRemove: [
                                  'select2d',
                                  'lasso2d',
                                  'autoScale2d',
                                  'toggleSpikelines',
                                ]
                              }}
                              style={{width: '100%', borderRadius: '8px'}}
                              className="stock-chart"
                            />
                          )}
                        </Card.Body>
                      </Card>
                    </Col>
                  </Row>
                </Tab>
                
                <Tab eventKey="actions" title="Actions">
                  <div className="py-3">
                    <h6>Available Actions</h6>
                    <div className="action-buttons mt-3">
                      <Button variant="primary" className="me-2">
                        <FaHistory className="me-2" /> Update Price Data
                      </Button>
                      <Button variant="success" className="me-2">
                        <FaChartLine className="me-2" /> Generate Analysis
                      </Button>
                      <Button variant="warning">
                        <FaEdit className="me-2" /> Edit Stock
                      </Button>
                    </div>
                    
                    <Alert variant="info" className="mt-4">
                      <h6>Indicators and Signals</h6>
                      <p className="mb-2">Latest technical analysis indicators would appear here.</p>
                      <ul className="mb-0">
                        <li>Moving Average: Bullish</li>
                        <li>RSI: Neutral (54)</li>
                        <li>MACD: Potential crossover</li>
                      </ul>
                    </Alert>
                    
                    <div className="mt-4">
                      <h6>Performance Metrics</h6>
                      <div className="mt-3">
                        {currentStock && currentStock.price_history && (
                          <Plot
                            data={[
                              {
                                type: 'scatter',
                                mode: 'lines',
                                x: currentStock.price_history.map(item => item.date),
                                y: currentStock.price_history.map(item => item.close),
                                line: {color: '#3f51b5', width: 2},
                                name: 'Close Price',
                              },
                              {
                                type: 'scatter',
                                mode: 'lines',
                                x: currentStock.price_history.map(item => item.date),
                                y: currentStock.price_history.map((item, i, arr) => {
                                  if (i < 10) return null;
                                  // Calculate SMA-10
                                  let sum = 0;
                                  for (let j = i; j > i - 10; j--) {
                                    sum += arr[j].close;
                                  }
                                  return sum / 10;
                                }),
                                line: {color: '#ff9800', width: 2, dash: 'dash'},
                                name: 'SMA-10',
                              },
                              {
                                type: 'scatter',
                                mode: 'lines',
                                x: currentStock.price_history.map(item => item.date),
                                y: currentStock.price_history.map((item, i, arr) => {
                                  if (i < 20) return null;
                                  // Calculate SMA-20
                                  let sum = 0;
                                  for (let j = i; j > i - 20; j--) {
                                    sum += arr[j].close;
                                  }
                                  return sum / 20;
                                }),
                                line: {color: '#e91e63', width: 2, dash: 'dash'},
                                name: 'SMA-20',
                              }
                            ]}
                            layout={{
                              autosize: true,
                              height: 250,
                              margin: {l: 50, r: 20, t: 30, b: 40},
                              title: {
                                text: 'Price Performance with Moving Averages',
                                font: { size: 13 }
                              },
                              legend: {
                                orientation: 'h',
                                y: -0.2
                              },
                              xaxis: {
                                showgrid: false,
                              },
                              yaxis: {
                                title: 'Price',
                                showgrid: true,
                                gridcolor: 'rgba(0,0,0,0.1)',
                              },
                              paper_bgcolor: 'rgba(0,0,0,0)',
                              plot_bgcolor: 'rgba(0,0,0,0)',
                              font: { 
                                family: '"Poppins", sans-serif'
                              },
                            }}
                            config={{
                              responsive: true,
                              displaylogo: false,
                              modeBarButtonsToRemove: [
                                'select2d',
                                'lasso2d',
                                'autoScale2d',
                                'toggleSpikelines',
                              ]
                            }}
                            style={{width: '100%'}}
                            className="performance-chart"
                          />
                        )}
                      </div>
                    </div>
                  </div>
                </Tab>
              </Tabs>
            )}
          </Modal.Body>
        ) : (
          <Form onSubmit={handleSubmit}>
            <Modal.Body>
              <Row className="mb-3">
                <Col md={6}>
                  <Form.Group>
                    <Form.Label>Symbol</Form.Label>
                    <Form.Control
                      type="text"
                      value={formData.symbol}
                      onChange={e => setFormData({...formData, symbol: e.target.value.toUpperCase()})}
                      readOnly={modalMode === 'edit'} // Symbol shouldn't be changed after creation
                      required
                    />
                  </Form.Group>
                </Col>
                <Col md={6}>
                  <Form.Group>
                    <Form.Label>Sector</Form.Label>
                    <Form.Control
                      type="text"
                      value={formData.sector}
                      onChange={e => setFormData({...formData, sector: e.target.value})}
                      required
                    />
                  </Form.Group>
                </Col>
              </Row>
              
              <Row className="mb-3">
                <Col>
                  <Form.Group>
                    <Form.Label>Company Name</Form.Label>
                    <Form.Control
                      type="text"
                      value={formData.company_name}
                      onChange={e => setFormData({...formData, company_name: e.target.value})}
                      required
                    />
                  </Form.Group>
                </Col>
              </Row>
              
              <Row className="mb-3">
                <Col>
                  <Form.Group>
                    <Form.Label>Description</Form.Label>
                    <Form.Control
                      as="textarea"
                      rows={3}
                      value={formData.description}
                      onChange={e => setFormData({...formData, description: e.target.value})}
                    />
                  </Form.Group>
                </Col>
              </Row>
              
              <Row className="mb-3">
                <Col>
                  <Form.Group>
                    <Form.Label>Outstanding Shares</Form.Label>
                    <Form.Control
                      type="number"
                      value={formData.outstanding_shares}
                      onChange={e => setFormData({...formData, outstanding_shares: parseInt(e.target.value) || 0})}
                      required
                    />
                  </Form.Group>
                </Col>
              </Row>
              
              <Form.Group>
                <Form.Check
                  type="switch"
                  id="stock-active"
                  label="Active Stock"
                  checked={formData.is_active}
                  onChange={e => setFormData({...formData, is_active: e.target.checked})}
                />
                <Form.Text className="text-muted">
                  Inactive stocks won't be included in data updates and analysis.
                </Form.Text>
              </Form.Group>
            </Modal.Body>
            <Modal.Footer>
              <Button variant="secondary" onClick={() => setShowModal(false)}>
                Cancel
              </Button>
              <Button 
                variant="primary" 
                type="submit" 
                disabled={actionLoading}
              >
                {actionLoading ? (
                  <>
                    <Spinner as="span" animation="border" size="sm" className="me-2" />
                    Saving...
                  </>
                ) : (
                  'Save Stock'
                )}
              </Button>
            </Modal.Footer>
          </Form>
        )}
      </Modal>
      
      <p className="text-muted mt-3">
        <small>Last updated: {new Date().toLocaleString()}</small>
      </p>
    </div>
  );
};

export default StockManagement;
