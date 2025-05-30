"""
Notification API endpoints for ZAHAAM Stock Prediction platform
"""
from fastapi import APIRouter, Depends, HTTPException, Query
from sqlalchemy.orm import Session
from typing import List, Optional, Dict, Any
from datetime import datetime, timedelta
import uuid

from app.core.database import get_db
from app.models.big_data import (
    Notification, NotificationSettings, 
    NotificationType, NotificationPriority
)
from app.models.stocks import Stock, StockPrice

router = APIRouter(prefix="/api/notifications", tags=["notifications"])

@router.get("/")
async def get_notifications(
    user_id: Optional[str] = None,
    ticker: Optional[str] = None,
    type: Optional[str] = None,
    is_read: Optional[bool] = None,
    limit: int = 50,
    db: Session = Depends(get_db)
):
    """Get notifications for a user, filtered by various criteria"""
    # Build query with filters
    query = db.query(Notification)
    
    if user_id:
        # Include user-specific and global notifications
        query = query.filter(
            (Notification.user_id == user_id) | (Notification.user_id.is_(None))
        )
    
    if ticker:
        query = query.filter(Notification.ticker == ticker)
    
    if type:
        try:
            notification_type = NotificationType(type)
            query = query.filter(Notification.type == notification_type)
        except ValueError:
            pass  # Invalid type, ignore filter
    
    if is_read is not None:
        query = query.filter(Notification.is_read == is_read)
    
    # Filter out expired notifications
    query = query.filter(
        (Notification.expires_at.is_(None)) | (Notification.expires_at > datetime.now())
    )
    
    # Order by created_at (newest first) and limit results
    notifications = query.order_by(Notification.created_at.desc()).limit(limit).all()
    
    # Format response
    result = []
    for notification in notifications:
        result.append({
            "id": notification.id,
            "user_id": notification.user_id,
            "ticker": notification.ticker,
            "type": notification.type.value,
            "priority": notification.priority.value,
            "title": notification.title,
            "message": notification.message,
            "data": notification.data,
            "is_read": notification.is_read,
            "created_at": notification.created_at.isoformat(),
            "expires_at": notification.expires_at.isoformat() if notification.expires_at else None
        })
    
    return {
        "notifications": result,
        "count": len(result),
        "unread_count": len([n for n in result if not n["is_read"]])
    }

@router.post("/mark-read/{notification_id}")
async def mark_notification_read(
    notification_id: str,
    db: Session = Depends(get_db)
):
    """Mark a notification as read"""
    notification = db.query(Notification).filter(Notification.id == notification_id).first()
    
    if not notification:
        raise HTTPException(status_code=404, detail="Notification not found")
    
    notification.is_read = True
    db.commit()
    
    return {"success": True}

@router.post("/mark-all-read")
async def mark_all_notifications_read(
    user_id: str,
    db: Session = Depends(get_db)
):
    """Mark all notifications for a user as read"""
    db.query(Notification).filter(
        Notification.user_id == user_id,
        Notification.is_read == False
    ).update({"is_read": True})
    
    db.commit()
    
    return {"success": True}

@router.get("/settings/{user_id}")
async def get_notification_settings(
    user_id: str,
    db: Session = Depends(get_db)
):
    """Get notification settings for a user"""
    settings = db.query(NotificationSettings).filter(
        NotificationSettings.user_id == user_id
    ).first()
    
    if not settings:
        # Create default settings if none exist
        settings = NotificationSettings(
            id=str(uuid.uuid4()),
            user_id=user_id,
            enable_signal_change=True,
            enable_price_alerts=True,
            enable_news=True,
            enable_prediction_updates=True,
            enable_system=True,
            min_priority=NotificationPriority.LOW,
            watched_tickers=[],
            email_notifications=False
        )
        db.add(settings)
        db.commit()
        db.refresh(settings)
    
    return {
        "user_id": settings.user_id,
        "enable_signal_change": settings.enable_signal_change,
        "enable_price_alerts": settings.enable_price_alerts,
        "enable_news": settings.enable_news,
        "enable_prediction_updates": settings.enable_prediction_updates,
        "enable_system": settings.enable_system,
        "min_priority": settings.min_priority.value,
        "watched_tickers": settings.watched_tickers or [],
        "email_notifications": settings.email_notifications,
        "updated_at": settings.updated_at.isoformat()
    }

