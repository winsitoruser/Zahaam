import React, { useState } from 'react';
import { Card, Nav, Tab, Table, Badge, OverlayTrigger, Tooltip } from 'react-bootstrap';
import { Link } from 'react-router-dom';

const TopStocksWidget = ({ gainers = [], losers = [], mostActive = [] }) => {
  const [activeTab, setActiveTab] = useState('gainers');
  
  // Fungsi untuk memformat angka
  const formatChange = (change) => {
    if (change === null || change === undefined || isNaN(change)) return '0.00%';
    return (change >= 0 ? '+' : '') + change.toFixed(2) + '%';
  };
  
  const formatVolume = (volume) => {
    if (volume === null || volume === undefined || isNaN(volume)) return '0';
    if (volume >= 1000000000) return (volume / 1000000000).toFixed(2) + 'B';
    if (volume >= 1000000) return (volume / 1000000).toFixed(2) + 'M';
    if (volume >= 1000) return (volume / 1000).toFixed(2) + 'K';
    return volume.toString();
  };
  
  return (
    <Card className="h-100 border-0 shadow-sm">
      <Card.Header className="bg-transparent border-bottom-0 py-3">
        <div className="d-flex justify-content-between align-items-center">
          <div className="d-flex align-items-center">
            <i className="bi bi-trophy me-2 text-warning"></i>
            <h5 className="card-title mb-0">Top Stocks</h5>
          </div>
          <Badge bg="info" className="py-1 px-2">
            <i className="bi bi-clock me-1"></i>
            Live Data
          </Badge>
        </div>
      </Card.Header>
      
      <Card.Body className="p-0">
        <Tab.Container id="top-stocks-tabs" activeKey={activeTab} onSelect={(k) => setActiveTab(k)}>
          <Nav variant="tabs" className="border-0">
            <Nav.Item>
              <Nav.Link eventKey="gainers" className="border-0 rounded-0">
                <i className="bi bi-graph-up-arrow me-1 text-success"></i>
                Top Gainers
              </Nav.Link>
            </Nav.Item>
            <Nav.Item>
              <Nav.Link eventKey="losers" className="border-0 rounded-0">
                <i className="bi bi-graph-down-arrow me-1 text-danger"></i>
                Top Losers
              </Nav.Link>
            </Nav.Item>
            <Nav.Item>
              <Nav.Link eventKey="mostActive" className="border-0 rounded-0">
                <i className="bi bi-activity me-1 text-primary"></i>
                Most Active
              </Nav.Link>
            </Nav.Item>
          </Nav>
          
          <Tab.Content>
            <Tab.Pane eventKey="gainers" className="p-0">
              <div className="table-responsive">
                <Table hover className="align-middle mb-0">
                  <thead className="small bg-light">
                    <tr>
                      <th>Stock</th>
                      <th className="text-end">Change %</th>
                      <th className="text-end">Volume</th>
                    </tr>
                  </thead>
                  <tbody>
                    {gainers.length > 0 ? gainers.slice(0, 5).map((stock) => (
                      <tr key={stock.ticker}>
                        <td>
                          <Link to={`/stocks/${stock.ticker}`} className="text-decoration-none">
                            <div className="fw-bold text-dark">{stock.ticker}</div>
                            <div className="small text-muted">{stock.name}</div>
                          </Link>
                        </td>
                        <td className="text-end">
                          <Badge bg="success" className="px-2 py-1">
                            {formatChange(stock.change)}
                          </Badge>
                        </td>
                        <td className="text-end">
                          <div className="text-muted small">{formatVolume(stock.volume)}</div>
                        </td>
                      </tr>
                    )) : (
                      <tr>
                        <td colSpan="3" className="text-center py-3 text-muted">
                          <i className="bi bi-info-circle me-2"></i>No data available
                        </td>
                      </tr>
                    )}
                  </tbody>
                </Table>
              </div>
            </Tab.Pane>
            
            <Tab.Pane eventKey="losers" className="p-0">
              <div className="table-responsive">
                <Table hover className="align-middle mb-0">
                  <thead className="small bg-light">
                    <tr>
                      <th>Stock</th>
                      <th className="text-end">Change %</th>
                      <th className="text-end">Volume</th>
                    </tr>
                  </thead>
                  <tbody>
                    {losers.length > 0 ? losers.slice(0, 5).map((stock) => (
                      <tr key={stock.ticker}>
                        <td>
                          <Link to={`/stocks/${stock.ticker}`} className="text-decoration-none">
                            <div className="fw-bold text-dark">{stock.ticker}</div>
                            <div className="small text-muted">{stock.name}</div>
                          </Link>
                        </td>
                        <td className="text-end">
                          <Badge bg="danger" className="px-2 py-1">
                            {formatChange(stock.change)}
                          </Badge>
                        </td>
                        <td className="text-end">
                          <div className="text-muted small">{formatVolume(stock.volume)}</div>
                        </td>
                      </tr>
                    )) : (
                      <tr>
                        <td colSpan="3" className="text-center py-3 text-muted">
                          <i className="bi bi-info-circle me-2"></i>No data available
                        </td>
                      </tr>
                    )}
                  </tbody>
                </Table>
              </div>
            </Tab.Pane>
            
            <Tab.Pane eventKey="mostActive" className="p-0">
              <div className="table-responsive">
                <Table hover className="align-middle mb-0">
                  <thead className="small bg-light">
                    <tr>
                      <th>Stock</th>
                      <th className="text-end">Change %</th>
                      <th className="text-end">Volume</th>
                    </tr>
                  </thead>
                  <tbody>
                    {mostActive.length > 0 ? mostActive.slice(0, 5).map((stock) => (
                      <tr key={stock.ticker}>
                        <td>
                          <Link to={`/stocks/${stock.ticker}`} className="text-decoration-none">
                            <div className="fw-bold text-dark">{stock.ticker}</div>
                            <div className="small text-muted">{stock.name}</div>
                          </Link>
                        </td>
                        <td className="text-end">
                          <Badge bg={stock.change >= 0 ? 'success' : 'danger'} className="px-2 py-1">
                            {formatChange(stock.change)}
                          </Badge>
                        </td>
                        <td className="text-end">
                          <div className="text-muted small">{formatVolume(stock.volume)}</div>
                        </td>
                      </tr>
                    )) : (
                      <tr>
                        <td colSpan="3" className="text-center py-3 text-muted">
                          <i className="bi bi-info-circle me-2"></i>No data available
                        </td>
                      </tr>
                    )}
                  </tbody>
                </Table>
              </div>
            </Tab.Pane>
          </Tab.Content>
        </Tab.Container>
      </Card.Body>
    </Card>
  );
};

export default TopStocksWidget;
