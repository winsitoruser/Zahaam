import React, { useState, useEffect } from 'react';
import { Card, Table, Button, Alert, Spinner, Row, Col } from 'react-bootstrap';
import { FaTrash, FaBroom, FaMemory, FaSync } from 'react-icons/fa';
import axios from 'axios';

const CacheManagement = ({ lastRefresh }) => {
  const [cacheData, setCacheData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [actionLoading, setActionLoading] = useState(false);
  const [actionStatus, setActionStatus] = useState(null);

  const fetchCacheStats = async () => {
    setLoading(true);
    try {
      const response = await axios.get('/api/admin/cache/stats');
      setCacheData(response.data);
      setError(null);
    } catch (err) {
      console.error('Error fetching cache stats:', err);
      setError('Gagal mengambil statistik cache');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchCacheStats();
  }, [lastRefresh]);

  const handleClearCache = async (clearAll = false) => {
    setActionLoading(true);
    setActionStatus(null);
    
    try {
      const response = await axios.post('/api/admin/cache/clear', {
        clear_all: clearAll
      });
      
      setActionStatus({
        type: 'success',
        message: response.data.message
      });
      
      // Refresh cache stats after clearing
      setTimeout(() => fetchCacheStats(), 1000);
    } catch (err) {
      console.error('Error clearing cache:', err);
      setActionStatus({
        type: 'danger',
        message: 'Gagal membersihkan cache'
      });
    } finally {
      setActionLoading(false);
    }
  };

  const calculateEfficiency = (hitRate) => {
    if (!hitRate && hitRate !== 0) return 'N/A';
    
    if (hitRate >= 90) return 'Excellent';
    if (hitRate >= 75) return 'Good';
    if (hitRate >= 50) return 'Average';
    if (hitRate >= 25) return 'Poor';
    return 'Very Poor';
  };

  const getVariantForEfficiency = (efficiency) => {
    switch (efficiency) {
      case 'Excellent': return 'success';
      case 'Good': return 'primary';
      case 'Average': return 'info';
      case 'Poor': return 'warning';
      case 'Very Poor': return 'danger';
      default: return 'secondary';
    }
  };

  if (loading) {
    return (
      <div className="text-center py-5">
        <Spinner animation="border" variant="primary" />
        <p className="mt-3">Loading cache statistics...</p>
      </div>
    );
  }

  if (error) {
    return (
      <Alert variant="danger">
        {error}
      </Alert>
    );
  }

  const hitRate = cacheData?.hit_rate || 0;
  const efficiency = calculateEfficiency(hitRate);
  const efficiencyVariant = getVariantForEfficiency(efficiency);

  return (
    <div className="cache-management">
      <h3 className="mb-4">Cache Management</h3>
      
      <Row className="mb-4 g-3">
        <Col md={6} sm={12}>
          <Card className="h-100">
            <Card.Header>
              <h5 className="mb-0">Cache Overview</h5>
            </Card.Header>
            <Card.Body>
              <div className="d-flex align-items-center mb-4">
                <div className="stats-icon me-3">
                  <FaMemory />
                </div>
                <div>
                  <h4 className="stats-value">{cacheData?.total_entries || 0}</h4>
                  <p className="stats-label">Total Cache Entries</p>
                </div>
              </div>
              
              <Table className="admin-table" bordered size="sm">
                <tbody>
                  <tr>
                    <th>Memory Usage</th>
                    <td>{cacheData?.memory_usage || 'Unknown'}</td>
                  </tr>
                  <tr>
                    <th>Hit Rate</th>
                    <td>
                      <span className={`text-${efficiencyVariant}`}>
                        {hitRate}% ({efficiency})
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <th>Hits</th>
                    <td>{cacheData?.hits || 0}</td>
                  </tr>
                  <tr>
                    <th>Misses</th>
                    <td>{cacheData?.misses || 0}</td>
                  </tr>
                  <tr>
                    <th>Expired Entries</th>
                    <td>{cacheData?.expired_entries || 0}</td>
                  </tr>
                </tbody>
              </Table>
            </Card.Body>
          </Card>
        </Col>
        
        <Col md={6} sm={12}>
          <Card className="h-100">
            <Card.Header>
              <h5 className="mb-0">Cache Actions</h5>
            </Card.Header>
            <Card.Body>
              <p>
                Cache management membantu meningkatkan performa aplikasi dengan menyimpan 
                hasil query dan komputasi yang sering diakses. Secara periodik cache perlu 
                dibersihkan untuk memastikan data tetap fresh.
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
              
              <div className="action-buttons">
                <Button 
                  variant="warning" 
                  onClick={() => handleClearCache(false)} 
                  disabled={actionLoading}
                  className="me-2"
                >
                  {actionLoading ? (
                    <>
                      <Spinner as="span" animation="border" size="sm" className="me-2" />
                      Processing...
                    </>
                  ) : (
                    <>
                      <FaBroom className="me-2" /> Clear Expired Cache
                    </>
                  )}
                </Button>
                
                <Button 
                  variant="danger" 
                  onClick={() => handleClearCache(true)} 
                  disabled={actionLoading}
                >
                  <FaTrash className="me-2" /> Clear All Cache
                </Button>
              </div>
              
              <div className="mt-4">
                <Button 
                  variant="light" 
                  size="sm"
                  onClick={fetchCacheStats}
                  disabled={loading}
                >
                  <FaSync className="me-1" /> Refresh Stats
                </Button>
              </div>
            </Card.Body>
          </Card>
        </Col>
      </Row>
      
      <Card className="mb-4">
        <Card.Header>
          <h5 className="mb-0">Cache Recommendations</h5>
        </Card.Header>
        <Card.Body>
          <ul>
            <li>
              {hitRate >= 75 ? (
                <span className="text-success">Cache performing well! Current hit rate is optimal.</span>
              ) : hitRate >= 50 ? (
                <span className="text-info">Cache performance is acceptable. Consider adjusting TTL for higher hit rates.</span>
              ) : (
                <span className="text-warning">Low hit rate detected. Consider reviewing cache strategy.</span>
              )}
            </li>
            <li>
              {cacheData?.expired_entries > 50 ? (
                <span className="text-warning">High number of expired entries. Consider reducing TTL or clearing expired cache.</span>
              ) : (
                <span className="text-success">Expired entries count is at a healthy level.</span>
              )}
            </li>
            <li>
              Cache usage is most effective for: frequently accessed market data, stock lists, and user authentication.
            </li>
          </ul>
        </Card.Body>
      </Card>
      
      <p className="text-muted mt-3">
        <small>Last updated: {cacheData?.timestamp || 'N/A'}</small>
      </p>
    </div>
  );
};

export default CacheManagement;