@router.post("/settings/{user_id}")
async def update_notification_settings(
    user_id: str,
    settings: dict,
    db: Session = Depends(get_db)
):
    """Update notification settings for a user"""
    user_settings = db.query(NotificationSettings).filter(
        NotificationSettings.user_id == user_id
    ).first()
    
    if not user_settings:
        # Create new settings
        user_settings = NotificationSettings(
            id=str(uuid.uuid4()),
            user_id=user_id
        )
        db.add(user_settings)
    
    # Update fields from request
    if "enable_signal_change" in settings:
        user_settings.enable_signal_change = settings["enable_signal_change"]
    
    if "enable_price_alerts" in settings:
        user_settings.enable_price_alerts = settings["enable_price_alerts"]
    
    if "enable_news" in settings:
        user_settings.enable_news = settings["enable_news"]
    
    if "enable_prediction_updates" in settings:
        user_settings.enable_prediction_updates = settings["enable_prediction_updates"]
    
    if "enable_system" in settings:
        user_settings.enable_system = settings["enable_system"]
    
    if "min_priority" in settings:
        try:
            user_settings.min_priority = NotificationPriority(settings["min_priority"])
        except ValueError:
            pass  # Invalid priority, ignore
    
    if "watched_tickers" in settings:
        user_settings.watched_tickers = settings["watched_tickers"]
    
    if "email_notifications" in settings:
        user_settings.email_notifications = settings["email_notifications"]
    
    db.commit()
    db.refresh(user_settings)
    
    return {
        "user_id": user_settings.user_id,
        "enable_signal_change": user_settings.enable_signal_change,
        "enable_price_alerts": user_settings.enable_price_alerts,
        "enable_news": user_settings.enable_news,
        "enable_prediction_updates": user_settings.enable_prediction_updates,
        "enable_system": user_settings.enable_system,
        "min_priority": user_settings.min_priority.value,
        "watched_tickers": user_settings.watched_tickers or [],
        "email_notifications": user_settings.email_notifications,
        "updated_at": user_settings.updated_at.isoformat()
    }

@router.post("/create")
async def create_notification(
    notification: dict,
    db: Session = Depends(get_db)
):
    """Create a new notification"""
    # Validate required fields
    required_fields = ["title", "message", "type"]
    for field in required_fields:
        if field not in notification:
            raise HTTPException(status_code=400, detail=f"Missing required field: {field}")
    
    try:
        notification_type = NotificationType(notification["type"])
    except ValueError:
        raise HTTPException(status_code=400, detail=f"Invalid notification type: {notification['type']}")
    
    # Set default priority if not provided
    priority = NotificationPriority.MEDIUM
    if "priority" in notification:
        try:
            priority = NotificationPriority(notification["priority"])
        except ValueError:
            pass  # Invalid priority, use default
    
    # Create new notification
    new_notification = Notification(
        id=str(uuid.uuid4()),
        user_id=notification.get("user_id"),
        ticker=notification.get("ticker"),
        type=notification_type,
        priority=priority,
        title=notification["title"],
        message=notification["message"],
        data=notification.get("data"),
        is_read=False,
        created_at=datetime.now(),
        expires_at=notification.get("expires_at")
    )
    
    db.add(new_notification)
    db.commit()
    db.refresh(new_notification)
    
    return {
        "id": new_notification.id,
        "user_id": new_notification.user_id,
        "ticker": new_notification.ticker,
        "type": new_notification.type.value,
        "priority": new_notification.priority.value,
        "title": new_notification.title,
        "message": new_notification.message,
        "created_at": new_notification.created_at.isoformat()
    }

@router.delete("/{notification_id}")
async def delete_notification(
    notification_id: str,
    db: Session = Depends(get_db)
):
    """Delete a notification"""
    notification = db.query(Notification).filter(Notification.id == notification_id).first()
    
    if not notification:
        raise HTTPException(status_code=404, detail="Notification not found")
    
    db.delete(notification)
    db.commit()
    
    return {"success": True, "id": notification_id}

@router.post("/generate-signal-notification")
async def generate_signal_notification(
    ticker: str,
    db: Session = Depends(get_db)
):
    """Generate a notification for a signal change"""
    from app.core.ml_engine import get_ml_engine
    
    ml_engine = get_ml_engine(db)
    
    # Get signal metrics
    signal_metrics = ml_engine.calculate_signal_strength(ticker)
    
    # Get stock info
    stock = db.query(Stock).filter(Stock.ticker == ticker).first()
    stock_name = stock.name if stock else ticker
    
    # Create notification title and message based on signal
    signal = signal_metrics.get("overall_signal", "HOLD")
    short_term = signal_metrics.get("short_term_bias", "NEUTRAL")
    long_term = signal_metrics.get("long_term_bias", "NEUTRAL")
    
    title = f"{ticker} Signal: {signal}"
    message = f"{stock_name} ({ticker}) has a new {signal} signal with {signal_metrics.get('consensus_score', 0)}/10 consensus score. "
    message += f"Short-term trend is {short_term}, long-term is {long_term}."
    
    # Determine priority based on signal strength and consistency
    priority = NotificationPriority.MEDIUM
    if signal != "HOLD" and short_term == long_term and abs(signal_metrics.get("overall_strength", 0)) > 0.7:
        priority = NotificationPriority.HIGH
    
    # Create notification
    notification = Notification(
        id=str(uuid.uuid4()),
        user_id=None,  # Global notification
        ticker=ticker,
        type=NotificationType.SIGNAL_CHANGE,
        priority=priority,
        title=title,
        message=message,
        data=signal_metrics,
        is_read=False,
        created_at=datetime.now(),
        expires_at=datetime.now() + timedelta(days=1)  # Expire after 1 day
    )
    
    db.add(notification)
    db.commit()
    db.refresh(notification)
    
    return {
        "id": notification.id,
        "ticker": notification.ticker,
        "type": notification.type.value,
        "priority": notification.priority.value,
        "title": notification.title,
        "message": notification.message,
        "created_at": notification.created_at.isoformat()
    }
