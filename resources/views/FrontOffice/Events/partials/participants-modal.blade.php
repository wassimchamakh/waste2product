<div id="participantsModal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 opacity-0 pointer-events-none hidden">
    <div class="bg-white w-full h-full overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-primary to-success text-white py-6 px-6 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-1">Gestion des participants</h2>
                    <p class="opacity-90">{{ $event->title }}</p>
                </div>
                <button onclick="hideParticipantsModal()" class="bg-white/20 text-white p-2 rounded-full hover:bg-white/30 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <div class="p-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-primary to-success text-white rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total inscrits</p>
                            <p class="text-3xl font-bold">{{ $event->current_participants }}</p>
                        </div>
                        <div class="p-3 bg-white/20 rounded-lg">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Confirmés</p>
                            <p class="text-3xl font-bold text-success">{{ floor($event->current_participants * 0.8) }}</p>
                        </div>
                        <div class="p-3 bg-success/10 rounded-lg">
                            <i class="fas fa-check-circle text-2xl text-success"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">En attente</p>
                            <p class="text-3xl font-bold text-warning">{{ floor($event->current_participants * 0.15) }}</p>
                        </div>
                        <div class="p-3 bg-warning/10 rounded-lg">
                            <i class="fas fa-clock text-2xl text-warning"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Places restantes</p>
                            <p class="text-3xl font-bold text-primary">{{ $event->max_participants - $event->current_participants }}</p>
                        </div>
                        <div class="p-3 bg-primary/10 rounded-lg">
                            <i class="fas fa-plus-circle text-2xl text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" id="search-participants-modal" placeholder="Rechercher un participant..." 
                                   class="w-full pl-10 pr-4 py-2 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="md:w-48">
                        <select id="status-filter-modal" class="w-full px-3 py-2 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">Tous les statuts</option>
                            <option value="confirmed">✅ Confirmé</option>
                            <option value="pending">⏳ En attente</option>
                            <option value="attended">🎯 Présent</option>
                            <option value="cancelled">❌ Annulé</option>
                        </select>
                    </div>

                    <!-- Export -->
                    <div class="relative">
                        <button id="export-btn-modal" class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-orange-500 transition-colors">
                            <i class="fas fa-download mr-2"></i>Exporter
                        </button>
                        <div id="export-menu-modal" class="export-menu absolute right-0 top-12 bg-white border border-gray-200 rounded-lg shadow-lg py-2 min-w-48 z-10" style="transform: translateY(-10px); opacity: 0; visibility: hidden;">
                            <button class="w-full text-left px-4 py-2 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-csv mr-2 text-success"></i>CSV
                            </button>
                            <button class="w-full text-left px-4 py-2 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-excel mr-2 text-success"></i>Excel
                            </button>
                            <button class="w-full text-left px-4 py-2 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-pdf mr-2 text-accent"></i>PDF
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Results counter -->
                <div class="mt-4 text-sm text-gray-600">
                    <span id="results-count-modal">{{ $event->current_participants }} participants</span>
                </div>
            </div>

            <!-- Participants Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Table Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="checkbox" id="select-all-modal" class="text-primary focus:ring-primary">
                                <span class="ml-2 text-sm font-medium">Tout sélectionner</span>
                            </label>
                            <span id="selected-count-modal" class="text-sm text-gray-600 hidden">
                                <span id="selected-number-modal">0</span> sélectionné(s)
                            </span>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <button id="bulk-email-btn-modal" class="bulk-action-btn px-3 py-1 bg-primary text-white rounded text-sm hover:bg-green-600 transition-colors disabled:opacity-50" disabled>
                                <i class="fas fa-envelope mr-1"></i>Email groupé
                            </button>
                            <button id="bulk-confirm-btn-modal" class="bulk-action-btn px-3 py-1 bg-success text-white rounded text-sm hover:bg-green-600 transition-colors disabled:opacity-50" disabled>
                                <i class="fas fa-check mr-1"></i>Confirmer
                            </button>
                            <button id="bulk-present-btn-modal" class="bulk-action-btn px-3 py-1 bg-secondary text-white rounded text-sm hover:bg-orange-500 transition-colors disabled:opacity-50" disabled>
                                <i class="fas fa-user-check mr-1"></i>Marquer présent
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-12 px-6 py-3"></th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Participant
                                </th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date d'inscription
                                </th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Contact
                                </th>
                                <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" id="participants-tbody-modal">
                            <!-- Participants will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div id="empty-state-modal" class="text-center py-12 hidden">
                    <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-500 mb-2">Aucun participant trouvé</h3>
                    <p class="text-gray-400">Modifiez vos critères de recherche ou attendez de nouveaux inscrits.</p>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Affichage de <span id="showing-from-modal">1</span> à <span id="showing-to-modal">10</span> sur <span id="total-results-modal">{{ $event->current_participants }}</span> résultats
                </div>
                <div class="flex space-x-1">
                    <button class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="px-3 py-2 bg-primary text-white rounded-lg">1</button>
                    <button class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">2</button>
                    <button class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Email Modal -->
