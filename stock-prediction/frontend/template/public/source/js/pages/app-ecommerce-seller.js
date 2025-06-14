/*
Template Name: Larkon - Responsive 5 Admin Dashboard
Author: Techzaa
File: dashboard - ecommerce js
*/

//
// sales_funnel
//
var options = {
    chart: {
        type: "area",
        height: 60,
        sparkline: {
            enabled: true,
        },
    },
    series: [
        {
            data: [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54],
        },
    ],
    stroke: {
        width: 2,
        curve: "smooth",
    },
    fill: {
        type: "gradient",
        gradient: {
            shade: "light",
            type: "vertical",
            opacityFrom: 0.4,
            opacityTo: 0,
            stops: [0, 100],
        },
    },

    markers: {
        size: 0,
    },
    colors: ["#22c55e"],
    tooltip: {
        fixed: {
            enabled: false,
        },
        x: {
            show: false,
        },
        y: {
            title: {
                formatter: function (seriesName) {
                    return "";
                },
            },
        },
        marker: {
            show: false,
        },
    },
};

var sales = document.querySelector("#sales_funnel")
if (sales) {
    var chart = new ApexCharts(sales, options);
    chart.render();
}



   