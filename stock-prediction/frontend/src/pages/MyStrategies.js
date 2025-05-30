import React, { useState, useEffect } from 'react';
import { Container, Row, Col, Card, Button, Table, Spinner, Modal, Form, Alert, Tabs, Tab } from 'react-bootstrap';
import { Link } from 'react-router-dom';
import axios from 'axios';

const MyStrategies = () => {
  const [strategies, setStrategies] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [showModal, setShowModal] = useState(false);
  const [currentStrategy, setCurrentStrategy] = useState(null);
  const [strategyTemplates, setStrategyTemplates] = useState([]);
  const [showTemplatesModal, setShowTemplatesModal] = useState(false);
  
  // Form state
  const defaultFormData = {
    id: null,
    name: '',
    description: '',
    symbols: 'BBRI',
    type: 'trend_following',
    risk_level: 'medium',
    indicators: [],
    stop_loss_method: 'percentage',
    stop_loss_value: 2.5,
    take_profit_method: 'risk_reward',
    take_profit_value: 2.0,
    timeframe: 'daily',
    enabled: true,
    notes: '',
    buy_conditions: [],
    sell_conditions: [],
    trailing_stop_loss: {
      enabled: false,
      initial_trigger: 2.0,
      step: 0.5
    },
    take_profit_scaling: {
      enabled: false,
      levels: [30, 30, 40], // percentage of position to close at each level
      targets: [1.0, 1.5, 2.0] // risk/reward multiples
    },
    strategy_params: {
      trade_frequency: 'daily',
      position_sizing: 'fixed',
      position_size_value: 5,
      max_open_positions: 3,
      allow_partial_entries: false,
      profit_protection: false,
      profit_protection_threshold: 5.0,
      enable_ai_assist: false,
      order_types: ['market'],
      max_drawdown: 15,
      time_filters: {
        enabled: false,
        trading_hours: [9, 16] // trading from 9 AM to 4 PM
      },
      // Signal confirmation settings
      signal_confirmation_count: 1,
      require_volume_confirmation: false,
      volume_threshold: 50,
      signal_strength_method: 'binary',
      min_signal_strength: 50,
      use_trend_filter: false,
      trend_timeframe: 'daily',
      filters: ['market_hours']
    }
  };

  const [formData, setFormData] = useState(defaultFormData);

  useEffect(() => {
    fetchMyStrategies();
    fetchStrategyTemplates();
    
    // Initialize Bootstrap tooltips
    if (typeof window !== 'undefined' && typeof document !== 'undefined') {
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.forEach(tooltipTriggerEl => {
        new window.bootstrap.Tooltip(tooltipTriggerEl);
      });
    }
  }, []);

  const fetchMyStrategies = async () => {
    setLoading(true);
    try {
      const response = await axios.get('http://localhost:8000/api/strategies');
      setStrategies(response.data.strategies);
    } catch (err) {
      console.error('Error fetching strategies:', err);
      setError('Failed to load strategies. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  const fetchStrategyTemplates = async () => {
    try {
      const response = await axios.get('http://localhost:8000/api/strategy/templates');
      setStrategyTemplates(response.data.templates);
    } catch (err) {
      console.error('Error fetching strategy templates:', err);
    }
  };

  const handleCreateStrategy = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post('http://localhost:8000/api/strategy', formData);
      setShowModal(false);
      fetchMyStrategies(); // Refresh the list

      // Reset form
      setFormData(defaultFormData);
    } catch (err) {
      console.error('Error creating strategy:', err);
      setError('Failed to create strategy. Please try again.');
    }
  };

  const handleDeleteStrategy = async (strategyId) => {
    if (!window.confirm('Are you sure you want to delete this strategy?')) {
      return;
    }

    try {
      await axios.delete(`http://localhost:8000/api/strategy/${strategyId}`);
      fetchMyStrategies(); // Refresh the list
    } catch (err) {
      console.error('Error deleting strategy:', err);
      setError('Failed to delete strategy. Please try again.');
    }
  };

  const handleEditStrategy = (strategy) => {
    setCurrentStrategy(strategy);
    setFormData({
      ...defaultFormData,
      ...strategy
    });
    setShowModal(true);
  };

  const handleUpdateStrategy = async (e) => {
    e.preventDefault();
    try {
      await axios.put(`http://localhost:8000/api/strategy/${currentStrategy.id}`, formData);
      setShowModal(false);
      fetchMyStrategies(); // Refresh the list
    } catch (err) {
      console.error('Error updating strategy:', err);
      setError('Failed to update strategy. Please try again.');
    }
  };

  const handleApplyTemplate = (template) => {
    setFormData({
      ...defaultFormData,
      ...template
    });
    setShowTemplatesModal(false);
    setShowModal(true);
  };

  const handleFormChange = (e) => {
    const { name, value, type, checked } = e.target;
    if (type === 'checkbox') {
      setFormData({ ...formData, [name]: checked });
    } else {
      setFormData({ ...formData, [name]: value });
    }
  };

  const handleParamChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: parseFloat(value)
    });
  };

  const handleStrategyParamsChange = (param, value) => {
    setFormData({
      ...formData,
      strategy_params: {
        ...formData.strategy_params,
        [param]: value
      }
    });
  };

  const handleOrderTypeChange = (orderType, isChecked) => {
    let updatedOrderTypes = [...formData.strategy_params.order_types];

    if (isChecked && !updatedOrderTypes.includes(orderType)) {
      updatedOrderTypes.push(orderType);
    } else if (!isChecked) {
      updatedOrderTypes = updatedOrderTypes.filter(type => type !== orderType);
      // Ensure at least one order type is selected
      if (updatedOrderTypes.length === 0) {
        updatedOrderTypes = ['market'];
      }
    }

    handleStrategyParamsChange('order_types', updatedOrderTypes);
  };

  const handleTimeFiltersChange = (field, value) => {
    const updatedTimeFilters = { ...formData.strategy_params.time_filters };

    if (field === 'enabled') {
      updatedTimeFilters.enabled = value;
    } else if (field === 'start') {
      updatedTimeFilters.trading_hours = [value, updatedTimeFilters.trading_hours[1]];
    } else if (field === 'end') {
      updatedTimeFilters.trading_hours = [updatedTimeFilters.trading_hours[0], value];
    }

    handleStrategyParamsChange('time_filters', updatedTimeFilters);
  };

  const handleTrailingStopLossChange = (field, value) => {
    setFormData({
      ...formData,
      trailing_stop_loss: {
        ...formData.trailing_stop_loss,
        [field]: value
      }
    });
  };

  const handleTakeProfitScalingChange = (field, value) => {
    setFormData({
      ...formData,
      take_profit_scaling: {
        ...formData.take_profit_scaling,
        [field]: value
      }
    });
  };

  const handleTakeProfitScalingLevels = (index, value) => {
    const updatedLevels = [...formData.take_profit_scaling.levels];
    updatedLevels[index] = value;

    setFormData({
      ...formData,
      take_profit_scaling: {
        ...formData.take_profit_scaling,
        levels: updatedLevels
      }
    });
  };

  const handleTakeProfitScalingTargets = (index, value) => {
    const updatedTargets = [...formData.take_profit_scaling.targets];
    updatedTargets[index] = value;

    setFormData({
      ...formData,
      take_profit_scaling: {
        ...formData.take_profit_scaling,
        targets: updatedTargets
      }
    });
  };

  const handleSignalFilterChange = (filter, isChecked) => {
    const currentFilters = formData.strategy_params.filters || [];
    let updatedFilters;
    
    if (isChecked && !currentFilters.includes(filter)) {
      updatedFilters = [...currentFilters, filter];
    } else if (!isChecked) {
      updatedFilters = currentFilters.filter(item => item !== filter);
    } else {
      // No change needed
      return;
    }
    
    handleStrategyParamsChange('filters', updatedFilters);
  };

  const renderStrategyForm = () => (
    <Form onSubmit={currentStrategy ? handleUpdateStrategy : handleCreateStrategy}>
      <Form.Group className="mb-3">
        <Form.Label>Strategy Name</Form.Label>
        <Form.Control
          type="text"
          name="name"
          value={formData.name}
          onChange={handleFormChange}
          required
        />
      </Form.Group>
      
      <Form.Group className="mb-3">
        <Form.Label>Description</Form.Label>
        <Form.Control
          as="textarea"
          rows={3}
          name="description"
          value={formData.description}
          onChange={handleFormChange}
          placeholder="Enter strategy description"
        />
      </Form.Group>
      
      <Form.Group className="mb-3">
        <Form.Label>Symbols <small className="text-muted">(comma separated for multiple)</small></Form.Label>
        <Form.Control
          type="text"
          name="symbols"
          value={formData.symbols}
          onChange={handleFormChange}
          placeholder="e.g., BBRI,BBCA,TLKM"
        />
        <small className="text-muted">Enter stock code(s) to apply this strategy to</small>
      </Form.Group>
      
      <Row>
        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Strategy Type</Form.Label>
            <Form.Select
              name="type"
              value={formData.type}
              onChange={handleFormChange}
            >
              <option value="trend_following">Trend Following</option>
              <option value="mean_reversion">Mean Reversion</option>
              <option value="breakout">Breakout</option>
              <option value="swing">Swing Trading</option>
              <option value="momentum">Momentum</option>
              <option value="arbitrage">Arbitrage</option>
              <option value="pattern">Pattern Recognition</option>
              <option value="custom">Custom</option>
            </Form.Select>
          </Form.Group>
        </Col>
        
        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Risk Level</Form.Label>
            <Form.Select
              name="risk_level"
              value={formData.risk_level}
              onChange={handleFormChange}
            >
              <option value="low">Low</option>
              <option value="medium">Medium</option>
              <option value="high">High</option>
              <option value="aggressive">Aggressive</option>
            </Form.Select>
          </Form.Group>
        </Col>
      </Row>
      
      <Row>
        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Timeframe</Form.Label>
            <Form.Select
              name="timeframe"
              value={formData.timeframe}
              onChange={handleFormChange}
            >
              <option value="1m">1 Minute</option>
              <option value="5m">5 Minutes</option>
              <option value="15m">15 Minutes</option>
              <option value="30m">30 Minutes</option>
              <option value="1h">1 Hour</option>
              <option value="4h">4 Hours</option>
              <option value="daily">Daily</option>
              <option value="weekly">Weekly</option>
            </Form.Select>
          </Form.Group>
        </Col>
        
        <Col md={6}>
          <Form.Group className="mb-3 d-flex align-items-center" style={{marginTop: '32px'}}>
            <Form.Check
              type="switch"
              id="strategy-enabled"
              label="Strategy Enabled"
              name="enabled"
              checked={formData.enabled}
              onChange={handleFormChange}
            />
            <div className="ms-3" data-bs-toggle="tooltip" title="Enable or disable this strategy">
              <i className="bi bi-info-circle"></i>
            </div>
          </Form.Group>
        </Col>
      </Row>
      
      <Tabs defaultActiveKey="indicators" className="mb-3">
        <Tab eventKey="signalConfirmation" title="Signal Confirmation">
          <div className="p-3 border-start border-end border-bottom mb-3">
            <Row>
              <Col md={6}>
                <Form.Group className="mb-3">
                  <Form.Label>Minimum Confirmation Count</Form.Label>
                  <Form.Control
                    type="number"
                    value={formData.strategy_params.signal_confirmation_count || 1}
                    onChange={e => handleStrategyParamsChange('signal_confirmation_count', parseInt(e.target.value))}
                    min="1"
                    max="5"
                  />
                  <small className="text-muted">Minimum number of indicators that must confirm a signal before executing a trade</small>
                </Form.Group>

                <Form.Group className="mb-3">
                  <Form.Check 
                    type="checkbox"
                    id="require-volume-confirmation"
                    label="Require Volume Confirmation"
                    checked={formData.strategy_params.require_volume_confirmation}
                    onChange={e => handleStrategyParamsChange('require_volume_confirmation', e.target.checked)}
                  />
                  <small className="text-muted">Trade signal must be accompanied by significant volume</small>
                </Form.Group>

                <Form.Group className="mb-3">
                  <Form.Label>Volume Threshold (%)</Form.Label>
                  <Form.Control
                    type="number"
                    value={formData.strategy_params.volume_threshold || 50}
                    onChange={e => handleStrategyParamsChange('volume_threshold', parseInt(e.target.value))}
                    min="10"
                    max="500"
                    disabled={!formData.strategy_params.require_volume_confirmation}
                  />
                  <small className="text-muted">Volume must be x% above average to consider it significant</small>
                </Form.Group>
              </Col>

              <Col md={6}>
                <Form.Group className="mb-3">
                  <Form.Label>Signal Strength Classification</Form.Label>
                  <Form.Select
                    value={formData.strategy_params.signal_strength_method || 'binary'}
                    onChange={e => handleStrategyParamsChange('signal_strength_method', e.target.value)}
                  >
                    <option value="binary">Binary (Yes/No)</option>
                    <option value="percentage">Percentage Based</option>
                    <option value="score">Scoring System (1-10)</option>
                  </Form.Select>
                </Form.Group>

                <Form.Group className="mb-3">
                  <Form.Label>Minimum Signal Strength</Form.Label>
                  <Form.Control
                    type="number"
                    value={formData.strategy_params.min_signal_strength || 50}
                    onChange={e => handleStrategyParamsChange('min_signal_strength', parseInt(e.target.value))}
                    min="1"
                    max={formData.strategy_params.signal_strength_method === 'score' ? 10 : 100}
                    step="1"
                  />
                </Form.Group>

                <Form.Group className="mb-3">
                  <Form.Check 
                    type="checkbox"
                    id="trend-direction-filter"
                    label="Apply Trend Direction Filter"
                    checked={formData.strategy_params.use_trend_filter}
                    onChange={e => handleStrategyParamsChange('use_trend_filter', e.target.checked)}
                  />
                  <small className="text-muted">Only take trades aligned with the overall market trend</small>
                </Form.Group>

                {formData.strategy_params.use_trend_filter && (
                  <Form.Group className="mb-3">
                    <Form.Label>Trend Timeframe</Form.Label>
                    <Form.Select
                      value={formData.strategy_params.trend_timeframe || 'daily'}
                      onChange={e => handleStrategyParamsChange('trend_timeframe', e.target.value)}
                    >
                      <option value="5m">5 Minutes</option>
                      <option value="15m">15 Minutes</option>
                      <option value="30m">30 Minutes</option>
                      <option value="1h">1 Hour</option>
                      <option value="4h">4 Hours</option>
                      <option value="daily">Daily</option>
                      <option value="weekly">Weekly</option>
                    </Form.Select>
                  </Form.Group>
                )}
              </Col>
            </Row>

            <Row className="mt-3">
              <Col md={12}>
                <Form.Group className="mb-3">
                  <Form.Label>Signal Filters</Form.Label>
                  <div>
                    <Form.Check 
                      inline 
                      type="checkbox" 
                      id="filter-market-hours"
                      label="Market Hours Only" 
                      checked={formData.strategy_params.filters?.includes('market_hours')}
                      onChange={e => handleSignalFilterChange('market_hours', e.target.checked)}
                    />
                    <Form.Check 
                      inline 
                      type="checkbox" 
                      id="filter-price-action"
                      label="Price Action" 
                      checked={formData.strategy_params.filters?.includes('price_action')}
                      onChange={e => handleSignalFilterChange('price_action', e.target.checked)}
                    />
                    <Form.Check 
                      inline 
                      type="checkbox" 
                      id="filter-volatility"
                      label="Volatility" 
                      checked={formData.strategy_params.filters?.includes('volatility')}
                      onChange={e => handleSignalFilterChange('volatility', e.target.checked)}
                    />
                    <Form.Check 
                      inline 
                      type="checkbox" 
                      id="filter-news"
                      label="News Events" 
                      checked={formData.strategy_params.filters?.includes('news')}
                      onChange={e => handleSignalFilterChange('news', e.target.checked)}
                    />
                  </div>
                </Form.Group>
              </Col>
            </Row>
          </div>
        </Tab>

        <Tab eventKey="indicators" title="Indicators">
          <div className="p-3 border-start border-end border-bottom mb-3">
            <p><strong>Available Indicators:</strong></p>
            <div className="row">
              <div className="col-md-6">
                <Form.Group className="mb-3">
                  <Form.Label>Technical Indicators</Form.Label>
                  <div className="mb-2">
                    <Form.Check
                      type="checkbox"
                      label="SMA (Simple Moving Average)"
                      id="sma-checkbox"
                      onChange={() => handleIndicatorToggle('SMA')}
                      checked={formData.strategy_params.indicators.some(i => i.type === 'SMA')}
                    />
                    {formData.strategy_params.indicators.some(i => i.type === 'SMA') && (
                      <div className="ms-4 mt-2">
                        <div className="input-group input-group-sm mb-2">
                          <span className="input-group-text">Fast Period</span>
                          <input 
                            type="number" 
                            className="form-control" 
                            min="1" 
                            max="50"
                            value={formData.strategy_params.indicators.find(i => i.type === 'SMA')?.parameters?.fastPeriod || 20}
                            onChange={e => handleIndicatorParamChange('SMA', 'fastPeriod', parseInt(e.target.value))}
                          />
                        </div>
                        <div className="input-group input-group-sm">
                          <span className="input-group-text">Slow Period</span>
                          <input 
                            type="number" 
                            className="form-control" 
                            min="5" 
                            max="200"
                            value={formData.strategy_params.indicators.find(i => i.type === 'SMA')?.parameters?.slowPeriod || 50}
                            onChange={e => handleIndicatorParamChange('SMA', 'slowPeriod', parseInt(e.target.value))}
                          />
                        </div>
                      </div>
                    )}
                  </div>
                  
                  <div className="mb-2">
                    <Form.Check
                      type="checkbox"
                      label="EMA (Exponential Moving Average)"
                      id="ema-checkbox"
                      onChange={() => handleIndicatorToggle('EMA')}
                      checked={formData.strategy_params.indicators.some(i => i.type === 'EMA')}
                    />
                    {formData.strategy_params.indicators.some(i => i.type === 'EMA') && (
                      <div className="ms-4 mt-2">
                        <div className="input-group input-group-sm mb-2">
                          <span className="input-group-text">Fast Period</span>
                          <input 
                            type="number" 
                            className="form-control" 
                            min="1" 
                            max="50"
                            value={formData.strategy_params.indicators.find(i => i.type === 'EMA')?.parameters?.fastPeriod || 12}
                            onChange={e => handleIndicatorParamChange('EMA', 'fastPeriod', parseInt(e.target.value))}
                          />
                        </div>
                        <div className="input-group input-group-sm">
                          <span className="input-group-text">Slow Period</span>
                          <input 
                            type="number" 
                            className="form-control" 
                            min="5" 
                            max="200"
                            value={formData.strategy_params.indicators.find(i => i.type === 'EMA')?.parameters?.slowPeriod || 26}
                            onChange={e => handleIndicatorParamChange('EMA', 'slowPeriod', parseInt(e.target.value))}
                          />
                        </div>
                      </div>
                    )}
                  </div>
                  
                  <div className="mb-2">
                    <Form.Check
                      type="checkbox"
                      label="RSI (Relative Strength Index)"
                      id="rsi-checkbox"
                      onChange={() => handleIndicatorToggle('RSI')}
                      checked={formData.strategy_params.indicators.some(i => i.type === 'RSI')}
                    />
                    {formData.strategy_params.indicators.some(i => i.type === 'RSI') && (
                      <div className="ms-4 mt-2">
                        <div className="input-group input-group-sm mb-2">
                          <span className="input-group-text">Period</span>
                          <input 
                            type="number" 
                            className="form-control" 
                            min="1" 
                            max="30"
                            value={formData.strategy_params.indicators.find(i => i.type === 'RSI')?.parameters?.period || 14}
                            onChange={e => handleIndicatorParamChange('RSI', 'period', parseInt(e.target.value))}
                          />
                        </div>
                        <div className="input-group input-group-sm mb-2">
                          <span className="input-group-text">Oversold</span>
                          <input 
                            type="number" 
                            className="form-control" 
                            min="10" 
                            max="40"
                            value={formData.strategy_params.indicators.find(i => i.type === 'RSI')?.parameters?.oversold || 30}
                            onChange={e => handleIndicatorParamChange('RSI', 'oversold', parseInt(e.target.value))}
                          />
                        </div>
                        <div className="input-group input-group-sm">
                          <span className="input-group-text">Overbought</span>
                          <input 
                            type="number" 
                            className="form-control" 
                            min="60" 
                            max="90"
                            value={formData.strategy_params.indicators.find(i => i.type === 'RSI')?.parameters?.overbought || 70}
                            onChange={e => handleIndicatorParamChange('RSI', 'overbought', parseInt(e.target.value))}
                          />
                        </div>
                      </div>
                    )}
                  </div>
                </Form.Group>
              </div>
              
              <div className="col-md-6">
                <Form.Group className="mb-3">
                  <Form.Label>Advanced Indicators</Form.Label>
                  <div className="mb-2">
                    <Form.Check
                      type="checkbox"
                      label="MACD"
                      id="macd-checkbox"
                      onChange={() => handleIndicatorToggle('MACD')}
                      checked={formData.strategy_params.indicators.some(i => i.type === 'MACD')}
                    />
                    {formData.strategy_params.indicators.some(i => i.type === 'MACD') && (
                      <div className="ms-4 mt-2">
                        <div className="input-group input-group-sm mb-2">
                          <span className="input-group-text">Fast Period</span>
                          <input 
                            type="number" 
                            className="form-control" 
                            min="5" 
                            max="20"
                            value={formData.strategy_params.indicators.find(i => i.type === 'MACD')?.parameters?.fastPeriod || 12}
                            onChange={e => handleIndicatorParamChange('MACD', 'fastPeriod', parseInt(e.target.value))}
                          />
                        </div>
                        <div className="input-group input-group-sm mb-2">
                          <span className="input-group-text">Slow Period</span>
                          <input 
                            type="number" 
                            className="form-control" 
                            min="10" 
                            max="30"
                            value={formData.strategy_params.indicators.find(i => i.type === 'MACD')?.parameters?.slowPeriod || 26}
                            onChange={e => handleIndicatorParamChange('MACD', 'slowPeriod', parseInt(e.target.value))}
                          />
                        </div>
                        <div className="input-group input-group-sm">
                          <span className="input-group-text">Signal</span>
                          <input 
                            type="number" 
                            className="form-control" 
                            min="1" 
                            max="20"
                            value={formData.strategy_params.indicators.find(i => i.type === 'MACD')?.parameters?.signalPeriod || 9}
                            onChange={e => handleIndicatorParamChange('MACD', 'signalPeriod', parseInt(e.target.value))}
                          />
                        </div>
                      </div>
                    )}
                  </div>
                  
                  <div className="mb-2">
                    <Form.Check
                      type="checkbox"
                      label="Bollinger Bands"
                      id="bollinger-checkbox"
                      onChange={() => handleIndicatorToggle('BBANDS')}
                      checked={formData.strategy_params.indicators.some(i => i.type === 'BBANDS')}
                    />
                    {formData.strategy_params.indicators.some(i => i.type === 'BBANDS') && (
                      <div className="ms-4 mt-2">
                        <div className="input-group input-group-sm mb-2">
                          <span className="input-group-text">Period</span>
                          <input 
                            type="number" 
                            className="form-control" 
                            min="5" 
                            max="50"
                            value={formData.strategy_params.indicators.find(i => i.type === 'BBANDS')?.parameters?.period || 20}
                            onChange={e => handleIndicatorParamChange('BBANDS', 'period', parseInt(e.target.value))}
                          />
                        </div>
                        <div className="input-group input-group-sm">
                          <span className="input-group-text">Standard Dev</span>
                          <input 
                            type="number" 
                            className="form-control" 
                            step="0.1"
                            min="1" 
                            max="4"
                            value={formData.strategy_params.indicators.find(i => i.type === 'BBANDS')?.parameters?.stdDev || 2}
                            onChange={e => handleIndicatorParamChange('BBANDS', 'stdDev', parseFloat(e.target.value))}
                          />
                        </div>
                      </div>
                    )}
                  </div>
                  
                  <div className="mb-2">
                    <Form.Check
                      type="checkbox"
                      label="Ichimoku Cloud"
                      id="ichimoku-checkbox"
                      onChange={() => handleIndicatorToggle('ICHIMOKU')}
                      checked={formData.strategy_params.indicators.some(i => i.type === 'ICHIMOKU')}
                    />
                    {formData.strategy_params.indicators.some(i => i.type === 'ICHIMOKU') && (
                      <div className="ms-4 mt-2">
                        <div className="input-group input-group-sm mb-2">
                          <span className="input-group-text">Tenkan</span>
                          <input 
                            type="number" 
                            className="form-control" 
                            min="1" 
                            max="30"
                            value={formData.strategy_params.indicators.find(i => i.type === 'ICHIMOKU')?.parameters?.tenkanPeriod || 9}
                            onChange={e => handleIndicatorParamChange('ICHIMOKU', 'tenkanPeriod', parseInt(e.target.value))}
                          />
                        </div>
                        <div className="input-group input-group-sm mb-2">
                          <span className="input-group-text">Kijun</span>
                          <input 
                            type="number" 
                            className="form-control" 
                            min="10" 
                            max="60"
                            value={formData.strategy_params.indicators.find(i => i.type === 'ICHIMOKU')?.parameters?.kijunPeriod || 26}
                            onChange={e => handleIndicatorParamChange('ICHIMOKU', 'kijunPeriod', parseInt(e.target.value))}
                          />
                        </div>
                      </div>
                    )}
                  </div>
                </Form.Group>
              </div>
            </div>
            <div className="mt-3">
              <Form.Group>
                <Form.Label>Signal Confirmation</Form.Label>
                <Row>
                  <Col md={6}>
                    <div className="input-group input-group-sm">
                      <span className="input-group-text">Required Confirmations</span>
                      <input 
                        type="number" 
                        className="form-control" 
                        min="1" 
                        max="5"
                        value={formData.strategy_params.signal_confirmation_count}
                        onChange={e => handleStrategyParamsChange('signal_confirmation_count', parseInt(e.target.value))}
                      />
                    </div>
                    <small className="text-muted">How many indicators must agree to generate a signal</small>
                  </Col>
                </Row>
              </Form.Group>
            </div>
          </div>
        </Tab>
        
        <Tab eventKey="buy" title="Buy Conditions">
          <div className="p-3 border-start border-end border-bottom mb-3">
            <Alert variant="info">
              Build your own buy conditions with custom parameters in the upcoming version.
              For now, use templates for pre-configured buy conditions.
            </Alert>
          </div>
        </Tab>
        
        <Tab eventKey="sell" title="Sell Conditions">
          <div className="p-3 border-start border-end border-bottom mb-3">
            <Alert variant="info">
              Build your own sell conditions with custom parameters in the upcoming version.
              For now, use templates for pre-configured sell conditions.
            </Alert>
          </div>
        </Tab>
        
        <Tab eventKey="tradeSettings" title="Trade Settings">
          <div className="p-3 border-start border-end border-bottom mb-3">
            <Row>
              <Col md={6}>
                <Form.Group className="mb-3">
                  <Form.Label>Trade Frequency</Form.Label>
                  <Form.Select 
                    name="trade_frequency" 
                    value={formData.strategy_params.trade_frequency}
                    onChange={e => handleStrategyParamsChange('trade_frequency', e.target.value)}
                  >
                    <option value="intraday">Intraday (Multiple trades per day)</option>
                    <option value="daily">Daily (Positions held for days)</option>
                    <option value="swing">Swing Trading (Positions held for weeks)</option>
                    <option value="position">Position Trading (Longer term)</option>
                  </Form.Select>
                </Form.Group>
                
                <Form.Group className="mb-3">
                  <Form.Label>Position Sizing Method</Form.Label>
                  <Form.Select 
                    name="position_sizing" 
                    value={formData.strategy_params.position_sizing}
                    onChange={e => handleStrategyParamsChange('position_sizing', e.target.value)}
                  >
                    <option value="fixed">Fixed Percentage</option>
                    <option value="kelly">Kelly Criterion</option>
                    <option value="volatility">Volatility-Based</option>
                    <option value="risk_parity">Risk Parity</option>
                  </Form.Select>
                </Form.Group>
                
                <Form.Group className="mb-3">
                  <Form.Label>
                    Position Size Value
                    {formData.strategy_params.position_sizing === 'fixed' ? ' (%)' : ''}
                  </Form.Label>
                  <Form.Control
                    type="number"
                    value={formData.strategy_params.position_size_value}
                    onChange={e => handleStrategyParamsChange('position_size_value', parseFloat(e.target.value))}
                    step="0.1"
                    min="1"
                    max="100"
                  />
                </Form.Group>
              </Col>
              
              <Col md={6}>
                <Form.Group className="mb-3">
                  <Form.Label>Max Open Positions</Form.Label>
                  <Form.Control
                    type="number"
                    value={formData.strategy_params.max_open_positions}
                    onChange={e => handleStrategyParamsChange('max_open_positions', parseInt(e.target.value))}
                    min="1"
                    max="20"
                  />
                </Form.Group>
                
                <Form.Group className="mb-3">
                  <Form.Check 
                    type="checkbox"
                    id="allow-partial-entries"
                    label="Allow Partial Entries"
                    checked={formData.strategy_params.allow_partial_entries}
                    onChange={e => handleStrategyParamsChange('allow_partial_entries', e.target.checked)}
                  />
                  <small className="text-muted">Allow strategy to take smaller positions when full position can't be allocated</small>
                </Form.Group>
                
                <Form.Group className="mb-3">
                  <Form.Label>Order Types</Form.Label>
                  <div>
                    <Form.Check 
                      inline 
                      type="checkbox" 
                      id="market-order"
                      label="Market" 
                      checked={formData.strategy_params.order_types.includes('market')}
                      onChange={e => handleOrderTypeChange('market', e.target.checked)}
                    />
                    <Form.Check 
                      inline 
                      type="checkbox" 
                      id="limit-order"
                      label="Limit" 
                      checked={formData.strategy_params.order_types.includes('limit')}
                      onChange={e => handleOrderTypeChange('limit', e.target.checked)}
                    />
                    <Form.Check 
                      inline 
                      type="checkbox" 
                      id="stop-order"
                      label="Stop" 
                      checked={formData.strategy_params.order_types.includes('stop')}
                      onChange={e => handleOrderTypeChange('stop', e.target.checked)}
                    />
                  </div>
                </Form.Group>
              </Col>
            </Row>
            
            <Row className="mt-3">
              <Col md={6}>
                <Form.Group className="mb-3">
                  <Form.Check 
                    type="checkbox"
                    id="enable-time-filters"
                    label="Enable Trading Hour Filters"
                    checked={formData.strategy_params.time_filters.enabled}
                    onChange={e => handleTimeFiltersChange('enabled', e.target.checked)}
                  />
                </Form.Group>
                
                {formData.strategy_params.time_filters.enabled && (
                  <Row>
                    <Col xs={6}>
                      <Form.Group className="mb-3">
                        <Form.Label>Trading Start (hour)</Form.Label>
                        <Form.Control
                          type="number"
                          value={formData.strategy_params.time_filters.trading_hours[0]}
                          onChange={e => handleTimeFiltersChange('start', parseInt(e.target.value))}
                          min="0"
                          max="23"
                        />
                      </Form.Group>
                    </Col>
                    <Col xs={6}>
                      <Form.Group className="mb-3">
                        <Form.Label>Trading End (hour)</Form.Label>
                        <Form.Control
                          type="number"
                          value={formData.strategy_params.time_filters.trading_hours[1]}
                          onChange={e => handleTimeFiltersChange('end', parseInt(e.target.value))}
                          min="0"
                          max="23"
                        />
                      </Form.Group>
                    </Col>
                  </Row>
                )}
              </Col>
              
              <Col md={6}>
                <Form.Group className="mb-3">
                  <Form.Check 
                    type="checkbox"
                    id="enable-ai-assist"
                    label="Enable AI-Assisted Decision Enhancement"
                    checked={formData.strategy_params.enable_ai_assist}
                    onChange={e => handleStrategyParamsChange('enable_ai_assist', e.target.checked)}
                  />
                  <small className="text-muted">Use AI to validate signals and enhance trading decisions</small>
                </Form.Group>
              </Col>
            </Row>
          </div>
        </Tab>

        <Tab eventKey="risk" title="Risk Management">
          <div className="p-3 border-start border-end border-bottom mb-3">
            <Row>
              <Col md={6}>
                <h5 className="mb-3">Stop Loss Settings</h5>
                <Form.Group className="mb-3">
                  <Form.Label>Stop Loss Method</Form.Label>
                  <Form.Select
                    name="stop_loss_method"
                    value={formData.stop_loss_method}
                    onChange={handleFormChange}
                  >
                    <option value="fixed">Fixed Value</option>
                    <option value="percentage">Percentage</option>
                    <option value="atr">ATR Multiple</option>
                    <option value="support">Support Level</option>
                    <option value="volatility">Volatility Based</option>
                  </Form.Select>
                </Form.Group>
                
                <Form.Group className="mb-3">
                  <Form.Label>
                    {formData.stop_loss_method === 'percentage' 
                      ? 'Stop Loss Percentage (%)' 
                      : formData.stop_loss_method === 'atr'
                      ? 'ATR Multiple'
                      : formData.stop_loss_method === 'support'
                      ? 'Buffer Below Support (%)'
                      : formData.stop_loss_method === 'volatility'
                      ? 'Volatility Multiple'
                      : 'Stop Loss Value'}
                  </Form.Label>
                  <Form.Control
                    type="number"
                    name="stop_loss_value"
                    value={formData.stop_loss_value}
                    onChange={handleParamChange}
                    step="0.1"
                    min="0.1"
                  />
                </Form.Group>
                
                <Form.Group className="mb-4">
                  <Form.Check 
                    type="checkbox"
                    id="trailing-stop-enabled"
                    label="Use Trailing Stop Loss"
                    checked={formData.trailing_stop_loss.enabled}
                    onChange={e => handleTrailingStopLossChange('enabled', e.target.checked)}
                  />
                  
                  {formData.trailing_stop_loss.enabled && (
                    <div className="ms-4 mt-2">
                      <div className="input-group input-group-sm mb-2">
                        <span className="input-group-text">Initial Trigger (%)</span>
                        <input 
                          type="number" 
                          className="form-control" 
                          min="0.1" 
                          step="0.1"
                          value={formData.trailing_stop_loss.initial_trigger}
                          onChange={e => handleTrailingStopLossChange('initial_trigger', parseFloat(e.target.value))}
                        />
                      </div>
                      <div className="input-group input-group-sm">
                        <span className="input-group-text">Trail Step (%)</span>
                        <input 
                          type="number" 
                          className="form-control" 
                          min="0.1" 
                          step="0.1"
                          value={formData.trailing_stop_loss.step}
                          onChange={e => handleTrailingStopLossChange('step', parseFloat(e.target.value))}
                        />
                      </div>
                      <small className="text-muted d-block mt-1">Stop loss will trail price by the specified step when the position moves in your favor by the initial trigger amount</small>
                    </div>
                  )}
                </Form.Group>
              </Col>
              
              <Col md={6}>
                <h5 className="mb-3">Take Profit Settings</h5>
                <Form.Group className="mb-3">
                  <Form.Label>Take Profit Method</Form.Label>
                  <Form.Select
                    name="take_profit_method"
                    value={formData.take_profit_method}
                    onChange={handleFormChange}
                  >
                    <option value="risk_reward">Risk/Reward Ratio</option>
                    <option value="percentage">Percentage</option>
                    <option value="resistance">Resistance Level</option>
                    <option value="parabolic">Parabolic SAR</option>
                    <option value="fibonacci">Fibonacci Extension</option>
                  </Form.Select>
                </Form.Group>
                
                <Form.Group className="mb-3">
                  <Form.Label>
                    {formData.take_profit_method === 'risk_reward' 
                      ? 'Risk/Reward Ratio' 
                      : formData.take_profit_method === 'percentage'
                      ? 'Take Profit Percentage (%)'
                      : formData.take_profit_method === 'resistance'
                      ? 'Buffer Below Resistance (%)'
                      : formData.take_profit_method === 'fibonacci'
                      ? 'Fibonacci Extension Level'
                      : 'Take Profit Value'}
                  </Form.Label>
                  <Form.Control
                    type="number"
                    name="take_profit_value"
                    value={formData.take_profit_value}
                    onChange={handleParamChange}
                    step="0.1"
                    min="0.1"
                  />
                </Form.Group>
                
                <Form.Group className="mb-4">
                  <Form.Check 
                    type="checkbox"
                    id="tp-scaling-enabled"
                    label="Use Profit Scaling"
                    checked={formData.take_profit_scaling.enabled}
                    onChange={e => handleTakeProfitScalingChange('enabled', e.target.checked)}
                  />
                  
                  {formData.take_profit_scaling.enabled && (
                    <div className="ms-4 mt-2">
                      <Form.Label>Profit Targets (% of position to close)</Form.Label>
                      <Row>
                        <Col xs={4}>
                          <div className="input-group input-group-sm mb-2">
                            <span className="input-group-text">1st</span>
                            <input 
                              type="number" 
                              className="form-control" 
                              min="1" 
                              max="100"
                              value={formData.take_profit_scaling.levels[0]}
                              onChange={e => handleTakeProfitScalingLevels(0, parseInt(e.target.value))}
                            />
                            <span className="input-group-text">%</span>
                          </div>
                        </Col>
                        <Col xs={4}>
                          <div className="input-group input-group-sm mb-2">
                            <span className="input-group-text">2nd</span>
                            <input 
                              type="number" 
                              className="form-control" 
                              min="1" 
                              max="100"
                              value={formData.take_profit_scaling.levels[1]}
                              onChange={e => handleTakeProfitScalingLevels(1, parseInt(e.target.value))}
                            />
                            <span className="input-group-text">%</span>
                          </div>
                        </Col>
                        <Col xs={4}>
                          <div className="input-group input-group-sm mb-2">
                            <span className="input-group-text">3rd</span>
                            <input 
                              type="number" 
                              className="form-control" 
                              min="1" 
                              max="100"
                              value={formData.take_profit_scaling.levels[2]}
                              onChange={e => handleTakeProfitScalingLevels(2, parseInt(e.target.value))}
                            />
                            <span className="input-group-text">%</span>
                          </div>
                        </Col>
                      </Row>
                      
                      <Form.Label>Target Price Multiples (R/R)</Form.Label>
                      <Row>
                        <Col xs={4}>
                          <div className="input-group input-group-sm">
                            <span className="input-group-text">1st</span>
                            <input 
                              type="number" 
                              className="form-control" 
                              min="0.5" 
                              step="0.1"
                              value={formData.take_profit_scaling.targets[0]}
                              onChange={e => handleTakeProfitScalingTargets(0, parseFloat(e.target.value))}
                            />
                          </div>
                        </Col>
                        <Col xs={4}>
                          <div className="input-group input-group-sm">
                            <span className="input-group-text">2nd</span>
                            <input 
                              type="number" 
                              className="form-control" 
                              min="0.5" 
                              step="0.1"
                              value={formData.take_profit_scaling.targets[1]}
                              onChange={e => handleTakeProfitScalingTargets(1, parseFloat(e.target.value))}
                            />
                          </div>
                        </Col>
                        <Col xs={4}>
                          <div className="input-group input-group-sm">
                            <span className="input-group-text">3rd</span>
                            <input 
                              type="number" 
                              className="form-control" 
                              min="0.5" 
                              step="0.1"
                              value={formData.take_profit_scaling.targets[2]}
                              onChange={e => handleTakeProfitScalingTargets(2, parseFloat(e.target.value))}
                            />
                          </div>
                        </Col>
                      </Row>
                    </div>
                  )}
                </Form.Group>
              </Col>
            </Row>
            
            <Row className="mt-3">
              <Col md={6}>
                <Form.Group className="mb-3">
                  <Form.Check 
                    type="checkbox"
                    id="profit-protection"
                    label="Enable Profit Protection"
                    checked={formData.strategy_params.profit_protection}
                    onChange={e => handleStrategyParamsChange('profit_protection', e.target.checked)}
                  />
                  
                  {formData.strategy_params.profit_protection && (
                    <div className="ms-4 mt-2">
                      <div className="input-group input-group-sm">
                        <span className="input-group-text">Lock-in Threshold (%)</span>
                        <input 
                          type="number" 
                          className="form-control" 
                          min="1" 
                          step="0.5"
                          value={formData.strategy_params.profit_protection_threshold}
                          onChange={e => handleStrategyParamsChange('profit_protection_threshold', parseFloat(e.target.value))}
                        />
                      </div>
                      <small className="text-muted d-block mt-1">Automatically adjust stop loss to protect profit when position reaches this profit percentage</small>
                    </div>
                  )}
                </Form.Group>
              </Col>
              
              <Col md={6}>
                <Form.Group className="mb-3">
                  <Form.Label>Maximum Drawdown Limit (%)</Form.Label>
                  <Form.Control
                    type="number"
                    value={formData.strategy_params.max_drawdown || 15}
                    onChange={e => handleStrategyParamsChange('max_drawdown', parseFloat(e.target.value))}
                    min="1"
                    max="50"
                    step="1"
                  />
                  <small className="text-muted">Strategy will pause trading if drawdown exceeds this limit</small>
                </Form.Group>
              </Col>
            </Row>
          </div>
        </Tab>
      </Tabs>
      
      <Form.Group className="mb-3">
        <Form.Check
          type="checkbox"
          label="Make this strategy public"
          name="is_public"
          checked={formData.is_public}
          onChange={handleFormChange}
        />
      </Form.Group>
      
      <div className="d-flex justify-content-between">
        <Button variant="secondary" onClick={() => setShowModal(false)}>
          Cancel
        </Button>
        <div>
          <Button variant="info" className="me-2" onClick={() => setShowTemplatesModal(true)}>
            Use Template
          </Button>
          <div className="d-flex justify-content-between align-items-center mt-4">
          <div>
            <Form.Group className="mb-0">
              <Form.Check 
                type="checkbox"
                id="backtest-after-save"
                label="Run backtest after saving"
                checked={formData.runBacktestAfterSave || false}
                onChange={(e) => setFormData({...formData, runBacktestAfterSave: e.target.checked})}
              />
            </Form.Group>
          </div>
          
          <div>
            <Button variant="secondary" className="me-2" onClick={() => setShowModal(false)}>
              Cancel
            </Button>
            <Button variant="primary" type="submit">
              {currentStrategy ? 'Update Strategy' : 'Create Strategy'}
            </Button>
          </div>
        </div>
        </div>
      </div>
    </Form>
  );

  const renderStrategyTemplatesModal = () => (
    <Modal 
      show={showTemplatesModal} 
      onHide={() => setShowTemplatesModal(false)}
      size="lg"
    >
      <Modal.Header closeButton>
        <Modal.Title>Strategy Templates</Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <Table striped hover>
          <thead>
            <tr>
              <th>Template Name</th>
              <th>Description</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {strategyTemplates.map((template, index) => (
              <tr key={index}>
                <td>{template.name}</td>
                <td>{template.description}</td>
                <td>
                  <Button 
                    variant="primary" 
                    size="sm"
                    onClick={() => handleApplyTemplate(template)}
                  >
                    Use Template
                  </Button>
                </td>
              </tr>
            ))}
          </tbody>
        </Table>
      </Modal.Body>
    </Modal>
  );

  return (
    <Container className="py-4">
      <Row className="mb-4">
        <Col>
          <h2>My Trading Strategies</h2>
          <p className="text-muted">
            Create and manage your custom trading strategies. Test them on historical data and apply them to stocks.
          </p>
        </Col>
        <Col xs="auto">
          <Button 
            variant="primary" 
            onClick={() => {
              setCurrentStrategy(null);
              setFormData({
                name: '',
                description: '',
                is_public: false,
                strategy_params: {
                  indicators: []
                },
                buy_conditions: [],
                sell_conditions: [],
                stop_loss_method: 'percentage',
                stop_loss_value: 2.0,
                take_profit_method: 'risk_reward',
                take_profit_value: 2.0
              });
              setShowModal(true);
            }}
          >
            Create New Strategy
          </Button>
        </Col>
      </Row>
      
      {error && (
        <Alert variant="danger" onClose={() => setError(null)} dismissible>
          {error}
        </Alert>
      )}
      
      {loading ? (
        <div className="text-center my-5">
          <Spinner animation="border" role="status">
            <span className="visually-hidden">Loading...</span>
          </Spinner>
        </div>
      ) : strategies.length === 0 ? (
        <Card className="text-center p-5">
          <Card.Body>
            <h4>No Strategies Yet</h4>
            <p>
              You haven't created any trading strategies yet. Create your first strategy or use one of our templates.
            </p>
            <div className="mt-3">
              <Button 
                variant="primary" 
                className="me-2"
                onClick={() => {
                  setCurrentStrategy(null);
                  setShowModal(true);
                }}
              >
                Create Strategy
              </Button>
              <Button 
                variant="outline-primary"
                onClick={() => setShowTemplatesModal(true)}
              >
                Browse Templates
              </Button>
            </div>
          </Card.Body>
        </Card>
      ) : (
        <Row>
          {strategies.map((strategy) => (
            <Col md={4} key={strategy.id} className="mb-4">
              <Card>
                <Card.Body>
                  <Card.Title>{strategy.name}</Card.Title>
                  <Card.Text>
                    {strategy.description || 'No description provided'}
                  </Card.Text>
                  
                  <div className="d-flex justify-content-between mb-2">
                    <small className="text-muted">Created: {new Date(strategy.created_at).toLocaleDateString()}</small>
                    {strategy.is_public && (
                      <span className="badge bg-info">Public</span>
                    )}
                  </div>
                  
                  <Card.Subtitle className="mb-2 text-muted">Performance</Card.Subtitle>
                  <Table size="sm">
                    <tbody>
                      <tr>
                        <td>Win Rate:</td>
                        <td className="text-end">{strategy.win_rate ? `${strategy.win_rate.toFixed(1)}%` : 'N/A'}</td>
                      </tr>
                      <tr>
                        <td>Avg. Profit:</td>
                        <td className="text-end">{strategy.avg_profit ? `${strategy.avg_profit.toFixed(2)}%` : 'N/A'}</td>
                      </tr>
                      <tr>
                        <td>Total Trades:</td>
                        <td className="text-end">{strategy.total_trades || 0}</td>
                      </tr>
                    </tbody>
                  </Table>
                </Card.Body>
                <Card.Footer>
                  <div className="d-flex justify-content-between">
                    <Button 
                      variant="outline-primary" 
                      size="sm"
                      as={Link}
                      to={`/stock-analysis?strategyId=${strategy.id}`}
                    >
                      Apply to Stock
                    </Button>
                    <div>
                      <Button 
                        variant="outline-secondary" 
                        size="sm"
                        className="me-2"
                        onClick={() => handleEditStrategy(strategy)}
                      >
                        Edit
                      </Button>
                      <Button 
                        variant="outline-danger" 
                        size="sm"
                        onClick={() => handleDeleteStrategy(strategy.id)}
                      >
                        Delete
                      </Button>
                    </div>
                  </div>
                </Card.Footer>
              </Card>
            </Col>
          ))}
        </Row>
      )}
      
      {/* Strategy Form Modal */}
      <Modal 
        show={showModal} 
        onHide={() => setShowModal(false)}
        size="lg"
      >
        <Modal.Header closeButton>
          <Modal.Title>
            {currentStrategy ? `Edit Strategy: ${currentStrategy.name}` : 'Create New Strategy'}
          </Modal.Title>
        </Modal.Header>
        <Modal.Body>
          {renderStrategyForm()}
        </Modal.Body>
      </Modal>
      
      {/* Strategy Templates Modal */}
      {renderStrategyTemplatesModal()}
      
    </Container>
  );
};

export default MyStrategies;
