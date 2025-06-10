"""
API routes for portfolio management
"""
from fastapi import APIRouter, Depends, HTTPException, Query, Path, File, UploadFile
from typing import List, Dict, Any, Optional
from sqlalchemy.orm import Session
from datetime import datetime, timedelta, date
import pandas as pd
import io
import csv
import json
from decimal import Decimal

from app.core.database import get_db
from app.models.stocks import Stock, StockPrice
from app.models.portfolio import Portfolio, PortfolioHolding, PortfolioTransaction, PortfolioSnapshot, PortfolioAlert, TransactionType
from app.core.security import get_current_active_user
from pydantic import BaseModel, Field, validator

router = APIRouter(tags=["portfolio"])

# Pydantic models for request/response validation
class PortfolioCreate(BaseModel):
    name: str
    description: Optional[str] = None
    default_currency: str = "IDR"
    is_default: bool = False

class TransactionCreate(BaseModel):
    portfolio_id: str
    transaction_type: str
    ticker: Optional[str] = None
    date: Optional[datetime] = None
    shares: Optional[float] = None
    price: Optional[float] = None
    amount: float
    fees: float = 0.0
    notes: Optional[str] = None
    split_ratio: Optional[str] = None
    
    @validator('transaction_type')
    def validate_transaction_type(cls, v):
        if v not in [t.value for t in TransactionType]:
            raise ValueError(f"Invalid transaction type. Must be one of: {', '.join([t.value for t in TransactionType])}")
        return v
    
    @validator('ticker')
    def validate_ticker(cls, v, values):
        if values.get('transaction_type') not in [TransactionType.DEPOSIT.value, TransactionType.WITHDRAWAL.value]:
            if not v:
                raise ValueError("Ticker is required for buy/sell/dividend/split transactions")
        return v
    
    @validator('shares')
    def validate_shares(cls, v, values):
        if values.get('transaction_type') in [TransactionType.BUY.value, TransactionType.SELL.value]:
            if not v:
                raise ValueError("Shares is required for buy/sell transactions")
        return v
    
    @validator('price')
    def validate_price(cls, v, values):
        if values.get('transaction_type') in [TransactionType.BUY.value, TransactionType.SELL.value]:
            if not v:
                raise ValueError("Price is required for buy/sell transactions")
        return v
        
class AlertCreate(BaseModel):
    portfolio_id: Optional[str] = None
    ticker: Optional[str] = None
    alert_type: str
    target_value: Optional[float] = None
    target_percentage: Optional[float] = None
    direction: Optional[str] = None
    
    @validator('alert_type')
    def validate_alert_type(cls, v):
        valid_types = ["price_target", "price_change", "portfolio_value"]
        if v not in valid_types:
            raise ValueError(f"Invalid alert type. Must be one of: {', '.join(valid_types)}")
        return v
    
    @validator('ticker')
    def validate_ticker(cls, v, values):
        if values.get('alert_type') in ["price_target", "price_change"]:
            if not v:
                raise ValueError(f"Ticker is required for {values.get('alert_type')} alerts")
        return v
    
    @validator('portfolio_id')
    def validate_portfolio_id(cls, v, values):
        if values.get('alert_type') == "portfolio_value":
            if not v:
                raise ValueError("Portfolio ID is required for portfolio_value alerts")
        return v

@router.get("/portfolios")
async def get_portfolios(
    db: Session = Depends(get_db),
    user = Depends(get_current_active_user)
):
    """
    Get all portfolios for the current user
    """
    user_id = user.get('id')
    portfolios = db.query(Portfolio).filter(Portfolio.user_id == user_id).all()
    
    results = []
    for p in portfolios:
        results.append({
            "id": p.id,
            "name": p.name,
            "description": p.description,
            "default_currency": p.default_currency,
            "is_default": p.is_default,
            "cash_balance": p.cash_balance,
            "total_value": p.total_value,
            "total_cost": p.total_cost,
            "created_at": p.created_at,
            "holdings_count": len(p.holdings)
        })
    
    return {"portfolios": results}

