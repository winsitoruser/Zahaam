import React from 'react';
import { Spinner } from 'react-bootstrap';

const LoadingIndicator = ({ message = 'Loading data...', size = 'md' }) => {
  return (
    <div className="text-center py-5">
      <Spinner 
        animation="border" 
        role="status"
        className={`mb-2 ${size === 'sm' ? 'spinner-border-sm' : ''}`}
      >
        <span className="visually-hidden">Loading...</span>
      </Spinner>
      <p className={size === 'sm' ? 'small mb-0' : 'mb-0'}>{message}</p>
    </div>
  );
};

export default LoadingIndicator;
