# 🧪 Testing Guide - Waste2Product Features

## ✨ AI Assistant for Categories

### Test: Smart Category Creation with AI
1. **Navigate to Categories**
   - Go to: `http://localhost:8000/admin/categories`
   - Click green "Nouvelle Catégorie" button

2. **Open AI Assistant**
   - Click purple "✨ Assistant IA" button (should have sparkle animation)
   - Modal should popup with guided questions

3. **Answer Questions**
   ```
   Question 1: Quel type de déchet?
   Answer: "Plastique PET"
   
   Question 2: Quelle est la principale utilisation?
   Select: "Recyclage direct"
   
   Question 3: Niveau de traitement requis?
   Select: "Moyen - Tri et préparation"
   ```

4. **Generate with AI**
   - Click "Générer avec l'IA"
   - Should show loading spinner with sparkles
   - Wait 2-3 seconds for AI response

5. **Review Suggestions**
   - AI should suggest:
     - ✅ Category name (e.g., "Plastique PET Recyclable")
     - ✅ FontAwesome icon (e.g., `fas fa-recycle`)
     - ✅ Color code (hex)
     - ✅ Professional description
     - ✅ 2-3 relevant certifications
     - ✅ Recycling tips

6. **Apply Suggestions**
   - Click "Appliquer" button
   - Modal should close
   - All form fields should auto-fill
   - Success message should appear (green, top-right)
   - Page should scroll to top

7. **Verify Auto-filled Data**
   - ✅ Name field populated
   - ✅ Icon preview showing
   - ✅ Color picker updated
   - ✅ Description filled
   - ✅ Tips added
   - ✅ Certifications dropdowns populated

8. **Submit Category**
   - Click "Créer la Catégorie"
   - Should redirect to categories list
   - New category should appear with AI-suggested data

### Expected Results
- ✅ AI generates relevant, professional data
- ✅ All fields correctly populated
- ✅ No JavaScript errors in console
- ✅ Smooth UX with loading states
- ✅ Success confirmation

---

## 🗂️ Dechet Filters & Search

### Test: Search Functionality
1. **Go to Dechets**
   - Navigate to: `http://localhost:8000/dechets`

2. **Test Search**
   ```
   Search: "plastique"
   Expected: Shows only dechets with "plastique" in title/description
   ```

3. **Test Category Filter**
   ```
   Select Category: "Plastique"
   Expected: Shows only dechets in Plastique category
   Count should match number shown in dropdown
   ```

4. **Test Status Filter**
   ```
   Select Status: "Disponible"
   Expected: Shows only available dechets
   ```

5. **Test Location Filter**
   ```
   Enter Location: "Tunis"
   Expected: Shows only dechets in Tunis
   ```

6. **Test Combined Filters**
   ```
   Search: "bouteille"
   Category: "Plastique"
   Status: "Disponible"
   Location: "Ariana"
   Expected: Shows dechets matching ALL criteria
   ```

7. **Clear Filters**
   - Click "Réinitialiser"
   - All filters should clear
   - Should show all dechets

### Expected Results
- ✅ Each filter works independently
- ✅ Filters work together (AND logic)
- ✅ Results update without page reload
- ✅ URL parameters updated
- ✅ Pagination respects filters
- ✅ Stats update with filters

---

## ❤️ Favorites Functionality

### Test: Add/Remove Favorites
1. **Go to Dechets List**
   - Navigate to: `http://localhost:8000/dechets`

2. **Add to Favorites**
   - Find a dechet card
   - Click heart icon (gray)
   - Should:
     - ✅ Turn red
     - ✅ Show toast: "Ajouté aux favoris"
     - ✅ Increase favorites count on card
     - ✅ No page reload

3. **Remove from Favorites**
   - Click red heart icon again
   - Should:
     - ✅ Turn gray
     - ✅ Show toast: "Retiré des favoris"
     - ✅ Decrease favorites count
     - ✅ No page reload

4. **View Favorites Page**
   - Click "Mes favoris" button (pink/red)
   - Navigate to: `http://localhost:8000/dechets/favoris`
   - Should show:
     - ✅ Only favorited dechets
     - ✅ All hearts are red
     - ✅ Can remove from favorites here

5. **Favorite from Detail Page**
   - Click on a dechet to view details
   - Click favorite button
   - Should update without reload
   - Return to list - status should persist

### Expected Results
- ✅ Real-time updates (no refresh)
- ✅ Toast notifications
- ✅ Count updates immediately
- ✅ Persistent across pages
- ✅ Visual feedback (color change)

---

## 🏷️ Category Management

### Test: Icon Selection
1. **Create Category**
   - Icon field: `fas fa-leaf`
   - Should show leaf icon preview immediately

