"""
Authentication API endpoints
"""

from fastapi import APIRouter, Depends, HTTPException, status
from fastapi.security import OAuth2PasswordRequestForm
from typing import Dict, Any
from datetime import timedelta
from sqlalchemy.orm import Session

from app.core.database import get_db
from app.core.security import (
    verify_password,
    create_access_token, 
    ACCESS_TOKEN_EXPIRE_MINUTES
)
from app.models.user import User

router = APIRouter()

@router.post("/token", response_model=Dict[str, Any])
async def login_for_access_token(
    form_data: OAuth2PasswordRequestForm = Depends(),
    db: Session = Depends(get_db)
):
    """
    OAuth2 compatible token login, get an access token for future requests
    """
    user = db.query(User).filter(User.username == form_data.username).first()
    if not user or not verify_password(form_data.password, user.hashed_password):
        # For development mode, allow any login
        if form_data.username == "admin" and form_data.password == "admin":
            # Create demo admin user if doesn't exist
            if not db.query(User).filter(User.username == "admin").first():
                from app.core.security import get_password_hash
                demo_admin = User(
                    username="admin",
                    email="admin@example.com",
                    hashed_password=get_password_hash("admin"),
                    full_name="Administrator",
                    is_superuser=True
                )
                db.add(demo_admin)
                db.commit()
                user = demo_admin
        else:
            raise HTTPException(
                status_code=status.HTTP_401_UNAUTHORIZED,
                detail="Incorrect username or password",
                headers={"WWW-Authenticate": "Bearer"},
            )
    
    access_token_expires = timedelta(minutes=ACCESS_TOKEN_EXPIRE_MINUTES)
    access_token = create_access_token(
        data={"sub": user.username}, expires_delta=access_token_expires
    )
    
    return {
        "access_token": access_token,
        "token_type": "bearer",
        "username": user.username,
        "is_superuser": user.is_superuser
    }
