import React from 'react';
import Plot from 'react-plotly.js';

const SectorChart = ({ data }) => {
  // Prepare data for Plotly pie chart
  const labels = data.map(sector => sector.name);
  const values = data.map(sector => sector.percentage);
  const colors = ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14', '#20c997', '#6c757d', '#0dcaf0', '#d63384'];
  
  // Format values with % for hover info
  const hoverText = values.map(val => val + '%');

  return (
    <Plot
      data={[{
        type: 'pie',
        hole: 0.65, // Creates a donut chart (65% hole size)
        labels: labels,
        values: values,
        textinfo: 'label+percent',
        textposition: 'outside',
        automargin: true,
        marker: {
          colors: colors,
          line: {
            color: '#FFFFFF',
            width: 1
          }
        },
        hovertemplate: '%{label}: %{value}%<extra></extra>',
        pull: 0.01, // Slightly separated sectors for better visual
        insidetextorientation: 'radial',
        direction: 'clockwise'
      }]}
      layout={{
        height: 350,
        margin: { t: 10, b: 40, l: 10, r: 10 },
        showlegend: true,
        legend: {
          orientation: 'h',
          xanchor: 'center',
          yanchor: 'bottom',
          y: -0.2,
          x: 0.5
        },
        font: {
          family: 'Segoe UI, Tahoma, Geneva, Verdana, sans-serif',
          size: 12
        },
        annotations: [{
          font: {
            size: 16,
            color: '#212529',
            family: 'Segoe UI, Tahoma, Geneva, Verdana, sans-serif',
          },
          showarrow: false,
          text: 'Total<br>100%',
          x: 0.5,
          y: 0.5
        }],
        paper_bgcolor: 'rgba(0,0,0,0)',
        plot_bgcolor: 'rgba(0,0,0,0)'
      }}
      config={{
        displayModeBar: false,
        responsive: true
      }}
      style={{ width: '100%', height: '100%' }}
    />
  );
};

export default SectorChart;
