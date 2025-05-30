import React, { useState, useEffect } from 'react';
import { Container, Row, Col, Card, Button, Form, Alert, Spinner, Tabs, Tab, Table } from 'react-bootstrap';
import { useSearchParams } from 'react-router-dom';
import Chart from 'react-apexcharts';
import axios from 'axios';

const StockAnalysis = () => {
  const [searchParams] = useSearchParams();
  const strategyIdFromUrl = searchParams.get('strategyId');
  
  const [selectedStock, setSelectedStock] = useState('');
  const [stockOptions, setStockOptions] = useState([]);
  const [selectedStrategy, setSelectedStrategy] = useState(strategyIdFromUrl || '');
  const [strategies, setStrategies] = useState([]);
  const [historyDays, setHistoryDays] = useState(365);
  
  const [stockData, setStockData] = useState(null);
  const [prediction, setPrediction] = useState(null);
  const [backtestResult, setBacktestResult] = useState(null);
  
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [activeTab, setActiveTab] = useState('analysis');
  
  useEffect(() => {
    // Load stocks and strategies on component mount
    fetchStocks();
    fetchStrategies();
  }, []);
  
  const fetchStocks = async () => {
    try {
      const response = await axios.get('http://localhost:8000/api/stocks/db');
      if (response.data && response.data.stocks) {
        setStockOptions(response.data.stocks);
        // Set first stock as selected if none is selected
        if (!selectedStock && response.data.stocks.length > 0) {
          setSelectedStock(response.data.stocks[0].ticker);
        }
      }
    } catch (err) {
      console.error('Error fetching stocks:', err);
      setError('Failed to load stocks. Please try again.');
    }
  };
  
  const fetchStrategies = async () => {
    try {
      const response = await axios.get('http://localhost:8000/api/strategies');
      if (response.data && response.data.strategies) {
        setStrategies(response.data.strategies);
        // Set selected strategy from URL or first in list
        if (!selectedStrategy && response.data.strategies.length > 0) {
          setSelectedStrategy(response.data.strategies[0].id.toString());
        }
      }
    } catch (err) {
      console.error('Error fetching strategies:', err);
      setError('Failed to load strategies. Please try again.');
    }
  };
  
  const fetchStockData = async () => {
    try {
      const response = await axios.get(`http://localhost:8000/api/stock/${selectedStock}/db?days=${historyDays}`);
      setStockData(response.data);
    } catch (err) {
      console.error('Error fetching stock data:', err);
      setError('Failed to load stock data. Please try again.');
      setStockData(null);
    }
  };
  
  const generatePrediction = async () => {
    setLoading(true);
    setError(null);
    
    try {
      // Fetch stock data if not already loaded
      if (!stockData) {
        await fetchStockData();
      }
      
      // Generate prediction
      const response = await axios.post(
        `http://localhost:8000/api/strategy/${selectedStrategy}/predict/${selectedStock}?days=${historyDays}`
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
    setLoading(true);
    setError(null);
    
    try {
      const response = await axios.post(
        `http://localhost:8000/api/strategy/${selectedStrategy}/backtest/${selectedStock}?days=${historyDays}`
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
  
  const handleSubmit = (e) => {
    e.preventDefault();
    generatePrediction();
  };
  
  const renderStockChart = () => {
    if (!stockData || !stockData.data || stockData.data.length === 0) {
      return (
        <div className="text-center p-4">
          <p>No historical data available for this stock.</p>
        </div>
      );
    }
    
    const chartData = stockData.data.map(item => ({
      x: new Date(item.Date).getTime(),
      y: [item.Open, item.High, item.Low, item.Close]
    }));
    
    const chartOptions = {
      chart: {
        type: 'candlestick',
        height: 350
      },
      title: {
        text: `${stockData.ticker} Stock Price`,
        align: 'left'
      },
      xaxis: {
        type: 'datetime'
      },
      yaxis: {
        tooltip: {
          enabled: true
        }
      }
    };
    
    const series = [{
      name: 'Price',
      data: chartData
    }];
    
    return (
      <Chart 
        options={chartOptions}
        series={series}
        type="candlestick"
        height={350}
      />
    );
  };
  
  const renderPredictionResults = () => {
    if (!prediction) {
      return (
        <div className="text-center p-4">
          <p>Generate a prediction to see results.</p>
        </div>
      );
    }
    
    return (
      <Row>
        <Col md={6}>
          <Card className="mb-3">
            <Card.Header>Prediction Summary</Card.Header>
            <Card.Body>
              <h5 className={`text-${prediction.action === 'BUY' ? 'success' : prediction.action === 'SELL' ? 'danger' : 'warning'}`}>
                {prediction.action} Signal
              </h5>
              <p>{prediction.recommendation}</p>
              
              <Table striped>
                <tbody>
                  <tr>
                    <td>Current Price:</td>
                    <td className="text-end">{prediction.latest_price}</td>
                  </tr>
                  {prediction.action === 'BUY' && prediction.entry_point && (
                    <>
                      <tr>
                        <td>Entry Price:</td>
                        <td className="text-end">{prediction.entry_point.price}</td>
                      </tr>
                      <tr>
                        <td>Stop Loss:</td>
                        <td className="text-end">{prediction.entry_point.stop_loss}</td>
                      </tr>
                      <tr>
                        <td>Take Profit:</td>
                        <td className="text-end">{prediction.entry_point.take_profit}</td>
                      </tr>
                    </>
                  )}
                  {prediction.action === 'SELL' && prediction.exit_point && (
                    <tr>
                      <td>Exit Price:</td>
                      <td className="text-end">{prediction.exit_point.price}</td>
                    </tr>
                  )}
                  <tr>
                    <td>Date:</td>
                    <td className="text-end">{prediction.prediction_date}</td>
                  </tr>
                </tbody>
              </Table>
            </Card.Body>
          </Card>
        </Col>
        
        <Col md={6}>
          <Card>
            <Card.Header>Technical Indicators</Card.Header>
            <Card.Body>
              {prediction.technical_indicators && (
                <Table striped hover>
                  <tbody>
                    {Object.entries(prediction.technical_indicators).map(([key, value]) => (
                      value !== null && (
                        <tr key={key}>
                          <td>{key.toUpperCase()}:</td>
                          <td className="text-end">{typeof value === 'number' ? value.toFixed(2) : value}</td>
                        </tr>
                      )
                    ))}
                  </tbody>
                </Table>
              )}
            </Card.Body>
          </Card>
        </Col>
      </Row>
    );
  };
  
  const renderBacktestResults = () => {
    if (!backtestResult) {
      return (
        <div className="text-center p-4">
          <p>Run backtest to see results.</p>
        </div>
      );
    }
    
    return (
      <Row>
        <Col md={6}>
          <Card className="mb-3">
            <Card.Header>Backtest Statistics</Card.Header>
            <Card.Body>
              <Table striped>
                <tbody>
                  <tr>
                    <td>Total Trades:</td>
                    <td className="text-end">{backtestResult.statistics.total_trades}</td>
                  </tr>
                  <tr>
                    <td>Winning Trades:</td>
                    <td className="text-end">{backtestResult.statistics.winning_trades}</td>
                  </tr>
                  <tr>
                    <td>Win Rate:</td>
                    <td className="text-end">{backtestResult.statistics.win_rate}%</td>
                  </tr>
                  <tr>
                    <td>Average Profit:</td>
                    <td className="text-end">{backtestResult.statistics.avg_profit}%</td>
                  </tr>
                  <tr>
                    <td>Max Drawdown:</td>
                    <td className="text-end">{backtestResult.statistics.max_drawdown}%</td>
                  </tr>
                  {backtestResult.statistics.profit_factor && (
                    <tr>
                      <td>Profit Factor:</td>
                      <td className="text-end">{backtestResult.statistics.profit_factor}</td>
                    </tr>
                  )}
                </tbody>
              </Table>
            </Card.Body>
          </Card>
        </Col>
        
        <Col md={6}>
          <Card>
            <Card.Header>Trade History</Card.Header>
            <Card.Body style={{ maxHeight: '400px', overflowY: 'auto' }}>
              {backtestResult.trades.length === 0 ? (
                <p>No trades were generated during the backtest period.</p>
              ) : (
                <Table striped hover responsive>
                  <thead>
                    <tr>
                      <th>Entry Date</th>
                      <th>Entry Price</th>
                      <th>Exit Date</th>
                      <th>Exit Price</th>
                      <th>Profit%</th>
                    </tr>
                  </thead>
                  <tbody>
                    {backtestResult.trades.map((trade, index) => (
                      <tr key={index} className={trade.profit_pct >= 0 ? 'table-success' : 'table-danger'}>
                        <td>{trade.entry_date}</td>
                        <td>{trade.entry_price}</td>
                        <td>{trade.exit_date}</td>
                        <td>{trade.exit_price}</td>
                        <td className={trade.profit_pct >= 0 ? 'text-success' : 'text-danger'}>
                          {trade.profit_pct}%
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </Table>
              )}
            </Card.Body>
          </Card>
        </Col>
      </Row>
    );
  };
  
  return (
    <Container className="py-4">
      <h2>Stock Analysis with My Strategy</h2>
      <p className="text-muted mb-4">
        Apply your custom trading strategies to analyze stocks and generate trading signals.
      </p>
      
      {error && (
        <Alert variant="danger" onClose={() => setError(null)} dismissible>
          {error}
        </Alert>
      )}
      
      <Card className="mb-4">
        <Card.Body>
          <Form onSubmit={handleSubmit}>
            <Row className="align-items-end">
              <Col md={4}>
                <Form.Group className="mb-3">
                  <Form.Label>Select Stock</Form.Label>
                  <Form.Select
                    value={selectedStock}
                    onChange={(e) => setSelectedStock(e.target.value)}
                    required
                  >
                    <option value="">Select a stock...</option>
                    {stockOptions.map((stock) => (
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
                    <option value="">Select a strategy...</option>
                    {strategies.map((strategy) => (
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
