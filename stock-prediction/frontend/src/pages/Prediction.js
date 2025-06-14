import React, { useState, useEffect } from 'react';
import { Container, Row, Col, Card, Form, Button, Table, Spinner, Alert } from 'react-bootstrap';
import Plot from 'react-plotly.js';
import { fetchStockData, formatCurrency, formatNumber, getValueColor } from '../services/api';

const Prediction = () => {
  const [selectedStock, setSelectedStock] = useState('BBCA.JK');
  const [predictionPeriod, setPredictionPeriod] = useState('30');
  const [predictionData, setPredictionData] = useState(null);
  const [historicalData, setHistoricalData] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  
  // Stock options - should ideally come from API
  const stockOptions = [
    { value: 'BBCA.JK', label: 'BBCA.JK - Bank Central Asia' },
    { value: 'BBRI.JK', label: 'BBRI.JK - Bank Rakyat Indonesia' },
    { value: 'BMRI.JK', label: 'BMRI.JK - Bank Mandiri' },
    { value: 'TLKM.JK', label: 'TLKM.JK - Telkom Indonesia' },
    { value: 'ASII.JK', label: 'ASII.JK - Astra International' },
    { value: 'UNVR.JK', label: 'UNVR.JK - Unilever Indonesia' },
    { value: 'ICBP.JK', label: 'ICBP.JK - Indofood CBP Sukses Makmur' },
    { value: 'INDF.JK', label: 'INDF.JK - Indofood Sukses Makmur' },
    { value: 'EXCL.JK', label: 'EXCL.JK - XL Axiata' },
    { value: 'KLBF.JK', label: 'KLBF.JK - Kalbe Farma' }
  ];
  
  // Period options
  const periodOptions = [
    { value: '7', label: '7 Days' },
    { value: '14', label: '14 Days' },
    { value: '30', label: '30 Days' },
    { value: '60', label: '60 Days' },
    { value: '90', label: '90 Days' }
  ];
  
  useEffect(() => {
    // Load historical data for the default stock
    fetchHistoricalData(selectedStock);
  }, []);
  
  const fetchHistoricalData = async (ticker) => {
    try {
      setLoading(true);
      setError(null);
      
      // Fetch historical data using the API service
      const data = await fetchStockData(ticker);
      setHistoricalData(data);
      
    } catch (err) {
      console.error(`Error loading data for ${ticker}:`, err);
      setError(`Failed to load historical data for ${ticker}. Please try again.`);
    } finally {
      setLoading(false);
    }
  };
  
  const handleSubmit = (e) => {
    e.preventDefault();
    generatePrediction(selectedStock, predictionPeriod);
  };
  
  const generatePrediction = async (ticker, days) => {
    try {
      setLoading(true);
      setError(null);
      
      // In a real app, this would be an API call to a machine learning model
      // For demo purposes, we'll simulate a prediction
      await new Promise(resolve => setTimeout(resolve, 1500));
      
      // Get historical data for reference
      const lastClose = historicalData?.data[historicalData.data.length - 1]?.Close || 5000;
      const startDate = new Date();
      
      // Generate mock prediction data
      const mockPrediction = {
        ticker,
        generatedAt: new Date().toISOString(),
        predictionPeriod: parseInt(days),
        predictedData: Array(parseInt(days)).fill().map((_, i) => {
          const date = new Date(startDate);
          date.setDate(date.getDate() + i + 1);
          
          // Create a price trend with some randomness
          const randomFactor = 0.01 + (Math.random() * 0.02); // 1-3% daily variation
          const trendFactor = 0.0005; // Slight upward trend
          const price = lastClose * (1 + (i * trendFactor) + ((Math.random() > 0.5 ? 1 : -1) * randomFactor));
          
          return {
            date: date.toISOString().split('T')[0],
            predicted: parseFloat(price.toFixed(2)),
            lower: parseFloat((price * 0.98).toFixed(2)),
            upper: parseFloat((price * 1.02).toFixed(2))
          };
        }),
        summary: {
          startPrice: lastClose,
          endPrice: 0, // Will be set below
          maxPrice: 0,
          minPrice: 0,
          recommendation: ''
        }
      };
      
      // Calculate summary statistics
      const endPrice = mockPrediction.predictedData[mockPrediction.predictedData.length - 1].predicted;
      const prices = mockPrediction.predictedData.map(item => item.predicted);
      const maxPrice = Math.max(...prices);
      const minPrice = Math.min(...prices);
      const priceChange = endPrice - lastClose;
      const priceChangePercent = (priceChange / lastClose) * 100;
      
      // Set recommendation based on prediction
      let recommendation = '';
      if (priceChangePercent > 5) {
        recommendation = 'Strong Buy';
      } else if (priceChangePercent > 2) {
        recommendation = 'Buy';
      } else if (priceChangePercent > -2) {
        recommendation = 'Hold';
      } else if (priceChangePercent > -5) {
        recommendation = 'Sell';
      } else {
        recommendation = 'Strong Sell';
      }
      
      // Update summary
      mockPrediction.summary.endPrice = endPrice;
      mockPrediction.summary.maxPrice = maxPrice;
      mockPrediction.summary.minPrice = minPrice;
      mockPrediction.summary.priceChange = priceChange;
      mockPrediction.summary.priceChangePercent = priceChangePercent;
      mockPrediction.summary.recommendation = recommendation;
      
      setPredictionData(mockPrediction);
      
    } catch (err) {
      console.error('Error generating prediction:', err);
      setError('Failed to generate prediction. Please try again.');
    } finally {
      setLoading(false);
    }
  };
  
  // Prepare data for Plotly chart
  const preparePlotlyData = () => {
    if (!predictionData) return [];
    
    const dates = predictionData.predictedData.map(item => item.date);
    const predictedValues = predictionData.predictedData.map(item => item.predicted);
    const lowerValues = predictionData.predictedData.map(item => item.lower);
    const upperValues = predictionData.predictedData.map(item => item.upper);
    
    return [
      // Prediction line
      {
        type: 'scatter',
        mode: 'lines',
        name: 'Predicted Price',
        x: dates,
        y: predictedValues,
        line: { color: '#3f51b5', width: 3 },
        hovertemplate: '%{x}<br>%{y:,.2f} IDR<extra>Predicted</extra>'
      },
      // Lower bound
      {
        type: 'scatter',
        mode: 'lines',
        name: 'Lower Bound',
        x: dates,
        y: lowerValues,
        line: { color: '#f44336', width: 2, dash: 'dot' },
        hovertemplate: '%{x}<br>%{y:,.2f} IDR<extra>Lower Bound</extra>'
      },
      // Upper bound
      {
        type: 'scatter',
        mode: 'lines',
        name: 'Upper Bound',
        x: dates,
        y: upperValues,
        line: { color: '#4caf50', width: 2, dash: 'dot' },
        hovertemplate: '%{x}<br>%{y:,.2f} IDR<extra>Upper Bound</extra>'
      },
      // Fill area between bounds
      {
        type: 'scatter',
        x: [...dates, ...dates.slice().reverse()],
        y: [...upperValues, ...lowerValues.slice().reverse()],
        fill: 'toself',
        fillcolor: 'rgba(76, 175, 80, 0.1)',
        line: { color: 'transparent' },
        name: 'Confidence Interval',
        showlegend: false,
        hoverinfo: 'skip'
      }
    ];
  };
  
  // Layout config for Plotly chart
  const plotlyLayout = {
    title: {
      text: `${selectedStock} Price Prediction`,
      font: { size: 18 }
    },
    height: 380,
    margin: { l: 50, r: 20, t: 50, b: 30 },
    xaxis: {
      title: 'Date',
      gridcolor: '#f1f1f1'
    },
    yaxis: {
      title: 'Price (IDR)',
      tickprefix: '',
      tickformat: ',.0f',
      gridcolor: '#f1f1f1'
    },
    legend: { orientation: 'h', y: -0.2 },
    hovermode: 'closest',
    paper_bgcolor: 'rgba(0,0,0,0)',
    plot_bgcolor: 'rgba(0,0,0,0)'
  };
  
  return (
    <Container>
      <h3 className="mb-4">Stock Price Prediction</h3>
      
      <Card className="mb-4 dashboard-card">
        <Card.Body>
          <Form onSubmit={handleSubmit}>
            <Row className="align-items-end">
              <Col md={5}>
                <Form.Group className="mb-3 mb-md-0">
                  <Form.Label>Select Stock</Form.Label>
                  <Form.Select 
                    value={selectedStock}
                    onChange={(e) => {
                      setSelectedStock(e.target.value);
                      fetchHistoricalData(e.target.value);
                    }}
                  >
                    {stockOptions.map(option => (
                      <option key={option.value} value={option.value}>
                        {option.label}
                      </option>
                    ))}
                  </Form.Select>
                </Form.Group>
              </Col>
              <Col md={4}>
                <Form.Group className="mb-3 mb-md-0">
                  <Form.Label>Prediction Period</Form.Label>
                  <Form.Select 
                    value={predictionPeriod}
                    onChange={(e) => setPredictionPeriod(e.target.value)}
                  >
                    {periodOptions.map(option => (
                      <option key={option.value} value={option.value}>
                        {option.label}
                      </option>
                    ))}
                  </Form.Select>
                </Form.Group>
              </Col>
              <Col md={3}>
                <Button 
                  type="submit" 
                  variant="primary" 
                  className="w-100"
                  disabled={loading}
                >
                  {loading ? (
                    <>
                      <Spinner as="span" animation="border" size="sm" className="me-2" />
                      Generating...
                    </>
                  ) : (
                    'Generate Prediction'
                  )}
                </Button>
              </Col>
            </Row>
          </Form>
        </Card.Body>
      </Card>
      
      {error && (
        <Alert variant="danger" className="mb-4" onClose={() => setError(null)} dismissible>
          {error}
        </Alert>
      )}
      
      {predictionData && (
        <>
          <Card className="mb-4 dashboard-card">
            <Card.Header className="bg-white">
              <div className="d-flex justify-content-between align-items-center">
                <h5 className="mb-0">Price Prediction Chart</h5>
                <div className="text-muted">
                  Generated on: {new Date(predictionData.generatedAt).toLocaleString()}
                </div>
              </div>
            </Card.Header>
            <Card.Body>
              <Plot
                data={preparePlotlyData()}
                layout={plotlyLayout}
                config={{ responsive: true, displayModeBar: false }}
                style={{ width: '100%', height: '100%' }}
              />
            </Card.Body>
          </Card>
          
          <Row className="mb-4">
            <Col md={8}>
              <Card className="h-100 dashboard-card">
                <Card.Header className="bg-white">
                  <h5 className="mb-0">Prediction Summary</h5>
                </Card.Header>
                <Card.Body>
                  <Row>
                    <Col md={6} className="mb-3">
                      <div className="border p-3 rounded h-100">
                        <h6>Price Change (Predicted)</h6>
                        <div className="d-flex justify-content-between align-items-center mt-3">
                          <div>
                            <div className="small text-muted">Current</div>
                            <div className="fs-5">{formatCurrency(predictionData.summary.startPrice, 'IDR')}</div>
                          </div>
                          <div className="text-center">
                            <div className={`fs-3 ${getValueColor(predictionData.summary.priceChange)}`}>
                              {predictionData.summary.priceChange >= 0 ? '→' : '→'}
                            </div>
                          </div>
                          <div className="text-end">
                            <div className="small text-muted">Predicted ({predictionData.predictionPeriod} days)</div>
                            <div className="fs-5">{formatCurrency(predictionData.summary.endPrice, 'IDR')}</div>
                          </div>
                        </div>
                        <div className={`text-center mt-3 ${getValueColor(predictionData.summary.priceChange)}`}>
                          <span className="fs-4">
                            {predictionData.summary.priceChange >= 0 ? '+' : ''}
                            {formatCurrency(predictionData.summary.priceChange, 'IDR')}
                          </span>
                          <span className="ms-2">
                            ({predictionData.summary.priceChange >= 0 ? '+' : ''}
                            {predictionData.summary.priceChangePercent.toFixed(2)}%)
                          </span>
                        </div>
                      </div>
                    </Col>
                    <Col md={6} className="mb-3">
                      <div className="border p-3 rounded h-100">
                        <h6>Investment Recommendation</h6>
                        <div className="text-center my-3">
                          <div className={`recommendation-badge p-2 rounded-pill d-inline-block px-4 
                            ${predictionData.summary.recommendation.includes('Buy') ? 'bg-success text-white' : 
                            predictionData.summary.recommendation.includes('Sell') ? 'bg-danger text-white' : 
                            'bg-warning'}`}>
                            <span className="fs-5 fw-bold">{predictionData.summary.recommendation}</span>
                          </div>
                        </div>
                        <div className="small mt-3">
                          <div className="d-flex justify-content-between mb-2">
                            <span>Confidence Level:</span>
                            <span className="fw-bold">Medium</span>
                          </div>
                          <div className="d-flex justify-content-between mb-2">
                            <span>Based on:</span>
                            <span className="fw-bold">Time Series Analysis</span>
                          </div>
                          <div className="d-flex justify-content-between">
                            <span>Prediction Horizon:</span>
                            <span className="fw-bold">{predictionData.predictionPeriod} Days</span>
                          </div>
                        </div>
                      </div>
                    </Col>
                  </Row>
                  
                  <div className="border p-3 rounded mt-3">
                    <h6>Price Range (Predicted)</h6>
                    <Table responsive className="mt-3 mb-0">
                      <thead>
                        <tr>
                          <th>Metric</th>
                          <th>Price</th>
                          <th>Change from Current</th>
                          <th>Potential Return</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Minimum Price</td>
                          <td>{formatCurrency(predictionData.summary.minPrice, 'IDR')}</td>
                          <td className={getValueColor(predictionData.summary.minPrice - predictionData.summary.startPrice)}>
                            {formatCurrency(predictionData.summary.minPrice - predictionData.summary.startPrice, 'IDR')}
                          </td>
                          <td className={getValueColor(predictionData.summary.minPrice - predictionData.summary.startPrice)}>
                            {((predictionData.summary.minPrice - predictionData.summary.startPrice) / predictionData.summary.startPrice * 100).toFixed(2)}%
                          </td>
                        </tr>
                        <tr>
                          <td>Expected Price</td>
                          <td>{formatCurrency(predictionData.summary.endPrice, 'IDR')}</td>
                          <td className={getValueColor(predictionData.summary.endPrice - predictionData.summary.startPrice)}>
                            {formatCurrency(predictionData.summary.endPrice - predictionData.summary.startPrice, 'IDR')}
                          </td>
                          <td className={getValueColor(predictionData.summary.endPrice - predictionData.summary.startPrice)}>
                            {((predictionData.summary.endPrice - predictionData.summary.startPrice) / predictionData.summary.startPrice * 100).toFixed(2)}%
                          </td>
                        </tr>
                        <tr>
                          <td>Maximum Price</td>
                          <td>{formatCurrency(predictionData.summary.maxPrice, 'IDR')}</td>
                          <td className={getValueColor(predictionData.summary.maxPrice - predictionData.summary.startPrice)}>
                            {formatCurrency(predictionData.summary.maxPrice - predictionData.summary.startPrice, 'IDR')}
                          </td>
                          <td className={getValueColor(predictionData.summary.maxPrice - predictionData.summary.startPrice)}>
                            {((predictionData.summary.maxPrice - predictionData.summary.startPrice) / predictionData.summary.startPrice * 100).toFixed(2)}%
                          </td>
                        </tr>
                      </tbody>
                    </Table>
                  </div>
                </Card.Body>
              </Card>
            </Col>
            
            <Col md={4}>
              <Card className="h-100 dashboard-card">
                <Card.Header className="bg-white">
                  <h5 className="mb-0">Prediction Data Table</h5>
                </Card.Header>
                <Card.Body className="p-0">
                  <div style={{ maxHeight: '400px', overflowY: 'auto' }}>
                    <Table responsive className="mb-0">
                      <thead className="sticky-top bg-light">
                        <tr>
                          <th>Date</th>
                          <th>Predicted</th>
                          <th>Range</th>
                        </tr>
                      </thead>
                      <tbody>
                        {predictionData.predictedData.map((item, index) => (
                          <tr key={index}>
                            <td>{new Date(item.date).toLocaleDateString()}</td>
                            <td>{formatCurrency(item.predicted, 'IDR')}</td>
                            <td className="small">
                              {formatCurrency(item.lower, 'IDR')} - {formatCurrency(item.upper, 'IDR')}
                            </td>
                          </tr>
                        ))}
                      </tbody>
                    </Table>
                  </div>
                </Card.Body>
              </Card>
            </Col>
          </Row>
          
          <div className="alert alert-info mb-4">
            <div className="d-flex">
              <i className="bx bx-info-circle fs-4 me-2"></i>
              <div>
                <strong>Disclaimer:</strong> The predictions shown are for demonstration purposes only and should not be considered as financial advice. 
                Stock market investments involve risk, and past performance is not indicative of future results. 
                Always conduct your own research and consider consulting with a financial advisor before making investment decisions.
              </div>
            </div>
          </div>
        </>
      )}
      
      {!predictionData && !loading && (
        <div className="text-center py-5 border rounded bg-light">
          <i className="bx bx-line-chart fs-1 text-primary mb-3"></i>
          <h5>Generate stock price predictions</h5>
          <p className="text-muted">
            Select a stock and prediction period, then click "Generate Prediction" to see the forecast.
          </p>
        </div>
      )}
    </Container>
  );
};

export default Prediction;
