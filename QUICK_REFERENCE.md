# 🚀 Quick Reference - Category Management

## 📍 Key URLs

| Page | URL | Access |
|------|-----|--------|
| Categories List | `/admin/categories` | Admin |
| Create Category | `/admin/categories/create` | Admin |
| Edit Category | `/admin/categories/{id}/edit` | Admin |
| AI Suggestions API | `POST /admin/categories/ai-suggest` | Admin |

## 🎯 Quick Actions

### Create a New Category
1. Go to `/admin/categories`
2. Click green "Nouvelle Catégorie" button
3. Fill in:
   - Name (required)
   - Icon (FontAwesome class, e.g., `fas fa-recycle`)
   - Color (hex code)
   - Description
4. Click purple "IA: Suggérer" for AI certification help
5. Add certifications from dropdown
6. Upload optional image
7. Submit

### Use AI Suggestions
1. Enter category name and description
2. Click "IA: Suggérer des certifications"
3. Wait for AI to analyze (2-3 seconds)
4. Certifications auto-populate
5. Review and adjust as needed

### Add Tunisian Location (Dechets)
- Go to Dechets BackOffice create/edit
- Select from 24 governorate dropdown
- Field is now required

## 🔑 API Keys Setup

### Quick Setup (Windows)
```bash
setup_categories.bat
```

### Quick Setup (Linux/Mac)
```bash
chmod +x setup_categories.sh
./setup_categories.sh
```

### Manual Setup
Edit `.env`:
```env
GEMINI_API_KEY=AIza...your_key_here
```

Get key: https://makersuite.google.com/app/apikey

## 🎨 UI Components

### Category Forms Display
- **BackOffice Categories** → Full form with AI
- **BackOffice Dechets** → Simple select dropdown
- **FrontOffice Dechets** → Cards with icons
- **Projects** → Simple select dropdown

### Certification Options
- ISO 14001, 14040, 14044, 14046, 14064
- FSC, PEFC
- Green Dot, Cradle to Cradle, EU Ecolabel
- ISO 9001, 45001
- GRS, RCS

### Tunisian Governorates (24)
Tunis, Ariana, Ben Arous, Manouba, Nabeul, Zaghouan, Bizerte, Béja, Jendouba, Kef, Siliana, Sousse, Monastir, Mahdia, Sfax, Kairouan, Kasserine, Sidi Bouzid, Gabès, Medenine, Tataouine, Gafsa, Tozeur, Kebili

## 🔒 Validation Rules

| Field | Rules |
|-------|-------|
| Name | Required, Max 100 chars, Unique |
| Description | Required |
| Icon | Required, Max 50 chars |
| Color | Required, Valid hex code |
| Image | Optional, JPEG/PNG/JPG/SVG/WEBP, Max 2MB |
| Certifications | Optional, Array |
| Location (Dechets) | Required, Tunisian governorate |

## 🐛 Troubleshooting

| Issue | Fix |
|-------|-----|
| AI not working | Check `GEMINI_API_KEY` in `.env` |
| Button invisible | Run `npm run build`, clear cache |
| Image upload fails | Check `public/storage/categories` exists |
| Certifications not saving | Verify `certifications` is JSON in DB |

## 📚 Documentation

- **Full Guide:** `CATEGORY_MANAGEMENT_GUIDE.md`
- **Summary:** `IMPLEMENTATION_SUMMARY.md`
- **This File:** `QUICK_REFERENCE.md`

## 💡 Tips

1. **Use AI suggestions** - Saves time and ensures standards compliance
2. **Icons matter** - Choose relevant FontAwesome icons for better UX
3. **Colors** - Use distinct colors for easy visual identification
4. **Certifications** - Multiple selections allowed, choose all applicable
5. **Locations** - Always use dropdown, don't freetext in Dechets

## 🎯 Common Tasks

### Add New Certification Option
Edit both `create.blade.php` and `edit.blade.php`:
```html
<optgroup label="Your Group">
    <option value="NEW_CERT">NEW CERT - Description</option>
</optgroup>
```

### Change Category Display
- **Cards:** FrontOffice Dechets
- **Select:** BackOffice Dechets, Projects
- **Full Form:** BackOffice Categories

### Test AI Suggestions
1. Name: "Plastique Recyclé"
2. Description: "Plastique post-consommation recyclable"
3. Click AI button
4. Expected: ISO 14001, GRS, Cradle to Cradle

## ⚡ Keyboard Shortcuts

| Key | Action |
|-----|--------|
| Tab | Navigate fields |
| Enter | Submit form (in single-line inputs) |
| Esc | Close modals/dropdowns |

## 📊 Status Indicators

| Color | Meaning |
|-------|---------|
| 🟢 Green | Success, Primary actions |
| 🟣 Purple | AI features |
| 🔴 Red | Delete, Errors |
| 🟡 Yellow | Add items |
| ⚪ Gray | Cancel, Secondary |

## 🔗 Quick Links

- Google AI Studio: https://makersuite.google.com/app/apikey
- Cloud Vision API: https://console.cloud.google.com/
- FontAwesome Icons: https://fontawesome.com/icons
- Laravel Docs: https://laravel.com/docs

---

**Keep this file handy for quick reference!** 📌
