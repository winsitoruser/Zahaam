import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Dashboard from './pages/Dashboard';
import Prediction from './pages/Prediction';
import MyWatchlist from './pages/MyWatchlist';
import StockAnalysis from './pages/StockAnalysis';
import Portfolio from './pages/Portfolio';
import MyStrategies from './pages/MyStrategies';
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