2. **Color Picker**
   - Click color picker
   - Select green (#10b981)
   - Should update:
     - ✅ Color preview circle
     - ✅ Hex text input
     - ✅ Preview card background

3. **Certifications**
   - Click "Ajouter une Certification"
   - Should add new dropdown
   - Select different certifications
   - Can remove with trash button

4. **Live Preview**
   - As you type/change fields
   - Preview card should update in real-time:
     - ✅ Name
     - ✅ Icon
     - ✅ Color
     - ✅ Description

### Test: Edit Category
1. **Edit Existing**
   - Go to categories list
   - Click "Modifier" on any category
   - Should show:
     - ✅ All fields pre-filled
     - ✅ Certifications loaded
     - ✅ Current image displayed
     - ✅ Statistics panel

2. **Update with AI**
   - Click "✨ Assistant IA"
   - Answer questions
   - Apply suggestions
   - Should update fields (not replace)

3. **Delete Category**
   - Click "Supprimer"
   - If has dechets:
     - ✅ Shows error message
     - ✅ Prevents deletion
   - If empty:
     - ✅ Deletes successfully
     - ✅ Removes image file

---

## 📍 Tunisian Locations

### Test: Location Dropdowns (Dechets BackOffice)
1. **Create Dechet (BackOffice)**
   - Go to: `/admin/dechets/create`
   - Location field should be dropdown
   - Should show all 24 governorates
   - Try: Select "Tunis"
   - Required field - can't submit without

2. **Edit Dechet**
   - Edit existing dechet
   - Location should be pre-selected
   - Can change to different governorate

3. **Verify List**
   - All 24 governorates present:
     ```
     Tunis, Ariana, Ben Arous, Manouba, Nabeul,
     Zaghouan, Bizerte, Béja, Jendouba, Kef,
     Siliana, Sousse, Monastir, Mahdia, Sfax,
     Kairouan, Kasserine, Sidi Bouzid, Gabès,
     Medenine, Tataouine, Gafsa, Tozeur, Kebili
     ```

---

## 🎨 UI/UX Tests

### Test: Responsive Design
1. **Mobile View** (< 768px)
   - ✅ AI modal fits screen
   - ✅ Filters stack vertically
   - ✅ Cards responsive
   - ✅ Buttons touch-friendly

2. **Tablet View** (768-1024px)
   - ✅ 2-column card grid
   - ✅ Modal readable
   - ✅ Form fields appropriate size

3. **Desktop** (> 1024px)
   - ✅ 3-column grid
   - ✅ Modal centered
   - ✅ All features accessible

### Test: Animations
1. **AI Assistant Button**
   - Should have pulse animation
   - Sparkle icon

2. **Modal**
   - Smooth fade-in
   - Backdrop blur

3. **Favorites**
   - Heart scale on hover
   - Color transition

4. **Toast Notifications**
   - Slide in from top-right
   - Auto-dismiss after 3s

---

## 🔧 Error Handling Tests

### Test: AI Assistant Errors
1. **Missing API Key**
   - Remove `GEMINI_API_KEY` from .env
   - Try AI assistant
   - Should show error message

2. **Invalid Responses**
   - Disconnect internet
   - Try AI assistant
   - Should handle gracefully

3. **Empty Questions**
   - Don't fill questions
   - Click "Générer"
   - Should show: "⚠️ Veuillez répondre à toutes les questions"

### Test: Validation
1. **Required Fields (Category)**
   - Leave name empty
   - Try to submit
   - Should show validation error

2. **Image Upload**
   - Try uploading 5MB file
   - Should reject (max 2MB)
   - Try .txt file
   - Should reject (images only)

3. **Location Required (Dechets BO)**
   - Don't select location
   - Try to submit
   - Should show error

---

## 📊 Performance Tests

### Test: Page Load
- [ ] Categories index loads < 2s
- [ ] Dechets index loads < 2s
- [ ] AI response < 5s
- [ ] Favorite toggle < 500ms
- [ ] Filter update < 1s

### Test: Large Datasets
- [ ] 100+ dechets - pagination works
- [ ] 50+ categories - dropdown usable
- [ ] Multiple favorites - no lag

---

## ✅ Final Checklist

### Categories
- [ ] Create with manual input
- [ ] Create with AI assistant
- [ ] Edit existing
- [ ] Delete (with safety check)
- [ ] Icon preview works
- [ ] Color picker works
- [ ] Certifications dropdowns
- [ ] Live preview updates

### Dechets FrontOffice
- [ ] List all dechets
- [ ] Search by keyword
- [ ] Filter by category
- [ ] Filter by status
- [ ] Filter by location
- [ ] Add to favorites
- [ ] Remove from favorites
- [ ] View favorites page
- [ ] All filters combined

### Dechets BackOffice
- [ ] Category is select dropdown
- [ ] Location is governorate dropdown
- [ ] All 24 locations available
- [ ] Create dechet
- [ ] Edit dechet

### Projects
- [ ] Category is select dropdown
- [ ] Shows in create form
- [ ] Shows in edit form

### AI Features
- [ ] Modal opens/closes
- [ ] Questions validated
- [ ] AI generates data
- [ ] Suggestions displayed
- [ ] Apply works correctly
- [ ] Success feedback
- [ ] Error handling

---

## 🐛 Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| AI button doesn't work | Check `GEMINI_API_KEY` in .env |
| Favorites not toggling | Check route: `/dechets/{id}/favorite` |
| Filters not working | Check URL parameters |
| Modal not showing | Check for JavaScript errors |
| Location dropdown empty | Check database has categories |

---

## 📝 Testing Checklist Summary

**Before Testing:**
- [x] Composer packages installed
- [ ] `GEMINI_API_KEY` in .env
- [ ] Database migrated
- [ ] `public/storage/categories` folder exists
- [ ] Server running

**Core Features:**
- [ ] AI Assistant (guided questions)
- [ ] Auto-fill with AI suggestions
- [ ] Search functionality
- [ ] Category filter
- [ ] Status filter
- [ ] Location filter
- [ ] Favorites toggle
- [ ] Favorites page
- [ ] Tunisian locations dropdown

**All Tests Passed:** ✅ / ❌

---

**Happy Testing! 🎉**

*If you find any bugs, check the browser console and Laravel logs.*
