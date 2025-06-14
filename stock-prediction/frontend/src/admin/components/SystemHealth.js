import React, { useState, useEffect } from 'react';
import { Row, Col, Card, ProgressBar, Table, Alert, Spinner } from 'react-bootstrap';
import { FaServer, FaDatabase, FaMicrochip, FaMemory, FaHdd } from 'react-icons/fa';
import axios from 'axios';

const SystemHealth = ({ lastRefresh }) => {
  const [healthData, setHealthData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const fetchHealthData = async () => {
    setLoading(true);
    try {
      const response = await axios.get('/api/admin/health');
      setHealthData(response.data);
      setError(null);
    } catch (err) {
      console.error('Error fetching health data:', err);
      setError('Failed to fetch system health data');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchHealthData();
  }, [lastRefresh]);

  const getStatusClass = (value, thresholds = { warning: 70, critical: 90 }) => {
    if (value >= thresholds.critical) return 'critical';
    if (value >= thresholds.warning) return 'warning';
    return 'healthy';
  };

  if (loading) {
    return (
      <div className="text-center py-5">
        <Spinner animation="border" variant="primary" />
        <p className="mt-3">Loading system health data...</p>
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

  if (!healthData) {
    return <Alert variant="warning">No health data available</Alert>;
  }

  const { system, database, api_response_time_ms } = healthData;

  return (
    <div className="system-health">
      <h3 className="mb-4">System Health Dashboard</h3>
      
      <Row className="mb-4 g-3">
        <Col lg={4} md={6}>
          <Card className="stats-card">
            <Card.Body className="d-flex align-items-center">
              <div className="stats-icon me-3">
                <FaServer />
              </div>
              <div>
                <h4 className="stats-value">
                  <span className={`status-${getStatusClass(system.cpu_percent)}`}>
                    {system.cpu_percent}%
                  </span>
                </h4>
                <p className="stats-label">CPU Usage</p>
                <ProgressBar 
                  now={system.cpu_percent} 
                  variant={getStatusClass(system.cpu_percent) === 'critical' ? 'danger' : 
                          getStatusClass(system.cpu_percent) === 'warning' ? 'warning' : 'success'} 
                />
              </div>
            </Card.Body>
          </Card>
        </Col>
        
        <Col lg={4} md={6}>
          <Card className="stats-card">
            <Card.Body className="d-flex align-items-center">
              <div className="stats-icon me-3">
                <FaMemory />
              </div>
              <div>
                <h4 className="stats-value">
                  <span className={`status-${getStatusClass(system.memory_percent)}`}>
                    {system.memory_percent}%
                  </span>
                </h4>
                <p className="stats-label">Memory Usage</p>
                <ProgressBar 
                  now={system.memory_percent} 
                  variant={getStatusClass(system.memory_percent) === 'critical' ? 'danger' : 
                          getStatusClass(system.memory_percent) === 'warning' ? 'warning' : 'success'} 
                />
              </div>
            </Card.Body>
          </Card>
        </Col>
        
        <Col lg={4} md={6}>
          <Card className="stats-card">
            <Card.Body className="d-flex align-items-center">
              <div className="stats-icon me-3">
                <FaHdd />
              </div>
              <div>
                <h4 className="stats-value">
                  <span className={`status-${getStatusClass(system.disk_percent)}`}>
                    {system.disk_percent}%
                  </span>
                </h4>
                <p className="stats-label">Disk Usage</p>
                <ProgressBar 
                  now={system.disk_percent} 
                  variant={getStatusClass(system.disk_percent) === 'critical' ? 'danger' : 
                          getStatusClass(system.disk_percent) === 'warning' ? 'warning' : 'success'} 
                />
              </div>
            </Card.Body>
          </Card>
        </Col>

        <Col lg={4} md={6}>
          <Card className="stats-card">
            <Card.Body className="d-flex align-items-center">
              <div className="stats-icon me-3">
                <FaMicrochip />
              </div>
              <div>
                <h4 className="stats-value">
                  <span className={`status-${api_response_time_ms > 500 ? 'warning' : 'healthy'}`}>
                    {api_response_time_ms} ms
                  </span>
                </h4>
                <p className="stats-label">API Response Time</p>
              </div>
            </Card.Body>
          </Card>
        </Col>
        
        <Col lg={4} md={6}>
          <Card className="stats-card">
            <Card.Body className="d-flex align-items-center">
              <div className="stats-icon me-3">
                <FaDatabase />
              </div>
              <div>
                <h4 className="stats-value">
                  <div>
                    <span className="health-indicator mr-1" 
                          className={`health-indicator ${database.status === "healthy" ? 'healthy' : 'warning'}`}></span>
                    {database.status === "healthy" ? "Healthy" : "Warning"}
                  </div>
                </h4>
                <p className="stats-label">Database Status</p>
              </div>
            </Card.Body>
          </Card>
        </Col>
      </Row>

      <Card className="mb-4">
        <Card.Header>
          <h5 className="mb-0">Database Details</h5>
        </Card.Header>
        <Card.Body>
          <div className="table-wrapper">
            <Table className="admin-table" hover responsive>
              <thead>
                <tr>
                  <th>Property</th>
                  <th>Value</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Connection Type</td>
                  <td>{database.connection_type || 'PostgreSQL'}</td>
                </tr>
                <tr>
                  <td>Connection Pool</td>
                  <td>{database.connection_pool_status || 'Active'}</td>
                </tr>
                <tr>
                  <td>Active Connections</td>
                  <td>{database.active_connections || 'N/A'}</td>
                </tr>
                <tr>
                  <td>Database Size</td>
                  <td>{database.db_size || 'N/A'}</td>
                </tr>
                <tr>
                  <td>Last Optimization</td>
                  <td>{database.last_optimized || 'Unknown'}</td>
                </tr>
              </tbody>
            </Table>
          </div>
        </Card.Body>
      </Card>
      
      <p className="text-muted mt-3">
        <small>Last updated: {new Date(healthData.timestamp * 1000).toLocaleString()}</small>
      </p>
    </div>
  );
};

export default SystemHealth;