@router.post("/portfolios")
async def create_portfolio(
    portfolio: PortfolioCreate,
    db: Session = Depends(get_db),
    user = Depends(get_current_active_user)
):
    """
    Create a new portfolio for the current user
    """
    user_id = user.get('id')
    
    # If is_default is True, make all other portfolios not default
    if portfolio.is_default:
        db.query(Portfolio).filter(
            Portfolio.user_id == user_id,
            Portfolio.is_default == True
        ).update({"is_default": False})
    
    new_portfolio = Portfolio(
        user_id=user_id,
        name=portfolio.name,
        description=portfolio.description,
        default_currency=portfolio.default_currency,
        is_default=portfolio.is_default,
        cash_balance=0.0,
        total_value=0.0,
        total_cost=0.0,
    )
    
    db.add(new_portfolio)
    db.commit()
    db.refresh(new_portfolio)
    
    return {
        "id": new_portfolio.id,
        "name": new_portfolio.name,
        "description": new_portfolio.description,
        "default_currency": new_portfolio.default_currency,
        "is_default": new_portfolio.is_default,
        "created_at": new_portfolio.created_at
    }

@router.get("/portfolios/{portfolio_id}")
async def get_portfolio(
    portfolio_id: str,
    db: Session = Depends(get_db),
    user = Depends(get_current_active_user)
):
    """
    Get portfolio holdings and performance for a specific portfolio
    """
    user_id = user.get('id')
    
    # Get the portfolio
    portfolio = db.query(Portfolio).filter(
        Portfolio.id == portfolio_id,
        Portfolio.user_id == user_id
    ).first()
    
    if not portfolio:
        raise HTTPException(status_code=404, detail="Portfolio not found")
    
    # Get the portfolio holdings
    holdings = db.query(PortfolioHolding).filter(
        PortfolioHolding.portfolio_id == portfolio_id
    ).all()
    
    # Prepare holdings data with current prices
    portfolio_items = []
    total_current_value = portfolio.cash_balance
    
    for holding in holdings:
        # Get the stock info
        stock = db.query(Stock).filter(Stock.ticker == holding.ticker).first()
        stock_name = stock.name if stock else holding.ticker
        sector = stock.sector if stock else "Unknown"
        
        # Get latest price
        if stock:
            latest_price = db.query(StockPrice).filter(
                StockPrice.stock_id == stock.id
            ).order_by(StockPrice.date.desc()).first()
            
            current_price = latest_price.close if latest_price else holding.current_price
        else:
            current_price = holding.current_price
            
        # Recalculate current value
        current_value = holding.shares * current_price
        unrealized_gain_loss = current_value - holding.cost_basis
        unrealized_gain_loss_pct = (unrealized_gain_loss / holding.cost_basis) * 100 if holding.cost_basis > 0 else 0
        
        # Update holding with current price if needed
        if holding.current_price != current_price:
            holding.current_price = current_price
            holding.current_value = current_value
            holding.unrealized_gain_loss = unrealized_gain_loss
            holding.unrealized_gain_loss_pct = unrealized_gain_loss_pct
            holding.last_updated = datetime.now()
            db.commit()
        
        portfolio_items.append({
            "id": holding.id,
            "ticker": holding.ticker,
            "name": stock_name,
            "sector": sector,
            "shares": holding.shares,
            "avg_price": round(holding.average_cost, 2),
            "cost_basis": round(holding.cost_basis, 2),
            "current_price": round(current_price, 2),
            "current_value": round(current_value, 2),
            "unrealized_gain_loss": round(unrealized_gain_loss, 2),
            "unrealized_gain_loss_pct": round(unrealized_gain_loss_pct, 2),
            "realized_gain_loss": round(holding.realized_gain_loss, 2) if holding.realized_gain_loss else 0,
            "last_updated": holding.last_updated.strftime("%Y-%m-%d %H:%M:%S")
        })
        
        total_current_value += current_value
    
    # Update portfolio total value if needed
    if portfolio.total_value != total_current_value:
        portfolio.total_value = total_current_value
        portfolio.updated_at = datetime.now()
        db.commit()
    
    # Calculate total performance
    total_gain_loss = total_current_value - portfolio.total_cost
    total_gain_loss_percent = (total_gain_loss / portfolio.total_cost) * 100 if portfolio.total_cost > 0 else 0
    
    # Create allocation data
    sector_allocation = {}
    for item in portfolio_items:
        sector = item["sector"]
        if sector not in sector_allocation:
            sector_allocation[sector] = 0
        sector_allocation[sector] += item["current_value"]
    
    # Convert to percentages
    sector_allocation_percent = [
        {"sector": sector, "percentage": (value / total_current_value) * 100}
        for sector, value in sector_allocation.items()
    ] if total_current_value > 0 else []
    
    # Sort by allocation percentage
    sector_allocation_percent = sorted(
        sector_allocation_percent, 
        key=lambda x: x["percentage"], 
        reverse=True
    )
    
    return {
        "id": portfolio.id,
        "name": portfolio.name,
        "description": portfolio.description,
        "default_currency": portfolio.default_currency,
        "is_default": portfolio.is_default,
        "holdings": portfolio_items,
        "summary": {
            "cash_balance": round(portfolio.cash_balance, 2),
            "total_cost": round(portfolio.total_cost, 2),
            "total_current_value": round(total_current_value, 2),
            "total_gain_loss": round(total_gain_loss, 2),
            "total_gain_loss_percent": round(total_gain_loss_percent, 2),
            "last_updated": datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        },
        "allocation": {
            "by_sector": sector_allocation_percent
        }
    }

