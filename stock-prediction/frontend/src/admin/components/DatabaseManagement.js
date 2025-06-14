import React, { useState, useEffect } from 'react';
import { Card, Table, Button, Alert, Spinner, Row, Col, Form } from 'react-bootstrap';
import { FaSync, FaSearch, FaSort, FaDatabase } from 'react-icons/fa';
import axios from 'axios';

const DatabaseManagement = ({ lastRefresh }) => {
  const [dbStats, setDbStats] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [actionLoading, setActionLoading] = useState(false);
  const [actionStatus, setActionStatus] = useState(null);
  const [searchTerm, setSearchTerm] = useState('');
  const [sortBy, setSortBy] = useState('rows');
  const [sortDirection, setSortDirection] = useState('desc');

  const fetchDatabaseStats = async () => {
    setLoading(true);
    try {
      const response = await axios.get('/api/admin/db/stats');
      setDbStats(response.data);
      setError(null);
    } catch (err) {
      console.error('Error fetching database stats:', err);
      setError('Gagal mengambil statistik database');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchDatabaseStats();
  }, [lastRefresh]);

  const handleDatabaseOptimize = async (vacuum = false) => {
    setActionLoading(true);
    setActionStatus(null);
    try {
      const response = await axios.post('/api/admin/db/optimize', {
        perform_vacuum: vacuum
      });
      setActionStatus({
        type: 'success',
        message: `Optimisasi database berhasil dijadwalkan. ${vacuum ? 'Termasuk VACUUM.' : ''}`
      });
      
      // Refresh stats after a delay
      setTimeout(() => fetchDatabaseStats(), 2000);
    } catch (err) {
      console.error('Error optimizing database:', err);
      setActionStatus({
        type: 'danger',
        message: 'Gagal melakukan optimisasi database'
      });
    } finally {
      setActionLoading(false);
    }
  };

  const sortData = (data, key, direction) => {
    if (!data) return [];
    
    return [...data].sort((a, b) => {
      // Handle numeric values
      if (typeof a[key] === 'number') {
        return direction === 'asc' ? a[key] - b[key] : b[key] - a[key];
      }
      
      // Handle string values
      const valA = String(a[key]).toLowerCase();
      const valB = String(b[key]).toLowerCase();
      
      if (valA < valB) return direction === 'asc' ? -1 : 1;
      if (valA > valB) return direction === 'asc' ? 1 : -1;
      return 0;
    });
  };

  const handleSort = (field) => {
    if (sortBy === field) {
      setSortDirection(sortDirection === 'asc' ? 'desc' : 'asc');
    } else {
      setSortBy(field);
      setSortDirection('desc');
    }
  };

  const filteredTables = dbStats?.tables
    ? dbStats.tables.filter(table => 
        table.name.toLowerCase().includes(searchTerm.toLowerCase()))
    : [];
  
  const sortedTables = sortData(filteredTables, sortBy, sortDirection);
  
  const filteredIndices = dbStats?.indices
    ? dbStats.indices.filter(index => 
        index.name.toLowerCase().includes(searchTerm.toLowerCase()) || 
        index.table.toLowerCase().includes(searchTerm.toLowerCase()))
    : [];
  
  if (loading) {
    return (
      <div className="text-center py-5">
        <Spinner animation="border" variant="primary" />
        <p className="mt-3">Loading database statistics...</p>
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

  return (
    <div className="database-management">
      <h3 className="mb-4">Database Management</h3>
      
      <Card className="mb-4">
        <Card.Header className="d-flex justify-content-between align-items-center">
          <h5 className="mb-0">Database Actions</h5>
        </Card.Header>
        <Card.Body>
          <Row>
            <Col md={8}>
              <p>
                Lakukan optimisasi database untuk meningkatkan performa query dan mengurangi
                ukuran penyimpanan. Database optimization mencakup table analysis dan index management.
              </p>
              <p>
                <strong>VACUUM</strong> adalah proses membersihkan space yang sudah tidak terpakai. 
                Proses ini lebih intensif dan mungkin mempengaruhi performa selama berjalan.
              </p>
            </Col>
            <Col md={4}>
              <div className="action-buttons">
                <Button 
                  variant="primary" 
                  onClick={() => handleDatabaseOptimize(false)} 
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
                      <FaSync className="me-2" /> Optimize Database
                    </>
                  )}
                </Button>
                
                <Button 
                  variant="warning" 
                  onClick={() => handleDatabaseOptimize(true)} 
                  disabled={actionLoading}
                  className="w-100"
                >
                  <FaDatabase className="me-2" /> VACUUM Database
                </Button>
              </div>
            </Col>
          </Row>
          
          {actionStatus && (
            <Alert 
              variant={actionStatus.type} 
              className="mt-3" 
              onClose={() => setActionStatus(null)} 
              dismissible
            >
              {actionStatus.message}
            </Alert>
          )}
        </Card.Body>
      </Card>
      
      <Card className="mb-4">
        <Card.Header className="d-flex justify-content-between align-items-center">
          <h5 className="mb-0">Tables ({dbStats?.total_tables || 0})</h5>
          <div className="d-flex">
            <Form.Group className="mb-0 me-2">
              <Form.Control
                type="text"
                placeholder="Search tables..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                size="sm"
              />
            </Form.Group>
            <Button 
              variant="light" 
              size="sm" 
              onClick={fetchDatabaseStats}
              disabled={loading}
            >
              <FaSync />
            </Button>
          </div>
        </Card.Header>
        <Card.Body>
          <div className="table-wrapper">
            <Table className="admin-table" hover responsive>
              <thead>
                <tr>
                  <th onClick={() => handleSort('name')} style={{ cursor: 'pointer' }}>
                    Table Name 
                    {sortBy === 'name' && <FaSort className="ms-1" />}
                  </th>
                  <th onClick={() => handleSort('rows')} style={{ cursor: 'pointer' }}>
                    Rows 
                    {sortBy === 'rows' && <FaSort className="ms-1" />}
                  </th>
                  <th>Total Size</th>
                  <th>Index Size</th>
                </tr>
              </thead>
              <tbody>
                {sortedTables.length > 0 ? (
                  sortedTables.map((table, index) => (
                    <tr key={index}>
                      <td><code>{table.name}</code></td>
                      <td>{table.rows.toLocaleString()}</td>
                      <td>{table.total_size}</td>
                      <td>{table.index_size}</td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan="4" className="text-center">No tables found</td>
                  </tr>
                )}
              </tbody>
            </Table>
          </div>
        </Card.Body>
      </Card>
      
      <Card className="mb-4">
        <Card.Header>
          <h5 className="mb-0">Indexes</h5>
        </Card.Header>
        <Card.Body>
          <div className="table-wrapper">
            <Table className="admin-table" hover responsive>
              <thead>
                <tr>
                  <th>Index Name</th>
                  <th>Table</th>
                  <th>Size</th>
                  <th>Scans</th>
                </tr>
              </thead>
              <tbody>
                {filteredIndices.length > 0 ? (
                  filteredIndices.map((index, i) => (
                    <tr key={i}>
                      <td><code>{index.name}</code></td>
                      <td>{index.table}</td>
                      <td>{index.size}</td>
                      <td>{index.scans}</td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan="4" className="text-center">No indexes found</td>
                  </tr>
                )}
              </tbody>
            </Table>
          </div>
        </Card.Body>
      </Card>
      
      <p className="text-muted mt-3">
        <small>Last updated: {dbStats?.timestamp ? new Date(dbStats.timestamp * 1000).toLocaleString() : 'N/A'}</small>
      </p>
    </div>
  );
};

export default DatabaseManagement;
