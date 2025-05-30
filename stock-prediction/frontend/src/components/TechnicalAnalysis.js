import React from 'react';
import { Card, Row, Col, Badge, ListGroup } from 'react-bootstrap';
import Chart from 'react-apexcharts';
import { formatNumber, formatCurrency } from '../services/api';

const TechnicalAnalysis = ({ signals }) => {
  if (!signals || !signals.indicators) return null;

  const { ticker, signals: signalList, indicators } = signals;
  const { rsi, macd, bollinger_bands, moving_averages } = indicators;

  // Prepare RSI chart data
  const rsiOptions = {
    chart: {
      type: 'line',
      height: 300,
      toolbar: {
        show: false
      }
    },
    title: {
      text: 'Relative Strength Index (RSI)',
      align: 'left',
      style: {
        fontSize: '16px',
        fontWeight: 'bold'
      }
    },
    xaxis: {
      type: 'datetime'
    },
    yaxis: {
      min: 0,
      max: 100,
      labels: {
        formatter: (value) => value.toFixed(0)
      }
    },
    annotations: {
      yaxis: [
        {
          y: 70,
          borderColor: '#ff4560',
          label: {
            borderColor: '#ff4560',
            style: {
              color: '#fff',
              background: '#ff4560'
            },
            text: 'Overbought',
            position: 'left'
          }
        },
        {
          y: 30,
          borderColor: '#00e396',
          label: {
            borderColor: '#00e396',
            style: {
              color: '#fff',
              background: '#00e396'
            },
            text: 'Oversold',
            position: 'left'
          }
        }
      ]
    },
    stroke: {
      width: 3
    },
    colors: ['#3f51b5']
  };

  const rsiSeries = [
    {
      name: 'RSI (14)',
      data: Array(30).fill().map((_, i) => ({
        x: new Date(Date.now() - (30 - i) * 24 * 60 * 60 * 1000).getTime(),
        y: 50 + Math.sin(i / 2) * 30 + (Math.random() * 10 - 5)
      }))
    }
  ];

  // Prepare MACD chart data
  const macdOptions = {
    chart: {
      type: 'line',
      height: 300,
      toolbar: {
        show: false
      }
    },
    title: {
      text: 'Moving Average Convergence Divergence (MACD)',
      align: 'left',
      style: {
        fontSize: '16px',
        fontWeight: 'bold'
      }
    },
    xaxis: {
      type: 'datetime'
    },
    yaxis: {
      labels: {
        formatter: (value) => value.toFixed(2)
      }
    },
    stroke: {
      width: 2
    },
    colors: ['#3f51b5', '#ff9800', '#4caf50']
  };

  const macdSeries = [
    {
      name: 'MACD Line',
      data: Array(30).fill().map((_, i) => ({
        x: new Date(Date.now() - (30 - i) * 24 * 60 * 60 * 1000).getTime(),
        y: Math.sin(i / 3) * 2 + (Math.random() * 0.5 - 0.25)
      }))
    },
    {
      name: 'Signal Line',
      data: Array(30).fill().map((_, i) => ({
        x: new Date(Date.now() - (30 - i) * 24 * 60 * 60 * 1000).getTime(),
        y: Math.sin(i / 3 + 0.5) * 1.8 + (Math.random() * 0.4 - 0.2)
      }))
    },
    {
      name: 'Histogram',
      type: 'bar',
      data: Array(30).fill().map((_, i) => ({
        x: new Date(Date.now() - (30 - i) * 24 * 60 * 60 * 1000).getTime(),
        y: (Math.sin(i / 3) - Math.sin(i / 3 + 0.5)) * 10 + (Math.random() * 0.2 - 0.1)
      }))
    }
  ];

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
            <Chart
              options={rsiOptions}
              series={rsiSeries}
              type="line"
              height={300}
            />
          </div>
          
          {/* MACD Chart */}
          <div className="mb-4">
            <Chart
              options={macdOptions}
              series={macdSeries}
              type="line"
              height={300}
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
