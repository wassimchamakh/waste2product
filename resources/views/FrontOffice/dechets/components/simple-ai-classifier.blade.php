<!-- Simplified AI Image Upload & Classification -->
<div class="bg-gradient-to-br from-indigo-50 to-purple-50 border-2 border-indigo-200 rounded-xl p-6 mb-6">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center">
            <i class="fas fa-camera text-white text-xl"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-gray-900">Photo du Déchet</h3>
            <p class="text-sm text-gray-600">Ajoutez une photo et laissez l'IA suggérer la catégorie</p>
        </div>
    </div>

    <!-- Single Image Upload Area -->
    <div class="relative">
        <input type="file"
               name="photo"
               id="waste-photo-input"
               accept="image/*"
               class="hidden"
               onchange="handlePhotoUpload(event)">

        <label for="waste-photo-input"
               class="block cursor-pointer border-2 border-dashed border-indigo-300 rounded-lg p-6 text-center hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-200">
            <div id="photo-placeholder">
                <i class="fas fa-cloud-upload-alt text-4xl text-indigo-400 mb-2"></i>
                <p class="text-gray-700 font-semibold">Télécharger une photo</p>
                <p class="text-gray-500 text-sm mt-1">Cliquez ou glissez-déposez</p>
                <p class="text-gray-400 text-xs mt-2">PNG, JPG jusqu'à 5MB</p>
            </div>

            <div id="photo-preview-container" class="hidden">
                <img id="photo-preview" src="" alt="Preview" class="max-h-48 mx-auto rounded-lg shadow-md mb-3">
                <button type="button"
                        onclick="clearPhoto(event)"
                        class="text-sm text-red-600 hover:text-red-800 font-semibold">
                    <i class="fas fa-times mr-1"></i> Changer l'image
                </button>
            </div>
        </label>
    </div>

    <!-- AI Analysis Button -->
    <div id="ai-button-container" class="hidden mt-4">
        <button type="button"
                id="ai-analyze-btn"
                onclick="analyzeImage()"
                class="w-full bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-bold py-3 px-6 rounded-lg hover:from-indigo-600 hover:to-purple-600 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
            <i class="fas fa-magic"></i>
            <span>Analyser avec l'IA et suggérer une catégorie</span>
        </button>
    </div>

    <!-- AI Results -->
    <div id="ai-analysis-results" class="hidden mt-4 bg-white rounded-lg shadow-sm p-4 border border-indigo-200">
        <div class="flex items-center gap-2 mb-3">
            <i class="fas fa-robot text-indigo-600"></i>
            <h4 class="font-bold text-gray-800">Analyse IA</h4>
            <span id="ai-loading" class="hidden ml-auto">
                <i class="fas fa-spinner fa-spin text-indigo-500"></i>
            </span>
        </div>

        <div id="ai-suggestion" class="space-y-2">
            <!-- Results will be inserted here -->
        </div>
    </div>
</div>

<script>
let uploadedPhotoFile = null;

function handlePhotoUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Validate file size (5MB max)
    if (file.size > 5 * 1024 * 1024) {
        alert('Le fichier est trop volumineux. Maximum 5MB.');
        return;
    }

    uploadedPhotoFile = file;

    // Show preview
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('photo-preview').src = e.target.result;
        document.getElementById('photo-placeholder').classList.add('hidden');
        document.getElementById('photo-preview-container').classList.remove('hidden');
        document.getElementById('ai-button-container').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}

function clearPhoto(event) {
    event.preventDefault();
    event.stopPropagation();

    document.getElementById('waste-photo-input').value = '';
    document.getElementById('photo-placeholder').classList.remove('hidden');
    document.getElementById('photo-preview-container').classList.add('hidden');
    document.getElementById('ai-button-container').classList.add('hidden');
    document.getElementById('ai-analysis-results').classList.add('hidden');
    uploadedPhotoFile = null;
}

