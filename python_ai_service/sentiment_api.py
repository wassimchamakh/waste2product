"""
Sentiment Analysis Microservice for Event Feedback
Uses VADER for sentiment scoring and TextBlob for additional analysis
"""

from flask import Flask, request, jsonify
from flask_cors import CORS
from vaderSentiment.vaderSentiment import SentimentIntensityAnalyzer
from textblob import TextBlob
import re
from collections import Counter

app = Flask(__name__)
CORS(app)  # Enable CORS for Laravel integration

# Initialize VADER sentiment analyzer
vader_analyzer = SentimentIntensityAnalyzer()

def clean_text(text):
    """Clean and preprocess text"""
    if not text:
        return ""
    # Remove extra whitespace
    text = re.sub(r'\s+', ' ', text)
    # Remove special characters but keep basic punctuation
    text = re.sub(r'[^\w\s\.\,\!\?\-]', '', text)
    return text.strip()

def analyze_sentiment(text):
    """
    Analyze sentiment using VADER and TextBlob
    Returns comprehensive sentiment analysis
    """
    if not text or len(text.strip()) < 3:
        return {
            'error': 'Text too short for analysis',
            'label': 'neutral',
            'score': 0.0,
            'confidence': 0.0
        }
    
    cleaned_text = clean_text(text)
    
    # VADER Analysis (better for social media-style text)
    vader_scores = vader_analyzer.polarity_scores(cleaned_text)
    
    # TextBlob Analysis (better for general text)
    blob = TextBlob(cleaned_text)
    textblob_polarity = blob.sentiment.polarity
    textblob_subjectivity = blob.sentiment.subjectivity
    
    # Combine scores (weighted average)
    compound_score = (vader_scores['compound'] * 0.6) + (textblob_polarity * 0.4)
    
    # Determine sentiment label
    if compound_score >= 0.05:
        label = 'positive'
    elif compound_score <= -0.05:
        label = 'negative'
    else:
        label = 'neutral'
    
    # Calculate confidence based on score magnitude
    confidence = min(abs(compound_score), 1.0)
    
    return {
        'label': label,
        'score': round(compound_score, 4),
        'confidence': round(confidence, 4),
        'vader': {
            'positive': round(vader_scores['pos'], 4),
            'negative': round(vader_scores['neg'], 4),
            'neutral': round(vader_scores['neu'], 4),
            'compound': round(vader_scores['compound'], 4)
        },
        'textblob': {
            'polarity': round(textblob_polarity, 4),
            'subjectivity': round(textblob_subjectivity, 4)
        },
        'text_length': len(cleaned_text),
        'word_count': len(cleaned_text.split())
    }

def extract_themes(text, max_themes=5):
    """Extract key themes/topics from text"""
    if not text or len(text.strip()) < 10:
        return []
    
    try:
        cleaned_text = clean_text(text.lower())
        blob = TextBlob(cleaned_text)
        
        # Extract noun phrases as themes
        noun_phrases = list(blob.noun_phrases)
        
        # If no noun phrases found, extract important nouns and adjectives
        if not noun_phrases:
            # Get tagged words
            tags = blob.tags
            # Extract nouns (NN, NNS, NNP, NNPS) and adjectives (JJ, JJR, JJS)
            important_words = [
                word.lower() for word, tag in tags 
                if tag.startswith('NN') or tag.startswith('JJ')
            ]
            
            # Filter out common/stop words and single letters
            stop_words = {
                'thing', 'time', 'way', 'lot', 'bit', 'kind', 'sort',
                'much', 'many', 'some', 'such', 'very', 'more', 'most', 'same',
                'i', 'you', 'he', 'she', 'it', 'we', 'they', 'me', 'him', 'her'
            }
            important_words = [w for w in important_words if w not in stop_words and len(w) > 3]
            
            # Count frequency
            theme_counts = Counter(important_words)
            top_themes = [theme for theme, count in theme_counts.most_common(max_themes)]
            
            # If still no themes and we have 'event', include it as fallback
            if not top_themes and 'event' in [word.lower() for word, tag in tags]:
                return ['event']
            
            return top_themes
        
        # Count frequency of noun phrases
        theme_counts = Counter(noun_phrases)
        
        # Get top themes
        top_themes = [theme for theme, count in theme_counts.most_common(max_themes)]
        
        return top_themes
    except Exception as e:
        # If theme extraction fails, return empty list
        print(f"Theme extraction error: {e}")
        return []

