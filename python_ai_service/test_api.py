# Quick Test Script for Sentiment API
import requests
import json

BASE_URL = "http://127.0.0.1:5000"

def test_health():
    print("🔍 Testing Health Endpoint...")
    try:
        response = requests.get(f"{BASE_URL}/health")
        if response.status_code == 200:
            print("✅ Health check passed!")
            print(f"   Response: {response.json()}")
            return True
        else:
            print(f"❌ Health check failed with status {response.status_code}")
            return False
    except Exception as e:
        print(f"❌ Error: {e}")
        return False

def test_single_analysis():
    print("\n🔍 Testing Single Text Analysis...")
    test_texts = [
        "This event was amazing! I learned so much and met wonderful people.",
        "Not satisfied with the organization, it was poorly managed.",
        "The event was okay, nothing special."
    ]
    
    for text in test_texts:
        try:
            response = requests.post(
                f"{BASE_URL}/analyze",
                json={"text": text},
                headers={"Content-Type": "application/json"}
            )
            
            if response.status_code == 200:
                result = response.json()
                sentiment = result['sentiment']
                print(f"\n   Text: {text[:50]}...")
                print(f"   ✅ Sentiment: {sentiment['label']}")
                print(f"      Score: {sentiment['score']}")
                print(f"      Confidence: {sentiment['confidence']}")
                print(f"      Themes: {result.get('themes', [])}")
            else:
                print(f"   ❌ Failed with status {response.status_code}")
        except Exception as e:
            print(f"   ❌ Error: {e}")

def test_batch_analysis():
    print("\n🔍 Testing Batch Analysis...")
    feedback_list = [
        {"id": 1, "text": "Great event! Highly recommended."},
        {"id": 2, "text": "Disappointing experience, would not attend again."},
        {"id": 3, "text": "It was fine, met my expectations."}
    ]
    
    try:
        response = requests.post(
            f"{BASE_URL}/analyze-batch",
            json={"feedback": feedback_list},
            headers={"Content-Type": "application/json"}
        )
        
        if response.status_code == 200:
            result = response.json()
            data = result['data']
            aggregate = data['aggregate']
            
            print(f"   ✅ Batch analysis complete!")
            print(f"      Total: {aggregate['total_count']}")
            print(f"      Positive: {aggregate['positive_count']} ({aggregate['positive_percentage']}%)")
            print(f"      Negative: {aggregate['negative_count']} ({aggregate['negative_percentage']}%)")
            print(f"      Neutral: {aggregate['neutral_count']} ({aggregate['neutral_percentage']}%)")
            print(f"      Avg Score: {aggregate['average_score']}")
            print(f"      Top Themes: {data['top_themes']}")
        else:
            print(f"   ❌ Failed with status {response.status_code}")
    except Exception as e:
        print(f"   ❌ Error: {e}")

def main():
    print("=" * 60)
    print("  Sentiment Analysis API - Test Suite")
    print("=" * 60)
    
    # Test health first
    if not test_health():
        print("\n❌ API is not running or not accessible at http://127.0.0.1:5000")
        print("   Make sure to start the API with: python sentiment_api.py")
        return
    
    # Run other tests
    test_single_analysis()
    test_batch_analysis()
    
    print("\n" + "=" * 60)
    print("  ✅ All tests completed!")
    print("=" * 60)

if __name__ == "__main__":
    main()
