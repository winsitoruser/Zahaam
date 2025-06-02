# Zahaam Deployment Guide

## Project Structure
- Frontend: Static HTML/JS application
- Backend: FastAPI application for stock prediction

## Deployment Steps

### Backend Deployment
1. Install Python 3.8+ on your server
2. Clone the repository to your server
3. Install dependencies:
   ```bash
   cd stock-prediction/backend
   pip install -r requirements.txt
   ```
4. Run the application (for testing):
   ```bash
   cd stock-prediction/backend
   python -m uvicorn app.main:app --host 0.0.0.0 --port 8000
   ```
5. For production, use a process manager like Gunicorn with Uvicorn workers:
   ```bash
   pip install gunicorn
   gunicorn -w 4 -k uvicorn.workers.UvicornWorker app.main:app --bind 0.0.0.0:8000
   ```

### Frontend Deployment
1. Build the frontend:
   ```bash
   cd frontend
   npm install
   npm run build
   ```
2. Deploy the built static files to a web server like Nginx

### Using a Process Manager (recommended)
Install PM2 to keep your application running:
```bash
npm install -g pm2
pm2 start "cd /path/to/stock-prediction/backend && python -m uvicorn app.main:app --host 0.0.0.0 --port 8000" --name "zahaam-backend"
pm2 save
pm2 startup
```

### Setting up with Nginx (recommended)
Configure Nginx to serve the frontend and proxy API requests to the backend:

```nginx
server {
    listen 80;
    server_name your-domain.com;

    # Frontend
    location / {
        root /path/to/zahaam/frontend/dist;
        try_files $uri $uri/ /index.html;
    }

    # Backend API
    location /api/ {
        proxy_pass http://localhost:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

### Using Docker (alternative)
If you prefer to use Docker, you can create Docker files and deploy with Docker Compose.
