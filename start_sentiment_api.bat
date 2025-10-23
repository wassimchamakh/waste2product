@echo off
echo ========================================
echo  Starting Sentiment Analysis Service
echo ========================================
echo.

cd python_ai_service

if not exist "venv\" (
    echo ERROR: Virtual environment not found!
    echo Please run setup_sentiment_api.bat first
    pause
    exit /b 1
)

echo Activating virtual environment...
call venv\Scripts\activate.bat

echo Starting API on http://127.0.0.1:5000
echo Press Ctrl+C to stop
echo.

python sentiment_api.py
