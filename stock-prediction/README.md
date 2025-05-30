# Indonesian Stock Market Prediction

A web application for predicting and analyzing Indonesian stock market data using Yahoo Finance API.

## Features

- Real-time stock data for Indonesian stocks
- Technical analysis with various indicators (RSI, MACD, Bollinger Bands, Moving Averages)
- Buy/Sell signals based on technical indicators
- Stock price visualization
- Company information and financials

## Prerequisites

- Python 3.8+
- Node.js 14+ (for frontend)
- npm or yarn

## Backend Setup

1. Navigate to the backend directory:
   ```
   cd backend
   ```

2. Create a virtual environment (recommended):
   ```
   python -m venv venv
   .\\venv\\Scripts\\activate  # On Windows
   source venv/bin/activate  # On macOS/Linux
   ```

3. Install the required packages:
   ```
   pip install -r requirements.txt
   ```

4. Run the FastAPI server:
   ```
   uvicorn app.main:app --reload
   ```

   The API will be available at `http://localhost:8000`

## Frontend Setup

1. Navigate to the frontend directory:
   ```
   cd frontend
   ```

2. Install dependencies:
   ```
   npm install
   ```

3. Start the development server:
   ```
   npm run dev
   ```

   The frontend will be available at `http://localhost:3000`

## API Endpoints

- `GET /api/stocks` - Get list of available Indonesian stocks
- `GET /api/stock/{ticker}` - Get stock data for a specific ticker
  - Parameters:
    - `period`: Data period (default: "1y")
    - `interval`: Data interval (default: "1d")
- `GET /api/technical/signal/{ticker}` - Get technical signals for a stock
  - Parameters:
    - `period`: Data period (default: "1y")

## Project Structure

```
stock-prediction/
├── backend/
│   ├── app/
│   │   ├── api/           # API routes
│   │   ├── core/          # Core functionality
│   │   ├── models/        # Database models
│   │   └── main.py        # FastAPI application
│   └── requirements.txt   # Python dependencies
└── frontend/              # Frontend application
    ├── public/            # Static files
    ├── src/               # Source files
    ├── package.json       # Frontend dependencies
    └── ...
```

## License

MIT
