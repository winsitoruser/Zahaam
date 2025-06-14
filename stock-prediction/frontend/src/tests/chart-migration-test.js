/**
 * Chart Migration Test Script
 * 
 * Tujuan: Memastikan semua komponen chart yang dimigrasi dari ApexCharts ke Plotly.js
 * berfungsi dengan baik dan konsisten dengan desain UI Zahaam.
 */

import React from 'react';
import { render, screen } from '@testing-library/react';
import { MemoryRouter } from 'react-router-dom';

// Import komponen yang dimigrasi
import Prediction from '../pages/Prediction';
import StockAnalysis from '../pages/StockAnalysis';
import StockDetail from '../components/StockDetail';
import SectorChart from '../components/dashboard/SectorChart';

describe('Chart Migration Tests', () => {
  
  // Mock data untuk pengujian
  const mockPredictionData = {
    ticker: 'BBCA',
    predictions: [
      { date: '2025-06-01', predicted: 9200, lower_bound: 9000, upper_bound: 9400 },
      { date: '2025-06-02', predicted: 9250, lower_bound: 9050, upper_bound: 9450 }
    ]
  };
  
  const mockStockData = {
    prices: [
      { date: '2025-06-01', open: 9100, high: 9300, low: 9000, close: 9200, volume: 1000000 },
      { date: '2025-06-02', open: 9200, high: 9400, low: 9100, close: 9300, volume: 1200000 }
    ]
  };
  
  const mockSectorData = {
    sectors: [
      { name: 'Banking', percentage: 30 },
      { name: 'Technology', percentage: 25 },
      { name: 'Consumer', percentage: 20 },
      { name: 'Energy', percentage: 15 },
      { name: 'Others', percentage: 10 }
    ]
  };
  
  const mockStockDetail = {
    ticker: 'BBCA',
    company: { 
      name: 'Bank Central Asia', 
      currentPrice: 9200, 
      previousClose: 9100
    },
    data: [
      { Date: '2025-06-01', Open: 9100, High: 9300, Low: 9000, Close: 9200, Volume: 1000000 },
      { Date: '2025-06-02', Open: 9200, High: 9400, Low: 9100, Close: 9300, Volume: 1200000 }
    ]
  };

  test('Prediction component renders Plotly chart correctly', () => {
    render(
      <MemoryRouter>
        <Prediction predictionData={mockPredictionData} />
      </MemoryRouter>
    );
    
    // Verifikasi komponen telah di-render
    expect(screen.getByText(/price prediction/i)).toBeInTheDocument();
    
    // Verifikasi elemen Plotly ada
    const plotlyElement = document.querySelector('.js-plotly-plot');
    expect(plotlyElement).toBeInTheDocument();
  });

  test('StockAnalysis component renders Plotly chart correctly', () => {
    render(
      <MemoryRouter>
        <StockAnalysis stockData={mockStockData} />
      </MemoryRouter>
    );
    
    // Verifikasi komponen telah di-render
    expect(screen.getByText(/candlestick chart/i)).toBeInTheDocument();
    
    // Verifikasi elemen Plotly ada
    const plotlyElement = document.querySelector('.js-plotly-plot');
    expect(plotlyElement).toBeInTheDocument();
  });

  test('SectorChart component renders Plotly chart correctly', () => {
    render(<SectorChart data={mockSectorData} />);
    
    // Verifikasi komponen telah di-render
    expect(screen.getByText(/sector distribution/i)).toBeInTheDocument();
    
    // Verifikasi elemen Plotly ada
    const plotlyElement = document.querySelector('.js-plotly-plot');
    expect(plotlyElement).toBeInTheDocument();
  });

  test('StockDetail component renders Plotly chart correctly', () => {
    render(<StockDetail stockData={mockStockDetail} />);
    
    // Verifikasi komponen telah di-render
    expect(screen.getByText(/Bank Central Asia/i)).toBeInTheDocument();
    
    // Verifikasi elemen Plotly ada
    const plotlyElement = document.querySelector('.js-plotly-plot');
    expect(plotlyElement).toBeInTheDocument();
  });
});