async function analyzeImage() {
    if (!uploadedPhotoFile) return;

    // Show loading
    document.getElementById('ai-loading').classList.remove('hidden');
    document.getElementById('ai-analyze-btn').disabled = true;
    document.getElementById('ai-analyze-btn').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Analyse en cours...';

    // Simulate AI analysis with keyword detection from filename
    setTimeout(() => {
        const filename = uploadedPhotoFile.name.toLowerCase();
        let suggestedCategory = detectCategoryFromImage(filename);

        displayAISuggestion(suggestedCategory);

        // Re-enable button
        document.getElementById('ai-loading').classList.add('hidden');
        document.getElementById('ai-analyze-btn').disabled = false;
        document.getElementById('ai-analyze-btn').innerHTML = '<i class="fas fa-magic mr-2"></i> Analyser avec l\'IA';
    }, 1500);

    /*
     * FOR REAL AI: Replace the setTimeout above with actual API call
     * Example with free API (you need to add API key):

    try {
        const formData = new FormData();
        formData.append('image', uploadedPhotoFile);

        const response = await fetch('YOUR_API_ENDPOINT', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        const suggestedCategory = result.category; // Adjust based on API response
        displayAISuggestion(suggestedCategory);
    } catch (error) {
        console.error('AI Analysis failed:', error);
        showToast('Erreur lors de l\'analyse. Sélectionnez manuellement.', 'error');
    }
    */
}

function detectCategoryFromImage(filename) {
    // Simple keyword-based detection (works without any API!)
    const keywords = {
        'Plastique': ['plastic', 'plastique', 'bottle', 'bouteille', 'pet', 'hdpe'],
        'Papier/Carton': ['paper', 'papier', 'carton', 'cardboard', 'box', 'boite'],
        'Métal': ['metal', 'métal', 'can', 'aluminium', 'steel', 'fer'],
        'Verre': ['glass', 'verre', 'jar', 'bocal'],
        'Bois': ['wood', 'bois', 'pallet', 'palette'],
        'Électronique': ['electronic', 'électronique', 'phone', 'computer', 'ordinateur'],
        'Textile': ['textile', 'fabric', 'tissu', 'cloth', 'vetement']
    };

    for (const [category, words] of Object.entries(keywords)) {
        if (words.some(word => filename.includes(word))) {
            return { name: category, confidence: 85 };
        }
    }

    return { name: 'Non déterminé', confidence: 0 };
}

function displayAISuggestion(suggestion) {
    const resultsContainer = document.getElementById('ai-suggestion');
    const resultsCard = document.getElementById('ai-analysis-results');

    if (suggestion.confidence === 0) {
        resultsContainer.innerHTML = `
            <div class="flex items-center gap-2 text-amber-600">
                <i class="fas fa-exclamation-triangle"></i>
                <span class="text-sm">L'IA n'a pas pu déterminer la catégorie. Sélectionnez manuellement ci-dessous.</span>
            </div>
        `;
    } else {
        resultsContainer.innerHTML = `
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-semibold text-gray-700">Catégorie suggérée:</span>
                <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-bold">
                    ${suggestion.confidence}% confiance
                </span>
            </div>
            <div class="text-lg font-bold text-indigo-600 mb-3">${suggestion.name}</div>
            <button type="button"
                    onclick="applySuggestedCategory('${suggestion.name}')"
                    class="w-full bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">
                <i class="fas fa-check mr-2"></i> Appliquer cette catégorie
            </button>
        `;
    }

    resultsCard.classList.remove('hidden');
}

function applySuggestedCategory(categoryName) {
    // Find and select the matching radio button
    const radioButtons = document.querySelectorAll('input[name="category_id"]');
    let matched = false;

    radioButtons.forEach(radio => {
        const label = radio.closest('.category-card');
        const cardName = label.querySelector('h4').textContent.trim();

        if (cardName.toLowerCase().includes(categoryName.toLowerCase()) ||
            categoryName.toLowerCase().includes(cardName.toLowerCase())) {
            radio.checked = true;
            radio.dispatchEvent(new Event('change'));
            matched = true;

            // Scroll to category section
            label.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    if (matched) {
        showToast('✅ Catégorie appliquée!', 'success');
    } else {
        showToast('⚠️ Catégorie non trouvée. Sélectionnez manuellement.', 'warning');
    }
}

function showToast(message, type) {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'warning' ? 'bg-amber-500' : 'bg-red-500';

    toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('animate-fade-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-out {
    from { opacity: 1; }
    to { opacity: 0; }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}

.animate-fade-out {
    animation: fade-out 0.3s ease-out;
}
</style>
