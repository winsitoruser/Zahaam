import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, Link, Navigate } from 'react-router-dom';
import { Container, Row, Col, Navbar, Nav, Spinner, Alert } from 'react-bootstrap';
import Dashboard from './pages/Dashboard';
import Prediction from './pages/Prediction';
import MyWatchlist from './pages/MyWatchlist';
import 'bootstrap/dist/css/bootstrap.min.css';
import './App.css';

function App() {
  return (
    <Router>
      <div className="App">
        <Navbar bg="dark" variant="dark" expand="lg" sticky="top" className="mb-4">
          <Container>
            <Navbar.Brand as={Link} to="/">
              <i className="bx bx-line-chart me-2"></i>
              Indonesian Stock Market Prediction
            </Navbar.Brand>
            <Navbar.Toggle aria-controls="basic-navbar-nav" />
            <Navbar.Collapse id="basic-navbar-nav">
              <Nav className="me-auto">
                <Nav.Link as={Link} to="/dashboard">Dashboard</Nav.Link>
                <Nav.Link as={Link} to="/stocks">Stocks</Nav.Link>
                <Nav.Link as={Link} to="/portfolio">Portfolio</Nav.Link>
                <Nav.Link as={Link} to="/prediction">Prediction</Nav.Link>
                <Nav.Link as={Link} to="/my-strategies">Strategi Saya</Nav.Link>
                <Nav.Link as={Link} to="/my-watchlist">My Watchlist</Nav.Link>
              </Nav>
            </Navbar.Collapse>
          </Container>
        </Navbar>

        <div className="main-content">
          <Routes>
            <Route path="/" element={<Navigate to="/dashboard" />} />
            <Route path="/dashboard" element={<Dashboard />} />
            <Route path="/prediction" element={<Prediction />} />
            <Route path="/my-watchlist" element={<MyWatchlist />} />
            <Route path="*" element={<Dashboard />} />
          </Routes>
        </div>
        
        <footer className="bg-dark text-white mt-5 py-4">
          <Container>
            <div className="text-center">
              <p className="mb-0">© {new Date().getFullYear()} Indonesian Stock Market Prediction. All rights reserved.</p>
            </div>
          </Container>
        </footer>
      </div>
    </Router>
  );
}

// Stocks Page Component (moved from the main App)
function StocksPage() {
  const [stocks, setStocks] = useState([]);
  const [selectedStock, setSelectedStock] = useState(null);
  const [stockData, setStockData] = useState(null);
  const [technicalSignals, setTechnicalSignals] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    // Load available stocks
    const loadStocks = async () => {
      try {
        setLoading(true);
        const data = await fetchStocks();
        setStocks(data.stocks);
        // Select the first stock by default
        if (data.stocks.length > 0) {
          handleStockSelect(data.stocks[0]);
        }
      } catch (err) {
        setError('Failed to load stocks. Please try again later.');
        console.error('Error loading stocks:', err);
      } finally {
        setLoading(false);
      }
    };

    loadStocks();
  }, []);

  const handleStockSelect = async (ticker) => {
    try {
      setLoading(true);
      setSelectedStock(ticker);
      setError(null);
      
      // Fetch stock data and technical signals in parallel
      const [data, signals] = await Promise.all([
        fetchStockData(ticker),
        fetchTechnicalSignals(ticker)
      ]);
      
      setStockData(data);
      setTechnicalSignals(signals);
    } catch (err) {
      setError(`Failed to load data for ${ticker}. Please try again.`);
      console.error(`Error loading data for ${ticker}:`, err);
    } finally {
      setLoading(false);
    }
  };

  return (
    <Container>
      {error && (
        <Alert variant="danger" className="mb-4">
          {error}
        </Alert>
      )}
      
      <Row>
        <Col md={3} className="mb-4">
          <div className="card h-100">
            <div className="card-header bg-primary text-white">
              <h5 className="mb-0">Indonesian Stocks</h5>
            </div>
            <div className="card-body p-0">
              {loading && !selectedStock ? (
                <div className="text-center p-4">
                  <Spinner animation="border" variant="primary" />
                </div>
              ) : (
                <StockList 
                  stocks={stocks} 
                  selectedStock={selectedStock} 
                  onSelect={handleStockSelect} 
                />
              )}
            </div>
          </div>
        </Col>
        
        <Col md={9}>
          {loading && selectedStock ? (
            <div className="text-center p-5">
              <Spinner animation="border" variant="primary" />
              <p className="mt-2">Loading {selectedStock} data...</p>
            </div>
          ) : (
            <>
              {stockData && (
                <div className="mb-4">
                  <StockDetail stockData={stockData} />
                </div>
              )}
              
              {technicalSignals && (
                <div className="mb-4">
                  <TechnicalAnalysis signals={technicalSignals} />
                </div>
              )}
            </>
          )}
        </Col>
      </Row>
    </Container>
  );
}

export default App;
