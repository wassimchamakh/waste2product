# AI Image Classification Setup Guide

## FREE Waste Classification with Teachable Machine

Your Dechets module now has AI-powered image classification! Follow these steps to train your own model for FREE.

---

## Option 1: Google Teachable Machine (Recommended - 100% FREE, No Coding!)

### Step 1: Collect Training Images

Gather 20-50 images for each waste category:
- **Plastique**: Bottles, containers, bags
- **Papier/Carton**: Cardboard boxes, paper
- **Métal**: Cans, aluminum, steel
- **Verre**: Glass bottles, jars
- **Organic**: Food waste, plant materials
- **Electronics**: E-waste, batteries
- **Textile**: Clothes, fabrics

### Step 2: Train Your Model

1. Go to https://teachablemachine.withgoogle.com/train/image
2. Click "Image Project" → "Standard image model"
3. Create a class for each category (Plastique, Papier, etc.)
4. Upload 20-50 images per class
5. Click "Train Model" (takes 2-5 minutes)
6. Test your model with sample images

### Step 3: Export Your Model

1. Click "Export Model"
2. Select "TensorFlow.js"
3. Choose "Upload (shareable link)" - **FREE HOSTING BY GOOGLE!**
4. Copy the model URL (looks like: `https://teachablemachine.withgoogle.com/models/YOUR_MODEL_ID/`)

### Step 4: Integrate into Your App

Open this file:
```
resources/views/FrontOffice/dechets/components/ai-image-classifier.blade.php
```

Find line ~13:
```javascript
const MODEL_URL = 'https://teachablemachine.withgoogle.com/models/YOUR_MODEL_ID/';
```

Replace `YOUR_MODEL_ID` with your actual model ID from step 3.

### Step 5: Uncomment the Model Loading Code

In the same file, find these lines (~30-35) and uncomment them:
```javascript
// Load the model (commented out for now - uncomment when you have a model)
// model = await tmImage.load(modelURL, metadataURL);
// maxPredictions = model.getTotalClasses();
```

Remove the `//` to activate:
```javascript
model = await tmImage.load(modelURL, metadataURL);
maxPredictions = model.getTotalClasses();
```

And uncomment the model prediction code (~145-152):
```javascript
/* Actual implementation with Teachable Machine:
if (model) {
    const image = document.getElementById('preview-img');
    const predictions = await model.predict(image);
    currentPredictions = predictions;
    displayPredictions(predictions);
    document.getElementById('processing-badge').classList.add('hidden');
}
*/
```

### Step 6: Test!

1. Go to http://127.0.0.1:8000/dechets/create
2. Upload a waste image
3. Watch the AI classify it automatically!
4. Click "Appliquer la catégorie suggérée" to auto-select

---

## Option 2: Roboflow (More Advanced, Also FREE Tier)

### Benefits:
- More accurate models
- Custom training
- 1000 FREE API calls/month

### Setup:

1. Create account at https://roboflow.com (FREE)
2. Upload your waste images dataset
3. Label them (draw boxes around objects)
4. Train model (takes ~30 minutes)
5. Get API key from Roboflow dashboard
6. Use their JavaScript SDK:

```html
<script src="https://cdn.roboflow.com/0.2.26/roboflow.js"></script>

<script>
roboflow.auth({ publishable_key: "YOUR_KEY" });

const model = await roboflow
    .load({ model: "waste-classification", version: 1 });

const predictions = await model.detect(image);
</script>
```

---

## Option 3: Pre-trained Models (Instant Setup)

Use existing waste classification models:

### A) TrashNet Model
- Pre-trained on 2527 images across 6 categories
- GitHub: https://github.com/garythung/trashnet
- Categories: glass, paper, cardboard, plastic, metal, trash

### B) Waste Classification Dataset (Kaggle)
- https://www.kaggle.com/datasets/techsash/waste-classification-data
- 25,000+ images across 2 classes (Organic/Recyclable)

---

## How It Works

### Current Implementation (Mock Data)

The component currently shows mock predictions to demonstrate the UI:

```javascript
currentPredictions = [
    { className: 'Plastique', probability: 0.85 },
    { className: 'Papier/Carton', probability: 0.10 },
    { className: 'Métal', probability: 0.03 },
    { className: 'Verre', probability: 0.02 }
];
```

### With Real Model:

```javascript
const predictions = await model.predict(imageElement);
// Returns: [
//   { className: 'Plastique', probability: 0.92 },
//   { className: 'Métal', probability: 0.05 },
//   ...
// ]
```

---

## Tips for Better Accuracy

1. **Diverse Images**: Take photos from different angles, lighting conditions
2. **Clear Backgrounds**: Plain backgrounds help the model focus on the waste item
3. **Consistent Labeling**: Use the same category names as your database
4. **Balance Dataset**: Equal number of images per category (20-50 each minimum)
5. **Test Thoroughly**: Upload various images after training

---

## Troubleshooting

### Model not loading?
- Check the console for errors (F12 → Console tab)
- Verify your MODEL_URL is correct
- Make sure you uncommented the loading code

### Wrong classifications?
- Retrain with more diverse images
- Ensure category names match your database exactly
- Try different angles/lighting in training images

### Slow predictions?
- TensorFlow.js runs in browser - speed depends on user's device
- First prediction is slower (model loading)
- Subsequent predictions are fast

---

## Cost Summary

| Solution | Cost | API Calls | Training Time |
|----------|------|-----------|---------------|
| Teachable Machine | **FREE** | Unlimited | 5 minutes |
| Roboflow Free | **FREE** | 1000/month | 30 minutes |
| TensorFlow.js | **FREE** | Unlimited | Varies |

**Recommended**: Start with Teachable Machine - it's the easiest and completely free!

---

## Next Steps

1. Collect waste images (use your phone!)
2. Train model on Teachable Machine
3. Update MODEL_URL in the component
4. Uncomment the prediction code
5. Test and iterate

Happy classifying! 🤖♻️
