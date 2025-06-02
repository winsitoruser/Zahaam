import sqlite3
import pandas as pd

# Connect to the database
conn = sqlite3.connect('stock_prediction.db')
cursor = conn.cursor()

# Get total record count
cursor.execute('SELECT COUNT(*) FROM stock_price')
total_records = cursor.fetchone()[0]
print(f'Total records in stock_price table: {total_records}')

# Get unique ticker count
cursor.execute('SELECT COUNT(DISTINCT ticker) FROM stock_price')
unique_tickers = cursor.fetchone()[0]
print(f'Total unique tickers: {unique_tickers}')

# Get data count by ticker and interval
cursor.execute('''
    SELECT ticker, interval, COUNT(*) 
    FROM stock_price 
    GROUP BY ticker, interval 
    ORDER BY ticker, interval
''')
print('\nTicker data counts by interval:')
for row in cursor.fetchall():
    print(row)

# Check specifically for WIKA.JK data
cursor.execute('''
    SELECT interval, COUNT(*) 
    FROM stock_price 
    WHERE ticker = 'WIKA.JK'
    GROUP BY interval
''')
print('\nWIKA.JK data by interval:')
wika_data = cursor.fetchall()
if wika_data:
    for row in wika_data:
        print(row)
else:
    print("No data found for WIKA.JK")

# Get latest date for each interval
cursor.execute('''
    SELECT ticker, interval, MAX(date) 
    FROM stock_price 
    GROUP BY ticker, interval 
    HAVING ticker = 'WIKA.JK'
''')
print('\nLatest dates for WIKA.JK:')
dates = cursor.fetchall()
if dates:
    for row in dates:
        print(row)
else:
    print("No date data found for WIKA.JK")

conn.close()
