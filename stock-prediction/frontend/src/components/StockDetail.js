import React, { useEffect, useRef } from 'react';
import { Card, Row, Col, Badge } from 'react-bootstrap';
import Chart from 'react-apexcharts';
import { formatCurrency, formatPercentage, getValueColor } from '../services/api';

const StockDetail = ({ stockData }) => {
  const chartRef = useRef(null);
  
  if (!stockData) return null;

  const { ticker, data, indicators, company } = stockData;
  
  // Prepare chart data
  const chartOptions = {
    chart: {
      type: 'candlestick',
      height: 350,
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
        },
      },
    },
    title: {
      text: `${company?.name || ticker} (${ticker})`,
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
      tooltip: {
        enabled: true
      },
      labels: {
        formatter: (value) => formatCurrency(value, 'IDR').replace('IDR', '').trim()
      }
    },
    plotOptions: {
      candlestick: {
        colors: {
          upward: '#00B746',
          downward: '#EF403C'
        },
        wick: {
          useFillColor: true
        }
      }
    },
    tooltip: {
      enabled: true,
      x: {
        show: true,
        format: 'dd MMM yyyy',
        formatter: undefined,
      },
    },
  };

  const chartSeries = [
    {
      name: 'candlestick',
      data: data.map(item => ({
        x: new Date(item.Date).getTime(),
        y: [
          parseFloat(item.Open),
          parseFloat(item.High),
          parseFloat(item.Low),
          parseFloat(item.Close)
        ]
      }))
    }
  ];

  // Calculate price change
  const priceChange = company?.currentPrice - company?.previousClose;
  const priceChangePercent = (priceChange / company?.previousClose) * 100;

  return (
    <Card className="mb-4">
      <Card.Body>
        <div className="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h2 className="mb-0">{company?.name || ticker} <small className="text-muted">{ticker}</small></h2>
            <div className="text-muted">
              {company?.sector} • {company?.industry}
            </div>
          </div>
          <div className="text-end">
            <h3 className="mb-0">{formatCurrency(company?.currentPrice, 'IDR')}</h3>
            <div className={getValueColor(priceChange)}>
              {priceChange >= 0 ? '▲' : '▼'} {formatCurrency(Math.abs(priceChange), 'IDR')} 
              ({priceChangePercent.toFixed(2)}%)
            </div>
            <div className="text-muted small">
              Prev Close: {formatCurrency(company?.previousClose, 'IDR')}
            </div>
          </div>
        </div>

        <div className="mb-4" style={{ height: '350px' }}>
          <Chart
            options={chartOptions}
            series={chartSeries}
            type="candlestick"
            height="100%"
          />
        </div>

        <Row className="g-3">
          <Col md={4}>
            <div className="border p-3 rounded h-100">
              <h5 className="border-bottom pb-2 mb-3">Key Stats</h5>
              <div className="d-flex justify-content-between mb-2">
                <span className="text-muted">Open</span>
                <span>{formatCurrency(company?.open, 'IDR')}</span>
              </div>
              <div className="d-flex justify-content-between mb-2">
                <span className="text-muted">High</span>
                <span>{formatCurrency(company?.dayHigh, 'IDR')}</span>
              </div>
              <div className="d-flex justify-content-between mb-2">
                <span className="text-muted">Low</span>
                <span>{formatCurrency(company?.dayLow, 'IDR')}</span>
              </div>
              <div className="d-flex justify-content-between mb-2">
                <span className="text-muted">52W High</span>
                <span>{formatCurrency(company?.fiftyTwoWeekHigh, 'IDR')}</span>
              </div>
              <div className="d-flex justify-content-between">
                <span className="text-muted">52W Low</span>
                <span>{formatCurrency(company?.fiftyTwoWeekLow, 'IDR')}</span>
              </div>
            </div>
          </Col>
          
          <Col md={4}>
            <div className="border p-3 rounded h-100">
              <h5 className="border-bottom pb-2 mb-3">Valuation</h5>
              <div className="d-flex justify-content-between mb-2">
                <span className="text-muted">Market Cap</span>
                <span>{formatCurrency(company?.marketCap, 'IDR')}</span>
              </div>
              <div className="d-flex justify-content-between mb-2">
                <span className="text-muted">Volume</span>
                <span>{formatNumber(company?.volume)}</span>
              </div>
              <div className="d-flex justify-content-between mb-2">
                <span className="text-muted">Avg Volume</span>
                <span>{formatNumber(company?.averageVolume || company?.volume)}</span>
              </div>
            </div>
          </Col>
          
          <Col md={4}>
            <div className="border p-3 rounded h-100">
              <h5 className="border-bottom pb-2 mb-3">Technical Indicators</h5>
              <div className="d-flex justify-content-between mb-2">
                <span className="text-muted">RSI (14)</span>
                <span className={indicators?.rsi > 70 ? 'text-danger' : indicators?.rsi < 30 ? 'text-success' : ''}>
                  {indicators?.rsi ? indicators.rsi.toFixed(2) : 'N/A'}
                  {indicators?.rsi > 70 && <Badge bg="danger" className="ms-2">Overbought</Badge>}
                  {indicators?.rsi < 30 && <Badge bg="success" className="ms-2">Oversold</Badge>}
                </span>
              </div>
              <div className="d-flex justify-content-between mb-2">
                <span className="text-muted">SMA (20)</span>
                <span>{indicators?.sma_20 ? formatCurrency(indicators.sma_20, 'IDR') : 'N/A'}</span>
              </div>
              <div className="d-flex justify-content-between">
                <span className="text-muted">SMA (50)</span>
                <span>{indicators?.sma_50 ? formatCurrency(indicators.sma_50, 'IDR') : 'N/A'}</span>
              </div>
            </div>
          </Col>
        </Row>
      </Card.Body>
    </Card>
  );
};

export default StockDetail;
