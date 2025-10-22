# 🤖 Sentiment Analysis AI - Complete System

> AI-powered sentiment analysis for event feedback using VADER + TextBlob

[![Status](https://img.shields.io/badge/status-production%20ready-brightgreen)]()
[![Python](https://img.shields.io/badge/python-3.9+-blue)]()
[![Laravel](https://img.shields.io/badge/laravel-11-red)]()
[![AI](https://img.shields.io/badge/AI-VADER%20%2B%20TextBlob-orange)]()

---

## 📖 Quick Navigation

| Document | Purpose | Size | Read Time |
|----------|---------|------|-----------|
| **[INSTALLATION.md](INSTALLATION.md)** | Setup guide (3 steps) | 4.8 KB | 5 min |
| **[SENTIMENT_AI_GUIDE.md](SENTIMENT_AI_GUIDE.md)** | Complete reference | 23.8 KB | 20 min |
| [FINAL_SUMMARY.md](FINAL_SUMMARY.md) | Visual summary | 10.8 KB | 10 min |
| [CLEANUP_SUMMARY.md](CLEANUP_SUMMARY.md) | What was cleaned | 4.7 KB | 5 min |

---

## ⚡ Quick Start (30 seconds)

**Command Prompt (CMD):**
```cmd
# 1. Setup (one time, ~2 minutes)
setup_sentiment_api.bat

# 2. Migrate database
php artisan migrate

# 3. Start API service
start_sentiment_api.bat

# 4. Start Laravel (new terminal)
php artisan serve

# 5. Login and click "Analyser les Sentiments" on any event!
```

**PowerShell:**
```powershell
# 1. Setup (one time, ~2 minutes)
.\setup_sentiment_api.bat

# 2. Migrate database
php artisan migrate

# 3. Start API service
.\start_sentiment_api.bat

# 4. Start Laravel (new terminal)
php artisan serve

# 5. Login and click "Analyser les Sentiments" on any event!
```

✅ **Done!** The AI is now analyzing sentiment in real-time.

---

## 🎯 What This System Does

### For Event Organizers:
- 📊 **Automatic sentiment analysis** of participant feedback
- 😊 😐 😞 **Visual dashboard** with positive/negative/neutral breakdown
- 🏷️ **Key themes extraction** (automatically finds topics like "venue", "materials", "instructor")
- ⚠️ **Concerning feedback alerts** (highlights negative feedback for immediate action)
- 📈 **Charts & statistics** (pie charts, score gauges, percentages)

### Example:
**Input:** "This workshop was fantastic! I learned so much."  
**AI Output:**
- Sentiment: **Positive** 😊
- Score: **0.68** (68% positive)
- Confidence: **68%**
- Themes: `workshop`, `fantastic`, `learned`

---

## 🏗️ System Architecture

```
┌─────────────────┐
│  Laravel App    │
│  (Frontend)     │
└────────┬────────┘
         │ HTTP REST API
         │
         ▼
┌─────────────────┐      ┌──────────────┐
│  Python Flask   │      │  AI Models   │
│  API Service    │─────▶│  • VADER     │
│  Port 5000      │      │  • TextBlob  │
└────────┬────────┘      └──────────────┘
         │
         ▼
┌─────────────────┐
│  MySQL Database │
│  (participants) │
└─────────────────┘
```

---

## 📁 Project Structure

```
waste2product/
│
├── 📚 DOCUMENTATION
│   ├── INSTALLATION.md           ← Start here!
│   ├── SENTIMENT_AI_GUIDE.md     ← Complete guide
│   ├── FINAL_SUMMARY.md          ← Visual summary
│   └── CLEANUP_SUMMARY.md        ← Cleanup details
│
├── 🚀 SCRIPTS
│   ├── setup_sentiment_api.bat   ← One-click setup (includes NLTK)
│   └── start_sentiment_api.bat   ← Start API
│
├── 🐍 python_ai_service/
│   ├── sentiment_api.py          ← Main API (270 lines)
│   ├── test_api.py               ← Automated tests
│   ├── requirements.txt          ← Python dependencies
│   ├── README.md                 ← API quick reference
│   └── venv/                     ← Virtual environment
│
├── 💻 app/
│   ├── Services/
│   │   └── SentimentAnalysisService.php
│   ├── Http/Controllers/Frontoffice/
│   │   └── EventController.php   ← +2 new methods
│   └── Models/
│       └── Participant.php       ← Updated with sentiment fields
│
├── 🗄️ database/migrations/
│   └── 2025_10_20_000001_add_sentiment_analysis_to_participants.php
│
└── 🎨 resources/views/FrontOffice/Events/partials/
    └── sentiment-modal.blade.php ← Dashboard UI
```

---

## 🔌 API Endpoints

**Base URL:** `http://127.0.0.1:5000`

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/health` | Check API status |
| `POST` | `/analyze` | Analyze single text |
| `POST` | `/analyze-batch` | Analyze multiple texts |
| `POST` | `/themes` | Extract themes only |

**Example Request:**
```bash
curl -X POST http://127.0.0.1:5000/analyze \
  -H "Content-Type: application/json" \
  -d '{"text": "Great event!"}'
```

**Example Response:**
```json
{
  "success": true,
  "sentiment": {
    "label": "positive",
    "score": 0.7845,
    "confidence": 0.7845
  },
  "themes": ["great event"]
}
```

---

## 💻 Usage in Laravel

### Analyze Event Feedback
```php
use App\Services\SentimentAnalysisService;

$service = new SentimentAnalysisService();

// Single text
$result = $service->analyze("Great workshop!");

// Batch analysis
$feedbacks = [
    ['id' => 1, 'text' => 'Amazing!'],
    ['id' => 2, 'text' => 'Terrible.']
];
$results = $service->analyzeBatch($feedbacks);

// Check if API is available
if ($service->isAvailable()) {
    // API is running
}
```

### Controller Routes
```php
// Trigger analysis
POST /events/{id}/analyze-sentiment

// Get results
GET /events/{id}/sentiment-results
```

---

## 📊 Dashboard Features

When you click **"Analyser les Sentiments"** on an event:

### Summary Cards
- 📊 Total feedback count
- 😊 Positive count & percentage (green)
- 😞 Negative count & percentage (red)
- 😐 Neutral count & percentage (gray)

### Visualizations
- 🥧 **Pie Chart** - Sentiment distribution
- 📏 **Score Gauge** - Average sentiment score with color coding

### Insights
- 🏷️ **Top Themes** - Most mentioned topics across all feedback
- ⚠️ **Concerning Feedback** - All negative feedback highlighted
- 📝 **Complete List** - All feedback with sentiment tags

---

## 🧠 How It Works

### 1. Text Processing
- Clean and normalize input text
- Remove special characters, extra whitespace

### 2. Dual AI Analysis
- **VADER** (60% weight) - Optimized for social media-style text
- **TextBlob** (40% weight) - General-purpose sentiment analysis

### 3. Score Calculation
- Combine VADER + TextBlob scores (weighted average)
- Range: **-1.0** (very negative) to **+1.0** (very positive)

### 4. Label Assignment
- Score ≥ 0.05 → **Positive** 😊
- Score ≤ -0.05 → **Negative** 😞
- Otherwise → **Neutral** 😐

### 5. Theme Extraction
- Extract noun phrases (e.g., "great workshop")
- Fallback to important nouns and adjectives
- Filter common stop words

### 6. Results
- Store in database with timestamp
- Cache for 1 hour
- Display in beautiful dashboard

---

## 🎓 Score Interpretation

```
-1.0                    0.0                    +1.0
 │─────────────────────│─────────────────────│
 │                     │                     │
VERY NEGATIVE      NEUTRAL           VERY POSITIVE
   😡                  😐                    😊
```

**Examples:**
- **-0.92**: "Absolutely terrible and horrible!"
- **-0.65**: "Very disappointed with the service."
- **0.00**: "It was fine."
- **0.68**: "Great experience overall!"
- **0.92**: "Absolutely amazing and fantastic!"

---

## 🔧 Configuration

### .env (Optional)
```env
SENTIMENT_API_URL=http://127.0.0.1:5000
SENTIMENT_API_TIMEOUT=30
SENTIMENT_CACHE_TTL=3600
```

### config/services.php
```php
'sentiment_analysis' => [
    'api_url' => env('SENTIMENT_API_URL', 'http://127.0.0.1:5000'),
    'timeout' => env('SENTIMENT_API_TIMEOUT', 30),
    'cache_ttl' => env('SENTIMENT_CACHE_TTL', 3600),
],
```

---

## 🧪 Testing

### Automated Tests
```bash
cd python_ai_service
venv\Scripts\activate
python test_api.py
```

**Expected Output:**
```
✅ Health check passed!
✅ Sentiment analysis: positive (0.8765)
✅ Sentiment analysis: negative (-0.6249)
✅ Batch analysis complete!
✅ All tests completed!
```

### Manual Testing
1. Open browser: `http://127.0.0.1:5000/health`
2. Should see: `{"status": "healthy"}`

---

## 🐛 Troubleshooting

| Problem | Solution |
|---------|----------|
| ❌ Connection refused | Run `start_sentiment_api.bat` |
| ❌ Module not found | Run `setup_sentiment_api.bat` |
| ❌ NLTK resources missing | Re-run `setup_sentiment_api.bat` |
| ❌ Port 5000 in use | Change port in `sentiment_api.py` |
| ❌ No themes extracted | Restart API to load new code |

See **[INSTALLATION.md](INSTALLATION.md)** for detailed troubleshooting.

---

## 📦 Dependencies

### Python
- Flask 3.0.0
- vaderSentiment 3.3.2
- textblob 0.17.1
- flask-cors 4.0.0

### Laravel
- Guzzle HTTP Client (included)
- Laravel Cache (included)

### Frontend
- Chart.js 4.4.0 (for dashboard)
- TailwindCSS (for styling)

---

## 🚀 Performance

- **Analysis Speed:** ~100ms per text
- **Batch Processing:** ~50ms per text (when batched)
- **Caching:** 1-hour TTL (configurable)
- **Concurrent Requests:** Supports multiple simultaneous analyses

---

## 📈 Extending the System

### Add New Sentiment Categories
Modify `sentiment_api.py` to add "very positive" or "very negative":
```python
if compound_score >= 0.5:
    label = 'very_positive'
elif compound_score >= 0.05:
    label = 'positive'
# ... etc
```

### Multi-Language Support
Use TextBlob's translation:
```python
blob = TextBlob(text)
if blob.detect_language() != 'en':
    blob = blob.translate(to='en')
```

### Custom Keywords
Add domain-specific themes:
```python
custom_keywords = ['recycling', 'waste', 'sustainable']
```

---

## 📚 Learn More

| Resource | Link |
|----------|------|
| VADER Paper | [AAAI ICWSM](https://ojs.aaai.org/index.php/ICWSM/article/view/14550) |
| TextBlob Docs | [textblob.readthedocs.io](https://textblob.readthedocs.io/) |
| Flask Docs | [flask.palletsprojects.com](https://flask.palletsprojects.com/) |
| Laravel HTTP | [laravel.com/docs/http-client](https://laravel.com/docs/http-client) |

---

## ✅ Checklist

Before going to production:

- [ ] Run `setup_sentiment_api.bat` (one time)
- [ ] Run `php artisan migrate`
- [ ] Test with `python test_api.py`
- [ ] Configure `.env` if needed
- [ ] Add Chart.js CDN to layout
- [ ] Include sentiment modal in event view
- [ ] Test with real event feedback
- [ ] Monitor API logs for errors
- [ ] Set up process manager (PM2/Supervisor) for production

---

## 🎉 Summary

**What You Have:**
- ✅ Production-ready AI sentiment analysis
- ✅ Beautiful dashboard with charts
- ✅ Automatic theme extraction
- ✅ Comprehensive documentation
- ✅ Automated testing
- ✅ Clean code structure
- ✅ Easy to maintain and extend

**Next Steps:**
1. Read **INSTALLATION.md** (5 minutes)
2. Run setup scripts (3 minutes)
3. Test the system (2 minutes)
4. Start using it! 🚀

---

## 📞 Support

- 📖 Read: **SENTIMENT_AI_GUIDE.md** for detailed documentation
- 🔧 Check: **INSTALLATION.md** for setup issues
- 📊 Review: **FINAL_SUMMARY.md** for visual overview

---

## 📄 License

MIT License - Feel free to use and modify for your project.

---

## 👏 Credits

Built with:
- **VADER** by C.J. Hutto & Eric Gilbert
- **TextBlob** by Steven Loria
- **Flask** by Armin Ronacher
- **Laravel** by Taylor Otwell

---

**Created:** October 20, 2025  
**Version:** 1.0.0  
**Status:** ✅ Production Ready  
**Maintenance:** Easy

---

<div align="center">

### Ready to analyze sentiment? 🚀

```bash
start_sentiment_api.bat
php artisan serve
```

**Happy Analyzing!** 🤖✨

</div>