@router.post("/portfolios/{portfolio_id}/transactions")
async def add_portfolio_transaction(
    portfolio_id: str,
    transaction: TransactionCreate,
    db: Session = Depends(get_db),
    user = Depends(get_current_active_user)
):
    """
    Add a new transaction to a portfolio
    
    Parameters:
    - portfolio_id: Portfolio ID
    - transaction: Transaction details
    """
    user_id = user.get('id')
    
    # Verify portfolio exists and belongs to user
    portfolio = db.query(Portfolio).filter(
        Portfolio.id == portfolio_id,
        Portfolio.user_id == user_id
    ).first()
    
    if not portfolio:
        raise HTTPException(status_code=404, detail="Portfolio not found")
    
    # Set default date if not provided
    if not transaction.date:
        transaction.date = datetime.now()
    
    # Calculate amount if not explicitly set (for BUY/SELL)
    amount = transaction.amount
    if transaction.transaction_type in [TransactionType.BUY.value, TransactionType.SELL.value]:
        if transaction.shares and transaction.price:
            amount = transaction.shares * transaction.price
    
    # Begin transaction processing
    # Create transaction record
    new_transaction = PortfolioTransaction(
        portfolio_id=portfolio_id,
        transaction_type=transaction.transaction_type,
        ticker=transaction.ticker,
        shares=transaction.shares,
        price=transaction.price,
        amount=amount,
        fees=transaction.fees,
        date=transaction.date,
        notes=transaction.notes,
        split_ratio=transaction.split_ratio
    )
    
    db.add(new_transaction)
    
    # Process based on transaction type
    if transaction.transaction_type == TransactionType.DEPOSIT.value:
        portfolio.cash_balance += amount
        portfolio.total_value += amount
        
    elif transaction.transaction_type == TransactionType.WITHDRAWAL.value:
        if portfolio.cash_balance < amount:
            db.rollback()
            raise HTTPException(status_code=400, detail=f"Insufficient cash balance for withdrawal. Available: {portfolio.cash_balance}")
        portfolio.cash_balance -= amount
        portfolio.total_value -= amount
        
    elif transaction.transaction_type == TransactionType.BUY.value:
        total_cost = amount + transaction.fees
        if portfolio.cash_balance < total_cost:
            db.rollback()
            raise HTTPException(status_code=400, detail=f"Insufficient cash balance for purchase. Available: {portfolio.cash_balance}, Required: {total_cost}")
            
        # Update cash balance
        portfolio.cash_balance -= total_cost
        portfolio.total_cost += total_cost
        
        # Update or create holding
        holding = db.query(PortfolioHolding).filter(
            PortfolioHolding.portfolio_id == portfolio_id,
            PortfolioHolding.ticker == transaction.ticker
        ).first()
        
        if holding:
            # Update existing holding
            new_shares = holding.shares + transaction.shares
            new_cost_basis = holding.cost_basis + amount + transaction.fees
            holding.average_cost = new_cost_basis / new_shares if new_shares > 0 else 0
            holding.shares = new_shares
            holding.cost_basis = new_cost_basis
            holding.updated_at = datetime.now()
            
            # Update current value
            holding.current_value = holding.shares * holding.current_price
            holding.unrealized_gain_loss = holding.current_value - holding.cost_basis
            holding.unrealized_gain_loss_pct = (holding.unrealized_gain_loss / holding.cost_basis) * 100 if holding.cost_basis > 0 else 0
        else:
            # Create new holding
            current_price = transaction.price
            current_value = transaction.shares * current_price
            
            new_holding = PortfolioHolding(
                portfolio_id=portfolio_id,
                ticker=transaction.ticker,
                shares=transaction.shares,
                average_cost=transaction.price,
                cost_basis=amount + transaction.fees,
                current_price=current_price,
                current_value=current_value,
                unrealized_gain_loss=0,
                unrealized_gain_loss_pct=0,
                realized_gain_loss=0,
                last_updated=datetime.now()
            )
            
            db.add(new_holding)
            
    elif transaction.transaction_type == TransactionType.SELL.value:
        # Verify holding exists
        holding = db.query(PortfolioHolding).filter(
            PortfolioHolding.portfolio_id == portfolio_id,
            PortfolioHolding.ticker == transaction.ticker
        ).first()
        
        if not holding:
            db.rollback()
            raise HTTPException(status_code=400, detail=f"No {transaction.ticker} shares in portfolio")
            
        if holding.shares < transaction.shares:
            db.rollback()
            raise HTTPException(status_code=400, detail=f"Insufficient shares for sale. Available: {holding.shares}, Required: {transaction.shares}")
        
        # Calculate realized gain/loss
        cost_per_share = holding.average_cost
        cost_basis_sold = cost_per_share * transaction.shares
        sale_value = amount - transaction.fees
        realized_gain_loss = sale_value - cost_basis_sold
        
        # Update holding
        holding.realized_gain_loss = (holding.realized_gain_loss or 0) + realized_gain_loss
        holding.shares -= transaction.shares
        
        # If shares now zero, adjust cost basis, otherwise keep the average cost
        if holding.shares == 0:
            holding.cost_basis = 0
            holding.average_cost = 0
        else:
            holding.cost_basis -= cost_basis_sold
            
        # Update current value
        holding.current_value = holding.shares * holding.current_price
        holding.unrealized_gain_loss = holding.current_value - holding.cost_basis
        holding.unrealized_gain_loss_pct = (holding.unrealized_gain_loss / holding.cost_basis) * 100 if holding.cost_basis > 0 else 0
        holding.updated_at = datetime.now()
        
        # Update cash balance
        portfolio.cash_balance += sale_value
        portfolio.total_cost -= cost_basis_sold
        
    elif transaction.transaction_type == TransactionType.DIVIDEND.value:
        # Add dividend amount to cash balance
        portfolio.cash_balance += amount
        portfolio.total_value += amount
        
    elif transaction.transaction_type == TransactionType.SPLIT.value and transaction.split_ratio:
        # Handle stock split
        try:
            split_parts = transaction.split_ratio.split(':')
            if len(split_parts) != 2:
                raise ValueError("Invalid split ratio format")
                
            new_shares = int(split_parts[0])
            old_shares = int(split_parts[1])
            split_factor = new_shares / old_shares
            
            # Update holding
            holding = db.query(PortfolioHolding).filter(
                PortfolioHolding.portfolio_id == portfolio_id,
                PortfolioHolding.ticker == transaction.ticker
            ).first()
            
            if holding:
                # Adjust shares and cost basis
                holding.shares = holding.shares * split_factor
                holding.average_cost = holding.average_cost / split_factor
                holding.current_price = holding.current_price / split_factor
                holding.updated_at = datetime.now()
                
                # Current value remains the same
                holding.current_value = holding.shares * holding.current_price
            else:
                db.rollback()
                raise HTTPException(status_code=400, detail=f"No {transaction.ticker} shares in portfolio for split")
                
        except (ValueError, ZeroDivisionError) as e:
            db.rollback()
            raise HTTPException(status_code=400, detail=f"Invalid split ratio: {transaction.split_ratio}. Use format like '2:1'")
    
    # Update portfolio totals and timestamp
    portfolio.updated_at = datetime.now()
    db.commit()
    db.refresh(new_transaction)
    
    return {
        "status": "success",
        "message": f"{transaction.transaction_type} transaction added successfully",
        "transaction": {
            "id": new_transaction.id,
            "portfolio_id": portfolio_id,
            "ticker": new_transaction.ticker,
            "transaction_type": new_transaction.transaction_type,
            "shares": new_transaction.shares,
            "price": new_transaction.price,
            "amount": new_transaction.amount,
            "fees": new_transaction.fees,
            "date": new_transaction.date.strftime("%Y-%m-%d %H:%M:%S"),
            "notes": new_transaction.notes
        }
    }

