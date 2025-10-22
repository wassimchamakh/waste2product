# 🚀 Sentiment Analysis AI - Installation Guide

## 📋 Prerequisites

- ✅ Python 3.9 or higher installed
- ✅ Laravel project running
- ✅ Git (optional)

> **💡 Note for PowerShell Users:**  
> If using PowerShell, add `.\` before .bat files: `.\setup_sentiment_api.bat`  
> Or use Command Prompt (CMD) for simpler commands.

---

## ⚡ Quick Installation (3 Steps)

### Step 1: Setup Python Environment
Open terminal in your project root and run:

**Command Prompt (CMD):**
```cmd
setup_sentiment_api.bat
```

**PowerShell:**
```powershell
.\setup_sentiment_api.bat
```

This will:
- Create a Python virtual environment
- Install all required packages (Flask, VADER, TextBlob, etc.)
- Download NLTK language corpora
- Download missing NLTK resources

**Time:** ~2-3 minutes

---

### Step 2: Run Database Migration

```cmd
php artisan migrate
```

This adds sentiment analysis columns to the `participants` table:
- `sentiment_label` (positive/negative/neutral)
- `sentiment_score` (decimal -1.0 to 1.0)
- `sentiment_confidence` (decimal 0 to 1.0)
- `sentiment_details` (JSON)
- `feedback_themes` (JSON array)
- `sentiment_analyzed_at` (timestamp)

---

### Step 3: Start the AI Service

**Command Prompt (CMD):**
```cmd
start_sentiment_api.bat
```

**PowerShell:**
```powershell
.\start_sentiment_api.bat
```

**Keep this terminal window open!** The API runs on http://127.0.0.1:5000

✅ You should see:
```
🚀 Starting Sentiment Analysis API on http://127.0.0.1:5000
📊 Available endpoints:
   - GET  /health           - Health check
   - POST /analyze          - Analyze single text
   - POST /analyze-batch    - Analyze multiple texts
   - POST /themes           - Extract themes
 * Running on http://127.0.0.1:5000
```

---

## 🧪 Test Installation

### Test 1: Check API Health
Open browser: http://127.0.0.1:5000/health

Should show:
```json
{"status": "healthy", "service": "Sentiment Analysis API", "version": "1.0.0"}
```

### Test 2: Run Automated Test
Open a **new terminal**:

```cmd
cd python_ai_service
venv\Scripts\activate
python test_api.py
```

Should show all ✅ passed!

---

## 🌐 Test in Laravel

1. Start Laravel (in a new terminal):
```cmd
php artisan serve
```

2. Open browser: http://127.0.0.1:8000

3. Login to your account

4. Navigate to an event you organize

5. Click **"Analyser les Sentiments"** button

6. See the AI dashboard! 🎨

---

## 🐛 Troubleshooting

### ❌ "Connection refused"
**Fix:** Make sure API is running

**Command Prompt (CMD):**
```cmd
start_sentiment_api.bat
```

**PowerShell:**
```powershell
.\start_sentiment_api.bat
```

### ❌ "Module not found"
**Fix:** Install dependencies
```cmd
cd python_ai_service
venv\Scripts\activate
pip install -r requirements.txt
```

### ❌ Missing NLTK resources
**Fix:** Re-run the setup script

**Command Prompt (CMD):**
```cmd
setup_sentiment_api.bat
```

**PowerShell:**
```powershell
.\setup_sentiment_api.bat
```

This will download all missing NLTK resources including punkt_tab and averaged_perceptron_tagger_eng.

### ❌ "Port 5000 already in use"
**Fix:** Change port in `sentiment_api.py` line 297:
```python
app.run(host='0.0.0.0', port=5001, debug=True)  # Changed to 5001
```

---

## 📁 Project Structure

```
waste2product/
├── setup_sentiment_api.bat          # One-click setup (includes NLTK fix)
├── start_sentiment_api.bat          # Start API service
├── python_ai_service/
│   ├── sentiment_api.py             # Main API (270 lines)
│   ├── requirements.txt             # Python dependencies
│   ├── test_api.py                  # Automated tests
│   └── venv/                        # Virtual environment
├── app/
│   ├── Services/
│   │   └── SentimentAnalysisService.php  # Laravel service
│   ├── Http/Controllers/Frontoffice/
│   │   └── EventController.php      # Updated with 2 methods
│   └── Models/
│       └── Participant.php          # Updated model
├── database/migrations/
│   └── 2025_10_20_000001_add_sentiment_analysis_to_participants.php
└── resources/views/FrontOffice/Events/partials/
    └── sentiment-modal.blade.php    # Dashboard UI

```

---

## 🔧 Configuration Files

### config/services.php
Add sentiment analysis service configuration:

```php
'sentiment_analysis' => [
    'api_url' => env('SENTIMENT_API_URL', 'http://127.0.0.1:5000'),
    'timeout' => env('SENTIMENT_API_TIMEOUT', 30),
    'cache_ttl' => env('SENTIMENT_CACHE_TTL', 3600), // 1 hour
],
```

### .env (Optional)
```env
SENTIMENT_API_URL=http://127.0.0.1:5000
SENTIMENT_API_TIMEOUT=30
SENTIMENT_CACHE_TTL=3600
```

---

## 📦 Dependencies

### Python Packages (requirements.txt)
```
Flask==3.0.0
flask-cors==4.0.0
vaderSentiment==3.3.2
textblob==0.17.1
gunicorn==21.2.0
python-dotenv==1.0.0
```

### Laravel Packages
- Guzzle HTTP Client (included in Laravel)
- Laravel Cache (included in Laravel)

---

## ✅ Installation Complete!

Your sentiment analysis system is ready to use!

### Next Steps:
1. Keep `start_sentiment_api.bat` running
2. Start Laravel: `php artisan serve`
3. Test in browser!

**For usage instructions, see:** `SENTIMENT_AI_GUIDE.md`

---

**Created:** October 20, 2025  
**Status:** Production Ready  
**Version:** 1.0.0
