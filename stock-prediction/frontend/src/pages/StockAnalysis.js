import React, { useState, useEffect } from 'react';
import { Container, Row, Col, Form, Button, Card, Tabs, Tab, Spinner } from 'react-bootstrap';
import { useSearchParams } from 'react-router-dom';
import Chart from 'react-apexcharts';
import axios from 'axios';
import LoadingIndicator from '../components/LoadingIndicator';
import ErrorDisplay from '../components/ErrorDisplay';

const StockAnalysis = () => {
  const [searchParams] = useSearchParams();
  const initialSymbol = searchParams.get('symbol');
  
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
  
  useEffect(() => {
    if (selectedStock) {
      fetchStockData();
    }
  }, [selectedStock, selectedInterval, historyDays]);
  
  const fetchStocks = async () => {
    try {
      const response = await axios.get('/stocks/db');
      if (response.data && response.data.stocks) {
        setStockOptions(response.data.stocks);
        // Set first stock as selected if none is selected
        if (!selectedStock && response.data.stocks.length > 0) {
          setSelectedStock(response.data.stocks[0].ticker);
        }
      }
    } catch (err) {
      console.error('Error fetching stocks:', err);
      setError('Failed to fetch available stocks.');
    }
  };
  
  const fetchStrategies = async () => {
    try {
      const response = await axios.get('/strategies');
      if (response.data && response.data.strategies) {
        setStrategies(response.data.strategies);
        // Set selected strategy from URL or first in list
        if (response.data.strategies.length > 0) {
          setSelectedStrategy(response.data.strategies[0].id);
        }
      }
    } catch (err) {
      console.error('Error fetching strategies:', err);
      setError('Failed to fetch available strategies.');
    }
  };
  
  const fetchStockData = async () => {
    if (!selectedStock) return;
    
    setLoading(true);
    setError(null);
    
    try {
      const response = await axios.get(`/stock/${selectedStock}/price?interval=${selectedInterval}`);
      setStockData(response.data);
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
      setError('Please select both stock and strategy');
      return;
    }
    
    setLoading(true);
    setError(null);
    
    try {
      // Generate prediction
      const response = await axios.post(
        `/strategy/${selectedStrategy}/predict/${selectedStock}?days=${historyDays}`
      );
      
      setPrediction(response.data);
      setActiveTab('analysis');
    } catch (err) {
      console.error('Error generating prediction:', err);
      setError('Failed to generate prediction. Please try again.');
    } finally {
      setLoading(false);
    }
  };
  
  const runBacktest = async () => {
    if (!selectedStock || !selectedStrategy) return;
    
    setLoading(true);
    setError(null);
    
    try {
      const response = await axios.post(
        `/strategy/${selectedStrategy}/backtest/${selectedStock}?days=${historyDays}`
      );
      
      setBacktestResult(response.data);
      setActiveTab('backtest');
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
  
  // Prepare chart data for ApexCharts
  const prepareChartData = () => {
    if (!stockData || !stockData.prices || stockData.prices.length === 0) {
      return { series: [], options: {} };
    }
    
    const prices = stockData.prices;
    
    // Prepare candlestick data
    const ohlc = prices.map(price => ({
      x: new Date(price.date),
      y: [parseFloat(price.open), parseFloat(price.high), parseFloat(price.low), parseFloat(price.close)]
    }));
    
    // Prepare volume data
    const volume = prices.map(price => ({
      x: new Date(price.date),
      y: price.volume
    }));
    
    // Prepare technical indicators
    const indicatorSeries = [];
    
    // Add SMA if enabled
    if (indicators.sma.enabled) {
      const smaData = calculateSMA(prices, indicators.sma.period);
      indicatorSeries.push({
        name: `SMA(${indicators.sma.period})`,
        type: 'line',
        data: smaData
      });
    }
    
    // Add EMA if enabled
    if (indicators.ema.enabled) {
      const emaData = calculateEMA(prices, indicators.ema.period);
      indicatorSeries.push({
        name: `EMA(${indicators.ema.period})`,
        type: 'line',
        data: emaData
      });
    }
    
    // Prepare chart options
    const options = {
      chart: {
        type: 'candlestick',
        height: 350,
        id: 'stock-chart',
        toolbar: {
          show: true,
          tools: {
            download: true,
            selection: true,
            zoom: true,
            zoomin: true,
            zoomout: true,
            pan: true,
            reset: true
          }
        }
      },
      title: {
        text: `${selectedStock} Price Chart (${selectedInterval})`,
        align: 'center'
      },
      xaxis: {
        type: 'datetime',
        labels: {
          datetimeUTC: false
        }
      },
      yaxis: [
        {
          title: {
            text: 'Price'
          },
          tooltip: {
            enabled: true
          }
        },
        {
          opposite: true,
          title: {
            text: 'Volume'
          }
        }
      ],
      tooltip: {
        shared: true,
        custom: [
          function({ seriesIndex, dataPointIndex, w }) {
            const o = w.globals.seriesCandleO[0][dataPointIndex];
            const h = w.globals.seriesCandleH[0][dataPointIndex];
            const l = w.globals.seriesCandleL[0][dataPointIndex];
            const c = w.globals.seriesCandleC[0][dataPointIndex];
            
            return `
              <div class="apexcharts-tooltip-candlestick">
                <div>Open: <span>${o.toFixed(2)}</span></div>
                <div>High: <span>${h.toFixed(2)}</span></div>
                <div>Low: <span>${l.toFixed(2)}</span></div>
                <div>Close: <span>${c.toFixed(2)}</span></div>
              </div>
            `;
          }
        ]
      }
    };
    
    // Prepare series for the chart
    const series = [
      {
        name: 'Candlestick',
        type: 'candlestick',
        data: ohlc
      },
      {
        name: 'Volume',
        type: 'bar',
        data: volume
      },
      ...indicatorSeries
    ];
    
    return { series, options };
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
  
  const renderStockChart = () => {
    if (!stockData) return null;
    
    const { series, options } = prepareChartData();
    
    return (
      <div>
        <h4 className="mb-3">{selectedStock} Price Chart</h4>
        
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
            
            <Col md={4}>
              <Form.Group className="mb-3">
                <Form.Check
                  type="switch"
                  id="rsi-toggle"
                  label={`RSI (${indicators.rsi.period})`}
                  checked={indicators.rsi.enabled}
                  onChange={() => handleIndicatorToggle('rsi')}
                />
                {indicators.rsi.enabled && (
                  <Form.Control
                    type="number"
                    min="1"
                    max="100"
                    value={indicators.rsi.period}
                    onChange={(e) => handleIndicatorParamChange('rsi', 'period', e.target.value)}
                  />
                )}
              </Form.Group>
            </Col>
          </Row>
          
          <Row>
            <Col md={4}>
              <Form.Group className="mb-3">
                <Form.Check
                  type="switch"
                  id="macd-toggle"
                  label="MACD"
                  checked={indicators.macd.enabled}
                  onChange={() => handleIndicatorToggle('macd')}
                />
                {indicators.macd.enabled && (
                  <Row>
                    <Col>
                      <Form.Label>Fast</Form.Label>
                      <Form.Control
                        type="number"
                        min="1"
                        max="100"
                        value={indicators.macd.fastPeriod}
                        onChange={(e) => handleIndicatorParamChange('macd', 'fastPeriod', e.target.value)}
                      />
                    </Col>
                    <Col>
                      <Form.Label>Slow</Form.Label>
                      <Form.Control
                        type="number"
                        min="1"
                        max="100"
                        value={indicators.macd.slowPeriod}
                        onChange={(e) => handleIndicatorParamChange('macd', 'slowPeriod', e.target.value)}
                      />
                    </Col>
                    <Col>
                      <Form.Label>Signal</Form.Label>
                      <Form.Control
                        type="number"
                        min="1"
                        max="100"
                        value={indicators.macd.signalPeriod}
                        onChange={(e) => handleIndicatorParamChange('macd', 'signalPeriod', e.target.value)}
                      />
                    </Col>
                  </Row>
                )}
              </Form.Group>
            </Col>
            
            <Col md={4}>
              <Form.Group className="mb-3">
                <Form.Check
                  type="switch"
                  id="bollinger-toggle"
                  label="Bollinger Bands"
                  checked={indicators.bollinger.enabled}
                  onChange={() => handleIndicatorToggle('bollinger')}
                />
                {indicators.bollinger.enabled && (
                  <Row>
                    <Col>
                      <Form.Label>Period</Form.Label>
                      <Form.Control
                        type="number"
                        min="1"
                        max="100"
                        value={indicators.bollinger.period}
                        onChange={(e) => handleIndicatorParamChange('bollinger', 'period', e.target.value)}
                      />
                    </Col>
                    <Col>
                      <Form.Label>StdDev</Form.Label>
                      <Form.Control
                        type="number"
                        min="1"
                        max="10"
                        step="0.5"
                        value={indicators.bollinger.stdDev}
                        onChange={(e) => handleIndicatorParamChange('bollinger', 'stdDev', e.target.value)}
                      />
                    </Col>
                  </Row>
                )}
              </Form.Group>
            </Col>
            
            <Col md={4}>
              <Form.Group className="mb-3">
                <Form.Check
                  type="switch"
                  id="atr-toggle"
                  label={`ATR (${indicators.atr.period})`}
                  checked={indicators.atr.enabled}
                  onChange={() => handleIndicatorToggle('atr')}
                />
                {indicators.atr.enabled && (
                  <Form.Control
                    type="number"
                    min="1"
                    max="100"
                    value={indicators.atr.period}
                    onChange={(e) => handleIndicatorParamChange('atr', 'period', e.target.value)}
                  />
                )}
              </Form.Group>
            </Col>
          </Row>
        </div>
        
        <Chart options={options} series={series} type="candlestick" height={500} />
      </div>
    );
  };
  
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
            <Chart options={predictionOptions} series={predictionSeries} type="line" height={350} />
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
            <Chart options={equityOptions} series={equitySeries} type="line" height={350} />
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
      
      {stockData && (
        <Card className="mb-4">
          <Card.Body>
            {renderStockChart()}
          </Card.Body>
        </Card>
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
                  {loading ? 'Running...' : 'Run Backtest'}
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
