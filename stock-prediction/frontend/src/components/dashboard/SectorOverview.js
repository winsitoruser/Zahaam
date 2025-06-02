import React, { useState } from 'react';
import { Card, Row, Col, Nav, Table, Badge } from 'react-bootstrap';
import { Link } from 'react-router-dom';

const SectorOverview = ({ sectorPerformance = [], sectorData = {} }) => {
  const [activeView, setActiveView] = useState('performance');
  
  // Sortir data sektor berdasarkan perubahan (untuk tampilan performa) atau market cap (untuk tampilan stats)
  const sortedSectors = [...sectorPerformance].sort((a, b) => 
    activeView === 'performance' ? b.change - a.change : b.marketCap - a.marketCap
  );
  
  // Fungsi untuk memformat angka
  const formatPercentage = (value) => {
    if (value === null || value === undefined || isNaN(value)) return '0.00%';
    return (value >= 0 ? '+' : '') + value.toFixed(2) + '%';
  };
  
  const formatLargeNumber = (num) => {
    if (num === null || num === undefined || isNaN(num)) return '0';
    if (num >= 1000000000000) return (num / 1000000000000).toFixed(2) + 'T';
    if (num >= 1000000000) return (num / 1000000000).toFixed(2) + 'B';
    if (num >= 1000000) return (num / 1000000).toFixed(2) + 'M';
    if (num >= 1000) return (num / 1000).toFixed(2) + 'K';
    return num.toString();
  };
  
  // Fungsi untuk mendapatkan warna badge berdasarkan perubahan
  const getPerformanceColor = (change) => {
    if (change > 2) return 'success';
    if (change >= 0) return 'info';
    if (change >= -2) return 'warning';
    return 'danger';
  };
  
  // Mengambil statistik sektor
  const sectorStats = (sectorId) => {
    const stats = sectorData[sectorId] || {
      companiesCount: 0,
      marketCap: 0,
      volume: 0,
      gainers: 0,
      losers: 0
    };
    return stats;
  };
  
  return (
    <Card className="h-100 border-0 shadow-sm">
      <Card.Header className="bg-transparent border-bottom-0 py-3">
        <div className="d-flex justify-content-between align-items-center">
          <div className="d-flex align-items-center">
            <i className="bi bi-diagram-3 me-2 text-primary"></i>
            <h5 className="card-title mb-0">Sector Overview</h5>
          </div>
          <Badge bg="secondary" className="py-1 px-2">
            <i className="bi bi-briefcase me-1"></i>
            {sectorPerformance.length} Sectors
          </Badge>
        </div>
      </Card.Header>
      
      <Card.Body className="p-0">
        <Nav variant="tabs" className="border-0">
          <Nav.Item>
            <Nav.Link 
              active={activeView === 'performance'} 
              onClick={() => setActiveView('performance')}
              className="border-0 rounded-0"
            >
              <i className="bi bi-graph-up me-1"></i>
              Performance
            </Nav.Link>
          </Nav.Item>
          <Nav.Item>
            <Nav.Link 
              active={activeView === 'stats'} 
              onClick={() => setActiveView('stats')}
              className="border-0 rounded-0"
            >
              <i className="bi bi-pie-chart me-1"></i>
              Statistics
            </Nav.Link>
          </Nav.Item>
        </Nav>
        
        <div className="p-3">
          {activeView === 'performance' ? (
            <div className="table-responsive">
              <Table hover className="align-middle mb-0">
                <thead className="small bg-light">
                  <tr>
                    <th>Sector</th>
                    <th className="text-end">Change %</th>
                    <th className="text-end">Stocks</th>
                  </tr>
                </thead>
                <tbody>
                  {sortedSectors.length > 0 ? (
                    sortedSectors.map((sector) => (
                      <tr key={sector.id}>
                        <td>
                          <Link to={`/sectors/${sector.id}`} className="text-decoration-none">
                            <div className="fw-bold text-dark">{sector.name}</div>
                          </Link>
                        </td>
                        <td className="text-end">
                          <Badge bg={getPerformanceColor(sector.change)} className="px-2 py-1">
                            {formatPercentage(sector.change)}
                          </Badge>
                        </td>
                        <td className="text-end">
                          <div className="small text-muted">
                            {sectorStats(sector.id).companiesCount || '0'} stocks
                          </div>
                        </td>
                      </tr>
                    ))
                  ) : (
                    <tr>
                      <td colSpan="3" className="text-center py-3 text-muted">
                        <i className="bi bi-info-circle me-2"></i>No data available
                      </td>
                    </tr>
                  )}
                </tbody>
              </Table>
            </div>
          ) : (
            <Row className="g-2">
              {sortedSectors.length > 0 ? (
                sortedSectors.map((sector) => {
                  const stats = sectorStats(sector.id);
                  return (
                    <Col md={6} key={sector.id}>
                      <div className="p-2 border rounded mb-2">
                        <div className="d-flex justify-content-between align-items-center mb-2">
                          <Link to={`/sectors/${sector.id}`} className="text-decoration-none fw-bold">
                            {sector.name}
                          </Link>
                          <Badge bg={getPerformanceColor(sector.change)}>
                            {formatPercentage(sector.change)}
                          </Badge>
                        </div>
                        <Row className="g-2 small">
                          <Col xs={6}>
                            <div className="border-start ps-2 py-1">
                              <div className="text-muted">Market Cap</div>
                              <div className="fw-bold">{formatLargeNumber(stats.marketCap)}</div>
                            </div>
                          </Col>
                          <Col xs={6}>
                            <div className="border-start ps-2 py-1">
                              <div className="text-muted">Volume</div>
                              <div className="fw-bold">{formatLargeNumber(stats.volume)}</div>
                            </div>
                          </Col>
                          <Col xs={6}>
                            <div className="border-start ps-2 py-1">
                              <div className="text-muted">Companies</div>
                              <div className="fw-bold">{stats.companiesCount || '0'}</div>
                            </div>
                          </Col>
                          <Col xs={6}>
                            <div className="border-start ps-2 py-1">
                              <div className="d-flex justify-content-between">
                                <small className="text-success">
                                  <i className="bi bi-arrow-up-short"></i>{stats.gainers || '0'}
                                </small>
                                <small className="text-danger">
                                  <i className="bi bi-arrow-down-short"></i>{stats.losers || '0'}
                                </small>
                              </div>
                            </div>
                          </Col>
                        </Row>
                      </div>
                    </Col>
                  );
                })
              ) : (
                <Col xs={12} className="text-center py-3 text-muted">
                  <i className="bi bi-info-circle me-2"></i>No data available
                </Col>
              )}
            </Row>
          )}
        </div>
      </Card.Body>
    </Card>
  );
};

export default SectorOverview;
