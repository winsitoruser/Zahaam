import React from 'react';
import { Alert } from 'react-bootstrap';

const ErrorDisplay = ({ error, onDismiss }) => {
  if (!error) return null;
  
  return (
    <Alert 
      variant="danger" 
      dismissible={onDismiss !== undefined}
      onClose={onDismiss}
      className="mb-4"
    >
      <Alert.Heading>Error</Alert.Heading>
      <p className="mb-0">
        {typeof error === 'string' 
          ? error 
          : error.message || 'An unexpected error occurred. Please try again.'}
      </p>
      {error.details && (
        <p className="small mt-2 mb-0">
          <strong>Details:</strong> {error.details}
        </p>
      )}
    </Alert>
  );
};

export default ErrorDisplay;
