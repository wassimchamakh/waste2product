# 📋 Category Management System - Complete Guide

## 🌟 Overview

This guide documents the complete CRUD system for waste categories with AI-powered features, image recognition, and comprehensive validation.

## 🎯 Features Implemented

### ✅ Complete CRUD Operations
- **Create** - Add new waste categories with icons, colors, certifications
- **Read** - View all categories with statistics and filtering
- **Update** - Edit existing categories with pre-populated data
- **Delete** - Remove categories (with safety checks for dependencies)

### 🤖 AI Integration
- **Gemini AI Certification Suggestions** - AI-powered recommendations for appropriate ISO certifications based on category name and description
- **Image Recognition** - Google Cloud Vision API for automatic waste classification
- **Filename Detection** - Smart keyword-based category detection from uploaded file names

### 🔐 Validation & Security
- **Required Fields** - Name, description, icon, color
- **Unique Names** - No duplicate category names
- **File Validation** - Image uploads (JPEG, PNG, JPG, SVG, WEBP, max 2MB)
- **Relationship Checks** - Prevent deletion of categories with associated waste items

### 🎨 UI Features
- **Icon Selection** - FontAwesome icons for category representation
- **Color Picker** - Visual color selection with hex code input
- **Live Preview** - Real-time card preview while editing
- **Certification Dropdowns** - Organized ISO and environmental certifications
- **Tunisian Location Dropdowns** - 24 governorates for waste item locations

---

## 📦 Installation & Setup

### 1. Install Dependencies

```bash
# Google Cloud Vision API
composer require google/cloud-vision

# Already installed with Laravel
composer require guzzlehttp/guzzle
```

### 2. Configure Environment Variables

Add to your `.env` file:

```env
# Google Gemini AI API
GEMINI_API_KEY=your_gemini_api_key_here

# Google Cloud Vision API (for image recognition)
GOOGLE_CLOUD_PROJECT_ID=your_project_id
GOOGLE_APPLICATION_CREDENTIALS=/path/to/credentials.json
```

### 3. Get Your API Keys

