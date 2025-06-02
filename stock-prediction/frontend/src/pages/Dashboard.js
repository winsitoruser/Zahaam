import React, { useState, useEffect } from 'react';
import { Container, Row, Col, Card, Button, Alert, Form, Spinner, Table, Badge, InputGroup, OverlayTrigger, Tooltip } from 'react-bootstrap';
import '../components/dashboard/dashboard.css';
import './futuristic-dashboard.css'; // Import CSS baru untuk dashboard futuristik
import { fetchStocks } from '../services/api';
import MarketNews from '../components/MarketNews';

// Import komponen dashboard baru
import MarketOverview from '../components/dashboard/MarketOverview';
import SectorOverview from '../components/dashboard/SectorOverview';
import TopStocksWidget from '../components/dashboard/TopStocksWidget';
import StockTable from '../components/dashboard/StockTable';

// Helper functions
const formatNumber = (num) => {
  return new Intl.NumberFormat('id-ID').format(num);
};

const formatCurrency = (num) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
};

const getValueColor = (value) => {
  if (value > 0) return 'text-success';
  if (value < 0) return 'text-danger';
  return 'text-secondary';
};

const Dashboard = () => {
  const [topGainers, setTopGainers] = useState([]);
  const [topLosers, setTopLosers] = useState([]);
  const [marketIndex, setMarketIndex] = useState([]);
  const [loading, setLoading] = useState(true);

  // State untuk data sektor dan daftar saham
  const [sectors, setSectors] = useState([]);
  const [allStocks, setAllStocks] = useState([]);
  const [mostActive, setMostActive] = useState([]);
  const [ihsgData, setIhsgData] = useState([]);
  const [marketSummary, setMarketSummary] = useState({
    ihsg: 7025.36,
    change: 0.38,
    volume: 9827491200,
    value: 9327491000000,
    frequency: 983425,
    marketCap: 8452000000000000,
    advancing: 189,
    declining: 254,
    unchanged: 98
  });

  useEffect(() => {
    const loadDashboardData = async () => {
      try {
        setLoading(true);
        
        // Fetch list of stocks dari API
        const data = await fetchStocks();
        
        // Menghasilkan data dummy untuk pengembangan UI
        // Dalam implementasi sebenarnya, data ini akan diambil dari API
        
        // Data saham
        const stocksData = [
          { ticker: 'BBCA', name: 'Bank Central Asia Tbk', price: 9525, change: 3.53, volume: 12500000, sector: 'Keuangan' },
          { ticker: 'BBRI', name: 'Bank Rakyat Indonesia Tbk', price: 4640, change: 2.12, volume: 18750000, sector: 'Keuangan' },
          { ticker: 'BMRI', name: 'Bank Mandiri Tbk', price: 6250, change: 2.46, volume: 9870000, sector: 'Keuangan' },
          { ticker: 'TLKM', name: 'Telkom Indonesia Tbk', price: 4120, change: 3.0, volume: 7650000, sector: 'Telekomunikasi' },
          { ticker: 'ASII', name: 'Astra International Tbk', price: 5475, change: 2.34, volume: 8920000, sector: 'Otomotif & Komponen' },
          { ticker: 'UNVR', name: 'Unilever Indonesia Tbk', price: 4750, change: 2.04, volume: 5430000, sector: 'Barang Konsumen' },
          { ticker: 'ICBP', name: 'Indofood CBP Sukses Makmur Tbk', price: 8725, change: -3.06, volume: 3560000, sector: 'Barang Konsumen' },
          { ticker: 'INDF', name: 'Indofood Sukses Makmur Tbk', price: 6150, change: -2.77, volume: 4250000, sector: 'Barang Konsumen' },
          { ticker: 'EXCL', name: 'XL Axiata Tbk', price: 2150, change: -5.29, volume: 6780000, sector: 'Telekomunikasi' },
          { ticker: 'KLBF', name: 'Kalbe Farma Tbk', price: 1475, change: -4.84, volume: 9870000, sector: 'Kesehatan' },
          { ticker: 'ANTM', name: 'Aneka Tambang Tbk', price: 2340, change: 1.75, volume: 10250000, sector: 'Pertambangan' },
          { ticker: 'PTBA', name: 'Bukit Asam Tbk', price: 3150, change: -1.25, volume: 7650000, sector: 'Pertambangan' },
          { ticker: 'INCO', name: 'Vale Indonesia Tbk', price: 5250, change: 0.86, volume: 5430000, sector: 'Pertambangan' },
          { ticker: 'SMGR', name: 'Semen Indonesia Tbk', price: 8150, change: -0.98, volume: 3560000, sector: 'Industri Dasar' },
          { ticker: 'JPFA', name: 'Japfa Comfeed Indonesia Tbk', price: 1590, change: 2.58, volume: 6780000, sector: 'Agrikultur' },
          { ticker: 'CPIN', name: 'Charoen Pokphand Indonesia Tbk', price: 5125, change: 1.79, volume: 4250000, sector: 'Agrikultur' },
          { ticker: 'INKP', name: 'Indah Kiat Pulp & Paper Tbk', price: 7850, change: -1.51, volume: 2340000, sector: 'Industri Dasar' },
          { ticker: 'TPIA', name: 'Chandra Asri Pacific Tbk', price: 10250, change: 3.12, volume: 1890000, sector: 'Industri Dasar' },
          { ticker: 'ERAA', name: 'Erajaya Swasembada Tbk', price: 825, change: -2.37, volume: 7890000, sector: 'Perdagangan & Jasa' },
          { ticker: 'MNCN', name: 'Media Nusantara Citra Tbk', price: 1240, change: 0.81, volume: 6540000, sector: 'Perdagangan & Jasa' },
          // Tambahkan lebih banyak saham untuk demo
          { ticker: 'PGAS', name: 'Perusahaan Gas Negara Tbk', price: 1570, change: 1.29, volume: 8760000, sector: 'Infrastruktur' },
          { ticker: 'JSMR', name: 'Jasa Marga Tbk', price: 4020, change: -0.74, volume: 3290000, sector: 'Infrastruktur' },
          { ticker: 'AKRA', name: 'AKR Corporindo Tbk', price: 1285, change: 2.15, volume: 7650000, sector: 'Perdagangan & Jasa' },
          { ticker: 'SCMA', name: 'Surya Citra Media Tbk', price: 1660, change: -1.19, volume: 4560000, sector: 'Perdagangan & Jasa' },
          { ticker: 'BRPT', name: 'Barito Pacific Tbk', price: 985, change: 3.68, volume: 9870000, sector: 'Industri Dasar' },
          { ticker: 'ITMG', name: 'Indo Tambangraya Megah Tbk', price: 16250, change: -2.41, volume: 2450000, sector: 'Pertambangan' },
          { ticker: 'INTP', name: 'Indocement Tunggal Prakarsa Tbk', price: 9750, change: 0.93, volume: 2750000, sector: 'Industri Dasar' },
          { ticker: 'UNTR', name: 'United Tractors Tbk', price: 27500, change: 1.85, volume: 1980000, sector: 'Perdagangan & Jasa' },
          { ticker: 'BSDE', name: 'Bumi Serpong Damai Tbk', price: 1120, change: -1.32, volume: 8540000, sector: 'Properti & Real Estate' },
          { ticker: 'SMRA', name: 'Summarecon Agung Tbk', price: 890, change: 2.30, volume: 6580000, sector: 'Properti & Real Estate' }
        ];
        
        // Menyimpan data saham
        setAllStocks(stocksData);
        
        // Menambahkan properti percentChange ke setiap saham
        stocksData.forEach(stock => {
          // Menghitung percentChange berdasarkan price dan change
          stock.percentChange = (stock.change / stock.price) * 100;
        });
        
        // Top Gainers - diurutkan berdasarkan perubahan harga (positif)
        const gainers = [...stocksData]
          .filter(stock => stock.change > 0)
          .sort((a, b) => b.change - a.change)
          .slice(0, 5);
        setTopGainers(gainers);
        
        // Top Losers - diurutkan berdasarkan perubahan harga (negatif)
        const losers = [...stocksData]
          .filter(stock => stock.change < 0)
          .sort((a, b) => a.change - b.change)
          .slice(0, 5);
        setTopLosers(losers);
        
        // Most Active - diurutkan berdasarkan volume
        const active = [...stocksData]
          .sort((a, b) => b.volume - a.volume)
          .slice(0, 5);
        setMostActive(active);
        
        // Agregasi data sektor
        const sectorMap = {};
        stocksData.forEach(stock => {
          if (!sectorMap[stock.sector]) {
            sectorMap[stock.sector] = {
              name: stock.sector,
              stocks: [],
              performance: 0,
              stockCount: 0,
              totalMarketCap: 0,
              percentage: 0
            };
          }
          sectorMap[stock.sector].stocks.push(stock);
          sectorMap[stock.sector].stockCount++;
          // Menghitung rata-rata performa sektor
          sectorMap[stock.sector].performance += stock.change;
        });
        
        // Mengolah data sektor
        let sectorsData = Object.values(sectorMap);
        
        // Definisi warna untuk masing-masing sektor
        const sectorColors = {
          'Keuangan': '#2E86C1',
          'Telekomunikasi': '#3498DB',
          'Otomotif & Komponen': '#5DADE2',
          'Barang Konsumen': '#85C1E9',
          'Kesehatan': '#AED6F1',
          'Pertambangan': '#17A589',
          'Industri Dasar': '#1ABC9C',
          'Agrikultur': '#48C9B0',
          'Perdagangan & Jasa': '#76D7C4',
          'Infrastruktur': '#A3E4D7',
          'Properti & Real Estate': '#D4AC0D'
        };
        
        sectorsData.forEach(sector => {
          sector.performance = sector.performance / sector.stockCount;
          sector.percentage = Math.round((sector.stockCount / stocksData.length) * 100);
          sector.color = sectorColors[sector.name] || '#F1C40F'; // Assign warna ke sektor
        });
        
        setSectors(sectorsData);
        
        // Simulasi data IHSG untuk chart
        const ihsgHistorical = [
          { x: new Date('2025-05-15').getTime(), y: 6872.67 },
          { x: new Date('2025-05-16').getTime(), y: 6888.23 },
          { x: new Date('2025-05-17').getTime(), y: 6895.52 },
          { x: new Date('2025-05-18').getTime(), y: 6909.78 },
          { x: new Date('2025-05-19').getTime(), y: 6921.43 },
          { x: new Date('2025-05-20').getTime(), y: 6918.25 },
          { x: new Date('2025-05-21').getTime(), y: 6932.67 },
          { x: new Date('2025-05-22').getTime(), y: 6945.73 },
          { x: new Date('2025-05-23').getTime(), y: 6967.84 },
          { x: new Date('2025-05-24').getTime(), y: 6958.32 },
          { x: new Date('2025-05-25').getTime(), y: 6972.46 },
          { x: new Date('2025-05-26').getTime(), y: 6985.21 },
          { x: new Date('2025-05-27').getTime(), y: 7012.54 },
          { x: new Date('2025-05-28').getTime(), y: 6998.74 },
          { x: new Date('2025-05-29').getTime(), y: 7025.36 }
        ];
        
        setIhsgData(ihsgHistorical.map(item => item.y));
        setMarketIndex(ihsgHistorical);
        
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
        <Spinner animation="border" role="status" className="pulse-animation">
          <span className="visually-hidden">Loading...</span>
        </Spinner>
      </div>
    );
  }
  
  return (
    <div className="dashboard-container dashboard-futuristic">
      {/* Header Floating Bar */}
      <div className="dashboard-header-floating">
        <Container fluid>
          <Row className="align-items-center">
            <Col md={6} className="d-flex align-items-center">
              <div className="dashboard-logo">
                <i className="bi bi-graph-up-arrow"></i>
              </div>
              <div className="ms-3">
                <h1 className="dashboard-title mb-0">NEXUS TRADE</h1>
                <div className="dashboard-subtitle">Advanced Market Intelligence</div>
              </div>
            </Col>
            <Col md={6}>
              <div className="d-flex align-items-center justify-content-end">
                <div className="dashboard-date-badge me-3">
                  <i className="bi bi-calendar3 me-2"></i>
                  {new Date().toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                  })}
                </div>
                <div className="search-container">
                  <InputGroup>
                    <InputGroup.Text className="search-icon">
                      <i className="bi bi-search"></i>
                    </InputGroup.Text>
                    <Form.Control 
                      placeholder="Search ticker or company..." 
                      className="search-input"
                      aria-label="Search stocks"
                    />
                  </InputGroup>
                </div>
              </div>
            </Col>
          </Row>
        </Container>
      </div>

      <Container fluid className="dashboard-content">
        {/* Market Pulse Section */}
        <div className="dashboard-section market-pulse-section">
          <div className="section-header">
            <h2 className="section-title">Market Pulse</h2>
            <div className="section-subtitle">Real-time market insights</div>
          </div>
          
          <Row className="g-4">
            {/* Market Overview Card - Enhanced */}
            <Col lg={5} md={12}>
              <div className="futuristic-card primary-card">
                <div className="card-glow"></div>
                <div className="card-content h-100">
                  <MarketOverview ihsgData={ihsgData} marketSummary={marketSummary} />
                </div>
              </div>
            </Col>
            
            {/* Sector Overview - Enhanced */}
            <Col lg={7} md={12}>
              <div className="futuristic-card secondary-card">
                <div className="card-glow"></div>
                <div className="card-content h-100">
                  <SectorOverview sectors={sectors} />
                </div>
              </div>
            </Col>
          </Row>
        </div>
        
        {/* Market Leaders Section */}
        <div className="dashboard-section market-leaders-section mt-5">
          <div className="section-header">
            <h2 className="section-title">Market Leaders</h2>
            <div className="section-subtitle">Top performing stocks and market movers</div>
          </div>
          
          <div className="futuristic-card accent-card">
            <div className="card-glow"></div>
            <div className="card-content">
              <TopStocksWidget gainers={topGainers} losers={topLosers} mostActive={mostActive} />
            </div>
          </div>
        </div>
        
        {/* Market Data Grid Section */}
        <div className="dashboard-section market-data-section mt-5">
          <div className="section-header with-actions">
            <div>
              <h2 className="section-title">Market Data</h2>
              <div className="section-subtitle">Comprehensive stock performance data</div>
            </div>
            <div className="section-actions">
              <OverlayTrigger placement="top" overlay={<Tooltip>Export data to CSV</Tooltip>}>
                <Button variant="outline-light" size="sm" className="action-button">
                  <i className="bi bi-download me-2"></i>Export
                </Button>
              </OverlayTrigger>
              <OverlayTrigger placement="top" overlay={<Tooltip>Filter market data</Tooltip>}>
                <Button variant="outline-light" size="sm" className="action-button ms-2">
                  <i className="bi bi-funnel me-2"></i>Filter
                </Button>
              </OverlayTrigger>
            </div>
          </div>
          
          <div className="futuristic-card data-card">
            <div className="card-glow"></div>
            <div className="card-content p-0">
              <StockTable stocks={allStocks} />
            </div>
          </div>
        </div>
        
        {/* Market Intelligence Section */}
        <div className="dashboard-section market-news-section mt-5 mb-5">
          <div className="section-header">
            <h2 className="section-title">Market Intelligence</h2>
            <div className="section-subtitle">Latest news and market analysis</div>
          </div>
          
          <div className="futuristic-card info-card">
            <div className="card-glow"></div>
            <div className="card-content">
              <MarketNews />
            </div>
          </div>
        </div>
        
        {/* Dashboard Footer */}
        <div className="dashboard-footer">
          <div className="footer-branding">
            <span className="footer-logo"><i className="bi bi-graph-up-arrow"></i> NEXUS TRADE</span>
            <span className="footer-tagline">Real-time market intelligence platform</span>
          </div>
          <div className="footer-status">
            <Badge bg="success" pill className="status-badge">
              <i className="bi bi-broadcast me-1"></i> Live Data
            </Badge>
            <span className="footer-timestamp">Last updated: {new Date().toLocaleTimeString()}</span>
          </div>
        </div>
      </Container>
      
      {/* Floating Action Button */}
      <div className="floating-action-button">
        <OverlayTrigger placement="left" overlay={<Tooltip>Quick actions</Tooltip>}>
          <Button className="rounded-circle pulse-button">
            <i className="bi bi-lightning-fill"></i>
          </Button>
        </OverlayTrigger>
      </div>
      
      {/* Ambient Background Elements */}
      <div className="ambient-elements">
        <div className="ambient-circle circle-1"></div>
        <div className="ambient-circle circle-2"></div>
        <div className="ambient-circle circle-3"></div>
        <div className="ambient-line line-1"></div>
        <div className="ambient-line line-2"></div>
        <div className="ambient-dot dot-grid"></div>
      </div>
    </div>
  );
};

export default Dashboard;
