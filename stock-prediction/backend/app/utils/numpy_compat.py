"""
Compatibility layer for handling different versions of NumPy
"""
import numpy as np

# Define NaN for older code expecting it in uppercase
NaN = np.nan

# Export this as npNaN for compatibility with pandas_ta
npNaN = NaN
