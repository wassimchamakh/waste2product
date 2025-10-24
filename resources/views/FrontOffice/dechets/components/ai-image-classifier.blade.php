<!-- AI-Powered Image Classification Component -->
<div class="bg-gradient-to-br from-purple-50 to-blue-50 border-2 border-purple-200 rounded-xl p-6 mb-6">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-500 rounded-full flex items-center justify-center">
            <i class="fas fa-robot text-white text-xl"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-gray-900">Classification Intelligente par IA</h3>
            <p class="text-sm text-gray-600">Téléchargez une photo et l'IA détectera automatiquement le type de déchet</p>
        </div>
    </div>

    <!-- Image Upload Area with Preview -->
    <div class="relative">
        <input type="file"
               id="ai-image-input"
               accept="image/*"
               class="hidden"
               onchange="handleImageUpload(event)">

        <label for="ai-image-input"
               class="block cursor-pointer border-2 border-dashed border-purple-300 rounded-lg p-8 text-center hover:border-purple-500 hover:bg-purple-50 transition-all duration-200">
            <div id="upload-placeholder">
                <i class="fas fa-cloud-upload-alt text-5xl text-purple-400 mb-3"></i>
                <p class="text-gray-700 font-semibold">Cliquez pour télécharger une image</p>
                <p class="text-gray-500 text-sm mt-1">ou glissez-déposez ici</p>
                <p class="text-gray-400 text-xs mt-2">PNG, JPG, WebP jusqu'à 10MB</p>
            </div>

            <div id="image-preview" class="hidden">
                <img id="preview-img" src="" alt="Preview" class="max-h-64 mx-auto rounded-lg shadow-lg mb-4">
                <button type="button"
                        onclick="clearImage()"
                        class="text-sm text-red-600 hover:text-red-800 font-semibold">
                    <i class="fas fa-times mr-1"></i> Supprimer l'image
                </button>
            </div>
        </label>
    </div>

    <!-- AI Classification Results -->
    <div id="ai-results" class="hidden mt-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-bold text-gray-800">Résultats de l'Analyse IA</h4>
                <span id="processing-badge" class="hidden px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">
                    <i class="fas fa-spinner fa-spin mr-1"></i> Analyse en cours...
                </span>
            </div>

            <!-- Classification Results List -->
            <div id="predictions-list" class="space-y-2">
                <!-- Results will be inserted here by JavaScript -->
            </div>

            <!-- Auto-select Category Button -->
            <button type="button"
                    id="apply-ai-category"
                    onclick="applyAICategory()"
                    class="mt-4 w-full bg-gradient-to-r from-purple-500 to-blue-500 text-white font-semibold py-2 px-4 rounded-lg hover:from-purple-600 hover:to-blue-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-magic mr-2"></i> Appliquer la catégorie suggérée
            </button>
        </div>
    </div>
</div>

<!-- Include TensorFlow.js and Teachable Machine -->
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest/dist/tf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@latest/dist/teachablemachine-image.min.js"></script>

<script>
let model, maxPredictions;
let currentPredictions = [];

// Load the Teachable Machine model (you'll need to train your own or use a pre-trained one)
// For demo, using a placeholder URL - replace with your actual trained model
const MODEL_URL = 'https://teachablemachine.withgoogle.com/models/YOUR_MODEL_ID/';

// Alternative: Use a simple waste classification model
// You can train your own at: https://teachablemachine.withgoogle.com/train/image

async function loadModel() {
    try {
        const modelURL = MODEL_URL + 'model.json';
        const metadataURL = MODEL_URL + 'metadata.json';

        // Load the model (commented out for now - uncomment when you have a model)
        // model = await tmImage.load(modelURL, metadataURL);
        // maxPredictions = model.getTotalClasses();

        console.log('Model loaded successfully');
    } catch (error) {
        console.error('Error loading model:', error);
    }
}

// Initialize on page load
// loadModel();

function handleImageUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Show image preview
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('preview-img').src = e.target.result;
        document.getElementById('upload-placeholder').classList.add('hidden');
        document.getElementById('image-preview').classList.remove('hidden');

        // Classify the image
        classifyImage(e.target.result);
    };
    reader.readAsDataURL(file);
}

async function classifyImage(imageSrc) {
    document.getElementById('ai-results').classList.remove('hidden');
    document.getElementById('processing-badge').classList.remove('hidden');

    // Simulate AI classification (replace with actual model prediction)
    setTimeout(() => {
        // Mock predictions - replace with actual model.predict()
        currentPredictions = [
            { className: 'Plastique', probability: 0.85 },
            { className: 'Papier/Carton', probability: 0.10 },
            { className: 'Métal', probability: 0.03 },
            { className: 'Verre', probability: 0.02 }
        ];

        displayPredictions(currentPredictions);
        document.getElementById('processing-badge').classList.add('hidden');
    }, 1500);

    /* Actual implementation with Teachable Machine:
    if (model) {
        const image = document.getElementById('preview-img');
        const predictions = await model.predict(image);
        currentPredictions = predictions;
        displayPredictions(predictions);
        document.getElementById('processing-badge').classList.add('hidden');
    }
    */
}

function displayPredictions(predictions) {
    const container = document.getElementById('predictions-list');
    container.innerHTML = '';

    predictions
        .sort((a, b) => b.probability - a.probability)
        .slice(0, 4) // Top 4 predictions
        .forEach(pred => {
            const percentage = (pred.probability * 100).toFixed(1);
            const barWidth = percentage;

            const predictionHTML = `
                <div class="prediction-item">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-semibold text-gray-700">${pred.className}</span>
                        <span class="text-sm font-bold ${percentage > 70 ? 'text-green-600' : percentage > 40 ? 'text-yellow-600' : 'text-gray-500'}">
                            ${percentage}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full transition-all duration-500 ${
                            percentage > 70 ? 'bg-gradient-to-r from-green-400 to-green-600' :
                            percentage > 40 ? 'bg-gradient-to-r from-yellow-400 to-yellow-600' :
                            'bg-gray-400'
                        }" style="width: ${barWidth}%"></div>
                    </div>
                </div>
            `;
            container.innerHTML += predictionHTML;
        });
}

function applyAICategory() {
    if (currentPredictions.length === 0) return;

    // Get the top prediction
    const topPrediction = currentPredictions[0];

    // Find matching category radio button by name
    const categoryCards = document.querySelectorAll('.category-card');
    let matched = false;

    categoryCards.forEach(card => {
        const categoryName = card.querySelector('h4').textContent.trim();
        if (categoryName.toLowerCase().includes(topPrediction.className.toLowerCase()) ||
            topPrediction.className.toLowerCase().includes(categoryName.toLowerCase())) {
            // Click the radio button input
            const radioInput = card.querySelector('input[type="radio"]');
            if (radioInput) {
                radioInput.checked = true;
                radioInput.dispatchEvent(new Event('change'));
                matched = true;

                // Scroll to category section smoothly
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    if (matched) {
        // Show success message
        showToast('Catégorie appliquée avec succès!', 'success');
    } else {
        showToast('Aucune catégorie correspondante trouvée. Sélectionnez manuellement.', 'warning');
    }
}

function clearImage() {
    document.getElementById('ai-image-input').value = '';
    document.getElementById('upload-placeholder').classList.remove('hidden');
    document.getElementById('image-preview').classList.add('hidden');
    document.getElementById('ai-results').classList.add('hidden');
    currentPredictions = [];
}

function showToast(message, type) {
    // Simple toast notification (you can enhance this)
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white font-semibold z-50 ${
        type === 'success' ? 'bg-green-500' :
        type === 'warning' ? 'bg-yellow-500' :
        'bg-blue-500'
    }`;
    toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'} mr-2"></i>${message}`;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>

<style>
.prediction-item {
    margin-bottom: 12px;
}

#image-preview img {
    max-height: 250px;
    object-fit: contain;
}
</style>
