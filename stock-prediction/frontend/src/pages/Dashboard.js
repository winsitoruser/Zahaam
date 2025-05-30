import React, { useState, useEffect } from 'react';
import { Container, Row, Col, Card, Table, Spinner } from 'react-bootstrap';
import Chart from 'react-apexcharts';
import { fetchStocks, formatCurrency, formatNumber, getValueColor } from '../services/api';
import MarketNews from '../components/MarketNews';

const Dashboard = () => {
  const [marketData, setMarketData] = useState(null);
  const [topGainers, setTopGainers] = useState([]);
  const [topLosers, setTopLosers] = useState([]);
  const [marketIndex, setMarketIndex] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const loadDashboardData = async () => {
      try {
        setLoading(true);
        
        // Fetch list of stocks
        const data = await fetchStocks();
        
        // Simulate market data
        setMarketData({
          totalVolume: 5827491200,
          totalValue: 9327491000000,
          advancers: 189,
          decliners: 254,
          unchanged: 98,
          marketCap: 8452000000000000,
          lastUpdated: new Date().toISOString()
        });
        
        // Simulate top gainers
        setTopGainers([
          { ticker: "BBCA.JK", name: "Bank Central Asia", price: 9525, change: 325, percentChange: 3.53 },
          { ticker: "TLKM.JK", name: "Telkom Indonesia", price: 4120, change: 120, percentChange: 3.0 },
          { ticker: "BMRI.JK", name: "Bank Mandiri", price: 6250, change: 150, percentChange: 2.46 },
          { ticker: "ASII.JK", name: "Astra International", price: 5475, change: 125, percentChange: 2.34 },
          { ticker: "UNVR.JK", name: "Unilever Indonesia", price: 4750, change: 95, percentChange: 2.04 }
        ]);
        
        // Simulate top losers
        setTopLosers([
          { ticker: "EXCL.JK", name: "XL Axiata", price: 2150, change: -120, percentChange: -5.29 },
          { ticker: "KLBF.JK", name: "Kalbe Farma", price: 1475, change: -75, percentChange: -4.84 },
          { ticker: "ICBP.JK", name: "Indofood CBP", price: 8725, change: -275, percentChange: -3.06 },
          { ticker: "INDF.JK", name: "Indofood Sukses Makmur", price: 6150, change: -175, percentChange: -2.77 },
          { ticker: "BBRI.JK", name: "Bank Rakyat Indonesia", price: 4640, change: -110, percentChange: -2.32 }
        ]);
        
        // Simulate market index data
        setMarketIndex([
          { date: '2025-05-29', value: 7025.36 },
          { date: '2025-05-28', value: 6998.74 },
          { date: '2025-05-27', value: 7012.54 },
          { date: '2025-05-26', value: 6985.21 },
          { date: '2025-05-25', value: 6972.46 },
          { date: '2025-05-24', value: 6958.32 },
          { date: '2025-05-23', value: 6967.84 },
          { date: '2025-05-22', value: 6945.73 },
          { date: '2025-05-21', value: 6932.67 },
          { date: '2025-05-20', value: 6918.25 },
          { date: '2025-05-19', value: 6921.43 },
          { date: '2025-05-18', value: 6909.78 },
          { date: '2025-05-17', value: 6895.52 },
          { date: '2025-05-16', value: 6888.23 },
          { date: '2025-05-15', value: 6872.67 }
        ].reverse());
      } catch (error) {
        console.error('Error loading dashboard data:', error);
      } finally {
        setLoading(false);
      }
    };
    
    loadDashboardData();
  }, []);
  
  // Index chart options
  const indexChartOptions = {
    chart: {
      type: 'area',
      height: 250,
      toolbar: {
        show: false
      },
      zoom: {
        enabled: false
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      width: 3
    },
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.7,
        opacityTo: 0.2,
        stops: [0, 90, 100]
      }
    },
    xaxis: {
      type: 'datetime',
      categories: marketIndex.map(item => item.date),
      labels: {
        formatter: function(value) {
          return new Date(value).toLocaleDateString('id-ID', {
            month: 'short',
            day: 'numeric'
          });
        }
      },
      axisBorder: {
        show: false
      },
      axisTicks: {
        show: false
      }
    },
    yaxis: {
      labels: {
        formatter: function(value) {
          return value.toFixed(0);
        }
      }
    },
    tooltip: {
      x: {
        format: 'dd MMM yyyy'
      }
    },
    colors: ['#3f51b5']
  };
  
  const indexChartSeries = [
    {
      name: 'IDX Composite',
      data: marketIndex.map(item => item.value)
    }
  ];
  
  // Calculate index change
  const calculateIndexChange = () => {
    if (marketIndex.length < 2) return { value: 0, percent: 0 };
    
    const latest = marketIndex[marketIndex.length - 1].value;
    const previous = marketIndex[marketIndex.length - 2].value;
    const change = latest - previous;
    const percentChange = (change / previous) * 100;
    
    return {
      value: change,
      percent: percentChange
    };
  };
  
  const indexChange = calculateIndexChange();
  
  if (loading) {
    return (
      <div className="text-center py-5">
        <Spinner animation="border" variant="primary" />
        <p className="mt-3">Loading market data...</p>
      </div>
    );
  }
  
  return (
    <Container>
      <h3 className="mb-4">Market Overview</h3>
      
      {/* Market Index Chart */}
      <Card className="mb-4 dashboard-card">
        <Card.Body>
          <div className="d-flex justify-content-between align-items-center mb-3">
            <div>
              <h4 className="mb-0">IDX Composite</h4>
              <p className="text-muted mb-0">Indonesia Stock Exchange</p>
            </div>
            <div className="text-end">
              <h4 className="mb-0">{marketIndex.length > 0 ? marketIndex[marketIndex.length - 1].value.toFixed(2) : 'N/A'}</h4>
              <p className={`mb-0 ${indexChange.value >= 0 ? 'stock-trend-up' : 'stock-trend-down'}`}>
                {indexChange.value >= 0 ? '▲' : '▼'} {Math.abs(indexChange.value).toFixed(2)} ({Math.abs(indexChange.percent).toFixed(2)}%)
              </p>
            </div>
          </div>
          <Chart
            options={indexChartOptions}
            series={indexChartSeries}
            type="area"
            height={250}
          />
        </Card.Body>
      </Card>
      
      {/* Market Statistics */}
      <Row className="mb-4">
        <Col lg={3} md={6} className="mb-3">
          <Card className="h-100 dashboard-card">
            <Card.Body className="d-flex flex-column align-items-center justify-content-center p-4">
              <div className="dashboard-label">Total Volume</div>
              <div className="dashboard-value">{formatNumber(marketData?.totalVolume)}</div>
            </Card.Body>
          </Card>
        </Col>
        <Col lg={3} md={6} className="mb-3">
          <Card className="h-100 dashboard-card">
            <Card.Body className="d-flex flex-column align-items-center justify-content-center p-4">
              <div className="dashboard-label">Market Capitalization</div>
              <div className="dashboard-value">{formatCurrency(marketData?.marketCap, 'IDR')}</div>
            </Card.Body>
          </Card>
        </Col>
        <Col lg={3} md={6} className="mb-3">
          <Card className="h-100 dashboard-card">
            <Card.Body className="d-flex flex-column align-items-center justify-content-center p-4">
              <div className="dashboard-label">Advancers</div>
              <div className="dashboard-value text-success">{marketData?.advancers}</div>
            </Card.Body>
          </Card>
        </Col>
        <Col lg={3} md={6} className="mb-3">
          <Card className="h-100 dashboard-card">
            <Card.Body className="d-flex flex-column align-items-center justify-content-center p-4">
              <div className="dashboard-label">Decliners</div>
              <div className="dashboard-value text-danger">{marketData?.decliners}</div>
            </Card.Body>
          </Card>
        </Col>
      </Row>
      
      {/* Top Gainers & Losers */}
      <Row className="mb-4">
        <Col md={6} className="mb-3">
          <Card className="h-100 dashboard-card">
            <Card.Header className="bg-white">
              <h5 className="mb-0">Top Gainers</h5>
            </Card.Header>
            <Card.Body className="p-0">
              <Table className="market-overview-table mb-0">
                <thead>
                  <tr>
                    <th>Symbol</th>
                    <th>Price</th>
                    <th>Change</th>
                    <th>% Change</th>
                  </tr>
                </thead>
                <tbody>
                  {topGainers.map((stock, index) => (
                    <tr key={index}>
                      <td>
                        <div className="fw-bold">{stock.ticker}</div>
                        <div className="small text-muted">{stock.name}</div>
                      </td>
                      <td>{formatCurrency(stock.price, 'IDR')}</td>
                      <td className={getValueColor(stock.change)}>
                        {stock.change > 0 ? '+' : ''}{stock.change}
                      </td>
                      <td className={getValueColor(stock.percentChange)}>
                        {stock.percentChange > 0 ? '+' : ''}{stock.percentChange.toFixed(2)}%
                      </td>
                    </tr>
                  ))}
                </tbody>
              </Table>
            </Card.Body>
          </Card>
        </Col>
        <Col md={6} className="mb-3">
          <Card className="h-100 dashboard-card">
            <Card.Header className="bg-white">
              <h5 className="mb-0">Top Losers</h5>
            </Card.Header>
            <Card.Body className="p-0">
              <Table className="market-overview-table mb-0">
                <thead>
                  <tr>
                    <th>Symbol</th>
                    <th>Price</th>
                    <th>Change</th>
                    <th>% Change</th>
                  </tr>
                </thead>
                <tbody>
                  {topLosers.map((stock, index) => (
                    <tr key={index}>
                      <td>
                        <div className="fw-bold">{stock.ticker}</div>
                        <div className="small text-muted">{stock.name}</div>
                      </td>
                      <td>{formatCurrency(stock.price, 'IDR')}</td>
                      <td className={getValueColor(stock.change)}>
                        {stock.change}
                      </td>
                      <td className={getValueColor(stock.percentChange)}>
                        {stock.percentChange.toFixed(2)}%
                      </td>
                    </tr>
                  ))}
                </tbody>
              </Table>
            </Card.Body>
          </Card>
        </Col>
      </Row>
      
      {/* Market News */}
      <div className="mb-4">
        <h4 className="mb-3">Market News</h4>
        <MarketNews />
      </div>
    </Container>
  );
};

export default Dashboard;
