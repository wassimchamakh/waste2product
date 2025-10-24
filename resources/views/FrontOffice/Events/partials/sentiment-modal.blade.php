<!-- Sentiment Analysis Dashboard Modal -->
<div id="sentimentModal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 opacity-0 pointer-events-none flex items-center justify-center transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl mx-4 max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 text-white px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i class="fas fa-chart-line text-2xl"></i>
                <div>
                    <h3 class="text-xl font-bold">Analyse des Sentiments</h3>
                    <p class="text-sm opacity-90">Analyse AI des feedbacks des participants</p>
                </div>
            </div>
            <button onclick="closeSentimentModal()" class="text-white hover:bg-white/20 rounded-full p-2 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-80px)]">
            <div class="p-6">
                <!-- Loading State -->
                <div id="sentimentLoading" class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-purple-500"></div>
                    <p class="mt-4 text-gray-600">Chargement de l'analyse...</p>
                </div>

                <!-- Error State -->
                <div id="sentimentError" class="hidden">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                        <i class="fas fa-exclamation-circle text-red-500 text-4xl mb-3"></i>
                        <h4 class="text-lg font-semibold text-red-700 mb-2">Erreur</h4>
                        <p id="sentimentErrorMessage" class="text-red-600"></p>
                        <button onclick="analyzeSentiment()" class="mt-4 bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600">
                            Réessayer
                        </button>
                    </div>
                </div>

                <!-- No Data State -->
                <div id="sentimentNoData" class="hidden">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
                        <i class="fas fa-inbox text-gray-400 text-5xl mb-3"></i>
                        <h4 class="text-lg font-semibold text-gray-700 mb-2">Aucun feedback à analyser</h4>
                        <p class="text-gray-600">Les participants n'ont pas encore laissé de feedback pour cet événement.</p>
                    </div>
                </div>

                <!-- Results Container -->
                <div id="sentimentResults" class="hidden space-y-6">
                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Total Feedback -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-blue-600 font-medium">Total Feedback</p>
                                    <p id="totalCount" class="text-3xl font-bold text-blue-700 mt-1">0</p>
                                </div>
                                <i class="fas fa-comments text-3xl text-blue-400"></i>
                            </div>
                        </div>

                        <!-- Positive -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-green-600 font-medium">Positifs</p>
                                    <p id="positiveCount" class="text-3xl font-bold text-green-700 mt-1">0</p>
                                    <p id="positivePercent" class="text-xs text-green-600 mt-1">0%</p>
                                </div>
                                <i class="fas fa-smile text-3xl text-green-400"></i>
                            </div>
                        </div>

                        <!-- Negative -->
                        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 border border-red-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-red-600 font-medium">Négatifs</p>
                                    <p id="negativeCount" class="text-3xl font-bold text-red-700 mt-1">0</p>
                                    <p id="negativePercent" class="text-xs text-red-600 mt-1">0%</p>
                                </div>
                                <i class="fas fa-frown text-3xl text-red-400"></i>
                            </div>
                        </div>

                        <!-- Neutral -->
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-4 border border-yellow-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-yellow-600 font-medium">Neutres</p>
                                    <p id="neutralCount" class="text-3xl font-bold text-yellow-700 mt-1">0</p>
                                    <p id="neutralPercent" class="text-xs text-yellow-600 mt-1">0%</p>
                                </div>
                                <i class="fas fa-meh text-3xl text-yellow-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Sentiment Distribution Chart -->
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-chart-pie text-purple-500"></i>
                                Distribution des Sentiments
                            </h4>
                            <canvas id="sentimentChart" class="max-h-64"></canvas>
                        </div>

                        <!-- Score Distribution -->
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-chart-bar text-indigo-500"></i>
                                Score Moyen
                            </h4>
                            <div class="text-center py-8">
                                <div class="inline-flex items-center justify-center w-32 h-32 rounded-full" id="scoreGauge">
                                    <div class="text-center">
                                        <p id="avgScore" class="text-4xl font-bold text-gray-800">0.0</p>
                                        <p class="text-sm text-gray-500">Score</p>
                                    </div>
                                </div>
                                <p id="avgConfidence" class="mt-4 text-gray-600">Confiance: <span class="font-semibold">0%</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Top Themes -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-tags text-teal-500"></i>
                            Thèmes Principaux
                        </h4>
                        <div id="themesContainer" class="flex flex-wrap gap-2">
                            <!-- Themes will be inserted here -->
                        </div>
                    </div>

                    <!-- Concerning Feedback -->
                    <div id="concernsSection" class="bg-red-50 rounded-xl border border-red-200 p-6 hidden">
                        <h4 class="text-lg font-semibold text-red-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle text-red-500"></i>
                            Feedback Négatifs à Examiner
                        </h4>
                        <div id="concernsContainer" class="space-y-3">
                            <!-- Concerns will be inserted here -->
                        </div>
                    </div>

                    <!-- All Feedback List -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-list text-gray-500"></i>
                            Tous les Feedbacks Analysés
                        </h4>
                        <div id="feedbackListContainer" class="space-y-3 max-h-96 overflow-y-auto">
                            <!-- Feedback list will be inserted here -->
                        </div>
                    </div>

                    <!-- Last Analyzed -->
                    <div class="text-center text-sm text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        Dernière analyse: <span id="lastAnalyzed">-</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t">
            <div class="text-sm text-gray-600">
                <i class="fas fa-robot mr-1"></i>
                Propulsé par l'IA
            </div>
            <div class="flex gap-3">
                <button onclick="analyzeSentiment()" class="bg-purple-500 text-white px-6 py-2 rounded-lg hover:bg-purple-600 transition-colors flex items-center gap-2">
                    <i class="fas fa-sync-alt"></i>
                    Réanalyser
                </button>
                <button onclick="closeSentimentModal()" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let sentimentChart = null;

