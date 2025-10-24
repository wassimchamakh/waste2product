# 🧠 Image Recognition Feature - Quick Guide

## ✨ Feature Overview

The **Predict Category** button uses Google Cloud Vision API to automatically identify waste categories from uploaded images.

## 📍 Location

**File**: `resources/views/FrontOffice/dechets/create.blade.php`
**Section**: Photo upload area

## 🎯 How It Works

### User Flow:
1. **Upload Image** → User uploads waste photo
2. **Preview Shows** → Image preview appears with "Prédire la catégorie" button
3. **Click Predict** → AI analyzes the image
4. **View Result** → Suggested category displays with confidence score
5. **Apply** → One-click to auto-select the predicted category

### Technical Flow:
```
Upload Image → Google Vision API → Label Detection → Category Mapping → Database Match → Auto-Select
```

## 🔧 Implementation Details

### Frontend (JavaScript)

**Function**: `predictCategory()`
- Uploads image to `/dechets/predict-category` route
- Shows loading spinner during analysis
- Displays result with category name
- Stores `predictedCategoryId` for application

**Function**: `applyPrediction()`
- Finds category card input by ID
- Auto-checks the radio button
- Scrolls to category section
- Adds visual flash effect
- Shows success alert

### Backend (Controller)

**Route**: `POST /dechets/predict-category`
**Method**: `DechetController@predictCategory`

**Process**:
1. Validates image (JPEG, PNG, JPG, GIF, WEBP, max 5MB)
2. Initializes Google Cloud Vision client
3. Performs label detection
4. Maps English labels to category keywords
5. Searches database for matching category
6. Falls back to French translations if needed
7. Returns JSON with category ID, name, labels, confidence

### Category Mapping

```php
'plastic' => ['plastic', 'bottle', 'container', 'packaging', 'polymer']
'paper' => ['paper', 'cardboard', 'newspaper', 'magazine', 'document']
'glass' => ['glass', 'bottle', 'jar', 'window']
'metal' => ['metal', 'aluminum', 'steel', 'iron', 'can']
'organic' => ['food', 'organic', 'vegetable', 'fruit', 'plant', 'leaf']
'electronic' => ['electronic', 'computer', 'phone', 'device', 'gadget']
'wood' => ['wood', 'timber', 'lumber', 'furniture', 'plank']
'textile' => ['textile', 'fabric', 'clothing', 'cloth', 'garment']
```

## 🎨 UI Components

### Predict Button
```html
<button onclick="predictCategory()" class="bg-gradient-to-r from-purple-500 to-indigo-500">
    <i class="fas fa-brain"></i>
    Prédire la catégorie
</button>
```

### Loading Spinner
```html
<div id="prediction-loading" class="hidden">
    <div class="animate-spin rounded-full h-8 w-8 border-4 border-purple-200 border-t-purple-600"></div>
    <p>Analyse en cours...</p>
</div>
```

### Result Display
```html
<div id="prediction-result" class="bg-purple-50 border-purple-200">
    <p><i class="fas fa-check-circle"></i> Catégorie suggérée :</p>
    <p id="predicted-category" class="text-lg font-bold"></p>
    <button onclick="applyPrediction()">Appliquer cette catégorie</button>
</div>
```

## 📋 API Requirements

### Google Cloud Vision Setup

1. **Credentials File**: `google-vision-credentials.json` (project root)
2. **Environment Variable**: Set via `putenv()` in controller
3. **Package**: `google/cloud-vision` (already installed via Composer)

### JSON Response Format

**Success**:
```json
{
    "success": true,
    "category_id": 3,
    "category_name": "Plastique",
    "detected_labels": ["plastic", "bottle", "container", "polymer", "packaging"],
    "confidence": 80
}
```

**Error**:
```json
{
    "success": false,
    "message": "Erreur lors de l'analyse: ..."
}
```

## 🧪 Testing

### Test Flow:
1. Navigate to `/dechets/create`
2. Upload image of plastic bottle
3. Click "Prédire la catégorie"
4. Verify loading spinner appears
5. Check result shows "Plastique" (or similar)
6. Click "Appliquer cette catégorie"
7. Verify category card is auto-selected
8. Verify visual flash effect on card

### Test Images:
- **Plastic bottle** → Should detect "Plastique"
- **Cardboard box** → Should detect "Papier/Carton"
- **Glass jar** → Should detect "Verre"
- **Aluminum can** → Should detect "Métal"
- **Banana peel** → Should detect "Organique"
- **Old phone** → Should detect "Électronique"

## 🐛 Troubleshooting

### Issue: "Route [dechets.predict-category] not defined"
**Solution**: Clear route cache
```bash
php artisan route:clear
php artisan route:cache
```

### Issue: "GOOGLE_APPLICATION_CREDENTIALS not set"
**Solution**: Ensure `google-vision-credentials.json` exists in project root
```bash
# Check file exists
ls google-vision-credentials.json
```

### Issue: "No category found"
**Solution**: 
- Check database has categories with French names
- Verify category mapping includes the detected labels
- Add fallback to return first available category

### Issue: Image upload fails
**Solution**: Check file size and type
- Max size: 5MB
- Allowed: JPEG, PNG, JPG, GIF, WEBP

## 🔒 Security

- ✅ CSRF token validation on all POST requests
- ✅ Image validation (type, size, mime)
- ✅ Server-side API calls (credentials never exposed to frontend)
- ✅ Temporary file cleanup after analysis

## 📊 Performance

- **Average Response Time**: 2-4 seconds
- **API Quota**: Check Google Cloud Console
- **Caching**: Not implemented (each image analyzed fresh)
- **Optimization**: Consider caching common items (bottles, cans, etc.)

## 🚀 Future Enhancements

1. **Multiple Predictions**: Show top 3 category suggestions
2. **Confidence Threshold**: Only show if confidence > 70%
3. **Custom Training**: Train model on Tunisia-specific waste types
4. **Batch Processing**: Allow multiple images at once
5. **Object Detection**: Identify multiple waste items in one image
6. **Recycling Tips**: Show category-specific tips after prediction

## 📝 Code Locations

| Component | File Path |
|-----------|-----------|
| Upload UI | `resources/views/FrontOffice/dechets/create.blade.php` (lines 207-247) |
| JavaScript | `resources/views/FrontOffice/dechets/create.blade.php` (lines 304-388) |
| Controller | `app/Http/Controllers/Frontoffice/DechetController.php` (lines 294-420) |
| Route | `routes/web.php` (line 103) |

---

**Status**: ✅ Fully Implemented
**Last Updated**: October 23, 2025
