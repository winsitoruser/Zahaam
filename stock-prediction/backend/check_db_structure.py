import sqlite3

# Connect to the database
conn = sqlite3.connect('stock_prediction.db')
cursor = conn.cursor()

# Get list of tables
cursor.execute("SELECT name FROM sqlite_master WHERE type='table';")
tables = cursor.fetchall()

print("Tables in the database:")
for table in tables:
    print(f"- {table[0]}")
    # Get table structure
    try:
        cursor.execute(f"PRAGMA table_info({table[0]})")
        columns = cursor.fetchall()
        print("  Columns:")
        for col in columns:
            print(f"    - {col[1]} ({col[2]})")
        
        # Get row count
        cursor.execute(f"SELECT COUNT(*) FROM {table[0]}")
        count = cursor.fetchone()[0]
        print(f"  Row count: {count}")
        
        # Sample data (first 3 rows)
        cursor.execute(f"SELECT * FROM {table[0]} LIMIT 3")
        rows = cursor.fetchall()
        if rows:
            print("  Sample data (first 3 rows):")
            for row in rows:
                print(f"    {row}")
        print("")
    except sqlite3.Error as e:
        print(f"  Error getting info for table {table[0]}: {e}")

conn.close()
