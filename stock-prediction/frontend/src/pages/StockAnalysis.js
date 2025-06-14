import React, { useState, useEffect, useContext } from 'react';
import { Container, Row, Col, Form, Button, Card, Tabs, Tab, Spinner } from 'react-bootstrap';
import { useSearchParams, useParams, useNavigate } from 'react-router-dom';
import Plot from 'react-plotly.js';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faChartLine, faInfo, faDollarSign, faChartBar, faSync } from '@fortawesome/free-solid-svg-icons';
import LoadingIndicator from '../components/LoadingIndicator';
import ErrorDisplay from '../components/ErrorDisplay';
import * as api from '../services/apiIntegration';
import { AuthContext } from '../contexts/AuthContext';
import { getResponsiveConfig, optimizeDataSize, isMobile, getMobileOptimizedLayout } from '../utils/plotlyConfig';

const StockAnalysis = () => {
  const [searchParams] = useSearchParams();
  const params = useParams();
  const navigate = useNavigate();
  
  // Symbol can come from either the URL path parameter or query parameter
  const initialSymbol = params.symbol || searchParams.get('symbol');
  
  // State variables
  const [stockOptions, setStockOptions] = useState([]);
  const [strategies, setStrategies] = useState([]);
  const [selectedStock, setSelectedStock] = useState(initialSymbol || '');
  const [selectedStrategy, setSelectedStrategy] = useState('');
  const [historyDays, setHistoryDays] = useState(180);
  const [selectedInterval, setSelectedInterval] = useState('1d');
  const [stockData, setStockData] = useState(null);
  const [prediction, setPrediction] = useState(null);
  const [backtestResult, setBacktestResult] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [activeTab, setActiveTab] = useState('analysis');
  
  // Authentication context
  const { isAuthenticated } = useContext(AuthContext);
  
  // Technical indicators state
  const [indicators, setIndicators] = useState({
    sma: { enabled: false, period: 20 },
    ema: { enabled: false, period: 20 },
    rsi: { enabled: false, period: 14 },
    macd: { enabled: false, fastPeriod: 12, slowPeriod: 26, signalPeriod: 9 },
    bollinger: { enabled: false, period: 20, stdDev: 2 },
    atr: { enabled: false, period: 14 },
  });
  
  // Fetch available stocks and strategies on component mount
  useEffect(() => {
    fetchStocks();
    fetchStrategies();
  }, []);
  
  // Effect to update URL when stock is changed manually
  useEffect(() => {
    if (selectedStock && !params.symbol) {
      navigate(`/stocks/${selectedStock}`, { replace: true });
    }
  }, [selectedStock, params.symbol, navigate]);
  
  useEffect(() => {
    if (selectedStock) {
      fetchStockData();
    }
  }, [selectedStock, selectedInterval, historyDays]);
  
  const fetchStocks = async () => {
    try {
      // Using the integrated API with caching
      const result = await api.fetchStocks();
      if (result && result.stocks && Array.isArray(result.stocks)) {
        setStockOptions(result.stocks);
        // Set first stock as selected if none is selected
        if (!selectedStock && result.stocks.length > 0) {
          setSelectedStock(result.stocks[0].symbol); // Note: changed from ticker to symbol for consistency
        }
      } else {
        // Fallback to empty array if no stocks available
        setStockOptions([]);
      }
    } catch (err) {
      console.error('Error fetching stocks:', err);
      setError('Failed to fetch available stocks.');
      setStockOptions([]); // Defensive programming: set empty array on error
    }
  };
  
  const fetchStrategies = async () => {
    try {
      // Using the integrated API with caching
      const { strategies: fetchedStrategies } = await api.fetchStrategies();
      if (fetchedStrategies && Array.isArray(fetchedStrategies) && fetchedStrategies.length > 0) {
        setStrategies(fetchedStrategies);
        // Set selected strategy from URL or first in list
        setSelectedStrategy(fetchedStrategies[0].id);
      } else {
        // Fallback to empty array if no strategies available
        setStrategies([]);
        setError('No prediction strategies available.');
      }
    } catch (err) {
      console.error('Error fetching strategies:', err);
      // We'll still set the error message but the API will provide fallback data
      setError('Using default strategies - backend service may be unavailable.');
      setStrategies([]); // Defensive programming: set empty array on error
    }
  };
  
  const fetchStockData = async () => {
    if (!selectedStock) return;
    
    setLoading(true);
    setError(null);
    
    try {
      // Get stock data from integrated API with caching
      const stockResult = await api.fetchStockData(selectedStock, selectedInterval);
      // Import the API functions
      const { fetchStockData: apiGetStockData, fetchTechnicalSignals } = await import('../services/api');
      
      // Fetch both stock data and technical signals in parallel
      const [stockInfo, technicalData] = await Promise.all([
        apiGetStockData(selectedStock, historyDays + 'd', selectedInterval),
        fetchTechnicalSignals(selectedStock)
      ]);
      
      // Calculate price change and percentage if we have price data
      let priceChange = 0;
      let priceChangePercent = 0;
      
      if (stockInfo?.priceData && Array.isArray(stockInfo.priceData) && stockInfo.priceData.length >= 2) {
        const latestPrice = stockInfo.priceData[stockInfo.priceData.length - 1]?.close || 0;
        const previousPrice = stockInfo.priceData[stockInfo.priceData.length - 2]?.close || 0;
        
        priceChange = latestPrice - previousPrice;
        priceChangePercent = previousPrice !== 0 ? (priceChange / previousPrice) * 100 : 0;
      }
      
      // Combine the data
      const fullData = {
        ...stockInfo,
        technicalIndicators: technicalData?.indicators || {},
        company: {
          ...stockInfo?.companyInfo,
          name: stockInfo?.companyInfo?.name || selectedStock,
          currentPrice: stockInfo?.priceData?.[stockInfo?.priceData?.length - 1]?.close || 0,
          previousClose: stockInfo?.priceData?.[stockInfo?.priceData?.length - 2]?.close || 0,
        },
        priceChange,
        priceChangePercent
      };
      
      setStockData(fullData);
    } catch (err) {
      console.error('Error fetching stock data:', err);
      if (err.response && err.response.status === 404) {
        setError(`No data available for ${selectedStock} with ${selectedInterval} interval.`);
      } else {
        setError('Failed to fetch stock data. Please try again.');
      }
    } finally {
      setLoading(false);
    }
  };
  
  const handleFormSubmit = async (e) => {
    e.preventDefault();
    if (!selectedStock || !selectedStrategy) {
      setError('Please select both a stock and a strategy.');
      return;
    }
    
    setLoading(true);
    setError(null);
    setPrediction(null);
    
    try {
      // Fetch prediction from integrated API with caching
      const result = await api.fetchPrediction(selectedStock, selectedStrategy);
      
      if (result) {
        setPrediction(result);
      } else {
        setError('Failed to get prediction results. Please try again.');
      }
    } catch (err) {
      console.error('Error fetching prediction:', err);
      setError('Failed to fetch prediction. Please try again.');
    } finally {
      setLoading(false);
    }
  };
  
  const runBacktest = async () => {
    if (!selectedStock || !selectedStrategy) {
      setError('Please select both a stock and a strategy for backtesting.');
      return;
    }
    
    setLoading(true);
    setBacktestResult(null);
    setError(null);
    
    try {
      // Some backtest operations may require authentication
      if (selectedStrategy !== 'default' && !isAuthenticated) {
        navigate('/login', { state: { from: `/stocks/${selectedStock}` } });
        return;
      }
      
      // Call backtest API with authentication and caching
      const result = await api.runBacktest(selectedStock, selectedStrategy, historyDays);
      
      if (result) {
        setBacktestResult(result);
      } else {
        setError('No backtest results available. Please try again.');
      }
    } catch (err) {
      console.error('Error running backtest:', err);
      setError('Failed to run backtest. Please try again.');
    } finally {
      setLoading(false);
    }
  };
  
  // Handler for toggling technical indicators
  const handleIndicatorToggle = (indicator) => {
    setIndicators(prev => ({
      ...prev,
      [indicator]: {
        ...prev[indicator],
        enabled: !prev[indicator].enabled
      }
    }));
  };
  
  // Handler for changing indicator parameters
  const handleIndicatorParamChange = (indicator, param, value) => {
    setIndicators(prev => ({
      ...prev,
      [indicator]: {
        ...prev[indicator],
        [param]: parseInt(value)
      }
    }));
  };
  
  // Prepare chart data for Plotly.js
  const prepareChartData = () => {
    // Check if we have valid stock data to avoid errors
    if (!stockData || !stockData.priceData || !Array.isArray(stockData.priceData) || stockData.priceData.length === 0) {
      return { data: [], layout: {}, config: getResponsiveConfig() };
    }
    
    // Extract price data safely with defensive programming
    const dates = stockData.priceData.map(price => new Date(price?.date || price?.time || Date.now()));
    const closePrices = stockData.priceData.map(price => price?.close || 0);
    const openPrices = stockData.priceData.map(price => price?.open || 0);
    const highPrices = stockData.priceData.map(price => price?.high || 0);
    const lowPrices = stockData.priceData.map(price => price?.low || 0);
    const volumes = stockData.priceData.map(price => price?.volume || 0);

    // Create candlestick trace
    const candlestickTrace = {
      type: 'candlestick',
      name: selectedStock,
      x: dates,
      open: openPrices,
      high: highPrices,
      low: lowPrices,
      close: closePrices,
      increasing: {line: {color: '#00B746'}}, // Green candles - Zahaam color scheme
      decreasing: {line: {color: '#EF403C'}}, // Red candles - Zahaam color scheme
      yaxis: 'y',
      hoverinfo: 'x+open+high+low+close',
      showlegend: false
    };
    
    // Prepare volume trace
    const volumeTrace = {
      type: 'bar',
      name: 'Volume',
      x: dates,
      y: volumes,
      marker: {
        color: 'rgba(100, 100, 200, 0.4)'
      },
      yaxis: 'y2',
      hoverinfo: 'x+y',
    };
    
    // Prepare data traces array
    const dataTraces = [candlestickTrace, volumeTrace];
    
    // Add SMA if enabled
    if (indicators.sma.enabled) {
      const smaValues = calculateSMA(closePrices, indicators.sma.period);
      dataTraces.push({
        type: 'scatter',
        mode: 'lines',
        name: `SMA(${indicators.sma.period})`,
        x: dates.slice(indicators.sma.period - 1),
        y: smaValues,
        line: { width: 1.5, color: '#1976d2' },
        yaxis: 'y',
      });
    }
    
    // Add EMA if enabled
    if (indicators.ema.enabled) {
      const emaValues = calculateEMA(closePrices, indicators.ema.period);
      dataTraces.push({
        type: 'scatter',
        mode: 'lines',
        name: `EMA(${indicators.ema.period})`,
        x: dates,
        y: emaValues,
        line: { width: 1.5, color: '#f57c00' },
        yaxis: 'y',
      });
    }
    
    // Setup chart layout
    const chartLayout = {
      title: {
        text: `${selectedStock} Price Chart (${selectedInterval})`,
        font: { size: 18 }
      },
      autosize: true,
      height: 500,
      xaxis: {
        rangeslider: { visible: false },
        type: 'date',
        title: 'Date'
      },
      yaxis: {
        title: 'Price',
        autorange: true,
        domain: [0.3, 1] // Main chart takes 70% of height
      },
      yaxis2: {
        title: 'Volume',
        autorange: true,
        domain: [0, 0.25], // Volume chart takes 25% of height
        tickformat: ',d',
        showgrid: false
      },
      legend: {
        orientation: 'h',
        xanchor: 'center',
        x: 0.5,
        y: 1.03
      },
      paper_bgcolor: 'rgba(0,0,0,0)',
      plot_bgcolor: 'rgba(0,0,0,0)',
      margin: { l: 50, r: 20, t: 40, b: 20 },
      hovermode: 'closest'
    };
    
    // Apply mobile optimizations if on a mobile device
    if (isMobile()) {
      Object.assign(chartLayout, getMobileOptimizedLayout(chartLayout));
    }
    
    const config = getResponsiveConfig();
    
    return { data: dataTraces, layout: chartLayout, config };
  };
  
  // Render stock chart with prepared Plotly data
  const renderStockChart = () => {
    // Defensive check for stock data
    if (!stockData || !stockData.priceData || !Array.isArray(stockData.priceData) || stockData.priceData.length === 0) {
      return (
        <div className="text-center py-5">
          <p className="text-muted mb-0">No chart data available</p>
        </div>
      );
    }

    // Get prepared chart data using our existing function
    const { data, layout, config } = prepareChartData();

    // Handle empty data case
    if (!data || data.length === 0) {
      return (
        <div className="text-center py-5">
          <p className="text-muted mb-0">Unable to prepare chart data</p>
        </div>
      );
    }

    return (
      <div>
        <div className="mb-3">
          <Row>
            <Col md={4}>
              <Form.Group className="mb-3">
                <Form.Check
                  type="switch"
                  id="sma-toggle"
                  label={`SMA (${indicators.sma.period})`}
                  checked={indicators.sma.enabled}
                  onChange={() => handleIndicatorToggle('sma')}
                />
                {indicators.sma.enabled && (
                  <Form.Control
                    type="number"
                    min="1"
                    max="200"
                    value={indicators.sma.period}
                    onChange={(e) => handleIndicatorParamChange('sma', 'period', e.target.value)}
                  />
                )}
              </Form.Group>
            </Col>
            
            <Col md={4}>
              <Form.Group className="mb-3">
                <Form.Check
                  type="switch"
                  id="ema-toggle"
                  label={`EMA (${indicators.ema.period})`}
                  checked={indicators.ema.enabled}
                  onChange={() => handleIndicatorToggle('ema')}
                />
                {indicators.ema.enabled && (
                  <Form.Control
                    type="number"
                    min="1"
                    max="200"
                    value={indicators.ema.period}
                    onChange={(e) => handleIndicatorParamChange('ema', 'period', e.target.value)}
                  />
                )}
              </Form.Group>
            </Col>
          </Row>
        </div>

        <Plot
          className="w-100"
          data={data}
          layout={layout}
          config={config}
          style={{ width: '100%', height: '100%' }}
          useResizeHandler={true}
        />
      </div>
    );
  };
  
  // Calculate Simple Moving Average
  const calculateSMA = (prices, period) => {
    const sma = [];
    
    for (let i = 0; i < prices.length; i++) {
      if (i < period - 1) {
        sma.push({ x: new Date(prices[i].date), y: null });
        continue;
      }
      
      let sum = 0;
      for (let j = i - period + 1; j <= i; j++) {
        sum += parseFloat(prices[j].close);
      }
      
      sma.push({ x: new Date(prices[i].date), y: sum / period });
    }
    
    return sma;
  };
  
  // Calculate Exponential Moving Average
  const calculateEMA = (prices, period) => {
    const ema = [];
    const multiplier = 2 / (period + 1);
    
    // First EMA is SMA
    let sum = 0;
    for (let i = 0; i < period; i++) {
      sum += parseFloat(prices[i].close);
    }
    
    let prevEMA = sum / period;
    ema.push({ x: new Date(prices[period - 1].date), y: prevEMA });
    
    // Calculate subsequent EMAs
    for (let i = period; i < prices.length; i++) {
      const close = parseFloat(prices[i].close);
      const currentEMA = (close - prevEMA) * multiplier + prevEMA;
      prevEMA = currentEMA;
      ema.push({ x: new Date(prices[i].date), y: currentEMA });
    }
    
    // Add null values for dates before the period
    for (let i = 0; i < period - 1; i++) {
      ema.unshift({ x: new Date(prices[i].date), y: null });
    }
    
    return ema;
  };
  
  // Placeholder for old renderStockChart function that has been merged
  // with the renderStockChart function at line 342-376
  // No code needed here as we're using the earlier implementation
  
  const renderPredictionResults = () => {
    if (!prediction) {
      return (
        <div className="text-center p-4">
          {loading && <LoadingIndicator message="Fetching stock data..." />}
          <p>Generate a prediction to see results.</p>
        </div>
      );
    }
    
    const { predictions, signals, summary } = prediction;
    
    // Prepare chart data for prediction visualization
    const predictionSeries = [
      {
        name: 'Historical',
        data: predictions.historical.map(p => ({
          x: new Date(p.date),
          y: parseFloat(p.close)
        }))
      },
      {
        name: 'Predicted',
        data: predictions.forecast.map(p => ({
          x: new Date(p.date),
          y: parseFloat(p.predicted)
        }))
      }
    ];
    
    const predictionOptions = {
      chart: {
        type: 'line',
        height: 350,
        toolbar: {
          show: true
        }
      },
      title: {
        text: 'Price Prediction',
        align: 'center'
      },
      xaxis: {
        type: 'datetime',
        labels: {
          datetimeUTC: false
        }
      },
      yaxis: {
        title: {
          text: 'Price'
        }
      },
      stroke: {
        width: [3, 3],
        curve: 'smooth',
        dashArray: [0, 0]
      },
      legend: {
        show: true,
        position: 'top'
      },
      markers: {
        size: 0
      },
      tooltip: {
        x: {
          format: 'dd MMM yyyy'
        }
      }
    };
    
    return (
      <div>
        <Row>
          <Col md={8}>
            <Plot 
              data={prediction?.traces || []}
              layout={{
                title: "Price Prediction",
                height: 350,
                xaxis: { title: "Date" },
                yaxis: { title: "Price" },
                paper_bgcolor: 'rgba(0,0,0,0)',
                plot_bgcolor: 'rgba(0,0,0,0)'
              }}
              config={getResponsiveConfig()}
              style={{ width: '100%', height: '100%' }}
            />
          </Col>
          <Col md={4}>
            <Card>
              <Card.Body>
                <Card.Title>Prediction Summary</Card.Title>
                <ul className="list-unstyled">
                  <li><strong>Current Price:</strong> {summary.current_price}</li>
                  <li><strong>Predicted (7d):</strong> {summary.predicted_price}</li>
                  <li><strong>Change:</strong> {summary.predicted_change}%</li>
                  <li><strong>Confidence:</strong> {summary.confidence}%</li>
                  <li><strong>Signal:</strong> {summary.signal}</li>
                </ul>
              </Card.Body>
            </Card>
            
            <Card className="mt-3">
              <Card.Body>
                <Card.Title>Recent Signals</Card.Title>
                <div className="signal-list">
                  {signals.map((signal, idx) => (
                    <div key={idx} className={`signal-item ${signal.action.toLowerCase()}`}>
                      <div className="signal-date">{new Date(signal.date).toLocaleDateString()}</div>
                      <div className="signal-action">{signal.action}</div>
                      <div className="signal-price">{signal.price}</div>
                      <div className="signal-reason">{signal.reason}</div>
                    </div>
                  ))}
                </div>
              </Card.Body>
            </Card>
          </Col>
        </Row>
      </div>
    );
  };
  
  const renderBacktestResults = () => {
    if (!backtestResult) {
      return (
        <div className="text-center p-4">
          <p>Run a backtest to see results.</p>
        </div>
      );
    }
    
    const { trades, performance, equity_curve } = backtestResult;
    
    // Prepare chart data for equity curve
    const equitySeries = [
      {
        name: 'Portfolio Value',
        data: equity_curve.map(p => ({
          x: new Date(p.date),
          y: parseFloat(p.equity)
        }))
      }
    ];
    
    const equityOptions = {
      chart: {
        type: 'line',
        height: 350
      },
      title: {
        text: 'Equity Curve',
        align: 'center'
      },
      xaxis: {
        type: 'datetime'
      },
      yaxis: {
        title: {
          text: 'Portfolio Value'
        }
      },
      stroke: {
        width: 3,
        curve: 'smooth'
      }
    };
    
    return (
      <div>
        <Row>
          <Col md={8}>
            <Plot 
              data={backtestResult?.equity_traces || []}
              layout={{
                title: "Equity Curve",
                height: 350,
                xaxis: { title: "Date" },
                yaxis: { title: "Equity" },
                paper_bgcolor: 'rgba(0,0,0,0)',
                plot_bgcolor: 'rgba(0,0,0,0)'
              }}
              config={getResponsiveConfig()}
              style={{ width: '100%', height: '100%' }}
            />
          </Col>
          <Col md={4}>
            <Card>
              <Card.Body>
                <Card.Title>Performance Summary</Card.Title>
                <ul className="list-unstyled">
                  <li><strong>Total Return:</strong> {performance.total_return}%</li>
                  <li><strong>Annual Return:</strong> {performance.annual_return}%</li>
                  <li><strong>Sharpe Ratio:</strong> {performance.sharpe_ratio}</li>
                  <li><strong>Max Drawdown:</strong> {performance.max_drawdown}%</li>
                  <li><strong>Win Rate:</strong> {performance.win_rate}%</li>
                </ul>
              </Card.Body>
            </Card>
            
            <Card className="mt-3">
              <Card.Body>
                <Card.Title>Recent Trades</Card.Title>
                <div className="trade-list">
                  {trades.slice(0, 5).map((trade, idx) => (
                    <div key={idx} className={`trade-item ${trade.type.toLowerCase()}`}>
                      <div className="trade-date">{new Date(trade.entry_date).toLocaleDateString()}</div>
                      <div className="trade-type">{trade.type}</div>
                      <div className="trade-profit">{trade.profit_pct}%</div>
                    </div>
                  ))}
                </div>
              </Card.Body>
            </Card>
          </Col>
        </Row>
      </div>
    );
  };
  
  return (
    <Container className="py-4">
      <h2 className="mb-4">Stock Analysis & Prediction</h2>
      <p className="lead mb-4">
        Apply your custom trading strategies to analyze stocks and generate trading signals.
      </p>
      
      {error && <ErrorDisplay error={error} onDismiss={() => setError(null)} />}
      
      <Card className="mb-4">
        <Card.Body>
          <Form onSubmit={handleFormSubmit}>
            <Row>
              <Col md={4}>
                <Form.Group className="mb-3">
                  <Form.Label>Select Stock</Form.Label>
                  <Form.Select
                    value={selectedStock}
                    onChange={(e) => setSelectedStock(e.target.value)}
                    required
                  >
                    <option value="">Select a stock</option>
                    {stockOptions.map(stock => (
                      <option key={stock.ticker} value={stock.ticker}>
                        {stock.ticker} - {stock.name}
                      </option>
                    ))}
                  </Form.Select>
                </Form.Group>
              </Col>
              
              <Col md={4}>
                <Form.Group className="mb-3">
                  <Form.Label>Select Strategy</Form.Label>
                  <Form.Select
                    value={selectedStrategy}
                    onChange={(e) => setSelectedStrategy(e.target.value)}
                    required
                  >
                    <option value="">Select a strategy</option>
                    {strategies.map(strategy => (
                      <option key={strategy.id} value={strategy.id}>
                        {strategy.name}
                      </option>
                    ))}
                  </Form.Select>
                </Form.Group>
              </Col>
              
              <Col md={2}>
                <Form.Group className="mb-3">
                  <Form.Label>History (Days)</Form.Label>
                  <Form.Select
                    value={historyDays}
                    onChange={(e) => setHistoryDays(parseInt(e.target.value))}
                  >
                    <option value="30">30 days</option>
                    <option value="90">90 days</option>
                    <option value="180">180 days</option>
                    <option value="365">1 year</option>
                    <option value="730">2 years</option>
                  </Form.Select>
                </Form.Group>
              </Col>
              
              <Col md={2}>
                <Form.Group className="mb-3">
                  <Form.Label>Interval</Form.Label>
                  <Form.Select
                    value={selectedInterval}
                    onChange={(e) => setSelectedInterval(e.target.value)}
                  >
                    <option value="1d">Daily</option>
                    <option value="1h">Hourly</option>
                    <option value="15m">15 Minutes</option>
                    <option value="5m">5 Minutes</option>
                  </Form.Select>
                </Form.Group>
              </Col>
              
              <Col md={2}>
                <div className="d-grid gap-2">
                  <Button type="submit" disabled={loading || !selectedStock || !selectedStrategy}>
                    {loading ? (
                      <>
                        <Spinner
                          as="span"
                          animation="border"
                          size="sm"
                          role="status"
                          aria-hidden="true"
                        />
                        <span className="ms-2">Loading...</span>
                      </>
                    ) : (
                      'Analyze Stock'
                    )}
                  </Button>
                </div>
              </Col>
            </Row>
          </Form>
        </Card.Body>
      </Card>
      
      {error && (
        <div className="mb-4">
          <ErrorDisplay message={error} />
        </div>
      )}

      {loading && !stockData && (
        <div className="text-center py-5">
          <LoadingIndicator text="Loading stock data..." />
        </div>
      )}

      {stockData && (
        <>
          {/* Stock Header Card */}
          <Card className="mb-4">
            <Card.Body>
              <div className="d-flex justify-content-between align-items-center mb-4">
                <div>
                  <div className="d-flex align-items-center">
                    <div className="icon-circle bg-primary text-white me-3">
                      <FontAwesomeIcon icon={faChartLine} />
                    </div>
                    <div>
                      <h2 className="mb-0">
                        {stockData?.company?.name || stockData?.symbol || selectedStock}
                        <span className="badge bg-primary ms-2" style={{ fontSize: '0.5em', verticalAlign: 'middle' }}>Stock</span>
                      </h2>
                      <div className="d-flex align-items-center">
                        <div className="text-muted me-3">
                          {stockData?.company?.sector || 'N/A'} • {stockData?.company?.industry || 'N/A'}
                        </div>
                        <div className="live-indicator small">
                          <span className="text-success fw-bold">Live Data</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div className="text-end">
                  <h3 className="mb-0">
                    {stockData?.company?.currentPrice ? 
                      api.formatCurrency(stockData.company.currentPrice) : 'N/A'}
                  </h3>
                  <div className={stockData?.priceChange >= 0 ? 'text-success' : 'text-danger'}>
                    {stockData?.priceChange >= 0 ? '▲' : '▼'} 
                    {stockData?.priceChange ? 
                      api.formatCurrency(Math.abs(stockData.priceChange || 0)) : 'N/A'} 
                    ({stockData?.priceChangePercent ? 
                      api.formatPercentage(Math.abs(stockData.priceChangePercent || 0)) : 'N/A'})
                  </div>
                  <div className="text-muted small">
                    Last updated: {new Date().toLocaleTimeString()}
                  </div>
                </div>
              </div>

              {/* Chart Control section */}
              <div className="d-flex justify-content-between align-items-center mb-3">
                <div>
                  <span className="badge bg-secondary me-2">Chart</span>
                  <span className="badge bg-info">Interactive</span>
                </div>
                <div className="btn-group btn-group-sm">
                  <button className="btn btn-sm btn-outline-secondary" onClick={() => setHistoryDays(30)}>1M</button>
                  <button className="btn btn-sm btn-outline-secondary" onClick={() => setHistoryDays(90)}>3M</button>
                  <button className="btn btn-sm btn-outline-secondary" onClick={() => setHistoryDays(180)}>6M</button>
                  <button className="btn btn-sm btn-outline-secondary" onClick={() => setHistoryDays(365)}>1Y</button>
                </div>
              </div>

              {/* Stock Chart */}
              <div className="mb-4">
                {renderStockChart()}
              </div>

              {/* Stock Info Cards */}
              <Row className="g-3">
                {/* Key Stats Card */}
                <Col md={4}>
                  <div className="border rounded p-3 h-100">
                    <div className="d-flex align-items-center mb-3">
                      <div className="icon-circle bg-info text-white me-2">
                        <FontAwesomeIcon icon={faInfo} />
                      </div>
                      <h5 className="mb-0">Key Stats</h5>
                    </div>
                    <div className="d-flex justify-content-between mb-2">
                      <span className="text-muted">Open</span>
                      <span>{api.formatCurrency(stockData?.priceData?.[0]?.open || 0)}</span>
                    </div>
                    <div className="d-flex justify-content-between mb-2">
                      <span className="text-muted">High</span>
                      <span>{api.formatCurrency(stockData?.priceData?.[0]?.high || 0)}</span>
                    </div>
                    <div className="d-flex justify-content-between mb-2">
                      <span className="text-muted">Low</span>
                      <span>{api.formatCurrency(stockData?.priceData?.[0]?.low || 0)}</span>
                    </div>
                    <div className="d-flex justify-content-between mb-2">
                      <span className="text-muted">52W High</span>
                      <span>{api.formatCurrency(stockData?.company?.fiftyTwoWeekHigh || 0)}</span>
                    </div>
                    <div className="d-flex justify-content-between">
                      <span className="text-muted">52W Low</span>
                      <span>{api.formatCurrency(stockData?.company?.fiftyTwoWeekLow || 0)}</span>
                    </div>
                  </div>
                </Col>
                
                {/* Valuation Card */}
                <Col md={4}>
                  <div className="border rounded p-3 h-100">
                    <div className="d-flex align-items-center mb-3">
                      <div className="icon-circle bg-success text-white me-2">
                        <FontAwesomeIcon icon={faDollarSign} />
                      </div>
                      <h5 className="mb-0">Valuation</h5>
                    </div>
                    <div className="d-flex justify-content-between mb-2">
                      <span className="text-muted">Market Cap</span>
                      <span>{api.formatCurrency(stockData?.company?.marketCap || 0)}</span>
                    </div>
                    <div className="d-flex justify-content-between mb-2">
                      <span className="text-muted">Volume</span>
                      <span>{stockData?.priceData?.[0]?.volume?.toLocaleString() || 'N/A'}</span>
                    </div>
                    <div className="d-flex justify-content-between mb-2">
                      <span className="text-muted">Avg Volume</span>
                      <span>{stockData?.company?.averageVolume?.toLocaleString() || 'N/A'}</span>
                    </div>
                  </div>
                </Col>
                
                {/* Technical Indicators Card */}
                <Col md={4}>
                  <div className="border rounded p-3 h-100">
                    <div className="d-flex align-items-center mb-3">
                      <div className="icon-circle bg-warning text-white me-2">
                        <FontAwesomeIcon icon={faChartBar} />
                      </div>
                      <h5 className="mb-0">Technical Indicators</h5>
                    </div>
                    <div className="d-flex justify-content-between mb-2">
                      <span className="text-muted">RSI (14)</span>
                      <span className={
                        stockData?.technicalIndicators?.rsi > 70 ? 'text-danger' : 
                        stockData?.technicalIndicators?.rsi < 30 ? 'text-success' : ''
                      }>
                        {stockData?.technicalIndicators?.rsi?.toFixed(2) || 'N/A'}
                        {stockData?.technicalIndicators?.rsi > 70 && 
                          <span className="badge bg-danger ms-2">Overbought</span>}
                        {stockData?.technicalIndicators?.rsi < 30 && 
                          <span className="badge bg-success ms-2">Oversold</span>}
                      </span>
                    </div>
                    <div className="d-flex justify-content-between mb-2">
                      <span className="text-muted">SMA (20)</span>
                      <span>{api.formatCurrency(stockData?.technicalIndicators?.sma_20 || 0)}</span>
                    </div>
                    <div className="d-flex justify-content-between mb-2">
                      <span className="text-muted">SMA (50)</span>
                      <span>{api.formatCurrency(stockData?.technicalIndicators?.sma_50 || 0)}</span>
                    </div>
                    <div className="d-flex justify-content-between">
                      <span className="text-muted">MACD</span>
                      <span className={
                        stockData?.technicalIndicators?.macd > 0 ? 'text-success' : 'text-danger'
                      }>
                        {stockData?.technicalIndicators?.macd?.toFixed(2) || 'N/A'}
                      </span>
                    </div>
                  </div>
                </Col>
              </Row>
            </Card.Body>
          </Card>
        </>
      )}
      
      <Card>
        <Card.Header>
          <Tabs
            activeKey={activeTab}
            onSelect={(k) => setActiveTab(k)}
            className="mb-3"
          >
            <Tab eventKey="analysis" title="Strategy Analysis">
              {renderPredictionResults()}
            </Tab>
            <Tab eventKey="backtest" title="Backtest Results">
              <div className="mb-3 p-3">
                <Button 
                  onClick={runBacktest}
                  disabled={loading || !selectedStock || !selectedStrategy}
                  variant="outline-primary"
                >
                  {loading ? (
                    <>
                      <FontAwesomeIcon icon={faSync} spin className="me-2" />
                      Running...
                    </>
                  ) : 'Run Backtest'}
                </Button>
              </div>
              {renderBacktestResults()}
            </Tab>
          </Tabs>
        </Card.Header>
      </Card>
    </Container>
  );
};

export default StockAnalysis;