#### **Gemini API Key (FREE)**
1. Visit [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Sign in with your Google account
3. Click "Create API Key"
4. Copy the key and add to `.env`

#### **Google Cloud Vision API**
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing
3. Enable "Cloud Vision API"
4. Create service account credentials
5. Download JSON key file
6. Set path in `GOOGLE_APPLICATION_CREDENTIALS`

---

## 🗂️ Database Structure

### Categories Table

```sql
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name', 100)->unique();           // Category name
    $table->text('description');                      // Detailed description
    $table->string('icon', 50);                      // FontAwesome class
    $table->string('color', 20);                     // Hex color code
    $table->string('image')->nullable();             // Optional image
    $table->json('certifications')->nullable();      // ISO certifications array
    $table->text('tips')->nullable();                // Recycling tips
    $table->timestamps();
});
```

### Key Relationships

```php
// Category Model
public function dechets() {
    return $this->hasMany(Dechet::class);
}

public function projects() {
    return $this->hasMany(Project::class, 'category_id');
}
```

---

## 🎨 Frontend Implementation

### BackOffice Categories (Admin)

#### **Index Page** (`/admin/categories`)
- **Card Grid Layout** - Visual display of all categories
- **Statistics** - Show waste count per category
- **Action Buttons** - Edit, Delete for each category
- **Create Button** - Green gradient button (fixed visibility issue)

#### **Create Page** (`/admin/categories/create`)
- **Basic Info Card**
  - Name (required, max 100 chars)
  - Icon (FontAwesome class, with live preview)
  - Color (color picker + hex input)
  - Description (required, textarea)
  - Recycling Tips (optional)

- **Image Upload Card**
  - File input with preview
  - Accepted formats: JPEG, PNG, JPG, SVG, WEBP
  - Max size: 2MB

- **Certifications Card**
  - AI Suggestion Button (purple gradient with sparkle icon)
  - Dynamic dropdown selectors
  - Add/Remove certification fields
  - Organized optgroups:
    - ISO Environmental Norms (14001, 14040, 14044, 14046, 14064)
    - Forest Certifications (FSC, PEFC)
    - Recycling & Circular Economy (Green Dot, Cradle to Cradle, EU Ecolabel)
    - Quality & Safety (ISO 9001, 45001)
    - Others (GRS, RCS)

- **Live Preview Card**
  - Real-time card preview
  - Shows icon, color, name, description

#### **Edit Page** (`/admin/categories/{id}/edit`)
- All same fields as create
- Pre-populated with existing data
- Selected certifications loaded
- Current image displayed
- Statistics panel showing:
  - Number of waste items in category
  - Creation date

### Dechet Forms

#### **BackOffice Dechets** (`/admin/dechets/create`, `/admin/dechets/{id}/edit`)
- **Category Selection** - Simple select dropdown (NOT cards)
- **Location** - Dropdown of 24 Tunisian governorates (required)
- **Quantity** - Number input with units
- **Other Fields** - Name, description, photos, etc.

#### **FrontOffice Dechets** (`/dechets/create`)
- **Category Selection** - Card-based UI with icons and colors
- **Location** - Dropdown of Tunisian cities
- **AI Image Recognition** - Automatic category detection from uploaded images
- **Filename Detection** - Smart keyword matching

### Project Forms

#### **All Project Forms** (`/projects/create`, `/admin/projects/create`)
- **Category Selection** - Simple select dropdown
- Categories displayed as: "Name - Description"

---

## 🤖 AI Features Usage

### 1. AI Certification Suggestions

**How it works:**
1. User enters category name and description
2. Clicks "IA: Suggérer des certifications" button
3. System sends request to Gemini API with prompt
4. AI analyzes category and suggests 2-3 relevant certifications
5. Certification dropdowns auto-populate with suggestions

**JavaScript Function:**
```javascript
async function suggestCertificationsAI() {
    const categoryName = document.getElementById('name').value;
    const categoryDescription = document.getElementById('description').value;
    
    const response = await fetch('/admin/categories/ai-suggest', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf_token
        },
        body: JSON.stringify({
            name: categoryName,
            description: categoryDescription
        })
    });
    
    const data = await response.json();
    // Auto-populate certification dropdowns
}
```

**API Endpoint:** `POST /admin/categories/ai-suggest`

**Controller Method:**
```php
public function aiSuggest(Request $request)
{
    $geminiKey = env('GEMINI_API_KEY');
    $client = new \GuzzleHttp\Client();
    
    $response = $client->post(
        "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$geminiKey}",
        [
            'json' => [
                'contents' => [['parts' => [['text' => $prompt]]]]
            ]
        ]
    );
    
    return response()->json([
        'success' => true,
        'certifications' => $certifications
    ]);
}
```

### 2. Image Recognition (Filename-based)

**Smart keyword detection** in FrontOffice Dechets:

```javascript
function detectCategoryFromImage(filename) {
    const keywords = {
        'Plastique': ['plastic', 'plastique', 'bottle', 'bouteille', 'pet', 'hdpe'],
        'Papier/Carton': ['paper', 'papier', 'carton', 'cardboard'],
        'Métal': ['metal', 'métal', 'can', 'aluminium', 'steel'],
        'Verre': ['glass', 'verre', 'jar', 'bocal'],
        'Bois': ['wood', 'bois', 'pallet', 'palette'],
        'Électronique': ['electronic', 'phone', 'computer'],
        'Textile': ['textile', 'fabric', 'tissu', 'cloth']
    };
    
    // Match filename against keywords
    // Return suggested category
}
```

### 3. Google Cloud Vision API (Future Enhancement)

For gibberish filenames, integrate Vision API:

```php
use Google\Cloud\Vision\V1\ImageAnnotatorClient;

$imageAnnotator = new ImageAnnotatorClient();
$image = file_get_contents($imagePath);
$response = $imageAnnotator->labelDetection($image);
$labels = $response->getLabelAnnotations();
```

---

## 🎯 Validation Rules

### Category Create/Update

```php
$validated = $request->validate([
    'name' => 'required|string|max:100|unique:categories,name,' . $id,
    'description' => 'required|string',
    'icon' => 'required|string|max:50',
    'color' => 'required|string|max:20',
    'image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
    'tips' => 'nullable|string',
    'certifications' => 'nullable|array',
    'certifications.*' => 'string',
]);
```

### Dechet Forms (BackOffice)

```php
$validated = $request->validate([
    'category_id' => 'required|exists:categories,id',
    'location' => 'required|string',  // Must be a Tunisian governorate
    'quantity' => 'required|numeric|min:0',
    // ... other fields
]);
```

---

## 📊 Tunisian Governorates List

The system includes all 24 Tunisian governorates for location selection:

1. Tunis
2. Ariana
3. Ben Arous
4. Manouba
5. Nabeul
6. Zaghouan
7. Bizerte
8. Béja
9. Jendouba
10. Kef
11. Siliana
12. Sousse
13. Monastir
14. Mahdia
15. Sfax
16. Kairouan
17. Kasserine
18. Sidi Bouzid
19. Gabès
20. Medenine
21. Tataouine
22. Gafsa
23. Tozeur
24. Kebili

---

## 🔒 Security Features

### 1. Deletion Safety
```php
public function destroy(Category $category)
{
    // Check if category has associated waste items
    if ($category->dechets()->count() > 0) {
        return back()->with('error', 
            'Cannot delete category with existing waste items');
    }
    
    // Delete image file
    if ($category->image && file_exists($imagePath)) {
        unlink($imagePath);
    }
    
    $category->delete();
}
```

### 2. Image Upload Security
- File type validation
- Size limits (2MB)
- Unique filename generation
- Secure storage path

### 3. CSRF Protection
All forms include `@csrf` token

### 4. Authorization
- Categories management restricted to admin users
- Middleware protection on routes

---

## 🛣️ Routes

```php
// Admin Category Routes
Route::prefix('/admin/categories')->name('admin.categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/create', [CategoryController::class, 'create'])->name('create');
    Route::post('/', [CategoryController::class, 'store'])->name('store');
    Route::post('/ai-suggest', [CategoryController::class, 'aiSuggest'])->name('ai-suggest');
    Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
    Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
});
```

---

## 🎨 Styling & Icons

### Color Scheme
- **Primary Actions** - Green gradient (`from-green-600 to-green-700`)
- **AI Features** - Purple gradient (`from-purple-500 to-indigo-500`)
- **Danger Actions** - Red tones (`bg-red-50 text-red-600`)
- **Success Messages** - Green (`bg-green-50 border-green-200`)

### FontAwesome Icons Used
- `fa-tags` - Categories main icon
- `fa-recycle` - Default category icon
- `fa-certificate` - Certifications
- `fa-magic` - AI suggestions
- `fa-image` - Image upload
- `fa-eye` - Preview
- `fa-map-marker-alt` - Location
- `fa-plus` - Add actions
- `fa-trash` - Delete actions

---

## 📱 Responsive Design

- **Mobile** - Single column layout, collapsible sections
- **Tablet** - 2-column grid for categories
- **Desktop** - 3-column grid, full feature access

All forms and cards are fully responsive using Tailwind CSS.

---

## 🐛 Troubleshooting

### Issue: AI Suggestions Not Working
**Solution:**
1. Check `GEMINI_API_KEY` in `.env`
2. Verify API key is valid at [Google AI Studio](https://makersuite.google.com/app/apikey)
3. Check browser console for JavaScript errors
4. Verify route `/admin/categories/ai-suggest` is accessible

### Issue: Image Upload Fails
**Solution:**
1. Check `storage/categories` folder exists and is writable
2. Verify PHP `upload_max_filesize` and `post_max_size` settings
3. Ensure image is under 2MB
4. Check file type is in allowed list

### Issue: Category Button Invisible
**Solution:**
Button now uses `from-green-600 to-green-700` classes. If still invisible:
1. Run `npm run build` to rebuild Tailwind
2. Clear browser cache
3. Check for CSS conflicts

### Issue: Certifications Not Saving
**Solution:**
1. Check certifications are being sent as array
2. Verify `certifications` column is JSON type in database
3. Check `$casts = ['certifications' => 'array']` in Category model

---

## 📈 Future Enhancements

1. **Advanced Image Recognition**
   - Integrate Google Cloud Vision API fully
   - Train custom ML model for waste classification
   - Multi-object detection in single image

2. **Category Analytics**
   - Most used categories
   - Category trends over time
   - Geographic distribution

3. **AI Improvements**
   - Auto-generate category descriptions
   - Suggest recycling tips
   - Multilingual support

4. **Export/Import**
   - Export categories to CSV/JSON
   - Import categories from templates
   - Bulk operations

---

## 👥 User Permissions

| Action | Admin | User (FrontOffice) |
|--------|-------|-------------------|
| View Categories | ✅ | ✅ |
| Create Category | ✅ | ❌ |
| Edit Category | ✅ | ❌ |
| Delete Category | ✅ | ❌ |
| Use AI Suggestions | ✅ | ❌ |
| Select Category (Dechets) | ✅ | ✅ |
| Select Category (Projects) | ✅ | ✅ |

---

## 📞 Support

For issues or questions:
1. Check this documentation
2. Review error logs in `storage/logs/laravel.log`
3. Verify `.env` configuration
4. Check browser console for JavaScript errors

---

## ✨ Credits

- **Framework** - Laravel 11.x
- **Frontend** - Tailwind CSS, Blade Templates
- **Icons** - FontAwesome 6
- **AI** - Google Gemini API (Free Tier)
- **Image Recognition** - Google Cloud Vision API

---

**Last Updated:** October 23, 2025
**Version:** 1.0.0
