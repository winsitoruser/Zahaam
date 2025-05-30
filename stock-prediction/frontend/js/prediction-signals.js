// prediction-signals.js - Script untuk menangani sinyal entry/exit dan resistance/support pada prediksi harga saham

// Definisi fungsi getTranslation untuk file ini jika tidak tersedia dari translations.js
if (typeof getTranslation !== 'function') {
    function getTranslation(key, lang = 'id') {
        if (window.translations && window.translations[lang] && window.translations[lang][key]) {
            return window.translations[lang][key];
        }
        return key; // Kembalikan key jika terjemahan tidak ditemukan
    }
}

// Fungsi untuk menginisialisasi tampilan sinyal prediksi
function initPredictionSignals(stockSymbol = 'BBRI') {
    // Generate data prediksi untuk entry/exit signals
    generateSignalData(stockSymbol);
    
    // Menampilkan resistance/support levels
    displaySupportResistanceLevels(stockSymbol);
}

// Fungsi untuk menghasilkan dan menampilkan data sinyal entry/exit
function generateSignalData(symbol) {
    // Dalam implementasi nyata, ini akan memanggil API
    // Untuk contoh, gunakan data simulasi
    
    // Data simulasi untuk entry/exit signals
    const signals = {
        entry: {
            price: 4620,
            date: '2025-06-01',
            confidence: 87.5,
            type: 'BUY',
            reason: 'Bullish pattern detected with price breaking above resistance level of 4600 combined with increasing volume. MACD shows bullish crossover and RSI is at 62, indicating strong momentum.'
        },
        exit: {
            price: 4690,
            date: '2025-06-05',
            confidence: 82.3,
            type: 'SELL',
            reason: 'Price target achieved with overbought conditions developing. RSI approaching 70 and stochastic oscillator showing reversal pattern. Take profit recommended at this level.'
        },
        stopLoss: {
            price: 4550,
            riskRewardRatio: 2.33
        }
    };
    
    // Menampilkan entry/exit signals di UI
    displayEntryExitSignals(signals);
}

