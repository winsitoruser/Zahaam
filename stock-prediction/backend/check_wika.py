import sqlite3
import pandas as pd

# Connect to the database
conn = sqlite3.connect('stock_prediction.db')
cursor = conn.cursor()

# First, get the stock_id for WIKA.JK
cursor.execute("SELECT id FROM stocks WHERE ticker = 'WIKA.JK'")
wika_id = cursor.fetchone()

if wika_id:
    wika_id = wika_id[0]
    print(f"WIKA.JK stock_id: {wika_id}")

    # Check data in stock_prices table
    cursor.execute(f"SELECT date, open, high, low, close, volume FROM stock_prices WHERE stock_id = {wika_id} ORDER BY date")
    wika_prices = cursor.fetchall()
    
    if wika_prices:
        print(f"\nWIKA.JK price data available: {len(wika_prices)} records")
        print("\nSample of WIKA.JK price data (first 5 records):")
        for i, row in enumerate(wika_prices[:5]):
            print(f"{i+1}. Date: {row[0]}, Open: {row[1]}, High: {row[2]}, Low: {row[3]}, Close: {row[4]}, Volume: {row[5]}")
        
        # Check date ranges to determine which intervals were actually saved
        date_format = "%Y-%m-%d %H:%M:%S"
        dates = [row[0] for row in wika_prices]
        
        # Identify different intervals based on time differences
        intervals = {}
        
        # Sort dates and check patterns
        if len(dates) > 1:
            dates.sort()
            print("\nDate range analysis:")
            print(f"First date: {dates[0]}")
            print(f"Last date: {dates[-1]}")
            
            # Try to detect patterns in timestamps
            daily_count = 0
            hourly_count = 0
            minute15_count = 0
            minute5_count = 0
            
            for i in range(len(dates)-1):
                date_diff = pd.to_datetime(dates[i+1]) - pd.to_datetime(dates[i])
                hours = date_diff.total_seconds() / 3600
                
                if 20 <= hours <= 28:  # Daily (accounting for weekends, holidays)
                    daily_count += 1
                elif 0.9 <= hours <= 1.1:  # Hourly
                    hourly_count += 1
                elif 0.2 <= hours <= 0.3:  # 15-minute
                    minute15_count += 1
                elif 0.05 <= hours <= 0.15:  # 5-minute
                    minute5_count += 1
            
            print(f"\nDetected intervals based on time differences:")
            if daily_count > 0:
                print(f"- Daily data points: ~{daily_count}")
            if hourly_count > 0:
                print(f"- Hourly data points: ~{hourly_count}")
            if minute15_count > 0:
                print(f"- 15-minute data points: ~{minute15_count}")
            if minute5_count > 0:
                print(f"- 5-minute data points: ~{minute5_count}")
    else:
        print("No price data found for WIKA.JK")
else:
    print("WIKA.JK not found in stocks table")

conn.close()
