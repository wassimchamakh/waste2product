@extends('FrontOffice.layout1.app')

@section('title', 'Scanner QR - ' . $event->title)

@push('styles')
<style>
    #reader {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
    }
    
    #reader video {
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .scan-result {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            transform: translateX(-20px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .success-flash {
        animation: successPulse 0.5s ease-out;
    }

    @keyframes successPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .scanner-overlay {
        position: relative;
    }

    .scanner-overlay::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80%;
        height: 80%;
        border: 2px solid #06D6A0;
        border-radius: 12px;
        pointer-events: none;
    }

    .stat-card {
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-qrcode text-primary mr-3"></i>
                        Scanner QR
                    </h1>
                    <p class="text-gray-600 mt-1">{{ $event->title }}</p>
                </div>
                <a href="{{ route('Events.show', $event->id) }}" 
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Retour à l'événement
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="stat-card bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total Inscrits</p>
                            <p class="text-2xl font-bold text-gray-900" id="total-registered">{{ $stats->total_confirmed }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Présents</p>
                            <p class="text-2xl font-bold text-green-600" id="total-attended">{{ $stats->attended }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">En Attente</p>
                            <p class="text-2xl font-bold text-yellow-600" id="total-pending">{{ $stats->total_confirmed - $stats->attended }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Taux de Présence</p>
                            <p class="text-2xl font-bold text-purple-600" id="attendance-rate">
                                {{ $stats->total_confirmed > 0 ? round(($stats->attended / $stats->total_confirmed) * 100) : 0 }}%
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-chart-pie text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Scanner Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    
                    <!-- Camera Scanner -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-camera text-primary mr-2"></i>
                                Scanner Caméra
                            </h3>
                            <button id="toggle-camera" 
                                    class="bg-primary hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-video mr-2"></i>
                                <span id="camera-status">Démarrer</span>
                            </button>
                        </div>

                        <div id="reader" class="scanner-overlay hidden"></div>
                        
                        <div id="camera-message" class="text-center text-gray-500 py-8">
                            <i class="fas fa-camera text-6xl mb-4 text-gray-300"></i>
                            <p>Cliquez sur "Démarrer" pour activer la caméra</p>
                        </div>
                    </div>

                    <!-- Manual Entry -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-keyboard text-primary mr-2"></i>
                            Entrée Manuelle
                        </h3>
                        <form id="manual-form" class="flex gap-2">
                            <input type="text" 
                                   id="manual-ticket" 
                                   placeholder="TKT-2025-0006-0104" 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   pattern="TKT-\d{4}-\d{4}-\d{4}">
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                                <i class="fas fa-search mr-2"></i>Vérifier
                            </button>
                        </form>
                        <p class="text-xs text-gray-500 mt-2">
                            Format: TKT-ANNÉE-EVENTID-PARTICIPANTID
                        </p>
                    </div>

                </div>
            </div>

            <!-- Results Section -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-history text-primary mr-2"></i>
                        Scans Récents
                    </h3>

                    <div id="scan-results" class="space-y-3 max-h-[600px] overflow-y-auto">
                        <div class="text-center text-gray-400 py-8">
                            <i class="fas fa-qrcode text-4xl mb-2"></i>
                            <p class="text-sm">Aucun scan pour le moment</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Recent Attendees List -->
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-list text-primary mr-2"></i>
                Participants Présents ({{ $stats->attended }})
            </h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Billet</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure d'arrivée</th>
                        </tr>
                    </thead>
                    <tbody id="attendees-list" class="bg-white divide-y divide-gray-200">
                        @forelse($attendedParticipants as $participant)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-green-600 font-semibold">{{ substr($participant->user->name ?? 'U', 0, 1) }}</span>
                                        </div>
                                        <div class="font-medium text-gray-900">{{ $participant->user->name ?? 'Utilisateur inconnu' }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $participant->user->email ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        {{ \App\Helpers\TicketHelper::generateTicketNumber($participant) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $participant->updated_at->format('H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    Aucun participant scanné pour le moment
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Sound Effects (hidden audio elements) -->
<audio id="success-sound" preload="auto">
    <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIGGe87OicTgwPUKXh8LNmHAU2jdXwyH0tBSF0xPDcmEgLElyx6OyrWBUJQprc8sFtJAUsgs/y2Yk2CBdluu3ol04MDk+l4fC0Zx0FOJTX8sl9LgUgdMPw25ZICxFXrujrrFkVCj+Z2/PAaiUELIHP8tmJNggXZbrs6ZhPDA5PpeDwtGgeBDmV1/LIfi0EHnLB79yaSgwQVK3n7K9cFgpBmNryvmoiBC2C0PLYizUIGGW56+mYTgsMT6Xh8LRoHwU5ltjxyX4uBB9ywO/bmEkMD1Gs5+yvXBYKQZja8r1rJAQtgtDy2Is1CBdluOvpmE4LDE+l4fC1aB8FOZfZ8sl+LwQfccDv25hKDBBQrOfsr10WCkCY2fK9aycELYLQ8tiLNQgYZbjs6ZhOCw1QpuHwtWgeBTmX2fLJfS8DHnHA79yYSgwQUqzn7K9dFglAmNnyvWsnBC2C0PLYizQJF2O46+mZTgsMUKbh8LRoHgU6l9jyyX0vAx5xwO/bmEoMEFGr5+ywXhcJQJfZ8r5rJwQtgtDy2Ys1CRdju+vpmlEKC1Cl4fC1aR8FOJfZ8sp/LgQfccDv25hJCw9QrOfsr14XCT+Y2fK+aycEL4HQ8tmLNgkYY7ns6JlPDA5PpeDwtmlABTiX2fLJfi4EH3HA79uYSgsPUKzn7a9eFgo+mNjyv2wnBC+B0PLZizYJGGO56+iZTwwOT6Xg8LZpHwU4l9nyyX4uBB9xwO/bmEoLD1Cs5+yvXhYKPpjY8r9rJwQvgdDy2Ys2CRhjuevomU8MDk6k4PC2aR8FOJfZ8sl+LgQfccDv25hKCw9QrOfsr14WCj6Y2PK/aycEL4HQ8tmLNgkYY7nr6JlPDA5OpODwtmkfBTiX2fLJfi4EH3HA79uYSgsPUKzn7K9eFgo+mNjyv2snBC+B0PLZizYJGGO56+iZTwwOTqTg8LZpHwU4l9nyyX4uBB9xwO/bmEoLD1Cs5+yvXhYKPpjY8r9rJwQvgdDy2Ys2CRhjuevomU8MDk6k4PC2aR8FOJfZ8sl+LgQfccDv25hKCw9QrOfsr14WCj6Y2PK/aycEL4HQ8tmLNgkYY7nr6JlPDA5OpODwtmkfBTiX2fLJfi4EH3HA79uYSgsPUKzn7K9eFgo+mNjyv2snBC+B0PLZizYJGGO56+iZTwwOTqTg8LZpHwU4l9nyyX4uBB9xwO/bmEoLD1Cs5+yvXhYK" type="audio/wav">
</audio>
<audio id="error-sound" preload="auto">
    <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAABhZGdMUkxDU1pYXFNNSkpRUE1MS0xNTExLSUpKSkpIRkVFRENCQkJCQkFBQUBAQEBAQD8/Pz8+Pj0+Pz4+Pj0+PT0+PTw8PDw7Ozs7Ozs6Ojo6OTk5OTg4ODg3Nzc3NjY2NjU1NTQzMzMzMjIyMjExMTAwMC8vLi4tLSwrKikpKCcmJSQjIiAfHh0cGxoZGBcWFRQTEhEQDw4NDAsKCQgHBgUEAwIBAAAAAP/+/fz7+vn49/b19PPy8fDv7u3s6+rp6Ofm5eTj4uHg397d3Nva2djX1tXU09LR0M/OzczLysnIx8bFxMPCwcC/vr28u7q5uLe2tbSzsrGwr66trKuqqainpqWko6KhoJ+enZybmpmYl5aVlJOSkZCPjo2Mi4qJiIeGhYSDgoGAf359fHt6eXh3dnV0c3JxcG9ubWxramloZ2ZlZGNiYWBfXl1cW1pZWFdWVVRTUlFQT05NTEtKSUhHRkVEQ0JBQD8+PTw7Ojk4NzY1NDMyMTAvLi0sKyopKCcmJSQjIiEgHx4dHBsaGRgXFhUUExIREA8ODQwLCgkIBwYFBAMCAQAA/v39/Pv6+fj39vX08/Lx8O/u7ezr6uno5+bl5OPi4eDf3t3c29rZ2NfW1dTT0tHQz87NzMvKycjHxsXEw8LBwL++vby7urm4t7a1tLOysbCvrq2sq6qpqKempaSjoqGgn56dnJuamZiXlpWUk5KRkI+OjYyLiomIh4aFhIOCgYB/fn18e3p5eHd2dXRzcnFwb25tbGtqaWhnZmVkY2JhYF9eXVxbWllYV1ZVVFNSUVBPTk1MS0pJSEdGRURDQkFAPz49PDs6OTg3NjU0MzIxMC8uLSwrKikoJyYlJCMiISAfHh0cGxoZGBcWFRQTEhEQDw4NDAsKCQgHBgUEAwIBAA==" type="audio/wav">
</audio>
@endsection

@push('scripts')
<!-- html5-qrcode library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
let html5QrCode;
let isScannerActive = false;
let scannedTickets = new Set();

// Initialize scanner
function initScanner() {
    html5QrCode = new Html5Qrcode("reader");
}

// Toggle camera
document.getElementById('toggle-camera').addEventListener('click', async function() {
    if (!isScannerActive) {
        await startScanner();
    } else {
        await stopScanner();
    }
});

// Start scanner
async function startScanner() {
    try {
        document.getElementById('camera-message').classList.add('hidden');
        document.getElementById('reader').classList.remove('hidden');
        
        await html5QrCode.start(
            { facingMode: "environment" }, // Use back camera
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            onScanSuccess,
            onScanFailure
        );
        
        isScannerActive = true;
        document.getElementById('camera-status').textContent = 'Arrêter';
        document.getElementById('toggle-camera').classList.remove('bg-primary', 'hover:bg-green-600');
        document.getElementById('toggle-camera').classList.add('bg-red-500', 'hover:bg-red-600');
        
    } catch (err) {
        console.error('Scanner start error:', err);
        showNotification('Erreur caméra: ' + err, 'error');
    }
}

// Stop scanner
async function stopScanner() {
    try {
        await html5QrCode.stop();
        
        isScannerActive = false;
        document.getElementById('reader').classList.add('hidden');
        document.getElementById('camera-message').classList.remove('hidden');
        document.getElementById('camera-status').textContent = 'Démarrer';
        document.getElementById('toggle-camera').classList.remove('bg-red-500', 'hover:bg-red-600');
        document.getElementById('toggle-camera').classList.add('bg-primary', 'hover:bg-green-600');
        
    } catch (err) {
        console.error('Scanner stop error:', err);
    }
}

// On scan success
function onScanSuccess(decodedText, decodedResult) {
    console.log('QR Code detected:', decodedText);
    
    // Extract ticket number from URL
    const ticketMatch = decodedText.match(/TKT-\d{4}-\d{4}-\d{4}/);
    if (ticketMatch) {
        verifyTicket(ticketMatch[0], true);
    } else {
        showNotification('QR code invalide', 'error');
    }
}

// On scan failure (ignore, happens frequently)
function onScanFailure(error) {
    // Ignore scanning failures (too noisy)
}

// Verify ticket
async function verifyTicket(ticketNumber, fromCamera = false) {
    // Prevent duplicate scans within 3 seconds
    if (scannedTickets.has(ticketNumber)) {
        return;
    }
    scannedTickets.add(ticketNumber);
    setTimeout(() => scannedTickets.delete(ticketNumber), 3000);
    
    try {
        const response = await fetch(`/events/{{ $event->id }}/ticket/verify/${ticketNumber}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            playSound('success');
            addScanResult(data.data, 'success');
            updateStats(1);
            addToAttendeesList(data.data);
            
            if (fromCamera) {
                showNotification(`✓ ${data.data.participant_name} vérifié(e)`, 'success');
            }
        } else {
            playSound('error');
            addScanResult({ ticket_number: ticketNumber, error: data.message }, 'error');
            showNotification(data.message, 'error');
        }
        
    } catch (error) {
        console.error('Verification error:', error);
        playSound('error');
        showNotification('Erreur de vérification', 'error');
    }
}

// Add scan result to list
function addScanResult(data, type) {
    const resultsContainer = document.getElementById('scan-results');
    
    // Remove placeholder if exists
    const placeholder = resultsContainer.querySelector('.text-center');
    if (placeholder) {
        placeholder.remove();
    }
    
    const resultDiv = document.createElement('div');
    resultDiv.className = `scan-result p-4 rounded-lg border-l-4 ${type === 'success' ? 'bg-green-50 border-green-500' : 'bg-red-50 border-red-500'}`;
    
    if (type === 'success') {
        resultDiv.innerHTML = `
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-1">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <span class="font-semibold text-gray-900">${data.participant_name}</span>
                    </div>
                    <p class="text-sm text-gray-600">${data.participant_email}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded">${data.ticket_number}</span>
                    </p>
                    ${data.already_attended ? '<p class="text-xs text-yellow-600 mt-1">⚠️ Déjà scanné</p>' : ''}
                </div>
                <span class="text-xs text-gray-400">${new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}</span>
            </div>
        `;
    } else {
        resultDiv.innerHTML = `
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-1">
                        <i class="fas fa-times-circle text-red-600 mr-2"></i>
                        <span class="font-semibold text-gray-900">Erreur</span>
                    </div>
                    <p class="text-sm text-gray-600">${data.error}</p>
                    <p class="text-xs text-gray-500 mt-1">${data.ticket_number}</p>
                </div>
                <span class="text-xs text-gray-400">${new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}</span>
            </div>
        `;
    }
    
    resultsContainer.insertBefore(resultDiv, resultsContainer.firstChild);
    
    // Limit to 10 results
    while (resultsContainer.children.length > 10) {
        resultsContainer.removeChild(resultsContainer.lastChild);
    }
}

// Update statistics
function updateStats(increment) {
    const attendedEl = document.getElementById('total-attended');
    const pendingEl = document.getElementById('total-pending');
    const rateEl = document.getElementById('attendance-rate');
    
    const currentAttended = parseInt(attendedEl.textContent);
    const newAttended = currentAttended + increment;
    attendedEl.textContent = newAttended;
    attendedEl.parentElement.parentElement.classList.add('success-flash');
    setTimeout(() => attendedEl.parentElement.parentElement.classList.remove('success-flash'), 500);
    
    const currentPending = parseInt(pendingEl.textContent);
    pendingEl.textContent = Math.max(0, currentPending - increment);
    
    const total = parseInt(document.getElementById('total-registered').textContent);
    const newRate = total > 0 ? Math.round((newAttended / total) * 100) : 0;
    rateEl.textContent = newRate + '%';
}

// Add to attendees list
function addToAttendeesList(data) {
    const list = document.getElementById('attendees-list');
    
    // Remove placeholder if exists
    const placeholder = list.querySelector('td[colspan="4"]');
    if (placeholder) {
        placeholder.parentElement.remove();
    }
    
    const row = document.createElement('tr');
    row.className = 'scan-result';
    row.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                    <span class="text-green-600 font-semibold">${data.participant_name.charAt(0)}</span>
                </div>
                <div class="font-medium text-gray-900">${data.participant_name}</div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${data.participant_email}</td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                ${data.ticket_number}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}</td>
    `;
    
    list.insertBefore(row, list.firstChild);
}

// Manual form submission
document.getElementById('manual-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const ticketInput = document.getElementById('manual-ticket');
    const ticketNumber = ticketInput.value.trim();
    
    if (ticketNumber) {
        await verifyTicket(ticketNumber, false);
        ticketInput.value = '';
    }
});

// Play sound
function playSound(type) {
    const audio = document.getElementById(type + '-sound');
    if (audio) {
        audio.currentTime = 0;
        audio.play().catch(e => console.log('Sound play failed:', e));
    }
}

// Show notification
function showNotification(message, type) {
    // Create toast notification
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : 'times'}-circle mr-3 text-xl"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initScanner();
    
    // Auto-focus manual input
    document.getElementById('manual-ticket').focus();
});

// Cleanup on page unload
window.addEventListener('beforeunload', async function() {
    if (isScannerActive) {
        await stopScanner();
    }
});
</script>
@endpush
