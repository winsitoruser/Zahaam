import React, { useState, useEffect, useContext, useCallback } from 'react';
import { Container, Row, Col, Card, Table, Button, Form, Spinner, Modal, Alert, Tabs, Tab, Badge } from 'react-bootstrap';
import { Link, useNavigate } from 'react-router-dom';
import * as api from '../services/apiIntegration';
import { batchApi } from '../services/batchApi';
import { AuthContext } from '../contexts/AuthContext';

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
  const [lastUpdated, setLastUpdated] = useState(null);
  
  // Authentication context
  const { isAuthenticated, currentUser } = useContext(AuthContext);
  const navigate = useNavigate();
  
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
    if (!isAuthenticated) {
      navigate('/login', { state: { from: '/watchlist' } });
      return;
    }
    
    fetchWatchlist();
    fetchStocks();
    
    // Set up auto refresh interval for watchlist data (every 60 seconds if tab is visible)
    const refreshInterval = setInterval(() => {
      if (!document.hidden && isAuthenticated) {
        setRefreshTrigger(prev => prev + 1);
      }
    }, 60000); // 1 minute refresh
    
    // Cleanup pada unmount
    return () => clearInterval(refreshInterval);
  }, [isAuthenticated, navigate, fetchWatchlist]);
  
  // Effect untuk mengambil prediksi ketika tab aktif berubah
  useEffect(() => {
    if (activeTab === 'predictions') {
      fetchPredictions();
    }
  }, [activeTab, fetchPredictions]);
  
  // Fungsi untuk mengambil watchlist dengan menggunakan API terintegrasi dengan authentication dan caching
  const fetchWatchlist = useCallback(async () => {
    setLoading(true);
    try {
      // Menggunakan API terintegrasi yang mendukung caching dan authentication
      const result = await api.fetchWatchlist({
        useCache: true,
        cacheTTL: 300, // Cache selama 5 menit
        priorityFresh: true, // Prioritaskan data segar
        requireAuth: true // Memerlukan otentikasi
      });
      
      if (result && result.watchlist && Array.isArray(result.watchlist)) {
        setWatchlistItems(result.watchlist);
        setLastUpdated(new Date());
      } else {
        // Fallback jika data tidak ada
        setWatchlistItems([]);
      }
    } catch (err) {
      console.error('Error fetching watchlist:', err);
      setError('Failed to load watchlist data. Please try again.');
      setWatchlistItems([]); // Defensive programming: set empty array on error
    } finally {
      setLoading(false);
    }
  }, []);
  
  // Fungsi untuk mengambil daftar saham dengan caching dan authentication
  const fetchStocks = useCallback(async () => {
    try {
      // Menggunakan API terintegrasi dengan caching dan authentikasi
      const result = await api.fetchStocks({
        useCache: true,
        cacheTTL: 900, // Cache selama 15 menit
        requireAuth: false // Data saham dapat diakses tanpa auth
      });
      
      if (result && result.stocks && Array.isArray(result.stocks)) {
        setStocks(result.stocks);
      } else {
        // Fallback jika data tidak ada
        setStocks([]);
      }
    } catch (err) {
      console.error('Error fetching stocks:', err);
      setStocks([]); // Defensive programming: set empty array on error
    }
  }, []);
  
  // Fungsi untuk mengambil prediksi dengan menggunakan batch API dan cache
  const fetchPredictions = useCallback(async () => {
    try {
      setLoading(true);
      // Cek apakah ada watchlist items
      if (Array.isArray(watchlistItems) && watchlistItems.length > 0) {
        // Dapatkan array symbol untuk batch request
        const symbols = watchlistItems.map(item => item.symbol).filter(Boolean);
        
        if (symbols.length === 0) {
          setPredictions([]);
          return;
        }
        
        // Gunakan batch API untuk mengambil semua prediksi sekaligus dengan authentication
        // Memanfaatkan token refresh otomatis jika token kedaluwarsa
        const batchResult = await batchApi.batchFetchWatchlistPredictions(symbols, {
          useCache: true,
          cacheTTL: 600, // Cache selama 10 menit
          requireAuth: true
        });
        
        if (!batchResult) {
          throw new Error('Failed to fetch batch predictions');
        }
        
        // Transform hasil batch menjadi format prediksi
        const validPredictions = [];
        
        for (const symbol of symbols) {
          if (batchResult[symbol] && batchResult[symbol].data) {
            const data = batchResult[symbol].data;
            // Sekarang ambil prediksi untuk masing-masing saham dengan defensive programming
            try {
              // Use the new integrated API with caching for predictions
              const prediction = await api.fetchPrediction(symbol, 'default', {
                useCache: true,
                cacheTTL: 900, // Cache for 15 minutes
                requireAuth: true
              });
              
              if (prediction && typeof prediction === 'object') {
                validPredictions.push({
                  ticker: symbol,
                  current_price: prediction.current_price || data.price || 0,
                  predicted_price: prediction.predicted_price || data.price || 0,
                  predicted_change_pct: prediction.predicted_change_pct || 0,
                  confidence: prediction.confidence || 50,
                  recommendation: prediction.recommendation || 'HOLD'
                });
              } else {
                // Fallback jika prediksi gagal
                validPredictions.push({
                  ticker: symbol,
                  current_price: data.price || 0,
                  predicted_price: data.price || 0, // Default sama dengan harga saat ini
                  predicted_change_pct: 0,
                  confidence: 50,
                  recommendation: 'HOLD'
                });
              }
            } catch (predictErr) {
              console.error(`Error fetching prediction for ${symbol}:`, predictErr);
              // Fallback jika terjadi error
              validPredictions.push({
                ticker: symbol,
                current_price: data.price || 0,
                predicted_price: data.price || 0, // Default sama dengan harga saat ini
                predicted_change_pct: 0,
                confidence: 50,
                recommendation: 'HOLD'
              });
            }
          }
        }
        
        setPredictions(validPredictions);
        setLastUpdated(new Date());
      } else {
        setPredictions([]);
      }
    } catch (err) {
      console.error('Error fetching predictions:', err);
      setError('Failed to load prediction data');
      setPredictions([]); // Defensive programming: set empty array on error
    } finally {
      setLoading(false);
    }
  }, [watchlistItems]);
  
  // Handler untuk menambahkan item ke watchlist dengan authentication dan caching
  const handleAddToWatchlist = async (e) => {
    e.preventDefault();
    
    if (!selectedTicker) {
      setError('Please select a stock');
      return;
    }
    
    try {
      setLoading(true);
      setError(null);
      
      // Find selected stock details
      const stockDetails = stocks.find(stock => stock.symbol === selectedTicker);
      if (!stockDetails) {
        throw new Error('Selected stock not found');
      }
      
      // Prepare watchlist item data
      const watchlistItem = {
        symbol: selectedTicker,
        name: stockDetails.name || 'Unknown',
        notes: formData.notes || '',
        is_favorite: formData.is_favorite || false,
        alert_price_high: formData.alert_price_high ? parseFloat(formData.alert_price_high) : null,
        alert_price_low: formData.alert_price_low ? parseFloat(formData.alert_price_low) : null,
        notification_enabled: formData.notification_enabled || false,
        userId: currentUser?.id, // Include user ID for authentication
        created_at: new Date().toISOString()
      };
      
      // Call API to add to watchlist with authentication
      // The enhanced API service will automatically handle authentication and token refresh
      const result = await api.addToWatchlist(watchlistItem);
      
      if (result && result.success) {
        // Clear watchlist cache to ensure fresh data on next load
        api.clearCacheByPattern('watchlist');
        
        // Refresh watchlist
        fetchWatchlist();
        
        // Reset form and close modal
        setSelectedTicker('');
        setFormData({
          notes: '',
          is_favorite: false,
          alert_price_high: '',
          alert_price_low: '',
          notification_enabled: true
        });
        setShowAddModal(false);
      } else {
        throw new Error(result?.message || 'Failed to add to watchlist');
      }
    } catch (err) {
      console.error('Error adding to watchlist:', err);
      setError(err.message || 'Failed to add stock to watchlist. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  // Handler untuk remove item dari watchlist dengan authentication dan cache invalidation
  const handleRemoveFromWatchlist = async (itemId) => {
    if (!itemId) return;
    
    if (!window.confirm('Are you sure you want to remove this item from your watchlist?')) {
      return;
    }
    
    try {
      setLoading(true);
      
      // Call API to remove from watchlist with authentication
      // The enhanced API service will automatically handle authentication and token refresh
      const result = await api.removeFromWatchlist(itemId, {
        requireAuth: true, // Ensure authenticated API call
        userId: currentUser?.id // Include user ID for verification
      });
      
      if (result && result.success) {
        // Clear watchlist cache
        api.clearCacheByPattern('watchlist');
        
        // Refresh watchlist
        fetchWatchlist();
        
        // If in predictions tab, also refresh predictions
        if (activeTab === 'predictions') {
          fetchPredictions();
        }
      } else {
        throw new Error(result?.message || 'Failed to remove watchlist item');
      }
    } catch (err) {
      console.error('Error removing watchlist item:', err);
      setError('Failed to remove item from watchlist. Please try again.');
    } finally {
      setLoading(false);
    }
  };
  
  // Handler untuk update item watchlist dengan authentication dan caching
  const handleUpdateWatchlistItem = async (e) => {
    if (e) e.preventDefault();
    
    if (!editItem) {
      setError('No item selected for update');
      return;
    }
    
    try {
      setLoading(true);
      setError(null);
      
      // Prepare update data
      const updateData = {
        ...editItem,
        notes: formData.notes || '',
        is_favorite: formData.is_favorite || false,
        alert_price_high: formData.alert_price_high ? parseFloat(formData.alert_price_high) : null,
        alert_price_low: formData.alert_price_low ? parseFloat(formData.alert_price_low) : null,
        notification_enabled: formData.notification_enabled || false,
        userId: currentUser?.id, // Include user ID for authentication
        updated_at: new Date().toISOString()
      };
      
      // Call API to update item with authentication
      // The enhanced API service will automatically handle authentication and token refresh
      const result = await api.updateWatchlistItem(editItem.id, updateData);
      
      if (result && result.success) {
        // Clear watchlist cache to ensure fresh data on next load
        api.clearCacheByPattern('watchlist');
        
        // Refresh watchlist
        fetchWatchlist();
        
        // Reset state and close modal
        setEditItem(null);
        setShowEditModal(false);
        setFormData({
          notes: '',
          is_favorite: false,
          alert_price_high: '',
          alert_price_low: '',
          notification_enabled: true
        });
      } else {
        throw new Error(result?.message || 'Failed to update watchlist item');
      }
    } catch (err) {
      console.error('Error updating watchlist item:', err);
      setError(err.message || 'Failed to update watchlist item. Please try again.');
    } finally {
      setLoading(false);
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
                              <td>{api.formatCurrency(prediction.current_price)}</td>
                              <td>{api.formatCurrency(prediction.predicted_price)}</td>
                              <td className={api.getValueColor(prediction.predicted_change_pct)}>
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
                                <Badge bg={prediction.recommendation === 'BUY' ? 'success' : 
                                        prediction.recommendation === 'SELL' ? 'danger' : 'secondary'}>
                                  {prediction.recommendation || 
                                    (prediction.predicted_change_pct > 2 ? 'BUY' : 
                                     prediction.predicted_change_pct < -2 ? 'SELL' : 'HOLD')}
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
