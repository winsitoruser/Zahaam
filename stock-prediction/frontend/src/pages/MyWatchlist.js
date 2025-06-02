import React, { useState, useEffect } from 'react';
import { Container, Row, Col, Card, Table, Button, Form, Spinner, Modal, Alert, Tabs, Tab, Badge } from 'react-bootstrap';
import { Link } from 'react-router-dom';
import axios from 'axios';
import { formatCurrency, getValueColor } from '../services/api';

const MyWatchlist = () => {
  // State untuk menyimpan data
  const [watchlistItems, setWatchlistItems] = useState([]);
  const [stocks, setStocks] = useState([]);
  const [predictions, setPredictions] = useState([]);
  const [selectedTicker, setSelectedTicker] = useState('');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [showAddModal, setShowAddModal] = useState(false);
  const [showEditModal, setShowEditModal] = useState(false);
  const [editItem, setEditItem] = useState(null);
  const [refreshTrigger, setRefreshTrigger] = useState(0);
  const [activeTab, setActiveTab] = useState('stocks');
  
  // Form state
  const [formData, setFormData] = useState({
    notes: '',
    is_favorite: false,
    alert_price_high: '',
    alert_price_low: '',
    notification_enabled: true
  });
  
  // Inisialisasi data saat komponen di-mount
  useEffect(() => {
    fetchWatchlist();
    fetchStocks();
  }, [refreshTrigger]);
  
  // Effect untuk mengambil prediksi ketika tab aktif berubah
  useEffect(() => {
    if (activeTab === 'predictions') {
      fetchPredictions();
    }
  }, [activeTab]);
  
  // Fungsi untuk mengambil watchlist
  const fetchWatchlist = async () => {
    setLoading(true);
    try {
      const response = await axios.get('/watchlist');
      setWatchlistItems(response.data.watchlist);
    } catch (err) {
      console.error('Error fetching watchlist:', err);
      setError('Failed to load watchlist data');
    } finally {
      setLoading(false);
    }
  };
  
  // Fungsi untuk mengambil daftar saham
  const fetchStocks = async () => {
    try {
      const response = await axios.get('/stocks/db');
      setStocks(response.data.stocks);
    } catch (err) {
      console.error('Error fetching stocks:', err);
    }
  };
  
  // Fungsi untuk mengambil prediksi
  const fetchPredictions = async () => {
    try {
      setLoading(true);
      const response = await axios.get('/watchlist/predictions');
      setPredictions(response.data.predictions);
    } catch (err) {
      console.error('Error fetching predictions:', err);
      setError('Failed to load prediction data');
    } finally {
      setLoading(false);
    }
  };
  
  // Handler untuk menambahkan item ke watchlist
  const handleAddToWatchlist = async (e) => {
    e.preventDefault();
    if (!selectedTicker) {
      setError('Please select a stock');
      return;
    }
    
    try {
      await axios.post('/watchlist', {
        ticker: selectedTicker,
        notes: formData.notes,
        is_favorite: formData.is_favorite,
        alert_price_high: formData.alert_price_high || null,
        alert_price_low: formData.alert_price_low || null,
        notification_enabled: formData.notification_enabled
      });
      
      setShowAddModal(false);
      setSelectedTicker('');
      setFormData({
        notes: '',
        is_favorite: false,
        alert_price_high: '',
        alert_price_low: '',
        notification_enabled: true
      });
      
      setRefreshTrigger(prev => prev + 1);
    } catch (err) {
      console.error('Error adding to watchlist:', err);
      setError('Failed to add to watchlist');
    }
  };
  
  // Handler untuk update item watchlist
  const handleUpdateWatchlistItem = async (e) => {
    e.preventDefault();
    if (!editItem) {
      return;
    }
    
    try {
      await axios.put(`/watchlist/${editItem.id}`, {
        notes: formData.notes,
        is_favorite: formData.is_favorite,
        alert_price_high: formData.alert_price_high || null,
        alert_price_low: formData.alert_price_low || null,
        notification_enabled: formData.notification_enabled
      });
      
      setShowEditModal(false);
      setEditItem(null);
      setFormData({
        notes: '',
        is_favorite: false,
        alert_price_high: '',
        alert_price_low: '',
        notification_enabled: true
      });
      
      setRefreshTrigger(prev => prev + 1);
    } catch (err) {
      console.error('Error updating watchlist item:', err);
      setError('Failed to update watchlist item');
    }
  };
  
  // Handler untuk remove item dari watchlist
  const handleRemoveFromWatchlist = async (itemId) => {
    if (!window.confirm('Are you sure you want to remove this stock from your watchlist?')) {
      return;
    }
    
    try {
      await axios.delete(`/watchlist/${itemId}`);
      setRefreshTrigger(prev => prev + 1);
    } catch (err) {
      console.error('Error removing from watchlist:', err);
      setError('Failed to remove from watchlist');
    }
  };
  
  // Fungsi untuk menampilkan modal edit
  const handleShowEditModal = (item) => {
    setEditItem(item);
    setFormData({
      notes: item.notes || '',
      is_favorite: item.is_favorite || false,
      alert_price_high: item.alert_price_high || '',
      alert_price_low: item.alert_price_low || '',
      notification_enabled: item.notification_enabled !== false
    });
    setShowEditModal(true);
  };
  
  // Handler untuk input form
  const handleInputChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData({
      ...formData,
      [name]: type === 'checkbox' ? checked : value
    });
  };
  
  // Render modal tambah watchlist
  const renderAddWatchlistModal = () => (
    <Modal show={showAddModal} onHide={() => setShowAddModal(false)}>
      <Modal.Header closeButton>
        <Modal.Title>Add to Watchlist</Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <Form onSubmit={handleAddToWatchlist}>
          <Form.Group className="mb-3">
            <Form.Label>Stock</Form.Label>
            <Form.Select 
              value={selectedTicker}
              onChange={(e) => setSelectedTicker(e.target.value)}
              required
            >
              <option value="">Select a stock</option>
              {stocks.map(stock => (
                <option key={stock.ticker} value={stock.ticker}>
                  {stock.ticker} - {stock.name}
                </option>
              ))}
            </Form.Select>
          </Form.Group>
          
          <Form.Group className="mb-3">
            <Form.Label>Notes</Form.Label>
            <Form.Control
              as="textarea"
              name="notes"
              value={formData.notes}
              onChange={handleInputChange}
              placeholder="Add your notes about this stock"
            />
          </Form.Group>
          
          <Form.Group className="mb-3">
            <Form.Check
              type="checkbox"
              label="Mark as favorite"
              name="is_favorite"
              checked={formData.is_favorite}
              onChange={handleInputChange}
            />
          </Form.Group>
          
          <Row>
            <Col>
              <Form.Group className="mb-3">
                <Form.Label>Alert When Price Above</Form.Label>
                <Form.Control
                  type="number"
                  name="alert_price_high"
                  value={formData.alert_price_high}
                  onChange={handleInputChange}
                  placeholder="Optional"
                />
              </Form.Group>
            </Col>
            <Col>
              <Form.Group className="mb-3">
                <Form.Label>Alert When Price Below</Form.Label>
                <Form.Control
                  type="number"
                  name="alert_price_low"
                  value={formData.alert_price_low}
                  onChange={handleInputChange}
                  placeholder="Optional"
                />
              </Form.Group>
            </Col>
          </Row>
          
          <Form.Group className="mb-3">
            <Form.Check
              type="checkbox"
              label="Enable price notifications"
              name="notification_enabled"
              checked={formData.notification_enabled}
              onChange={handleInputChange}
            />
          </Form.Group>
          
          <div className="d-flex justify-content-end">
            <Button variant="secondary" className="me-2" onClick={() => setShowAddModal(false)}>
              Cancel
            </Button>
            <Button variant="primary" type="submit">
              Add to Watchlist
            </Button>
          </div>
        </Form>
      </Modal.Body>
    </Modal>
  );
  
  // Render modal edit watchlist
  const renderEditWatchlistItemModal = () => (
    <Modal show={showEditModal} onHide={() => setShowEditModal(false)}>
      <Modal.Header closeButton>
        <Modal.Title>Edit Watchlist Item</Modal.Title>
      </Modal.Header>
      <Modal.Body>
        {editItem && (
          <Form onSubmit={handleUpdateWatchlistItem}>
            <p className="fw-bold">{editItem.ticker} - {editItem.name}</p>
            
            <Form.Group className="mb-3">
              <Form.Label>Notes</Form.Label>
              <Form.Control
                as="textarea"
                name="notes"
                value={formData.notes}
                onChange={handleInputChange}
                placeholder="Add your notes about this stock"
              />
            </Form.Group>
            
            <Form.Group className="mb-3">
              <Form.Check
                type="checkbox"
                label="Mark as favorite"
                name="is_favorite"
                checked={formData.is_favorite}
                onChange={handleInputChange}
              />
            </Form.Group>
            
            <Row>
              <Col>
                <Form.Group className="mb-3">
                  <Form.Label>Alert When Price Above</Form.Label>
                  <Form.Control
                    type="number"
                    name="alert_price_high"
                    value={formData.alert_price_high}
                    onChange={handleInputChange}
                    placeholder="Optional"
                  />
                </Form.Group>
              </Col>
              <Col>
                <Form.Group className="mb-3">
                  <Form.Label>Alert When Price Below</Form.Label>
                  <Form.Control
                    type="number"
                    name="alert_price_low"
                    value={formData.alert_price_low}
                    onChange={handleInputChange}
                    placeholder="Optional"
                  />
                </Form.Group>
              </Col>
            </Row>
            
            <Form.Group className="mb-3">
              <Form.Check
                type="checkbox"
                label="Enable price notifications"
                name="notification_enabled"
                checked={formData.notification_enabled}
                onChange={handleInputChange}
              />
            </Form.Group>
            
            <div className="d-flex justify-content-end">
              <Button variant="secondary" className="me-2" onClick={() => setShowEditModal(false)}>
                Cancel
              </Button>
              <Button variant="primary" type="submit">
                Update
              </Button>
            </div>
          </Form>
        )}
      </Modal.Body>
    </Modal>
  );
  
  return (
    <>
      {/* Hero Section */}
      <div className="hero-section py-4">
        <Container>
          <Row className="align-items-center">
            <Col md={6}>
              <div className="hero-content">
                <h1>My Watchlist</h1>
                <p className="lead">
                  Track your favorite stocks and get personalized predictions.
                </p>
              </div>
            </Col>
            <Col md={6} className="text-end">
              <Button 
                variant="primary" 
                onClick={() => setShowAddModal(true)}
                disabled={loading || stocks.length === 0}
              >
                <i className="bi bi-plus-circle me-2"></i>
                Add to Watchlist
              </Button>
            </Col>
          </Row>
        </Container>
      </div>
      
      <Container className="py-4">
        {/* Error Alert */}
        {error && (
          <Alert variant="danger" onClose={() => setError(null)} dismissible>
            {error}
          </Alert>
        )}
        
        {/* Loading Spinner */}
        {loading ? (
          <div className="text-center py-5">
            <Spinner animation="border" role="status">
              <span className="visually-hidden">Loading...</span>
            </Spinner>
            <p className="mt-3">Loading your watchlist...</p>
          </div>
        ) : (
          <>
            {/* Tabs */}
            <Tabs
              activeKey={activeTab}
              onSelect={(k) => setActiveTab(k)}
              className="mb-4"
            >
              <Tab eventKey="stocks" title="My Stocks">
                {watchlistItems.length === 0 ? (
                  <div className="text-center py-5">
                    <p className="mb-4">You don't have any stocks in your watchlist yet.</p>
                    <Button 
                      variant="outline-primary" 
                      onClick={() => setShowAddModal(true)}
                      disabled={stocks.length === 0}
                    >
                      <i className="bi bi-plus-circle me-2"></i>
                      Add Your First Stock
                    </Button>
                  </div>
                ) : (
                  <Table responsive hover>
                    <thead>
                      <tr>
                        <th>Symbol</th>
                        <th>Name</th>
                        <th>Last Price</th>
                        <th>Change</th>
                        <th>Alerts</th>
                        <th>Notes</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      {watchlistItems.map(item => (
                        <tr key={item.id}>
                          <td>
                            {item.is_favorite && <i className="bi bi-star-fill text-warning me-2"></i>}
                            <Link to={`/stocks?symbol=${item.ticker}`} className="fw-bold">
                              {item.ticker}
                            </Link>
                          </td>
                          <td>{item.name}</td>
                          <td>{formatCurrency(item.last_price)}</td>
                          <td className={getValueColor(item.price_change_pct)}>
                            {(item.price_change_pct > 0 ? '+' : '') + item.price_change_pct.toFixed(2)}%
                          </td>
                          <td>
                            {(item.alert_price_high || item.alert_price_low) ? (
                              <div>
                                {item.alert_price_high && (
                                  <Badge bg="success" className="me-1">
                                    Above {formatCurrency(item.alert_price_high)}
                                  </Badge>
                                )}
                                {item.alert_price_low && (
                                  <Badge bg="danger">
                                    Below {formatCurrency(item.alert_price_low)}
                                  </Badge>
                                )}
                              </div>
                            ) : (
                              <span className="text-muted">No alerts</span>
                            )}
                          </td>
                          <td>
                            {item.notes ? (
                              <span className="text-truncate d-inline-block" style={{maxWidth: '150px'}}>
                                {item.notes}
                              </span>
                            ) : (
                              <span className="text-muted">No notes</span>
                            )}
                          </td>
                          <td>
                            <Button 
                              variant="outline-primary" 
                              size="sm" 
                              className="me-2"
                              onClick={() => handleShowEditModal(item)}
                            >
                              <i className="bi bi-pencil"></i>
                            </Button>
                            <Button 
                              variant="outline-danger" 
                              size="sm"
                              onClick={() => handleRemoveFromWatchlist(item.id)}
                            >
                              <i className="bi bi-trash"></i>
                            </Button>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </Table>
                )}
              </Tab>
              
              <Tab eventKey="predictions" title="Predictions">
                <Card>
                  <Card.Body>
                    <Card.Title>Stock Predictions for Your Watchlist</Card.Title>
                    <Card.Text>
                      View AI-powered predictions for stocks in your watchlist.
                    </Card.Text>
                    
                    {predictions.length === 0 ? (
                      <div className="text-center py-3">
                        <p>No prediction data available for your watchlist stocks.</p>
                      </div>
                    ) : (
                      <Table responsive hover>
                        <thead>
                          <tr>
                            <th>Symbol</th>
                            <th>Current Price</th>
                            <th>Predicted (7d)</th>
                            <th>Predicted Change</th>
                            <th>Confidence</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          {predictions.map(prediction => (
                            <tr key={prediction.ticker}>
                              <td>
                                <Link to={`/stocks?symbol=${prediction.ticker}`} className="fw-bold">
                                  {prediction.ticker}
                                </Link>
                              </td>
                              <td>{formatCurrency(prediction.current_price)}</td>
                              <td>{formatCurrency(prediction.predicted_price)}</td>
                              <td className={getValueColor(prediction.predicted_change_pct)}>
                                {(prediction.predicted_change_pct > 0 ? '+' : '') + prediction.predicted_change_pct.toFixed(2)}%
                              </td>
                              <td>
                                <div className="progress" style={{height: '20px'}}>
                                  <div 
                                    className={`progress-bar ${prediction.confidence > 70 ? 'bg-success' : 'bg-warning'}`}
                                    role="progressbar" 
                                    style={{width: `${prediction.confidence}%`}}
                                    aria-valuenow={prediction.confidence} 
                                    aria-valuemin="0" 
                                    aria-valuemax="100"
                                  >
                                    {prediction.confidence}%
                                  </div>
                                </div>
                              </td>
                              <td>
                                <Badge bg={prediction.predicted_change_pct > 2 ? 'success' : 
                                          prediction.predicted_change_pct < -2 ? 'danger' : 'secondary'}>
                                  {prediction.predicted_change_pct > 2 ? 'BUY' : 
                                   prediction.predicted_change_pct < -2 ? 'SELL' : 'HOLD'}
                                </Badge>
                              </td>
                            </tr>
                          ))}
                        </tbody>
                      </Table>
                    )}
                  </Card.Body>
                </Card>
              </Tab>
            </Tabs>
          </>
        )}
      </Container>
      
      {renderAddWatchlistModal()}
      {renderEditWatchlistItemModal()}
    </>
  );
};

export default MyWatchlist;