// Fungsi untuk menampilkan entry/exit signals di UI
function displayEntryExitSignals(signals) {
    const signalsContainer = document.getElementById('entryExitSignals');
    if (!signalsContainer) return;
    
    // Hitung durasi periode
    const entryDate = new Date(signals.entry.date);
    const exitDate = new Date(signals.exit.date);
    const holdingDays = Math.round((exitDate - entryDate) / (1000 * 60 * 60 * 24));
    
    // Hitung potensi return
    const potentialReturnPercent = ((signals.exit.price - signals.entry.price) / signals.entry.price * 100).toFixed(2);
    const potentialReturnAbs = (signals.exit.price - signals.entry.price).toFixed(0);
    
    // Dapatkan bahasa yang sedang aktif
    const currentLang = localStorage.getItem('language') || 'id';
    
    signalsContainer.innerHTML = `
        <div class="text-center mb-4">
            <div class="prediction-direction ${potentialReturnPercent > 0 ? 'up' : 'down'} mx-auto">
                <i class="bi ${potentialReturnPercent > 0 ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow'}" style="font-size: 1.75rem;"></i>
            </div>
            <div class="badge ${potentialReturnPercent > 0 ? 'bg-success' : 'bg-danger'} badge-signal mb-2">
                ${potentialReturnPercent > 0 ? getTranslation('bullish', currentLang) : getTranslation('bearish', currentLang)} SIGNAL
            </div>
            <p class="text-muted mb-0">${getTranslation('predicted_holding_period', currentLang)}: <span class="fw-bold">${holdingDays} ${getTranslation('days', currentLang)}</span></p>
        </div>
        
        <div class="row mt-4">
            <!-- Entry Signal -->
            <div class="col-md-12 mb-3">
                <div class="signal-card entry-signal">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-success mb-0"><i class="bi bi-arrow-right-circle me-2"></i> ${getTranslation('entry_signal', currentLang)}</h6>
                        <span class="badge bg-success badge-signal">${getTranslation(signals.entry.type.toLowerCase(), currentLang)}</span>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 col-md-4">
                            <small class="text-muted">${getTranslation('entry_price', currentLang)}:</small>
                            <div class="fw-bold">Rp ${signals.entry.price.toLocaleString()}</div>
                        </div>
                        <div class="col-6 col-md-4">
                            <small class="text-muted">${getTranslation('entry_date', currentLang)}:</small>
                            <div>${formatDate(signals.entry.date)}</div>
                        </div>
                        <div class="col-12 col-md-4 mt-2 mt-md-0">
                            <small class="text-muted">${getTranslation('signal_strength', currentLang)}:</small>
                            <div class="d-flex align-items-center">
                                <div class="progress signal-strength flex-grow-1 me-2">
                                    <div class="progress-bar bg-success" style="width: ${signals.entry.confidence}%"></div>
                                </div>
                                <small>${signals.entry.confidence}%</small>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted d-block">${getTranslation('analysis', currentLang)}:</small>
                        <p class="small mb-0">${signals.entry.reason}</p>
                    </div>
                </div>
            </div>
            
            <!-- Exit Strategy -->
            <div class="col-md-12 mb-3">
                <div class="signal-card exit-signal">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-danger mb-0"><i class="bi bi-arrow-left-circle me-2"></i> ${getTranslation('exit_strategy', currentLang)}</h6>
                        <div>
                            <span class="${potentialReturnPercent > 0 ? 'text-success' : 'text-danger'} fw-bold">
                                ${potentialReturnPercent}%
                            </span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 col-md-3">
                            <small class="text-muted">${getTranslation('target_price', currentLang)}:</small>
                            <div class="fw-bold">Rp ${signals.exit.price.toLocaleString()}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <small class="text-muted">${getTranslation('target_date', currentLang)}:</small>
                            <div>${formatDate(signals.exit.date)}</div>
                        </div>
                        <div class="col-6 col-md-3 mt-2 mt-md-0">
                            <small class="text-muted">${getTranslation('stop_loss', currentLang)}:</small>
                            <div class="text-danger">Rp ${signals.stopLoss.price.toLocaleString()}</div>
                        </div>
                        <div class="col-6 col-md-3 mt-2 mt-md-0">
                            <small class="text-muted">${getTranslation('risk_reward', currentLang)}:</small>
                            <div class="fw-bold">${signals.stopLoss.riskRewardRatio}</div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted d-block">${getTranslation('exit_reasoning', currentLang)}:</small>
                        <p class="small mb-0">${signals.exit.reason}</p>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="col-12">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="py-1 px-2 bg-light rounded quick-stat">
                            <small class="text-muted d-block">${getTranslation('profit_target', currentLang)}</small>
                            <span class="fw-bold text-success stat-value">Rp ${potentialReturnAbs}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="py-1 px-2 bg-light rounded quick-stat">
                            <small class="text-muted d-block">${getTranslation('confidence', currentLang)}</small>
                            <span class="fw-bold text-primary stat-value">${signals.entry.confidence}%</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="py-1 px-2 bg-light rounded quick-stat">
                            <small class="text-muted d-block">${getTranslation('time_frame', currentLang)}</small>
                            <span class="fw-bold stat-value">${holdingDays} ${getTranslation('days', currentLang)}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Fungsi untuk menampilkan resistance/support levels
function displaySupportResistanceLevels(symbol) {
    const levelsContainer = document.getElementById('supportResistance');
    if (!levelsContainer) return;
    
    // Dapatkan bahasa yang sedang aktif
    const currentLang = localStorage.getItem('language') || 'id';
    
    // Data simulasi untuk levels
    const levels = {
        resistances: [
            { price: 4700, strength: getTranslation('strong', currentLang), description: 'Previous all-time high, major psychological resistance' },
            { price: 4650, strength: getTranslation('moderate', currentLang), description: 'Previous swing high from April 2025' },
            { price: 4600, strength: getTranslation('weak', currentLang), description: 'Recent high with multiple tests' }
        ],
        supports: [
            { price: 4550, strength: getTranslation('moderate', currentLang), description: 'Previous resistance now acting as support' },
            { price: 4500, strength: getTranslation('strong', currentLang), description: 'Major psychological level with high volume' },
            { price: 4450, strength: getTranslation('strong', currentLang), description: '200-day moving average' }
        ],
        analysis: currentLang === 'id' ? 
            "Saham saat ini diperdagangkan dalam pola channel naik antara 4550 dan 4650. Break di atas 4700 akan mengkonfirmasi tren bullish jangka panjang, sementara turun di bawah 4500 dapat mengindikasikan potensi pembalikan tren. Pola volume menunjukkan akumulasi pada penurunan terbaru di dekat 4550, menunjukkan dukungan kuat pada level ini." :
            "The stock is currently trading in an ascending channel pattern between 4550 and 4650. Breaking above 4700 would confirm a longer-term bullish trend, while dropping below 4500 would suggest a potential trend reversal. Volume patterns show accumulation on recent dips near 4550, indicating strong support at this level."
    };
    
    // Memformat levels untuk ditampilkan di UI
    let resistanceHTML = '';
    levels.resistances.forEach(level => {
        resistanceHTML += `
            <div class="level-item resistance-level">
                <div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-arrow-up-circle text-danger me-2"></i>
                        <span class="fw-bold">Rp ${level.price.toLocaleString()}</span>
                    </div>
                    <span class="badge ${getStrengthClass(level.strength)} mt-1">${level.strength}</span>
                </div>
                <span class="text-muted small">${truncateText(level.description, 40)}</span>
            </div>
        `;
    });
    
    let supportHTML = '';
    levels.supports.forEach(level => {
        supportHTML += `
            <div class="level-item support-level">
                <div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-arrow-down-circle text-success me-2"></i>
                        <span class="fw-bold">Rp ${level.price.toLocaleString()}</span>
                    </div>
                    <span class="badge ${getStrengthClass(level.strength)} mt-1">${level.strength}</span>
                </div>
                <span class="text-muted small">${truncateText(level.description, 40)}</span>
            </div>
        `;
    });
    
    // Chart representation of levels
    const currentPrice = 4580; // Simulasi harga saat ini
    const maxPrice = Math.max(...levels.resistances.map(r => r.price), currentPrice);
    const minPrice = Math.min(...levels.supports.map(s => s.price), currentPrice);
    const range = maxPrice - minPrice;
    
    // Chart representation
    let chartHTML = `
        <div class="price-chart my-3" style="height: 100px; position: relative; border-left: 1px solid #ddd; border-bottom: 1px solid #ddd;">
            <!-- Current price line -->
            <div class="current-price" style="position: absolute; left: 0; right: 0; bottom: ${((currentPrice - minPrice) / range) * 100}%; border-top: 2px dashed #4e73df; z-index: 10;">
                <span class="badge bg-primary" style="position: absolute; right: 0; top: -10px;">Rp ${currentPrice.toLocaleString()}</span>
            </div>
    `;
    
    // Add resistance lines
    levels.resistances.forEach(level => {
        const position = ((level.price - minPrice) / range) * 100;
        const lineColor = level.strength === getTranslation('strong', currentLang) ? '#e74a3b' : (level.strength === getTranslation('moderate', currentLang) ? '#f6c23e' : '#36b9cc');
        chartHTML += `
            <div class="resistance-line" style="position: absolute; left: 0; right: 0; bottom: ${position}%; border-top: 1px solid ${lineColor}; z-index: 5;">
                <span class="small" style="position: absolute; left: 5px; top: -15px; color: ${lineColor};">${level.price}</span>
            </div>
        `;
    });
    
    // Add support lines
    levels.supports.forEach(level => {
        const position = ((level.price - minPrice) / range) * 100;
        const lineColor = level.strength === getTranslation('strong', currentLang) ? '#1cc88a' : (level.strength === getTranslation('moderate', currentLang) ? '#4e73df' : '#858796');
        chartHTML += `
            <div class="support-line" style="position: absolute; left: 0; right: 0; bottom: ${position}%; border-top: 1px solid ${lineColor}; z-index: 5;">
                <span class="small" style="position: absolute; left: 5px; top: -15px; color: ${lineColor};">${level.price}</span>
            </div>
        `;
    });
    
    chartHTML += `</div>`;
    
    levelsContainer.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0"><i class="bi bi-arrow-up-circle-fill text-danger me-2"></i>${getTranslation('resistance_levels', currentLang)}</h6>
            <span class="badge bg-light text-dark">${levels.resistances.length} ${getTranslation('levels', currentLang)}</span>
        </div>
        
        ${resistanceHTML}
        
        ${chartHTML}
        
        <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
            <h6 class="mb-0"><i class="bi bi-arrow-down-circle-fill text-success me-2"></i>${getTranslation('support_levels', currentLang)}</h6>
            <span class="badge bg-light text-dark">${levels.supports.length} ${getTranslation('levels', currentLang)}</span>
        </div>
        
        ${supportHTML}
        
        <div class="mt-4 pt-2 border-top">
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-graph-up me-2 text-primary"></i>
                <h6 class="mb-0">${getTranslation('technical_analysis', currentLang)}</h6>
            </div>
            <p class="small">${levels.analysis}</p>
        </div>
    `;
}

