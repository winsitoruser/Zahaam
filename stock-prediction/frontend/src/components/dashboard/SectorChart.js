import React from 'react';
import Chart from 'react-apexcharts';

const SectorChart = ({ data }) => {
  const chartOptions = {
    chart: {
      type: 'donut',
      height: 320,
      fontFamily: 'Segoe UI, Tahoma, Geneva, Verdana, sans-serif',
      background: 'transparent',
      toolbar: {
        show: false
      }
    },
    legend: {
      position: 'bottom',
      fontSize: '12px',
      markers: {
        width: 10,
        height: 10,
        radius: 50
      },
      itemMargin: {
        horizontal: 8,
        vertical: 4
      }
    },
    colors: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14', '#20c997', '#6c757d', '#0dcaf0', '#d63384'],
    labels: data.map(sector => sector.name),
    plotOptions: {
      pie: {
        donut: {
          size: '65%',
          labels: {
            show: true,
            name: {
              show: true,
              fontSize: '14px',
              fontWeight: 600,
            },
            value: {
              show: true,
              fontSize: '16px',
              fontWeight: 400,
              formatter: function(val) {
                return val + '%';
              }
            },
            total: {
              show: true,
              label: 'Total',
              formatter: function() {
                return '100%';
              }
            }
          }
        }
      }
    },
    dataLabels: {
      enabled: false
    },
    responsive: [{
      breakpoint: 992, // lg breakpoint
      options: {
        chart: {
          height: 300
        },
        legend: {
          position: 'right'
        },
      }
    }, {
      breakpoint: 767, // md breakpoint
      options: {
        chart: {
          height: 280
        },
        legend: {
          position: 'bottom'
        }
      }
    }, {
      breakpoint: 480, // sm breakpoint
      options: {
        chart: {
          height: 300
        },
        legend: {
          position: 'bottom'
        }
      }
    }],
    tooltip: {
      y: {
        formatter: function(val) {
          return val + '%';
        }
      }
    }
  };

  const chartSeries = data.map(sector => sector.percentage);

  return (
    <Chart
      options={chartOptions}
      series={chartSeries}
      type="donut"
      height={350}
    />
  );
};

export default SectorChart;