function openSentimentModal() {
    const modal = document.getElementById('sentimentModal');
    modal.classList.remove('opacity-0', 'pointer-events-none');
    modal.style.pointerEvents = 'auto';
    document.body.style.overflow = 'hidden';
    
    // Load existing results or trigger new analysis
    loadSentimentResults();
}

function closeSentimentModal() {
    const modal = document.getElementById('sentimentModal');
    modal.classList.add('opacity-0', 'pointer-events-none');
    modal.style.pointerEvents = 'none';
    document.body.style.overflow = 'auto';
}

function showLoading() {
    document.getElementById('sentimentLoading').classList.remove('hidden');
    document.getElementById('sentimentError').classList.add('hidden');
    document.getElementById('sentimentNoData').classList.add('hidden');
    document.getElementById('sentimentResults').classList.add('hidden');
}

function showError(message) {
    document.getElementById('sentimentLoading').classList.add('hidden');
    document.getElementById('sentimentError').classList.remove('hidden');
    document.getElementById('sentimentErrorMessage').textContent = message;
    document.getElementById('sentimentNoData').classList.add('hidden');
    document.getElementById('sentimentResults').classList.add('hidden');
}

function showNoData() {
    document.getElementById('sentimentLoading').classList.add('hidden');
    document.getElementById('sentimentError').classList.add('hidden');
    document.getElementById('sentimentNoData').classList.remove('hidden');
    document.getElementById('sentimentResults').classList.add('hidden');
}

function showResults() {
    document.getElementById('sentimentLoading').classList.add('hidden');
    document.getElementById('sentimentError').classList.add('hidden');
    document.getElementById('sentimentNoData').classList.add('hidden');
    document.getElementById('sentimentResults').classList.remove('hidden');
}

async function loadSentimentResults() {
    showLoading();
    
    try {
        const response = await fetch(`/events/{{ $event->id }}/sentiment-results`);
        const result = await response.json();
        
        if (!response.ok) {
            if (result.message.includes('Aucune analyse')) {
                showNoData();
            } else {
                showError(result.message || 'Erreur lors du chargement des résultats');
            }
            return;
        }
        
        if (result.success) {
            displaySentimentResults(result.data);
        } else {
            showError(result.message || 'Erreur inconnue');
        }
    } catch (error) {
        console.error('Error loading sentiment results:', error);
        showError('Erreur de connexion');
    }
}