// Helper function untuk mendapatkan class berdasarkan strength
function getStrengthClass(strength) {
    switch (strength.toLowerCase()) {
        case 'strong': return 'bg-danger';
        case 'moderate': return 'bg-warning text-dark';
        case 'weak': return 'bg-info text-dark';
        default: return 'bg-secondary';
    }
}

// Helper function untuk memformat tanggal
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { 
        day: 'numeric', 
        month: 'short', 
        year: 'numeric' 
    });
}

// Helper function untuk memotong teks
function truncateText(text, maxLength) {
    return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
}

// Initialize signals when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // delay the initialization to ensure other scripts have loaded
    setTimeout(() => {
        const stockSymbol = document.getElementById('stockSelector')?.value || 'BBRI';
        initPredictionSignals(stockSymbol);
        
        // Add event listener for stock symbol changes
        const stockSelector = document.getElementById('stockSelector');
        if (stockSelector) {
            stockSelector.addEventListener('change', function() {
                initPredictionSignals(this.value);
            });
        }
        
        // Add event listener for predict button
        const predictBtn = document.getElementById('predictBtn');
        if (predictBtn) {
            predictBtn.addEventListener('click', function() {
                const symbol = document.getElementById('stockSelector')?.value || 'BBRI';
                initPredictionSignals(symbol);
            });
        }
    }, 800);
});
