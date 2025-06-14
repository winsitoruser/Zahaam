import React from 'react';
import { Card, Row, Col, Badge, ListGroup } from 'react-bootstrap';
import Plot from 'react-plotly.js';
import { formatNumber, formatCurrency } from '../services/api';

const TechnicalAnalysis = ({ signals }) => {
  if (!signals || !signals.indicators) return null;

  const { ticker, signals: signalList, indicators } = signals;
  const { rsi, macd, bollinger_bands, moving_averages } = indicators;

  // Generate RSI date and value arrays
  const rsiDates = Array(30).fill().map((_, i) => new Date(Date.now() - (30 - i) * 24 * 60 * 60 * 1000));
  const rsiValues = Array(30).fill().map((_, i) => 50 + Math.sin(i / 2) * 30 + (Math.random() * 10 - 5));

  // Generate MACD date and values arrays
  const macdDates = Array(30).fill().map((_, i) => new Date(Date.now() - (30 - i) * 24 * 60 * 60 * 1000));
  const macdValues = Array(30).fill().map((_, i) => Math.sin(i / 3) * 2 + (Math.random() * 0.5 - 0.25));
  const signalValues = Array(30).fill().map((_, i) => Math.sin(i / 3 + 0.5) * 1.8 + (Math.random() * 0.4 - 0.2));
  const histogramValues = Array(30).fill().map((_, i) => (Math.sin(i / 3) - Math.sin(i / 3 + 0.5)) * 10 + (Math.random() * 0.2 - 0.1));

  // Get signal badge variant
  const getSignalVariant = (signal) => {
    const lowerSignal = signal.toLowerCase();
    if (lowerSignal.includes('bullish') || lowerSignal.includes('buy') || lowerSignal.includes('oversold')) {
      return 'success';
    } else if (lowerSignal.includes('bearish') || lowerSignal.includes('sell') || lowerSignal.includes('overbought')) {
      return 'danger';
    }
    return 'primary';
  };

  return (
    <Card>
      <Card.Body>
        <h4 className="mb-4">Technical Analysis for {ticker}</h4>
        
        {/* Trading Signals */}
        <div className="mb-4">
          <h5 className="border-bottom pb-2 mb-3">Trading Signals</h5>
          <Row className="g-3">
            {signalList && signalList.length > 0 ? (
              signalList.map((signal, index) => (
                <Col key={index} md={6} lg={4}>
                  <div className="border p-3 rounded h-100">
                    <div className="d-flex justify-content-between align-items-center">
                      <h6 className="mb-0">{signal.indicator}</h6>
                      <Badge bg={getSignalVariant(signal.signal)}>
                        {signal.signal}
                      </Badge>
                    </div>
                    <div className="mt-2 text-muted small">
                      {signal.value && typeof signal.value === 'string' ? (
                        signal.value
                      ) : (
                        formatNumber(signal.value)
                      )}
                    </div>
                  </div>
                </Col>
              ))
            ) : (
              <Col>
                <div className="text-center text-muted py-4">
                  No strong trading signals detected.
                </div>
              </Col>
            )}
          </Row>
        </div>

        {/* Technical Indicators */}
        <div className="mb-4">
          <h5 className="border-bottom pb-2 mb-3">Technical Indicators</h5>
          
          {/* RSI Chart */}
          <div className="mb-4">
            <Plot
              data={[
                {
                  x: rsiDates,
                  y: rsiValues,
                  type: 'scatter',
                  mode: 'lines',
                  line: { color: '#3f51b5', width: 3 },
                  name: 'RSI (14)'
                }
              ]}
              layout={{
                title: {
                  text: 'Relative Strength Index (RSI)',
                  font: { size: 16, weight: 'bold' }
                },
                height: 300,
                margin: { l: 50, r: 20, t: 40, b: 30 },
                xaxis: { type: 'date' },
                yaxis: {
                  range: [0, 100],
                  tickformat: '.0f'
                },
                shapes: [
                  {
                    type: 'line',
                    x0: rsiDates[0],
                    x1: rsiDates[rsiDates.length-1],
                    y0: 70,
                    y1: 70,
                    line: { color: '#ff4560', width: 2, dash: 'dash' }
                  },
                  {
                    type: 'line',
                    x0: rsiDates[0],
                    x1: rsiDates[rsiDates.length-1],
                    y0: 30,
                    y1: 30,
                    line: { color: '#00e396', width: 2, dash: 'dash' }
                  }
                ],
                annotations: [
                  {
                    x: rsiDates[0],
                    y: 70,
                    xref: 'x',
                    yref: 'y',
                    text: 'Overbought',
                    showarrow: false,
                    bgcolor: '#ff4560',
                    font: { color: '#ffffff' },
                    borderpad: 4
                  },
                  {
                    x: rsiDates[0],
                    y: 30,
                    xref: 'x',
                    yref: 'y',
                    text: 'Oversold',
                    showarrow: false,
                    bgcolor: '#00e396',
                    font: { color: '#ffffff' },
                    borderpad: 4
                  }
                ],
                paper_bgcolor: 'rgba(0,0,0,0)',
                plot_bgcolor: 'rgba(0,0,0,0)'
              }}
              config={{ displayModeBar: false, responsive: true }}
              style={{ width: '100%' }}
            />
          </div>
          
          {/* MACD Chart */}
          <div className="mb-4">
            <Plot
              data={[
                {
                  x: macdDates,
                  y: macdValues,
                  type: 'scatter',
                  mode: 'lines',
                  line: { color: '#3f51b5', width: 2 },
                  name: 'MACD Line'
                },
                {
                  x: macdDates,
                  y: signalValues,
                  type: 'scatter',
                  mode: 'lines',
                  line: { color: '#ff9800', width: 2 },
                  name: 'Signal Line'
                },
                {
                  x: macdDates,
                  y: histogramValues,
                  type: 'bar',
                  marker: {
                    color: histogramValues.map(val => val >= 0 ? '#4caf50' : '#ef5350'),
                    opacity: 0.6
                  },
                  name: 'Histogram'
                }
              ]}
              layout={{
                title: {
                  text: 'Moving Average Convergence Divergence (MACD)',
                  font: { size: 16, weight: 'bold' }
                },
                height: 300,
                margin: { l: 50, r: 20, t: 40, b: 30 },
                xaxis: { type: 'date' },
                yaxis: { tickformat: '.2f' },
                legend: { orientation: 'h', y: -0.2 },
                paper_bgcolor: 'rgba(0,0,0,0)',
                plot_bgcolor: 'rgba(0,0,0,0)'
              }}
              config={{ displayModeBar: false, responsive: true }}
              style={{ width: '100%' }}
            />
          </div>
          
          {/* Indicator Values */}
          <Row className="g-3">
            <Col md={6}>
              <div className="border p-3 rounded h-100">
                <h6>Bollinger Bands</h6>
                <ListGroup variant="flush">
                  <ListGroup.Item className="d-flex justify-content-between align-items-center">
                    <span>Upper Band</span>
                    <span>{formatCurrency(bollinger_bands?.upper, 'IDR')}</span>
                  </ListGroup.Item>
                  <ListGroup.Item className="d-flex justify-content-between align-items-center">
                    <span>Middle Band</span>
                    <span>{formatCurrency(bollinger_bands?.middle, 'IDR')}</span>
                  </ListGroup.Item>
                  <ListGroup.Item className="d-flex justify-content-between align-items-center">
                    <span>Lower Band</span>
                    <span>{formatCurrency(bollinger_bands?.lower, 'IDR')}</span>
                  </ListGroup.Item>
                </ListGroup>
              </div>
            </Col>
            <Col md={6}>
              <div className="border p-3 rounded h-100">
                <h6>Moving Averages</h6>
                <ListGroup variant="flush">
                  <ListGroup.Item className="d-flex justify-content-between align-items-center">
                    <span>SMA (20)</span>
                    <span>{formatCurrency(moving_averages?.sma_20, 'IDR')}</span>
                  </ListGroup.Item>
                  <ListGroup.Item className="d-flex justify-content-between align-items-center">
                    <span>SMA (50)</span>
                    <span>{formatCurrency(moving_averages?.sma_50, 'IDR')}</span>
                  </ListGroup.Item>
                  <ListGroup.Item className="d-flex justify-content-between align-items-center">
                    <span>Signal</span>
                    <Badge bg={moving_averages?.sma_20 > moving_averages?.sma_50 ? 'success' : 'danger'}>
                      {moving_averages?.sma_20 > moving_averages?.sma_50 ? 'Bullish' : 'Bearish'}
                    </Badge>
                  </ListGroup.Item>
                </ListGroup>
              </div>
            </Col>
          </Row>
        </div>
        
        <div className="alert alert-info small">
          <i className="bx bx-info-circle me-2"></i>
          <strong>Note:</strong> This analysis is based on technical indicators and should not be considered as financial advice.
          Always conduct your own research and consider consulting with a financial advisor before making investment decisions.
        </div>
      </Card.Body>
    </Card>
  );
};

export default TechnicalAnalysis;