@router.post("/portfolios/{portfolio_id}/upload")
async def upload_portfolio_csv(
    portfolio_id: str,
    file: UploadFile = File(...),
    db: Session = Depends(get_db),
    user = Depends(get_current_active_user)
):
    """
    Upload portfolio transactions from CSV file
    
    Parameters:
    - portfolio_id: Portfolio ID
    - file: CSV file with portfolio transactions
    
    Expected CSV format:
    ticker,transaction_type,quantity,price,date,notes,fees
    """
    user_id = user.get('id')
    
    # Verify portfolio exists and belongs to user
    portfolio = db.query(Portfolio).filter(
        Portfolio.id == portfolio_id,
        Portfolio.user_id == user_id
    ).first()
    
    if not portfolio:
        raise HTTPException(status_code=404, detail="Portfolio not found")
        
    # Check file extension
    if not file.filename.endswith('.csv'):
        raise HTTPException(status_code=400, detail="File must be a CSV")
    
    # Read CSV data
    try:
        contents = await file.read()
        df = pd.read_csv(io.StringIO(contents.decode('utf-8')))
        
        # Check required columns
        required_columns = ['ticker', 'transaction_type', 'quantity', 'price']
        missing_columns = [col for col in required_columns if col not in df.columns]
        if missing_columns:
            raise HTTPException(
                status_code=400, 
                detail=f"Missing required columns: {', '.join(missing_columns)}"
            )
        
        # Process transactions
        processed_count = 0
        failed_count = 0
        failures = []
        transactions = []
        
        for idx, row in df.iterrows():
            try:
                # Process ticker
                ticker = row['ticker'].strip().upper()
                
                # Validate transaction type
                transaction_type = str(row['transaction_type']).strip().lower()
                if transaction_type not in [t.value.lower() for t in TransactionType]:
                    raise ValueError(f"Invalid transaction type for {ticker}: {transaction_type}. Valid types: {', '.join([t.value.lower() for t in TransactionType])}")
                
                # Map to enum value (case insensitive)
                transaction_type = next(t.value for t in TransactionType if t.value.lower() == transaction_type.lower())
                
                # Validate quantity and price
                quantity = float(row['quantity']) if 'quantity' in row and not pd.isna(row['quantity']) else None
                price = float(row['price']) if 'price' in row and not pd.isna(row['price']) else None
                
                # Validate required fields based on transaction type
                if transaction_type in [TransactionType.BUY.value, TransactionType.SELL.value]:
                    if not quantity or quantity <= 0:
                        raise ValueError(f"Invalid quantity for {ticker}: {quantity}")
                    if not price or price <= 0:
                        raise ValueError(f"Invalid price for {ticker}: {price}")
                
                # Calculate amount
                amount = quantity * price if quantity and price else row.get('amount')
                if pd.isna(amount):
                    amount = 0
                
                # Get optional fields
                transaction_date = row.get('date')
                if not transaction_date or pd.isna(transaction_date):
                    transaction_date = datetime.now()
                elif isinstance(transaction_date, str):
                    # Try to parse the date string
                    try:
                        transaction_date = datetime.strptime(transaction_date, "%Y-%m-%d")
                    except ValueError:
                        try:
                            transaction_date = datetime.strptime(transaction_date, "%Y-%m-%d %H:%M:%S")
                        except ValueError:
                            transaction_date = datetime.now()
                
                notes = row.get('notes')
                if pd.isna(notes):
                    notes = None
                
                fees = float(row.get('fees', 0))
                if pd.isna(fees):
                    fees = 0
                
                split_ratio = row.get('split_ratio')
                if pd.isna(split_ratio):
                    split_ratio = None
                
                # Create transaction object
                transaction = TransactionCreate(
                    portfolio_id=portfolio_id,
                    transaction_type=transaction_type,
                    ticker=ticker,
                    shares=quantity,
                    price=price,
                    amount=amount,
                    fees=fees,
                    date=transaction_date,
                    notes=notes,
                    split_ratio=split_ratio
                )
                
                transactions.append(transaction)
                processed_count += 1
                
            except Exception as e:
                failed_count += 1
                failures.append(f"Row {idx+2}: {str(e)}")
        
        # Now process all valid transactions
        successful_transactions = []
        
        for transaction in transactions:
            try:
                # Create transaction record
                new_transaction = PortfolioTransaction(
                    portfolio_id=portfolio_id,
                    transaction_type=transaction.transaction_type,
                    ticker=transaction.ticker,
                    shares=transaction.shares,
                    price=transaction.price,
                    amount=transaction.amount,
                    fees=transaction.fees,
                    date=transaction.date,
                    notes=transaction.notes,
                    split_ratio=transaction.split_ratio
                )
                
                db.add(new_transaction)
                
                # Process based on transaction type
                if transaction.transaction_type == TransactionType.DEPOSIT.value:
                    portfolio.cash_balance += transaction.amount
                    portfolio.total_value += transaction.amount
                    
                elif transaction.transaction_type == TransactionType.WITHDRAWAL.value:
                    if portfolio.cash_balance < transaction.amount:
                        db.rollback()
                        failures.append(f"Transaction {transaction.transaction_type} for {transaction.amount}: Insufficient cash balance")
                        continue
                    portfolio.cash_balance -= transaction.amount
                    portfolio.total_value -= transaction.amount
                    
                elif transaction.transaction_type == TransactionType.BUY.value:
                    total_cost = transaction.amount + transaction.fees
                    if portfolio.cash_balance < total_cost:
                        db.rollback()
                        failures.append(f"Transaction {transaction.ticker} {transaction.transaction_type}: Insufficient cash balance")
                        continue
                        
                    # Update cash balance
                    portfolio.cash_balance -= total_cost
                    portfolio.total_cost += total_cost
                    
                    # Update or create holding
                    holding = db.query(PortfolioHolding).filter(
                        PortfolioHolding.portfolio_id == portfolio_id,
                        PortfolioHolding.ticker == transaction.ticker
                    ).first()
                    
                    if holding:
                        # Update existing holding
                        new_shares = holding.shares + transaction.shares
                        new_cost_basis = holding.cost_basis + transaction.amount + transaction.fees
                        holding.average_cost = new_cost_basis / new_shares if new_shares > 0 else 0
                        holding.shares = new_shares
                        holding.cost_basis = new_cost_basis
                        holding.updated_at = datetime.now()
                        
                        # Update current value
                        holding.current_value = holding.shares * holding.current_price
                        holding.unrealized_gain_loss = holding.current_value - holding.cost_basis
                        holding.unrealized_gain_loss_pct = (holding.unrealized_gain_loss / holding.cost_basis) * 100 if holding.cost_basis > 0 else 0
                    else:
                        # Create new holding
                        current_price = transaction.price
                        current_value = transaction.shares * current_price
                        
                        new_holding = PortfolioHolding(
                            portfolio_id=portfolio_id,
                            ticker=transaction.ticker,
                            shares=transaction.shares,
                            average_cost=transaction.price,
                            cost_basis=transaction.amount + transaction.fees,
                            current_price=current_price,
                            current_value=current_value,
                            unrealized_gain_loss=0,
                            unrealized_gain_loss_pct=0,
                            realized_gain_loss=0,
                            last_updated=datetime.now()
                        )
                        
                        db.add(new_holding)
                        
                elif transaction.transaction_type == TransactionType.SELL.value:
                    # Processing sell transaction logic similar to the add_portfolio_transaction endpoint
                    holding = db.query(PortfolioHolding).filter(
                        PortfolioHolding.portfolio_id == portfolio_id,
                        PortfolioHolding.ticker == transaction.ticker
                    ).first()
                    
                    if not holding:
                        failures.append(f"Transaction {transaction.ticker} {transaction.transaction_type}: No shares in portfolio")
                        continue
                        
                    if holding.shares < transaction.shares:
                        failures.append(f"Transaction {transaction.ticker} {transaction.transaction_type}: Insufficient shares for sale")
                        continue
                    
                    # Calculate realized gain/loss
                    cost_per_share = holding.average_cost
                    cost_basis_sold = cost_per_share * transaction.shares
                    sale_value = transaction.amount - transaction.fees
                    realized_gain_loss = sale_value - cost_basis_sold
                    
                    # Update holding
                    holding.realized_gain_loss = (holding.realized_gain_loss or 0) + realized_gain_loss
                    holding.shares -= transaction.shares
                    
                    # If shares now zero, adjust cost basis, otherwise keep the average cost
                    if holding.shares == 0:
                        holding.cost_basis = 0
                        holding.average_cost = 0
                    else:
                        holding.cost_basis -= cost_basis_sold
                        
                    # Update current value
                    holding.current_value = holding.shares * holding.current_price
                    holding.unrealized_gain_loss = holding.current_value - holding.cost_basis
                    holding.unrealized_gain_loss_pct = (holding.unrealized_gain_loss / holding.cost_basis) * 100 if holding.cost_basis > 0 else 0
                    holding.updated_at = datetime.now()
                    
                    # Update cash balance
                    portfolio.cash_balance += sale_value
                    portfolio.total_cost -= cost_basis_sold
                
                successful_transactions.append(new_transaction)
                
            except Exception as e:
                failures.append(f"Transaction processing error: {str(e)}")
        
        # Commit all successful transactions
        portfolio.updated_at = datetime.now()
        db.commit()
        
        return {
            "status": "success",
            "message": f"CSV processed with {len(successful_transactions)} transactions imported, {len(failures)} failed",
            "processed_count": len(successful_transactions),
            "failed_count": len(failures),
            "failures": failures
        }
        
    except Exception as e:
        db.rollback()
        raise HTTPException(status_code=500, detail=f"Error processing CSV: {str(e)}")