<div id="emailModalModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold">Envoyer un email</h3>
            <button onclick="hideEmailModalModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="email-form-modal">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Destinataires
                </label>
                <div id="recipients-list-modal" class="p-3 bg-gray-50 rounded-lg text-sm">
                    <!-- Recipients will be populated here -->
                </div>
            </div>

            <div class="mb-4">
                <label for="email-subject-modal" class="block text-sm font-medium text-gray-700 mb-2">
                    Sujet
                </label>
                <input type="text" id="email-subject-modal" value="[{{ $event->title }}] " 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>

            <div class="mb-6">
                <label for="email-message-modal" class="block text-sm font-medium text-gray-700 mb-2">
                    Message
                </label>
                <textarea id="email-message-modal" rows="6" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                          placeholder="Votre message aux participants..."></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideEmailModalModal()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i>Envoyer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Participants data for modal
    let participantsDataModal = [];
    let filteredParticipantsModal = [];
    let selectedParticipantsModal = new Set();
    let currentPageModal = 1;
    const participantsPerPageModal = 10;

    function loadParticipantsData() {
        // Generate sample participants data
        participantsDataModal = Array.from({length: {{ $event->current_participants }}}, (_, i) => {
            const statuses = ['confirmed', 'pending', 'attended', 'cancelled'];
            const rand = Math.random();
            const status = rand < 0.6 ? 'confirmed' : (rand < 0.85 ? 'pending' : (rand < 0.95 ? 'attended' : 'cancelled'));
            
            return {
                id: i + 1,
                name: `Participant ${i + 1}`,
                email: `participant${i + 1}@example.com`,
                phone: `98 ${String(i + 1).padStart(3, '0')} ${String((i + 1) * 2).padStart(3, '0')}`,
                registration_date: new Date(Date.now() - Math.random() * 30 * 24 * 60 * 60 * 1000).toISOString(),
                status: status,
                notes: Math.random() > 0.7 ? 'Personne très motivée' : null
            };
        });
        
        filteredParticipantsModal = [...participantsDataModal];
        renderParticipantsModal();
        setupEventListenersModal();
    }

    function setupEventListenersModal() {
        // Search
        document.getElementById('search-participants-modal').addEventListener('input', handleSearchModal);
        
        // Status filter
        document.getElementById('status-filter-modal').addEventListener('change', handleStatusFilterModal);
        
        // Select all
        document.getElementById('select-all-modal').addEventListener('change', handleSelectAllModal);
        
        // Export menu
        const exportBtn = document.getElementById('export-btn-modal');
        const exportMenu = document.getElementById('export-menu-modal');
        
        exportBtn.addEventListener('click', () => {
            const isVisible = exportMenu.style.visibility === 'visible';
            if (isVisible) {
                exportMenu.style.transform = 'translateY(-10px)';
                exportMenu.style.opacity = '0';
                exportMenu.style.visibility = 'hidden';
            } else {
                exportMenu.style.transform = 'translateY(0)';
                exportMenu.style.opacity = '1';
                exportMenu.style.visibility = 'visible';
            }
        });
        
        // Bulk actions
        document.getElementById('bulk-email-btn-modal').addEventListener('click', () => showEmailModalModal(Array.from(selectedParticipantsModal)));
        document.getElementById('bulk-confirm-btn-modal').addEventListener('click', () => bulkUpdateStatusModal('confirmed'));
        document.getElementById('bulk-present-btn-modal').addEventListener('click', () => bulkUpdateStatusModal('attended'));
        
        // Email form
        document.getElementById('email-form-modal').addEventListener('submit', sendBulkEmailModal);
    }

    function handleSearchModal() {
        const searchTerm = document.getElementById('search-participants-modal').value.toLowerCase();
        
        filteredParticipantsModal = participantsDataModal.filter(participant => 
            participant.name.toLowerCase().includes(searchTerm) ||
            participant.email.toLowerCase().includes(searchTerm)
        );
        
        applyStatusFilterModal();
        renderParticipantsModal();
        updateResultsCountModal();
    }

    function handleStatusFilterModal() {
        applyStatusFilterModal();
        renderParticipantsModal();
        updateResultsCountModal();
    }

    function applyStatusFilterModal() {
        const statusFilter = document.getElementById('status-filter-modal').value;
        
        if (statusFilter) {
            filteredParticipantsModal = filteredParticipantsModal.filter(participant => 
                participant.status === statusFilter
            );
        }
    }

    function handleSelectAllModal() {
        const selectAll = document.getElementById('select-all-modal');
        const checkboxes = document.querySelectorAll('.participant-checkbox-modal');
        
        if (selectAll.checked) {
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
                selectedParticipantsModal.add(parseInt(checkbox.value));
            });
        } else {
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            selectedParticipantsModal.clear();
        }
        
        updateBulkActionsModal();
    }

    function handleParticipantSelectModal(checkbox) {
        const participantId = parseInt(checkbox.value);
        
        if (checkbox.checked) {
            selectedParticipantsModal.add(participantId);
        } else {
            selectedParticipantsModal.delete(participantId);
        }
        
        // Update select all checkbox
        const totalCheckboxes = document.querySelectorAll('.participant-checkbox-modal').length;
        const selectAll = document.getElementById('select-all-modal');
        selectAll.checked = selectedParticipantsModal.size === totalCheckboxes;
        selectAll.indeterminate = selectedParticipantsModal.size > 0 && selectedParticipantsModal.size < totalCheckboxes;
        
        updateBulkActionsModal();
    }

    function updateBulkActionsModal() {
        const selectedCount = selectedParticipantsModal.size;
        const bulkActionBtns = document.querySelectorAll('.bulk-action-btn');
        const selectedCountEl = document.getElementById('selected-count-modal');
        const selectedNumberEl = document.getElementById('selected-number-modal');
        
        if (selectedCount > 0) {
            bulkActionBtns.forEach(btn => btn.disabled = false);
            selectedCountEl.classList.remove('hidden');
            selectedNumberEl.textContent = selectedCount;
        } else {
            bulkActionBtns.forEach(btn => btn.disabled = true);
            selectedCountEl.classList.add('hidden');
        }
    }

    function renderParticipantsModal() {
        const tbody = document.getElementById('participants-tbody-modal');
        const emptyState = document.getElementById('empty-state-modal');
        
        // Pagination
        const startIndex = (currentPageModal - 1) * participantsPerPageModal;
        const endIndex = startIndex + participantsPerPageModal;
        const paginatedParticipants = filteredParticipantsModal.slice(startIndex, endIndex);
        
        if (paginatedParticipants.length === 0) {
            tbody.classList.add('hidden');
            emptyState.classList.remove('hidden');
            return;
        }
        
        tbody.classList.remove('hidden');
        emptyState.classList.add('hidden');
        
        tbody.innerHTML = paginatedParticipants.map(participant => {
            const isSelected = selectedParticipantsModal.has(participant.id);
            
            return `
                <tr class="participant-row ${isSelected ? 'selected' : ''}" data-id="${participant.id}">
                    <td class="px-6 py-4">
                        <input type="checkbox" class="participant-checkbox-modal text-primary focus:ring-primary" 
                               value="${participant.id}" ${isSelected ? 'checked' : ''}
                               onchange="handleParticipantSelectModal(this)">
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="participant-avatar w-10 h-10 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                ${participant.name.charAt(0)}
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">${participant.name}</div>
                                <div class="text-sm text-gray-500">${participant.email}</div>
                                ${participant.notes ? `<div class="text-xs text-gray-400 italic">${participant.notes}</div>` : ''}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        ${formatDateModal(participant.registration_date)}
                    </td>
                    <td class="px-6 py-4">
                        <select class="status-select text-sm border-0 bg-transparent ${getStatusClassModal(participant.status)}" 
                                data-id="${participant.id}" onchange="updateParticipantStatusModal(this)">
                            <option value="pending" ${participant.status === 'pending' ? 'selected' : ''}>⏳ En attente</option>
                            <option value="confirmed" ${participant.status === 'confirmed' ? 'selected' : ''}>✅ Confirmé</option>
                            <option value="attended" ${participant.status === 'attended' ? 'selected' : ''}>🎯 Présent</option>
                            <option value="cancelled" ${participant.status === 'cancelled' ? 'selected' : ''}>❌ Annulé</option>
                        </select>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <div class="flex space-x-2">
                            <a href="mailto:${participant.email}" class="text-primary hover:text-green-600">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <a href="tel:${participant.phone}" class="text-primary hover:text-green-600">
                                <i class="fas fa-phone"></i>
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end space-x-2">
                            <button onclick="showEmailModalModal([${participant.id}])" 
                                    class="text-primary hover:text-green-600" title="Envoyer email">
                                <i class="fas fa-envelope"></i>
                            </button>
                            <button onclick="viewParticipantDetailsModal(${participant.id})" 
                                    class="text-secondary hover:text-orange-600" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="removeParticipantModal(${participant.id})" 
                                    class="text-accent hover:text-red-600" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
        
        updatePaginationModal();
    }

    function formatDateModal(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function getStatusClassModal(status) {
        switch (status) {
            case 'confirmed': return 'status-confirmed';
            case 'pending': return 'status-pending';
            case 'cancelled': return 'status-cancelled';
            case 'attended': return 'status-attended';
            default: return '';
        }
    }

    function updateResultsCountModal() {
        document.getElementById('results-count-modal').textContent = 
            `${filteredParticipantsModal.length} participant${filteredParticipantsModal.length > 1 ? 's' : ''}`;
    }

    function updatePaginationModal() {
        const total = filteredParticipantsModal.length;
        const startIndex = (currentPageModal - 1) * participantsPerPageModal;
        const endIndex = Math.min(startIndex + participantsPerPageModal, total);
        
        document.getElementById('showing-from-modal').textContent = startIndex + 1;
        document.getElementById('showing-to-modal').textContent = endIndex;
        document.getElementById('total-results-modal').textContent = total;
    }

    function updateParticipantStatusModal(select) {
        const participantId = parseInt(select.dataset.id);
        const newStatus = select.value;
        
        // Update in data
        const participant = participantsDataModal.find(p => p.id === participantId);
        if (participant) {
            participant.status = newStatus;
        }
        
        // Update visual feedback
        select.className = `status-select text-sm border-0 bg-transparent ${getStatusClassModal(newStatus)}`;
        
        // Show success message
        showNotificationModal(`Statut mis à jour pour le participant`, 'success');
    }

    function bulkUpdateStatusModal(status) {
        if (selectedParticipantsModal.size === 0) return;
        
        const count = selectedParticipantsModal.size;
        const statusText = {
            'confirmed': 'confirmé',
            'attended': 'présent',
            'cancelled': 'annulé'
        };
        
        if (confirm(`Marquer ${count} participant(s) comme ${statusText[status]} ?`)) {
            selectedParticipantsModal.forEach(participantId => {
                const participant = participantsDataModal.find(p => p.id === participantId);
                if (participant) {
                    participant.status = status;
                }
            });
            
            renderParticipantsModal();
            showNotificationModal(`${count} participant(s) mis à jour`, 'success');
            
            // Clear selection
            selectedParticipantsModal.clear();
            document.getElementById('select-all-modal').checked = false;
            updateBulkActionsModal();
        }
    }

    function showEmailModalModal(participantIds) {
        const participants = participantsDataModal.filter(p => participantIds.includes(p.id));
        const recipientsList = document.getElementById('recipients-list-modal');
        
        recipientsList.innerHTML = participants.map(p => 
            `<span class="inline-block bg-primary text-white px-2 py-1 rounded text-xs mr-2 mb-2">${p.name}</span>`
        ).join('');
        
        document.getElementById('emailModalModal').classList.remove('hidden');
        document.getElementById('emailModalModal').classList.add('flex');
        
        // Store participant IDs for sending
        document.getElementById('email-form-modal').dataset.participants = JSON.stringify(participantIds);
    }

    function hideEmailModalModal() {
        document.getElementById('emailModalModal').classList.add('hidden');
        document.getElementById('emailModalModal').classList.remove('flex');
        
        // Reset form
        document.getElementById('email-subject-modal').value = `[{{ $event->title }}] `;
        document.getElementById('email-message-modal').value = '';
    }

    function sendBulkEmailModal(e) {
        e.preventDefault();
        
        const participantIds = JSON.parse(e.target.dataset.participants);
        const subject = document.getElementById('email-subject-modal').value;
        const message = document.getElementById('email-message-modal').value;
        
        if (!subject.trim() || !message.trim()) {
            alert('Veuillez remplir le sujet et le message.');
            return;
        }
        
        // Show loading state
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Envoi...';
        submitBtn.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            hideEmailModalModal();
            showNotificationModal(`Email envoyé à ${participantIds.length} participant(s)`, 'success');
            
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 2000);
    }

    function removeParticipantModal(participantId) {
        const participant = participantsDataModal.find(p => p.id === participantId);
        
        if (confirm(`Supprimer ${participant.name} de cet événement ?`)) {
            // Remove from data
            const index = participantsDataModal.findIndex(p => p.id === participantId);
            if (index > -1) {
                participantsDataModal.splice(index, 1);
            }
            
            // Update filtered data
            const filteredIndex = filteredParticipantsModal.findIndex(p => p.id === participantId);
            if (filteredIndex > -1) {
                filteredParticipantsModal.splice(filteredIndex, 1);
            }
            
            // Remove from selection
            selectedParticipantsModal.delete(participantId);
            
            renderParticipantsModal();
            updateResultsCountModal();
            updateBulkActionsModal();
            showNotificationModal(`${participant.name} supprimé de l'événement`, 'success');
        }
    }

    function viewParticipantDetailsModal(participantId) {
        const participant = participantsDataModal.find(p => p.id === participantId);
        alert(`Détails de ${participant.name}:\n\nEmail: ${participant.email}\nTéléphone: ${participant.phone}\nInscription: ${formatDateModal(participant.registration_date)}\nStatut: ${participant.status}`);
    }

    function showNotificationModal(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-20 right-4 z-50 p-4 rounded-lg shadow-lg text-white ${
            type === 'success' ? 'bg-success' : 'bg-accent'
        } transform translate-x-full transition-transform duration-300`;
        
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
</script>