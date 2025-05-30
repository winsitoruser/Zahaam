"""
Script pengujian untuk endpoint prediksi multi-timeframe
"""
import requests
import json
import sys
from pprint import pprint

def test_endpoint(ticker):
    """Test the multi-timeframe predictions endpoint for a given ticker"""
    url = f"http://localhost:8000/api/ml/timeframe/predictions/{ticker}"
    print(f"Testing endpoint: {url}")
    
    try:
        response = requests.get(url, timeout=30)
        
        # Check if request was successful
        if response.status_code == 200:
            print(f"✅ SUCCESS: Status code {response.status_code}")
            
            # Parse the JSON response
            data = response.json()
            
            # Validate structure
            if "ticker" in data and "predictions" in data and "count" in data:
                print(f"✅ Valid response structure")
                print(f"Ticker: {data['ticker']}")
                print(f"Predictions count: {data['count']}")
                
                # Check if we have predictions for all timeframes
                timeframes = ["1h", "3h", "24h", "7d", "30d"]
                found_timeframes = [p["timeframe"] for p in data["predictions"]]
                
                print("\nTimeframes found:")
                for tf in timeframes:
                    if tf in found_timeframes:
                        print(f"✅ {tf}")
                    else:
                        print(f"❌ {tf} (missing)")
                
                # Validate first prediction in detail
                if data["predictions"]:
                    first_pred = data["predictions"][0]
                    print("\nValidating first prediction:")
                    
                    required_fields = [
                        "timeframe", "historical_data", "prediction_dates", 
                        "predicted_values", "upper_bound", "lower_bound", 
                        "signal", "confidence"
                    ]
                    
                    for field in required_fields:
                        if field in first_pred:
                            print(f"✅ Has {field}")
                        else:
                            print(f"❌ Missing {field}")
                    
                    # Check for historical data
                    if "historical_data" in first_pred and first_pred["historical_data"]:
                        print(f"✅ Has {len(first_pred['historical_data'])} historical data points")
                        print("Sample historical data point:")
                        pprint(first_pred["historical_data"][0])
                    
                    # Check for prediction values
                    if "predicted_values" in first_pred and first_pred["predicted_values"]:
                        print(f"✅ Has {len(first_pred['predicted_values'])} prediction points")
                        
                    # Print signal information
                    if "signal" in first_pred:
                        print(f"Signal: {first_pred['signal']}")
                        print(f"Confidence: {first_pred.get('confidence', 'N/A')}")
            else:
                print("❌ Invalid response structure")
                print("Expected: ticker, predictions, count fields")
                print("Actual:", list(data.keys()))
        else:
            print(f"❌ ERROR: Status code {response.status_code}")
            print(response.text)
    
    except requests.RequestException as e:
        print(f"❌ CONNECTION ERROR: {str(e)}")
        print("Make sure the backend server is running on http://localhost:8000")
    except json.JSONDecodeError:
        print("❌ ERROR: Invalid JSON response")
        print(response.text)
    except Exception as e:
        print(f"❌ UNEXPECTED ERROR: {str(e)}")

if __name__ == "__main__":
    # Use ticker from command line or default to BBRI
    ticker = sys.argv[1] if len(sys.argv) > 1 else "BBRI"
    test_endpoint(ticker)