@router.get("/portfolios/{portfolio_id}/performance")
async def get_portfolio_performance(
    portfolio_id: str,
    period: str = Query("1m", description="Time period: 1w, 1m, 3m, 6m, 1y, all"),
    db: Session = Depends(get_db),
    user = Depends(get_current_active_user)
):
    """
    Get historical performance data for a portfolio
    
    Parameters:
    - portfolio_id: Portfolio ID
    - period: Time period for performance data
    """
    user_id = user.get('id')
    
    # Verify portfolio exists and belongs to user
    portfolio = db.query(Portfolio).filter(
        Portfolio.id == portfolio_id,
        Portfolio.user_id == user_id
    ).first()
    
    if not portfolio:
        raise HTTPException(status_code=404, detail="Portfolio not found")
    # Determine date range based on period
    end_date = datetime.now()
    
    if period == "1w":
        start_date = end_date - timedelta(days=7)
    elif period == "1m":
        start_date = end_date - timedelta(days=30)
    elif period == "3m":
        start_date = end_date - timedelta(days=90)
    elif period == "6m":
        start_date = end_date - timedelta(days=180)
    elif period == "1y":
        start_date = end_date - timedelta(days=365)
    else:  # "all"
        start_date = end_date - timedelta(days=365*5)  # 5 years
    
    # TODO: Replace mock data with actual portfolio performance calculation
    # Implementation plan:
    # 1. Query all transactions for this portfolio ordered by date
    # 2. Calculate daily portfolio value by applying transactions to holdings
    # 3. For each day in the period, calculate portfolio value based on:
    #    - Cash balance changes from deposits/withdrawals
    #    - Stock positions using historical prices from StockPrice table
    #    - Dividend income and realized gains/losses
    # 4. Calculate performance metrics (return, volatility, drawdown) from actual data
    # 
    # For now, generate mock performance data
    
    # Generate daily data points
    days = (end_date - start_date).days
    performance_data = []
    
    # Mock initial portfolio value
    initial_value = 1_000_000
    current_value = initial_value
    
    # Generate random-ish daily changes, but with a general uptrend
    import random
    random.seed(12345)  # For reproducible results
    
    for i in range(days + 1):
        current_date = start_date + timedelta(days=i)
        
        # Generate daily price change
        # More likely to go up than down (60/40)
        change_percent = random.uniform(-1.5, 2.0)
        daily_change = current_value * (change_percent / 100)
        current_value += daily_change
        
        # Ensure value doesn't go below 50% of initial
        current_value = max(current_value, initial_value * 0.5)
        
        performance_data.append({
            "date": current_date.strftime("%Y-%m-%d"),
            "portfolio_value": round(current_value, 2),
            "change_percent": round(((current_value - initial_value) / initial_value) * 100, 2)
        })
    
    # Calculate summary statistics
    period_start_value = performance_data[0]["portfolio_value"]
    period_end_value = performance_data[-1]["portfolio_value"]
    period_change = period_end_value - period_start_value
    period_change_percent = (period_change / period_start_value) * 100
    
    # Find max drawdown
    max_drawdown = 0
    peak = performance_data[0]["portfolio_value"]
    
    for point in performance_data:
        if point["portfolio_value"] > peak:
            peak = point["portfolio_value"]
        else:
            drawdown = (peak - point["portfolio_value"]) / peak * 100
            max_drawdown = max(max_drawdown, drawdown)
    
    return {
        "performance_data": performance_data,
        "summary": {
            "period": period,
            "start_date": start_date.strftime("%Y-%m-%d"),
            "end_date": end_date.strftime("%Y-%m-%d"),
            "start_value": round(period_start_value, 2),
            "end_value": round(period_end_value, 2),
            "change": round(period_change, 2),
            "change_percent": round(period_change_percent, 2),
            "annualized_return": round(period_change_percent * (365 / days), 2) if days > 0 else 0,
            "max_drawdown": round(max_drawdown, 2)
        }
    }

