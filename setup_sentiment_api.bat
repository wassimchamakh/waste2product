@echo off
echo ========================================
echo  Sentiment Analysis Service Setup
echo ========================================
echo.

cd python_ai_service

echo [1/6] Creating virtual environment...
python -m venv venv
if %errorlevel% neq 0 (
    echo ERROR: Failed to create virtual environment
    pause
    exit /b 1
)

echo [2/6] Activating virtual environment...
call venv\Scripts\activate.bat

echo [3/6] Installing dependencies...
pip install -r requirements.txt
if %errorlevel% neq 0 (
    echo ERROR: Failed to install dependencies
    pause
    exit /b 1
)

echo [4/6] Downloading TextBlob corpora...
python -m textblob.download_corpora

echo [5/6] Downloading additional NLTK resources...
python -c "import nltk; nltk.download('punkt_tab')"
python -c "import nltk; nltk.download('averaged_perceptron_tagger_eng')"

echo [6/6] Verifying installation...
python -c "from textblob import TextBlob; from vaderSentiment.vaderSentiment import SentimentIntensityAnalyzer; print('✓ All packages installed successfully!')"

echo.
echo ========================================
echo  Setup Complete!
echo ========================================
echo.
echo To start the service, run:
echo   start_sentiment_api.bat
echo.
echo Or manually:
echo   cd python_ai_service
echo   venv\Scripts\activate
echo   python sentiment_api.py
echo.
pause
