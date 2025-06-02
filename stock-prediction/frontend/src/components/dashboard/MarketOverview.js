import React from 'react';
import { Card, Row, Col, Badge, ProgressBar, OverlayTrigger, Tooltip } from 'react-bootstrap';
import Chart from 'react-apexcharts';

const MarketOverview = ({ ihsgData, marketSummary }) => {
  // Memastikan data valid
  const safeMarketSummary = marketSummary || {
    ihsg: 7250.82,
    change: 0.75,
    volume: 12453000000,
    value: 9875000000000,
    frequency: 785400,
    marketCap: 9784500000000000,
    advancing: 217,
    declining: 304,
    unchanged: 142
  };
  
  // Menghitung persentase saham naik/turun/tetap
  const totalStocks = safeMarketSummary.advancing + safeMarketSummary.declining + safeMarketSummary.unchanged;
  const advancingPercentage = (safeMarketSummary.advancing / totalStocks) * 100;
  const decliningPercentage = (safeMarketSummary.declining / totalStocks) * 100;
  const unchangedPercentage = (safeMarketSummary.unchanged / totalStocks) * 100;
  
  // Format angka untuk menampilkan ke UI
  const formatNumber = (num, suffix = '') => {
    if (num === null || num === undefined || isNaN(num)) return '0';
    return num.toLocaleString('id-ID') + suffix;
  };
  
  const formatLargeNumber = (num) => {
    if (num === null || num === undefined || isNaN(num)) return '0';
    if (num >= 1000000000000) return (num / 1000000000000).toFixed(2) + 'T';
    if (num >= 1000000000) return (num / 1000000000).toFixed(2) + 'B';
    if (num >= 1000000) return (num / 1000000).toFixed(2) + 'M';
    if (num >= 1000) return (num / 1000).toFixed(2) + 'K';
    return num.toFixed(2);
  };
  
  // Chart options untuk area chart
  const chartOptions = {
    chart: {
      type: 'area',
      height: 180,
      sparkline: {
        enabled: true
      },
      toolbar: {
        show: false
      },
      background: 'transparent'
    },
    colors: [safeMarketSummary.change >= 0 ? '#28a745' : '#dc3545'],
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.7,
        opacityTo: 0.3,
        stops: [0, 90, 100]
      }
    },
    stroke: {
      curve: 'smooth',
      width: 2
    },
    grid: {
      padding: {
        left: 0,
        right: 0,
        top: 10,
        bottom: 0
      }
    },
    tooltip: {
      fixed: {
        enabled: false
      },
      x: {
        show: false
      },
      y: {
        title: {
          formatter: function() {
            return 'IHSG:';
          }
        },
        formatter: function(value) {
          return value.toLocaleString('id-ID');
        }
      },
      marker: {
        show: true
      }
    },
    dataLabels: {
      enabled: false
    }
  };

  return (
    <Card className="h-100 border-0 shadow-sm">
      <Card.Header className="d-flex justify-content-between align-items-center bg-transparent border-bottom-0 py-3">
        <div className="d-flex align-items-center">
          <i className="bi bi-graph-up me-2 text-primary"></i>
          <h5 className="card-title mb-0">Market Overview</h5>
        </div>
        <Badge bg={safeMarketSummary.change >= 0 ? 'success' : 'danger'} className="d-flex align-items-center px-2 py-1">
          <i className={`bi bi-arrow-${safeMarketSummary.change >= 0 ? 'up' : 'down'} me-1`}></i>
          IHSG: {formatNumber(safeMarketSummary.ihsg)}
        </Badge>
      </Card.Header>
      
      <Card.Body className="p-0">
        <Row className="g-0">
          <Col lg={5} md={12} className="p-3 border-end">
            <div className="mb-3">
              <div className="d-flex justify-content-between align-items-center mb-2">
                <small className="text-muted">Market Status</small>
                <Badge bg={safeMarketSummary.change >= 0 ? 'success' : 'danger'}>
                  {safeMarketSummary.change >= 0 ? 'Bullish' : 'Bearish'}
                </Badge>
              </div>
              <div className="d-flex justify-content-between align-items-center">
                <h5 className="mb-0">Change</h5>
                <h5 className={`mb-0 ${safeMarketSummary.change >= 0 ? 'text-success' : 'text-danger'}`}>
                  {safeMarketSummary.change >= 0 ? '+' : ''}{formatNumber(safeMarketSummary.change)}
                  <small className="ms-1">pts</small>
                </h5>
              </div>
            </div>
            
            <Row className="g-2 mb-3">
              <Col xs={6}>
                <OverlayTrigger placement="top" overlay={<Tooltip>Total trading volume</Tooltip>}>
                  <div className="p-2 border rounded text-center">
                    <div className="text-primary mb-1"><i className="bi bi-bar-chart-fill"></i></div>
                    <div className="small text-muted">Volume</div>
                    <div className="fw-bold">{formatLargeNumber(safeMarketSummary.volume)}</div>
                  </div>
                </OverlayTrigger>
              </Col>
              <Col xs={6}>
                <OverlayTrigger placement="top" overlay={<Tooltip>Total trading value</Tooltip>}>
                  <div className="p-2 border rounded text-center">
                    <div className="text-primary mb-1"><i className="bi bi-cash-stack"></i></div>
                    <div className="small text-muted">Nilai</div>
                    <div className="fw-bold">{formatLargeNumber(safeMarketSummary.value)}</div>
                  </div>
                </OverlayTrigger>
              </Col>
              <Col xs={6}>
                <OverlayTrigger placement="top" overlay={<Tooltip>Trading frequency</Tooltip>}>
                  <div className="p-2 border rounded text-center">
                    <div className="text-primary mb-1"><i className="bi bi-activity"></i></div>
                    <div className="small text-muted">Frekuensi</div>
                    <div className="fw-bold">{formatLargeNumber(safeMarketSummary.frequency)}</div>
                  </div>
                </OverlayTrigger>
              </Col>
              <Col xs={6}>
                <OverlayTrigger placement="top" overlay={<Tooltip>Total market capitalization</Tooltip>}>
                  <div className="p-2 border rounded text-center">
                    <div className="text-primary mb-1"><i className="bi bi-building"></i></div>
                    <div className="small text-muted">Market Cap</div>
                    <div className="fw-bold">{formatLargeNumber(safeMarketSummary.marketCap)}</div>
                  </div>
                </OverlayTrigger>
              </Col>
            </Row>
          </Col>
          
          <Col lg={7} md={12} className="p-3">
            <div className="mb-3">
              <h6 className="small text-muted mb-2">IHSG Performance</h6>
              <div className="position-relative" style={{ height: '170px' }}>
                <Chart 
                  options={chartOptions}
                  series={[{
                    name: 'IHSG',
                    data: ihsgData || [7150, 7180, 7220, 7200, 7250, 7240, 7230, 7260, 7300, 7280, 7260, 7250]
                  }]}
                  type="area"
                  height={170}
                />
              </div>
              <div className="d-flex justify-content-between mt-2 small">
                <div className="text-muted">
                  <span className="badge bg-light text-dark me-1">H</span>
                  {formatNumber(Math.max(...(ihsgData || [7500])))}
                </div>
                <div className="text-muted">
                  <span className="badge bg-light text-dark me-1">L</span>
                  {formatNumber(Math.min(...(ihsgData || [7000])))}
                </div>
              </div>
            </div>
            
            <div className="mt-3">
              <div className="d-flex justify-content-between align-items-center mb-2">
                <h6 className="small text-muted mb-0">
                  <i className="bi bi-pie-chart-fill me-1"></i>
                  Market Breadth
                </h6>
                <OverlayTrigger placement="top" overlay={<Tooltip>Distribution of advancing, unchanged, and declining stocks</Tooltip>}>
                  <i className="bi bi-info-circle text-muted small"></i>
                </OverlayTrigger>
              </div>
              
              <ProgressBar className="mb-2">
                <ProgressBar variant="success" now={advancingPercentage} key={1} />
                <ProgressBar variant="secondary" now={unchangedPercentage} key={2} />
                <ProgressBar variant="danger" now={decliningPercentage} key={3} />
              </ProgressBar>
              
              <Row className="text-center small">
                <Col xs={4}>
                  <div className="text-success fw-bold">{safeMarketSummary.advancing}</div>
                  <div className="text-muted">Advancing</div>
                </Col>
                <Col xs={4}>
                  <div className="text-secondary fw-bold">{safeMarketSummary.unchanged}</div>
                  <div className="text-muted">Unchanged</div>
                </Col>
                <Col xs={4}>
                  <div className="text-danger fw-bold">{safeMarketSummary.declining}</div>
                  <div className="text-muted">Declining</div>
                </Col>
              </Row>
            </div>
          </Col>
        </Row>
      </Card.Body>
    </Card>
  );
};

export default MarketOverview;
