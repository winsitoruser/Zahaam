#!/bin/bash

# Backup the original file
cp /Users/beever/Documents/ZAHAAM/stock-prediction/frontend/dashboard.html /Users/beever/Documents/ZAHAAM/stock-prediction/frontend/dashboard.html.backup

# Delete the duplicate "Top 5 Saham Bearish" card (lines 622-651)
sed -i '' '622,651d' /Users/beever/Documents/ZAHAAM/stock-prediction/frontend/dashboard.html

echo "Dashboard layout fixed successfully!"
