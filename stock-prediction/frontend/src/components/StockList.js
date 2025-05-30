import React from 'react';
import { ListGroup } from 'react-bootstrap';
import { formatNumber } from '../services/api';

const StockList = ({ stocks, selectedStock, onSelect }) => {
  // Group stocks by sector (you can enhance this with actual sector data)
  const groupedStocks = stocks.reduce((acc, stock) => {
    const sector = stock.split('.')[0]; // Simple grouping by first part of ticker
    if (!acc[sector]) {
      acc[sector] = [];
    }
    acc[sector].push(stock);
    return acc;
  }, {});

  return (
    <ListGroup variant="flush" className="stock-list">
      {Object.entries(groupedStocks).map(([sector, sectorStocks]) => (
        <React.Fragment key={sector}>
          <ListGroup.Item className="bg-light fw-bold small text-uppercase">
            {sector}
          </ListGroup.Item>
          {sectorStocks.map((stock) => (
            <ListGroup.Item
              key={stock}
              action
              active={selectedStock === stock}
              onClick={() => onSelect(stock)}
              className="d-flex justify-content-between align-items-center"
            >
              <span>{stock}</span>
              <span className="badge bg-primary rounded-pill">
                {formatNumber(Math.floor(Math.random() * 1000))}
              </span>
            </ListGroup.Item>
          ))}
        </React.Fragment>
      ))}
    </ListGroup>
  );
};

export default StockList;