async function analyzeSentiment() {
    showLoading();
    
    try {
        const response = await fetch(`/events/{{ $event->id }}/analyze-sentiment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const result = await response.json();
        
        if (!response.ok) {
            showError(result.message || 'Erreur lors de l\'analyse');
            return;
        }
        
        if (result.success) {
            // Reload results after analysis
            await loadSentimentResults();
        } else {
            showError(result.message || 'Erreur inconnue');
        }
    } catch (error) {
        console.error('Error analyzing sentiment:', error);
        showError('Erreur de connexion au service d\'analyse');
    }
}

function displaySentimentResults(data) {
    const { aggregate, top_themes, concerns, feedback_list } = data;
    
    // Update summary cards
    document.getElementById('totalCount').textContent = aggregate.total_count;
    document.getElementById('positiveCount').textContent = aggregate.positive_count;
    document.getElementById('positivePercent').textContent = aggregate.positive_percentage.toFixed(1) + '%';
    document.getElementById('negativeCount').textContent = aggregate.negative_count;
    document.getElementById('negativePercent').textContent = aggregate.negative_percentage.toFixed(1) + '%';
    document.getElementById('neutralCount').textContent = aggregate.neutral_count;
    document.getElementById('neutralPercent').textContent = aggregate.neutral_percentage.toFixed(1) + '%';
    
    // Update score
    const scoreValue = aggregate.average_score;
    const scoreElement = document.getElementById('avgScore');
    scoreElement.textContent = scoreValue.toFixed(2);
    
    // Color code the score gauge
    const scoreGauge = document.getElementById('scoreGauge');
    if (scoreValue > 0.05) {
        scoreGauge.classList.add('bg-green-100', 'border-4', 'border-green-500');
    } else if (scoreValue < -0.05) {
        scoreGauge.classList.add('bg-red-100', 'border-4', 'border-red-500');
    } else {
        scoreGauge.classList.add('bg-yellow-100', 'border-4', 'border-yellow-500');
    }
    
    document.getElementById('avgConfidence').innerHTML = `Confiance: <span class="font-semibold">${(aggregate.average_confidence * 100).toFixed(1)}%</span>`;
    
    // Update last analyzed
    document.getElementById('lastAnalyzed').textContent = new Date(aggregate.last_analyzed).toLocaleString('fr-FR');
    
    // Create sentiment chart
    createSentimentChart(aggregate);
    
    // Display themes
    displayThemes(top_themes);
    
    // Display concerns
    if (concerns && concerns.length > 0) {
        displayConcerns(concerns);
    }
    
    // Display feedback list
    displayFeedbackList(feedback_list);
    
    showResults();
}

function createSentimentChart(aggregate) {
    const ctx = document.getElementById('sentimentChart');
    
    if (sentimentChart) {
        sentimentChart.destroy();
    }
    
    sentimentChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Positifs', 'Négatifs', 'Neutres'],
            datasets: [{
                data: [aggregate.positive_count, aggregate.negative_count, aggregate.neutral_count],
                backgroundColor: ['#10b981', '#ef4444', '#f59e0b'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function displayThemes(themes) {
    const container = document.getElementById('themesContainer');
    container.innerHTML = '';
    
    if (Object.keys(themes).length === 0) {
        container.innerHTML = '<p class="text-gray-500">Aucun thème identifié</p>';
        return;
    }
    
    for (const [theme, count] of Object.entries(themes)) {
        const badge = document.createElement('span');
        badge.className = 'inline-flex items-center gap-2 bg-teal-100 text-teal-800 px-4 py-2 rounded-full text-sm font-medium';
        badge.innerHTML = `
            <span>${theme}</span>
            <span class="bg-teal-200 text-teal-900 px-2 py-0.5 rounded-full text-xs">${count}</span>
        `;
        container.appendChild(badge);
    }
}

function displayConcerns(concerns) {
    const section = document.getElementById('concernsSection');
    const container = document.getElementById('concernsContainer');
    
    section.classList.remove('hidden');
    container.innerHTML = '';
    
    concerns.forEach(concern => {
        const item = document.createElement('div');
        item.className = 'bg-white rounded-lg p-4 border border-red-200';
        item.innerHTML = `
            <div class="flex items-start gap-3">
                <i class="fas fa-user-circle text-2xl text-red-400 mt-1"></i>
                <div class="flex-1">
                    <p class="font-semibold text-red-700">${concern.participant}</p>
                    <p class="text-gray-700 mt-1">${concern.feedback}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded">Score: ${concern.score.toFixed(2)}</span>
                        ${concern.themes.map(t => `<span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">${t}</span>`).join('')}
                    </div>
                </div>
            </div>
        `;
        container.appendChild(item);
    });
}

function displayFeedbackList(feedbackList) {
    const container = document.getElementById('feedbackListContainer');
    container.innerHTML = '';
    
    feedbackList.forEach(item => {
        const sentimentColor = item.sentiment_label === 'positive' ? 'green' : 
                              item.sentiment_label === 'negative' ? 'red' : 'yellow';
        const sentimentIcon = item.sentiment_label === 'positive' ? 'smile' : 
                             item.sentiment_label === 'negative' ? 'frown' : 'meh';
        
        const div = document.createElement('div');
        div.className = 'bg-gray-50 rounded-lg p-4 border border-gray-200';
        div.innerHTML = `
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-user text-gray-400"></i>
                        <span class="font-medium text-gray-800">${item.user}</span>
                        <span class="text-xs bg-${sentimentColor}-100 text-${sentimentColor}-700 px-2 py-1 rounded-full flex items-center gap-1">
                            <i class="fas fa-${sentimentIcon}"></i>
                            ${item.sentiment_label}
                        </span>
                        ${item.rating ? `<span class="text-yellow-500">${'⭐'.repeat(item.rating)}</span>` : ''}
                    </div>
                    <p class="text-gray-700">${item.feedback}</p>
                    ${item.themes.length > 0 ? `
                        <div class="flex gap-1 mt-2">
                            ${item.themes.map(t => `<span class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded">${t}</span>`).join('')}
                        </div>
                    ` : ''}
                </div>
                <div class="text-right text-sm text-gray-500">
                    <p>Score: ${item.sentiment_score.toFixed(2)}</p>
                    <p class="text-xs">${(item.sentiment_confidence * 100).toFixed(0)}% confiance</p>
                </div>
            </div>
        `;
        container.appendChild(div);
    });
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSentimentModal();
    }
});
</script>