@router.get("/portfolios/{portfolio_id}/risk-analysis")
async def get_portfolio_risk_analysis(
    portfolio_id: str,
    db: Session = Depends(get_db),
    user = Depends(get_current_active_user)
):
    """
    Get risk analysis for a portfolio
    
    Parameters:
    - portfolio_id: Portfolio ID
    """
    user_id = user.get('id')
    
    # Verify portfolio exists and belongs to user
    portfolio = db.query(Portfolio).filter(
        Portfolio.id == portfolio_id,
        Portfolio.user_id == user_id
    ).first()
    
    if not portfolio:
        raise HTTPException(status_code=404, detail="Portfolio not found")
    # In a production app, we would calculate this from actual portfolio holdings
    # For now, return mock risk analysis data
    
    # Get active stocks for mock portfolio
    stocks = db.query(Stock).filter(Stock.is_active == True).limit(5).all()
    
    # Mock holdings
    holdings = []
    total_value = 0
    
    # Use these stocks for risk analysis
    for idx, stock in enumerate(stocks):
        # Get latest price
        latest_price = db.query(StockPrice).filter(
            StockPrice.stock_id == stock.id
        ).order_by(StockPrice.date.desc()).first()
        
        if not latest_price:
            continue
            
        # Create mock holding
        quantity = (idx + 1) * 100  # 100, 200, 300, etc.
        value = latest_price.close * quantity
        total_value += value
        
        holdings.append({
            "ticker": stock.ticker,
            "name": stock.name,
            "value": value
        })
    
    # Convert values to percentages
    for holding in holdings:
        holding["weight"] = (holding["value"] / total_value) * 100
    
    # Calculate basic risk metrics
    risk_analysis = {
        "volatility": {
            "portfolio_volatility": 12.5,  # Mock annual volatility
            "benchmark_volatility": 15.2,  # Mock S&P 500 volatility
            "relative_volatility": 0.82    # Lower than benchmark
        },
        "correlation": {
            "market_correlation": 0.85,  # High correlation with market
            "sector_correlations": [
                {"sector": "Technology", "correlation": 0.92},
                {"sector": "Finance", "correlation": 0.78},
                {"sector": "Healthcare", "correlation": 0.65},
                {"sector": "Consumer", "correlation": 0.72}
            ]
        },
        "diversification": {
            "sector_concentration": sum(h["weight"] for h in holdings if h["ticker"] in ["AAPL", "MSFT", "GOOGL"]),
            "largest_position": max(holdings, key=lambda x: x["weight"])["weight"],
            "top_5_concentration": sum(sorted([h["weight"] for h in holdings], reverse=True)[:5]),
            "recommendation": "Moderate diversification. Consider adding more healthcare stocks."
        },
        "risk_metrics": {
            "sharpe_ratio": 1.2,       # Return per unit risk
            "sortino_ratio": 1.5,      # Return per unit downside risk
            "beta": 0.9,               # Market sensitivity
            "alpha": 2.5,              # Excess return
            "max_drawdown": 18.5,      # Maximum historical loss
            "value_at_risk": 7.8       # 95% VAR
        },
        "stress_test": {
            "market_crash": -25.5,      # Portfolio loss in market crash scenario
            "interest_rate_hike": -8.2, # Portfolio loss in interest rate hike scenario
            "tech_bubble_burst": -32.1, # Portfolio loss in tech selloff scenario
            "recession": -18.7          # Portfolio loss in recession scenario
        }
    }
    
    return {
        "holdings": holdings,
        "risk_analysis": risk_analysis,
        "recommendations": [
            "Your portfolio has moderate diversification but is tech-heavy.",
            "Consider adding defensive stocks to reduce drawdowns.",
            "Your market correlation is high - may not provide protection in downturns.",
            "Portfolio volatility is below benchmark, suggesting good risk management."
        ]
    }
