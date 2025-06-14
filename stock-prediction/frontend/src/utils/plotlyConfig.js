/**
 * Konfigurasi Optimasi Plotly.js
 * 
 * File ini berisi konfigurasi standar untuk mengoptimalkan performa chart Plotly.js
 * di platform desktop dan mobile.
 */

// Konfigurasi dasar untuk semua chart Plotly
export const baseConfig = {
  responsive: true,
  displayModeBar: true,
  displaylogo: false,
  modeBarButtonsToRemove: [
    'lasso2d', 
    'select2d',
    'toggleSpikelines'
  ],
  toImageButtonOptions: {
    format: 'png',
    filename: 'zahaam-chart',
    height: 500,
    width: 700,
    scale: 1
  }
};

// Konfigurasi khusus untuk device mobile
export const mobileConfig = {
  ...baseConfig,
  responsive: true,
  displayModeBar: 'hover',
  scrollZoom: false,
  staticPlot: false, // Disable interaktivitas untuk performa lebih baik
  modeBarButtonsToRemove: [
    'lasso2d', 
    'select2d',
    'toggleSpikelines',
    'zoom2d',
    'pan2d',
    'zoomIn2d', 
    'zoomOut2d',
    'autoScale2d'
  ]
};

// Fungsi untuk mendeteksi device mobile
export const isMobile = () => {
  return window.innerWidth <= 768;
};

// Fungsi untuk mendapatkan konfigurasi berdasarkan device
export const getResponsiveConfig = () => {
  return isMobile() ? mobileConfig : baseConfig;
};

// Layout optimasi untuk tampilan mobile
export const getMobileOptimizedLayout = (baseLayout) => {
  return {
    ...baseLayout,
    height: 300, // Lebih kecil untuk tampilan mobile
    margin: { 
      l: 40, 
      r: 20, 
      t: 30, 
      b: 40 
    },
    legend: {
      orientation: 'h', // Legend horizontal untuk mobile
      y: -0.2
    },
    font: {
      size: 10 // Font lebih kecil untuk mobile
    },
    modebar: {
      orientation: 'v'
    }
  };
};

// Konfigurasi untuk optimasi ukuran data
export const optimizeDataSize = (originalData, maxPoints = 100) => {
  // Jika data point lebih kecil dari maxPoints, kembalikan data asli
  if (originalData.length <= maxPoints) return originalData;
  
  // Jika tidak, lakukan sampling data
  const downsampleRatio = Math.ceil(originalData.length / maxPoints);
  return originalData.filter((_, index) => index % downsampleRatio === 0);
};