def analyze_batch(feedback_list):
    """Analyze multiple feedback entries"""
    results = []
    
    for item in feedback_list:
        feedback_id = item.get('id')
        text = item.get('text', '')
        
        analysis = analyze_sentiment(text)
        themes = extract_themes(text)
        
        results.append({
            'id': feedback_id,
            'sentiment': analysis,
            'themes': themes
        })
    
    # Calculate aggregate statistics
    sentiments = [r['sentiment']['label'] for r in results if 'error' not in r['sentiment']]
    scores = [r['sentiment']['score'] for r in results if 'error' not in r['sentiment']]
    
    aggregate = {
        'total_count': len(results),
        'positive_count': sentiments.count('positive'),
        'negative_count': sentiments.count('negative'),
        'neutral_count': sentiments.count('neutral'),
        'average_score': round(sum(scores) / len(scores), 4) if scores else 0.0,
        'positive_percentage': round((sentiments.count('positive') / len(sentiments) * 100), 2) if sentiments else 0,
        'negative_percentage': round((sentiments.count('negative') / len(sentiments) * 100), 2) if sentiments else 0,
        'neutral_percentage': round((sentiments.count('neutral') / len(sentiments) * 100), 2) if sentiments else 0
    }
    
    # Extract all themes
    all_themes = []
    for result in results:
        all_themes.extend(result.get('themes', []))
    
    theme_counts = Counter(all_themes)
    top_themes = [{'theme': theme, 'count': count} for theme, count in theme_counts.most_common(10)]
    
    return {
        'results': results,
        'aggregate': aggregate,
        'top_themes': top_themes
    }

@app.route('/health', methods=['GET'])
def health_check():
    """Health check endpoint"""
    return jsonify({
        'status': 'healthy',
        'service': 'Sentiment Analysis API',
        'version': '1.0.0'
    })

@app.route('/analyze', methods=['POST'])
def analyze():
    """
    Analyze single feedback text
    POST /analyze
    Body: { "text": "feedback text here" }
    """
    try:
        data = request.get_json()
        
        if not data or 'text' not in data:
            return jsonify({
                'error': 'Missing text field in request body'
            }), 400
        
        text = data['text']
        
        sentiment = analyze_sentiment(text)
        themes = extract_themes(text)
        
        return jsonify({
            'success': True,
            'sentiment': sentiment,
            'themes': themes
        })
    
    except Exception as e:
        return jsonify({
            'error': str(e),
            'success': False
        }), 500

@app.route('/analyze-batch', methods=['POST'])
def analyze_batch_endpoint():
    """
    Analyze multiple feedback entries
    POST /analyze-batch
    Body: { 
        "feedback": [
            {"id": 1, "text": "Great event!"},
            {"id": 2, "text": "Not satisfied..."}
        ]
    }
    """
    try:
        data = request.get_json()
        
        if not data or 'feedback' not in data:
            return jsonify({
                'error': 'Missing feedback array in request body'
            }), 400
        
        feedback_list = data['feedback']
        
        if not isinstance(feedback_list, list):
            return jsonify({
                'error': 'Feedback must be an array'
            }), 400
        
        results = analyze_batch(feedback_list)
        
        return jsonify({
            'success': True,
            'data': results
        })
    
    except Exception as e:
        return jsonify({
            'error': str(e),
            'success': False
        }), 500

@app.route('/themes', methods=['POST'])
def extract_themes_endpoint():
    """
    Extract themes from text
    POST /themes
    Body: { "text": "feedback text here", "max_themes": 5 }
    """
    try:
        data = request.get_json()
        
        if not data or 'text' not in data:
            return jsonify({
                'error': 'Missing text field in request body'
            }), 400
        
        text = data['text']
        max_themes = data.get('max_themes', 5)
        
        themes = extract_themes(text, max_themes)
        
        return jsonify({
            'success': True,
            'themes': themes
        })
    
    except Exception as e:
        return jsonify({
            'error': str(e),
            'success': False
        }), 500

if __name__ == '__main__':
    # Run on port 5000
    print("🚀 Starting Sentiment Analysis API on http://127.0.0.1:5000")
    print("📊 Available endpoints:")
    print("   - GET  /health           - Health check")
    print("   - POST /analyze          - Analyze single text")
    print("   - POST /analyze-batch    - Analyze multiple texts")
    print("   - POST /themes           - Extract themes")
    app.run(host='127.0.0.1', port=5000, debug=True)
