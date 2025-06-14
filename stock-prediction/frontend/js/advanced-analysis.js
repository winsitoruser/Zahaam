/**
 * Advanced Technical Analysis Module for ZAHAAM
 * Provides technical indicators, motion analysis, and news sentiment
 */

// Base URL for API endpoints
const API_BASE_URL = '/api';

// Market Depth Module
const MarketDepth = {
    // Get market depth data for a stock
    getMarketDepthData: async function(ticker, realTime = false) {
        try {
            const url = `${API_BASE_URL}/market-depth/${ticker}?real_time=${realTime}`;
            const response = await fetch(url);
            
            if (!response.ok) {
                throw new Error(`Failed to fetch market depth data: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Error fetching market depth data:', error);
            return this.getDummyMarketDepthData(ticker);
        }
    },
    
    // Generate dummy market depth data for development/testing
    getDummyMarketDepthData: function(ticker) {
        const lastPrice = 5000;
        const bids = [];
        const asks = [];
        
        for (let i = 0; i < 10; i++) {
            const priceDecrement = (i * 5) + Math.floor(Math.random() * 5);
            const volumeFactor = 1.0 - (i / 20);
            const volume = Math.floor((Math.random() * 40000 + 10000) * volumeFactor / 100) * 100;
            
            bids.push({
                price: lastPrice - priceDecrement,
                volume: volume,
                orders: Math.floor(Math.random() * 17) + 3
            });
        }
        
        for (let i = 0; i < 10; i++) {
            const priceIncrement = (i * 5) + Math.floor(Math.random() * 5);
            const volumeFactor = 1.0 - (i / 20);
            const volume = Math.floor((Math.random() * 40000 + 10000) * volumeFactor / 100) * 100;
            
            asks.push({
                price: lastPrice + priceIncrement,
                volume: volume,
                orders: Math.floor(Math.random() * 17) + 3
            });
        }
        
        return {
            ticker: ticker,
            timestamp: new Date().toISOString(),
            last_price: lastPrice,
            is_real_time: false,
            bids: bids,
            asks: asks,
            total_bid_volume: bids.reduce((sum, bid) => sum + bid.volume, 0),
            total_ask_volume: asks.reduce((sum, ask) => sum + ask.volume, 0)
        };
    },
    
    // Render market depth chart
    renderMarketDepthChart: function(data, elementId = 'marketDepthChart') {
        if (!data) return;
        
        const chart = document.getElementById(elementId);
        if (!chart) return;
        
        // Clear previous chart if any
        chart.innerHTML = '';
        
        // Create ApexCharts config
        const options = {
            series: [
                {
                    name: 'Bids',
                    data: data.bids.map(bid => ({
                        x: bid.price,
                        y: bid.volume
                    })).reverse()
                },
                {
                    name: 'Asks',
                    data: data.asks.map(ask => ({
                        x: ask.price,
                        y: ask.volume
                    }))
                }
            ],
            chart: {
                type: 'area',
                height: 300,
                stacked: false,
                toolbar: {
                    show: false
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    opacityFrom: 0.6,
                    opacityTo: 0.8,
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 1,
                colors: ['transparent']
            },
            grid: {
                xaxis: {
                    lines: {
                        show: false
                    }
                }
            },
            xaxis: {
                type: 'numeric',
                title: {
                    text: 'Price'
                },
                labels: {
                    formatter: function(val) {
                        return Math.round(val).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Volume'
                },
                labels: {
                    formatter: function(val) {
                        if (val >= 1000000) return (val/1000000).toFixed(1) + 'M';
                        if (val >= 1000) return (val/1000).toFixed(1) + 'K';
                        return val;
                    }
                }
            },
            colors: ['#198754', '#dc3545'], // Green for bids, red for asks
            legend: {
                position: 'top'
            },
            tooltip: {
                shared: false,
                y: {
                    formatter: function(val) {
                        if (val >= 1000000) return (val/1000000).toFixed(1) + 'M';
                        if (val >= 1000) return (val/1000).toFixed(1) + 'K';
                        return val;
                    }
                }
            },
            annotations: {
                xaxis: [{
                    x: data.last_price,
                    strokeDashArray: 0,
                    borderColor: '#775DD0',
                    label: {
                        borderColor: '#775DD0',
                        style: {
                            color: '#fff',
                            background: '#775DD0'
                        },
                        text: 'Last Price'
                    }
                }]
            }
        };
        
        // Render ApexCharts
        const apexChart = new ApexCharts(chart, options);
        apexChart.render();
        
        // Update bid and ask volume info
        this.updateVolumeInfo(data);
    },
    
    // Update bid and ask volume info
    updateVolumeInfo: function(data) {
        const bidVolumeElement = document.querySelector('#market-depth-content .small:nth-child(1) .fw-bold');
        const askVolumeElement = document.querySelector('#market-depth-content .small:nth-child(2) .fw-bold');
        
        if (bidVolumeElement) {
            const bidVolume = this.formatVolume(data.total_bid_volume);
            bidVolumeElement.textContent = bidVolume;
        }
        
        if (askVolumeElement) {
            const askVolume = this.formatVolume(data.total_ask_volume);
            askVolumeElement.textContent = askVolume;
        }
    },
    
    // Format volume for display
    formatVolume: function(volume) {
        if (volume >= 1000000) return (volume/1000000).toFixed(1) + 'M';
        if (volume >= 1000) return (volume/1000).toFixed(1) + 'K';
        return volume;
    }
};

// Technical Analysis Module
const TechnicalAnalysis = {
    // Get technical indicators for a stock
    getIndicators: async function(ticker, days = 30) {
        try {
            const url = `${API_BASE_URL}/technical-analysis/indicators/${ticker}?days=${days}`;
            const response = await fetch(url);
            
            if (!response.ok) {
                throw new Error(`Failed to fetch indicators: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Error fetching technical indicators:', error);
            return null;
        }
    },
    
    // Calculate support and resistance levels
    getSupportResistance: async function(ticker, days = 30) {
        try {
            const url = `${API_BASE_URL}/technical-analysis/support-resistance/${ticker}?days=${days}`;
            const response = await fetch(url);
            
            if (!response.ok) {
                // Fallback to local calculation if API fails
                return this.calculateSupportResistance(await this.getPriceData(ticker, days));
            }
            
            return await response.json();
        } catch (error) {
            console.error('Error fetching support/resistance levels:', error);
            return {
                support: [],
                resistance: []
            };
        }
    },
    
    // Get price data for local calculations
    getPriceData: async function(ticker, days = 30) {
        try {
            const url = `${API_BASE_URL}/stocks/history/${ticker}?days=${days}`;
            const response = await fetch(url);
            
            if (!response.ok) {
                throw new Error(`Failed to fetch price data: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Error fetching price data:', error);
            return [];
        }
    },
    
    // Fallback local calculation for support/resistance levels
    calculateSupportResistance: function(priceData) {
        if (!priceData || !priceData.length) {
            return {
                support: [],
                resistance: []
            };
        }
        
        // Simple algorithm to find local minima/maxima
        const support = [];
        const resistance = [];
        
        // Implementation would go here
        
        return {
            support,
            resistance
        };
    }
};

// Motion Analysis Module
const MotionAnalysis = {
    // Get motion analysis for a stock
    getMotionAnalysis: async function(ticker, days = 30) {
        try {
            const url = `${API_BASE_URL}/technical-analysis/motion/${ticker}?days=${days}`;
            const response = await fetch(url);
            
            if (!response.ok) {
                // If API endpoint doesn't exist yet, return dummy data
                return this.getDummyMotionData(ticker);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Error fetching motion analysis:', error);
            return this.getDummyMotionData(ticker);
        }
    },
    
    // Generate dummy motion data for development/testing
    getDummyMotionData: function(ticker) {
        return {
            ticker: ticker,
            trend: {
                direction: 'up',
                strength: 'medium',
                confidence: 0.75
            },
            support_resistance: {
                current_price: 4650,
                resistance: [
                    { level: 4850, strength: 'strong' },
                    { level: 4720, strength: 'medium' }
                ],
                support: [
                    { level: 4580, strength: 'medium' },
                    { level: 4450, strength: 'strong' }
                ]
            },
            price_action: {
                pattern: 'Bullish Flag',
                momentum: 'increasing',
                volatility: 'medium'
            }
        };
    }
};

// News Sentiment Module
const NewsSentiment = {
    // Get news sentiment for a stock
    getSentiment: async function(ticker, days = 7) {
        try {
            const url = `${API_BASE_URL}/news-sentiment/ticker/${ticker}?days=${days}`;
            const response = await fetch(url);
            
            if (!response.ok) {
                throw new Error(`Failed to fetch news sentiment: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Error fetching news sentiment:', error);
            return this.getDummySentimentData(ticker);
        }
    },
    
    // Get latest news articles
    getLatestNews: async function(ticker, days = 7) {
        try {
            const url = `${API_BASE_URL}/news-sentiment/latest-news?ticker=${ticker}&days=${days}`;
            const response = await fetch(url);
            
            if (!response.ok) {
                throw new Error(`Failed to fetch latest news: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Error fetching latest news:', error);
            return { articles: [] };
        }
    },
    
    // Generate dummy sentiment data for development/testing
    getDummySentimentData: function(ticker) {
        return {
            ticker: ticker,
            overall: 'positive',
            score: 0.65,
            confidence: 0.78,
            distribution: {
                very_positive: 35,
                positive: 25,
                neutral: 20,
                negative: 15,
                very_negative: 5
            },
            news_items: [
                {
                    title: 'Strong Quarterly Results',
                    snippet: 'Company exceeded analyst expectations with 15% revenue growth',
                    date: '2025-06-12',
                    score: 0.85,
                    source: 'Financial Times'
                },
                {
                    title: 'New Product Launch',
                    snippet: 'Market reception mixed on innovative but unproven technology',
                    date: '2025-06-09',
                    score: 0.12,
                    source: 'Bloomberg'
                }
            ]
        };
    }
};

// UI Update Module
const AdvancedAnalysisUI = {
    // Initialize all tabs
    init: function(ticker) {
        this.ticker = ticker;
        this.initIndicatorsTab();
        this.initMotionTab();
        this.initSentimentTab();
        this.initMarketDepthTab();
    },
    
    // Initialize technical indicators tab
    initIndicatorsTab: async function() {
        try {
            const data = await TechnicalAnalysis.getIndicators(this.ticker);
            if (!data) return;
            
            // Update indicator table
            const table = document.getElementById('indicator-table');
            if (!table) return;
            
            // Update overall signal
            const overallSignal = document.getElementById('overall-signal');
            if (overallSignal) {
                const signalText = overallSignal.querySelector('h4');
                if (signalText) {
                    signalText.textContent = data.overall_signal || 'BUY';
                }
                
                // Update color based on signal
                if (data.overall_signal === 'BUY') {
                    overallSignal.style.backgroundColor = '#198754';
                } else if (data.overall_signal === 'SELL') {
                    overallSignal.style.backgroundColor = '#dc3545';
                } else {
                    overallSignal.style.backgroundColor = '#fd7e14';
                }
            }
            
            // Update indicators in table
            // This would be implemented based on actual API response structure
        } catch (error) {
            console.error('Error initializing indicators tab:', error);
        }
    },
    
    // Initialize motion analysis tab
    initMotionTab: async function() {
        try {
            const data = await MotionAnalysis.getMotionAnalysis(this.ticker);
            if (!data) return;
            
            // Update trend direction
            const trendDirection = document.getElementById('trend-direction');
            if (trendDirection) {
                const icon = trendDirection.querySelector('i');
                if (icon) {
                    if (data.trend.direction === 'up') {
                        icon.className = 'bi bi-arrow-up';
                        trendDirection.style.backgroundColor = '#0dcaf0';
                    } else {
                        icon.className = 'bi bi-arrow-down';
                        trendDirection.style.backgroundColor = '#dc3545';
                    }
                }
            }
            
            // Update trend strength
            const trendStrength = document.getElementById('trend-strength');
            if (trendStrength) {
                const strengthSpan = trendStrength.querySelector('span');
                if (strengthSpan) {
                    strengthSpan.textContent = data.trend.strength || 'Medium';
                }
            }
            
            // Update support/resistance data
            this.updateSupportResistance(data.support_resistance);
            
            // Update price action data
            this.updatePriceAction(data.price_action);
        } catch (error) {
            console.error('Error initializing motion tab:', error);
        }
    },
    
    // Update support/resistance display
    updateSupportResistance: function(data) {
        if (!data) return;
        
        const container = document.getElementById('support-resistance-data');
        if (!container) return;
        
        // Clear existing content
        container.innerHTML = '';
        
        // Add resistance levels
        if (data.resistance && data.resistance.length) {
            data.resistance.forEach((level, index) => {
                const div = document.createElement('div');
                div.className = 'd-flex justify-content-between';
                div.innerHTML = `
                    <span>Resistance ${index + 1}:</span>
                    <span class="fw-bold text-danger">${level.level}</span>
                `;
                container.appendChild(div);
            });
        }
        
        // Add current price
        const priceDiv = document.createElement('div');
        priceDiv.className = 'd-flex justify-content-between';
        priceDiv.innerHTML = `
            <span class="fw-bold">Current Price:</span>
            <span class="fw-bold">${data.current_price}</span>
        `;
        container.appendChild(priceDiv);
        
        // Add support levels
        if (data.support && data.support.length) {
            data.support.forEach((level, index) => {
                const div = document.createElement('div');
                div.className = 'd-flex justify-content-between';
                div.innerHTML = `
                    <span>Support ${index + 1}:</span>
                    <span class="fw-bold text-success">${level.level}</span>
                `;
                container.appendChild(div);
            });
        }
    },
    
    // Update price action display
    updatePriceAction: function(data) {
        if (!data) return;
        
        const container = document.getElementById('price-action-data');
        if (!container) return;
        
        // Clear existing content
        container.innerHTML = '';
        
        // Add pattern
        const patternDiv = document.createElement('div');
        patternDiv.className = 'd-flex justify-content-between';
        patternDiv.innerHTML = `
            <span>Pattern:</span>
            <span class="fw-bold">${data.pattern || 'None'}</span>
        `;
        container.appendChild(patternDiv);
        
        // Add momentum
        const momentumDiv = document.createElement('div');
        momentumDiv.className = 'd-flex justify-content-between';
        const momentumClass = data.momentum === 'increasing' ? 'text-success' : 
                             data.momentum === 'decreasing' ? 'text-danger' : '';
        momentumDiv.innerHTML = `
            <span>Momentum:</span>
            <span class="fw-bold ${momentumClass}">${data.momentum || 'Stable'}</span>
        `;
        container.appendChild(momentumDiv);
        
        // Add volatility
        const volatilityDiv = document.createElement('div');
        volatilityDiv.className = 'd-flex justify-content-between';
        volatilityDiv.innerHTML = `
            <span>Volatility:</span>
            <span class="fw-bold">${data.volatility || 'Low'}</span>
        `;
        container.appendChild(volatilityDiv);
    },
    
    // Initialize market depth tab
    initMarketDepthTab: async function() {
        try {
            // Get market depth data
            const realTimeCheckbox = document.getElementById('realTimeDepth');
            const isRealTime = realTimeCheckbox ? realTimeCheckbox.checked : false;
            
            const data = await MarketDepth.getMarketDepthData(this.ticker, isRealTime);
            if (!data) return;
            
            // Render market depth chart
            MarketDepth.renderMarketDepthChart(data);
            
            // Setup real-time checkbox event listener
            if (realTimeCheckbox) {
                realTimeCheckbox.addEventListener('change', async () => {
                    // Update market depth data when real-time option changes
                    const updatedData = await MarketDepth.getMarketDepthData(
                        this.ticker, 
                        realTimeCheckbox.checked
                    );
                    if (updatedData) {
                        MarketDepth.renderMarketDepthChart(updatedData);
                    }
                });
            }
        } catch (error) {
            console.error('Error initializing market depth tab:', error);
        }
    },
    
    // Initialize news sentiment tab
    initSentimentTab: async function() {
        try {
            const data = await NewsSentiment.getSentiment(this.ticker);
            if (!data) return;
            
            // Update sentiment score
            const scoreCircle = document.getElementById('sentiment-score-circle');
            const scoreValue = document.getElementById('sentiment-score-value');
            
            if (scoreCircle && scoreValue) {
                // Update score text
                const score = data.score || 0;
                scoreValue.textContent = score > 0 ? `+${score.toFixed(2)}` : score.toFixed(2);
                
                // Update circle color based on sentiment
                if (score >= 0.5) {
                    scoreCircle.style.backgroundColor = '#198754'; // Very positive - green
                } else if (score >= 0.05) {
                    scoreCircle.style.backgroundColor = '#20c997'; // Positive - light green
                } else if (score > -0.05) {
                    scoreCircle.style.backgroundColor = '#6c757d'; // Neutral - gray
                } else if (score > -0.5) {
                    scoreCircle.style.backgroundColor = '#fd7e14'; // Negative - orange
                } else {
                    scoreCircle.style.backgroundColor = '#dc3545'; // Very negative - red
                }
            }
            
            // Update confidence
            const confidenceSpan = document.getElementById('sentiment-confidence');
            if (confidenceSpan) {
                confidenceSpan.textContent = `${Math.round((data.confidence || 0) * 100)}%`;
            }
            
            // Update sentiment distribution
            this.updateSentimentDistribution(data.distribution);
            
            // Update news items
            this.updateNewsItems(data.news_items);
        } catch (error) {
            console.error('Error initializing sentiment tab:', error);
        }
    },
    
    // Update sentiment distribution chart
    updateSentimentDistribution: function(distribution) {
        if (!distribution) return;
        
        const container = document.querySelector('.sentiment-distribution');
        if (!container) return;
        
        const progressBar = container.querySelector('.progress');
        if (!progressBar) return;
        
        // Clear existing bars
        progressBar.innerHTML = '';
        
        // Add distribution bars
        const categories = [
            { key: 'very_positive', color: 'bg-success' },
            { key: 'positive', color: 'bg-info' },
            { key: 'neutral', color: 'bg-secondary' },
            { key: 'negative', color: 'bg-warning' },
            { key: 'very_negative', color: 'bg-danger' }
        ];
        
        categories.forEach(cat => {
            const value = distribution[cat.key] || 0;
            if (value > 0) {
                const div = document.createElement('div');
                div.className = `progress-bar ${cat.color}`;
                div.style.width = `${value}%`;
                div.setAttribute('role', 'progressbar');
                div.setAttribute('aria-valuenow', value);
                div.setAttribute('aria-valuemin', '0');
                div.setAttribute('aria-valuemax', '100');
                div.textContent = `${value}%`;
                progressBar.appendChild(div);
            }
        });
    },
    
    // Update news items display
    updateNewsItems: function(newsItems) {
        if (!newsItems || !newsItems.length) return;
        
        const container = document.getElementById('news-impact-container');
        if (!container) return;
        
        // Clear existing content
        container.innerHTML = '';
        
        // Add news items
        newsItems.forEach(item => {
            // Determine border color based on sentiment score
            let borderColor = 'border-secondary';
            let badgeColor = 'bg-secondary';
            const score = item.score || 0;
            
            if (score >= 0.5) {
                borderColor = 'border-success';
                badgeColor = 'bg-success';
            } else if (score >= 0.05) {
                borderColor = 'border-info';
                badgeColor = 'bg-info';
            } else if (score > -0.05) {
                borderColor = 'border-secondary';
                badgeColor = 'bg-secondary';
            } else if (score > -0.5) {
                borderColor = 'border-warning';
                badgeColor = 'bg-warning text-dark';
            } else {
                borderColor = 'border-danger';
                badgeColor = 'bg-danger';
            }
            
            // Create news item element
            const div = document.createElement('div');
            div.className = `news-impact-item mb-2 p-2 border-start border-4 ${borderColor}`;
            
            // Format date
            const date = new Date(item.date);
            const daysAgo = Math.round((new Date() - date) / (1000 * 60 * 60 * 24));
            const dateStr = daysAgo === 0 ? 'Today' : 
                          daysAgo === 1 ? 'Yesterday' : 
                          `${daysAgo} days ago`;
            
            div.innerHTML = `
                <h6 class="small fw-bold mb-1">${item.title}</h6>
                <p class="small mb-0">${item.snippet}</p>
                <div class="d-flex justify-content-between mt-1">
                    <span class="small text-muted">${dateStr}</span>
                    <span class="badge ${badgeColor}">${score > 0 ? '+' : ''}${score.toFixed(2)}</span>
                </div>
            `;
            
            container.appendChild(div);
        });
    }
};

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Get stock symbol from URL
    const urlParams = new URLSearchParams(window.location.search);
    const stockSymbol = urlParams.get('symbol') || 'BBCA';
    
    // Initialize advanced analysis
    AdvancedAnalysisUI.init(stockSymbol);
});
