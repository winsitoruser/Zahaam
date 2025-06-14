import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { Container, Row, Col, Card, Alert, Spinner } from 'react-bootstrap';
import Dashboard from './pages/Dashboard';
import Prediction from './pages/Prediction';
import MyWatchlist from './pages/MyWatchlist';
import StockAnalysis from './pages/StockAnalysis';
import Portfolio from './pages/Portfolio';
import MyStrategies from './pages/MyStrategies';
import AdminPanel from './admin/AdminPanel';
import Layout from './components/layout/Layout';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import './App.css';
import './styles/custom.css';

function App() {
  return (
    <Router>
      <Layout>
        <Routes>
          <Route path="/" element={<Navigate to="/dashboard" />} />
          <Route 
            path="/dashboard" 
            element={<Dashboard />} 
          />
          <Route 
            path="/stocks" 
            element={<StockAnalysis />} 
          />
          <Route 
            path="/stocks/:symbol" 
            element={<StockAnalysis />} 
          />
          <Route 
            path="/portfolio" 
            element={<Portfolio />} 
          />
          <Route 
            path="/prediction" 
            element={<Prediction />} 
          />
          <Route 
            path="/my-strategies" 
            element={<MyStrategies />} 
          />
          <Route 
            path="/my-watchlist" 
            element={<MyWatchlist />} 
          />
          <Route 
            path="/stock-details" 
            element={<StockAnalysis />} 
          />
          <Route 
            path="/stock-list" 
            element={<StockAnalysis />} 
          />
          <Route 
            path="/watchlist" 
            element={<MyWatchlist />} 
          />
          <Route 
            path="/strategy" 
            element={<MyStrategies />} 
          />
          <Route 
            path="/admin" 
            element={<AdminPanel />} 
          />
          <Route 
            path="*" 
            element={<Dashboard />} 
          />
        </Routes>
      </Layout>
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

  // Fetch stocks function
  const fetchStocks = async () => {
    try {
      const response = await fetch('/api/stocks');
      if (!response.ok) throw new Error('Failed to fetch stocks');
      const data = await response.json();
      return data;
    } catch (err) {
      console.error('Error fetching stocks:', err);
      setError('Failed to load stocks. Please try again.');
      return [];
    }
  };

  // Fetch stock data function
  const fetchStockData = async (symbol) => {
    try {
      const response = await fetch(`/api/stocks/${symbol}`);
      if (!response.ok) throw new Error('Failed to fetch stock data');
      const data = await response.json();
      return data;
    } catch (err) {
      console.error('Error fetching stock data:', err);
      setError('Failed to load stock data. Please try again.');
      return null;
    }
  };

  // Fetch technical signals function
  const fetchTechnicalSignals = async (symbol) => {
    try {
      const response = await fetch(`/api/stocks/${symbol}/technical`);
      if (!response.ok) throw new Error('Failed to fetch technical signals');
      const data = await response.json();
      return data;
    } catch (err) {
      console.error('Error fetching technical signals:', err);
      setError('Failed to load technical signals. Please try again.');
      return null;
    }
  };

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
        <Col md={3}>
          <Card className="mb-4">
            <Card.Header>Available Stocks</Card.Header>
            <Card.Body>
              {loading ? (
                <Spinner animation="border" role="status">
                  <span className="visually-hidden">Loading...</span>
                </Spinner>
              ) : (
                <div className="stock-list">
                  {stocks.map(stock => (
                    <div 
                      key={stock.symbol} 
                      className="stock-item" 
                      onClick={() => setSelectedStock(stock)}
                    >
                      {stock.symbol} - {stock.name}
                    </div>
                  ))}
                </div>
              )}
            </Card.Body>
          </Card>
        </Col>

        <Col md={9}>
          {selectedStock ? (
            loading ? (
              <Spinner animation="border" role="status">
                <span className="visually-hidden">Loading...</span>
              </Spinner>
            ) : (
              <div>
                {stockData && (
                  <Card className="mb-4">
                    <Card.Header>{selectedStock.symbol} Details</Card.Header>
                    <Card.Body>
                      <pre>{JSON.stringify(stockData, null, 2)}</pre>
                    </Card.Body>
                  </Card>
                )}
                
                {technicalSignals && (
                  <Card className="mb-4">
                    <Card.Header>Technical Analysis</Card.Header>
                    <Card.Body>
                      <pre>{JSON.stringify(technicalSignals, null, 2)}</pre>
                    </Card.Body>
                  </Card>
                )}
              </div>
            )
          ) : (
            <div className="text-center p-5">
              <h3>Select a stock from the list to view details</h3>
            </div>
          )}
        </Col>
      </Row>
    </Container>
  );
}

export default App;
