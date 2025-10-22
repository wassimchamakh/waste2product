<!-- Participants Management Dialog Modal -->
<div id="participantsModal" class="modal fixed inset-0 bg-black/50 backdrop-blur-sm z-50 opacity-0 pointer-events-none hidden items-center justify-center p-4 transition-all duration-300">
    <!-- Dialog Container -->
    <div class="bg-white rounded-2xl shadow-2xl max-w-6xl w-full max-h-[85vh] overflow-hidden transform scale-95 transition-transform duration-300" id="participantsDialog">
        
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-primary to-success text-white py-5 px-6 flex items-center justify-between border-b border-white/20">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold">Gestion des participants</h2>
                    <p class="text-sm opacity-90">{{ $event->title }}</p>
                </div>
            </div>
            <button onclick="hideParticipantsModal()" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Modal Content with Scroll -->
        <div class="overflow-y-auto" style="max-height: calc(85vh - 80px);">
            <div class="p-6">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-r from-primary to-success text-white rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs opacity-90 mb-1">Total inscrits</p>
                                <p class="text-2xl font-bold">{{ $stats->current_participants }}</p>
                            </div>
                            <div class="p-2 bg-white/20 rounded-lg">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Confirmés</p>
                                <p class="text-2xl font-bold text-success">{{ $stats->confirmed }}</p>
                            </div>
                            <div class="p-2 bg-success/10 rounded-lg">
                                <i class="fas fa-check-circle text-xl text-success"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-600 mb-1">En attente</p>
                                <p class="text-2xl font-bold text-warning">{{ $stats->registered }}</p>
                            </div>
                            <div class="p-2 bg-warning/10 rounded-lg">
                                <i class="fas fa-clock text-xl text-warning"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Places restantes</p>
                                <p class="text-2xl font-bold text-primary">{{ $stats->remaining_seats }}</p>
                            </div>
                            <div class="p-2 bg-primary/10 rounded-lg">
                                <i class="fas fa-plus-circle text-xl text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filters -->
                <div class="bg-gray-50 rounded-xl p-4 mb-6">
                    <div class="flex flex-col md:flex-row gap-3">
                        <!-- Search -->
                        <div class="flex-1">
                            <div class="relative">
                                <input type="text" id="search-participants-modal" placeholder="Rechercher un participant..." 
                                       class="w-full pl-10 pr-4 py-2 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="md:w-44">
                            <select id="status-filter-modal" class="w-full px-3 py-2 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Tous les statuts</option>
                                <option value="confirmed">✅ Confirmé</option>
                                <option value="registered">⏳ En attente</option>
                                <option value="attended">🎯 Présent</option>
                                <option value="cancelled">❌ Annulé</option>
                            </select>
                        </div>

                        @if($event->is_paid)
                        <!-- Payment Status Filter -->
                        <div class="md:w-44">
                            <select id="payment-status-filter-modal" class="w-full px-3 py-2 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Tous les paiements</option>
                                <option value="completed">✅ Payé</option>
                                <option value="pending_payment">⏳ En attente</option>
                                <option value="failed">❌ Échoué</option>
                                <option value="refunded">🔄 Remboursé</option>
                            </select>
                        </div>
                        @endif

                        <!-- Export -->
                        <div class="relative">
                            <button id="export-btn-modal" class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-orange-500 transition-colors text-sm">
                                <i class="fas fa-download mr-2"></i>Exporter
                            </button>
                            <div id="export-menu-modal" class="export-menu absolute right-0 top-12 bg-white border border-gray-200 rounded-lg shadow-lg py-2 min-w-40 z-10" style="transform: translateY(-10px); opacity: 0; visibility: hidden; transition: all 0.2s;">
                                <button onclick="exportParticipants('csv')" class="w-full text-left px-4 py-2 hover:bg-gray-50 transition-colors text-sm">
                                    <i class="fas fa-file-csv mr-2 text-success"></i>CSV
                                </button>
                                <button onclick="exportParticipants('excel')" class="w-full text-left px-4 py-2 hover:bg-gray-50 transition-colors text-sm">
                                    <i class="fas fa-file-excel mr-2 text-success"></i>Excel
                                </button>
                                <button onclick="exportParticipants('pdf')" class="w-full text-left px-4 py-2 hover:bg-gray-50 transition-colors text-sm">
                                    <i class="fas fa-file-pdf mr-2 text-accent"></i>PDF
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Results counter -->
                    <div class="mt-3 text-xs text-gray-600">
                        <span id="results-count-modal">{{ $stats->current_participants }} participants</span>
                    </div>
                </div>

                <!-- Participants Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Table Header with Actions -->
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" id="select-all-modal" class="text-primary focus:ring-primary rounded">
                                    <span class="ml-2 text-sm font-medium">Tout sélectionner</span>
                                </label>
                                <span id="selected-count-modal" class="text-sm text-gray-600 hidden">
                                    <span id="selected-number-modal">0</span> sélectionné(s)
                                </span>
                            </div>
                            
                            <!-- UPDATED BULK ACTIONS WITH DELETE BUTTON -->
                            <div class="flex items-center space-x-2">
                                <button id="bulk-email-btn-modal" class="bulk-action-btn px-3 py-1.5 bg-primary text-white rounded-lg text-xs hover:bg-green-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                    <i class="fas fa-envelope mr-1"></i>Email
                                </button>
                                <button id="bulk-confirm-btn-modal" class="bulk-action-btn px-3 py-1.5 bg-success text-white rounded-lg text-xs hover:bg-green-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                    <i class="fas fa-check mr-1"></i>Confirmer
                                </button>
                                <button id="bulk-present-btn-modal" class="bulk-action-btn px-3 py-1.5 bg-secondary text-white rounded-lg text-xs hover:bg-orange-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                    <i class="fas fa-user-check mr-1"></i>Présent
                                </button>
                                <button id="bulk-delete-btn-modal" class="bulk-action-btn px-3 py-1.5 bg-accent text-white rounded-lg text-xs hover:bg-red-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                    <i class="fas fa-trash mr-1"></i>Supprimer
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Table Content -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="w-10 px-4 py-2"></th>
                                    <th class="text-left px-4 py-2 text-xs font-semibold text-gray-600 uppercase">
                                        Participant
                                    </th>
                                    <th class="text-left px-4 py-2 text-xs font-semibold text-gray-600 uppercase">
                                        Inscription
                                    </th>
                                    <th class="text-left px-4 py-2 text-xs font-semibold text-gray-600 uppercase">
                                        Statut
                                    </th>
                                    @if($event->is_paid)
                                    <th class="text-left px-4 py-2 text-xs font-semibold text-gray-600 uppercase">
                                        Paiement
                                    </th>
                                    <th class="text-left px-4 py-2 text-xs font-semibold text-gray-600 uppercase">
                                        Montant
                                    </th>
                                    <th class="text-left px-4 py-2 text-xs font-semibold text-gray-600 uppercase">
                                        Facture
                                    </th>
                                    @endif
                                    <th class="text-left px-4 py-2 text-xs font-semibold text-gray-600 uppercase">
                                        Contact
                                    </th>
                                    <th class="text-right px-4 py-2 text-xs font-semibold text-gray-600 uppercase">
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
                        <i class="fas fa-users text-5xl text-gray-300 mb-3"></i>
                        <h3 class="text-lg font-medium text-gray-500 mb-1">Aucun participant trouvé</h3>
                        <p class="text-sm text-gray-400">Modifiez vos critères de recherche</p>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4 flex items-center justify-between text-sm">
                    <div class="text-gray-600">
                        <span id="showing-from-modal">1</span> - <span id="showing-to-modal">10</span> sur <span id="total-results-modal">{{ $stats->current_participants }}</span>
                    </div>
                    <div class="flex space-x-1">
                        <button class="px-3 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50" disabled>
                            <i class="fas fa-chevron-left text-xs"></i>
                        </button>
                        <button class="px-3 py-1.5 bg-primary text-white rounded-lg text-xs">1</button>
                        <button class="px-3 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-xs">2</button>
                        <button class="px-3 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Email Modal (Nested Dialog) -->
<div id="emailModalModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold">Envoyer un email</h3>
            <button onclick="hideEmailModalModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="email-form-modal">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Destinataires</label>
                <div id="recipients-list-modal" class="p-3 bg-gray-50 rounded-lg text-sm min-h-[40px]">
                    <!-- Recipients will be populated here -->
                </div>
            </div>

            <div class="mb-4">
                <label for="email-subject-modal" class="block text-sm font-medium text-gray-700 mb-2">Sujet</label>
                <input type="text" id="email-subject-modal" value="[{{ $event->title }}] " 
                       class="w-full px-3 py-2 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>

            <div class="mb-6">
                <label for="email-message-modal" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                <textarea id="email-message-modal" rows="5" 
                          class="w-full px-3 py-2 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                          placeholder="Votre message aux participants..."></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideEmailModalModal()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-green-600 transition-colors text-sm">
                    <i class="fas fa-paper-plane mr-2"></i>Envoyer
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Enhanced modal animations */
    #participantsModal.show {
        opacity: 1 !important;
        pointer-events: auto !important;
    }
    
    #participantsModal.show #participantsDialog {
        transform: scale(1) !important;
    }
    
    /* Scrollbar styling */
    #participantsModal ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    #participantsModal ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    #participantsModal ::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 4px;
    }
    
    #participantsModal ::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }
    
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
</style>

<script>
    // Participants data for modal - USE REAL DATA FROM BACKEND
    let participantsDataModal = @json($participantsData ?? []); 
    let filteredParticipantsModal = [];
    let selectedParticipantsModal = new Set();
    let currentPageModal = 1;
    const participantsPerPageModal = 10;

    function loadParticipantsData() {
        filteredParticipantsModal = [...participantsDataModal];
        renderParticipantsModal();
        setupEventListenersModal();
    }

    function setupEventListenersModal() {
        document.getElementById('search-participants-modal')?.addEventListener('input', handleSearchModal);
        document.getElementById('status-filter-modal')?.addEventListener('change', handleStatusFilterModal);
        @if($event->is_paid)
        document.getElementById('payment-status-filter-modal')?.addEventListener('change', handleStatusFilterModal);
        @endif
        document.getElementById('select-all-modal')?.addEventListener('change', handleSelectAllModal);
        
        const exportBtn = document.getElementById('export-btn-modal');
        const exportMenu = document.getElementById('export-menu-modal');
        
        if (exportBtn && exportMenu) {
            exportBtn.addEventListener('click', (e) => {
                e.stopPropagation();
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
            
            document.addEventListener('click', (e) => {
                if (!exportBtn.contains(e.target) && !exportMenu.contains(e.target)) {
                    exportMenu.style.transform = 'translateY(-10px)';
                    exportMenu.style.opacity = '0';
                    exportMenu.style.visibility = 'hidden';
                }
            });
        }
        
        document.getElementById('bulk-email-btn-modal')?.addEventListener('click', () => showEmailModalModal(Array.from(selectedParticipantsModal)));
        document.getElementById('bulk-confirm-btn-modal')?.addEventListener('click', () => bulkUpdateStatusModal('confirmed'));
        document.getElementById('bulk-present-btn-modal')?.addEventListener('click', () => bulkUpdateStatusModal('attended'));
        document.getElementById('bulk-delete-btn-modal')?.addEventListener('click', bulkDeleteParticipantsModal);
        document.getElementById('email-form-modal')?.addEventListener('submit', sendBulkEmailModal);
    }

    // Export functionality (unchanged)
    function exportParticipants(format) {
        const exportMenu = document.getElementById('export-menu-modal');
        if (exportMenu) {
            exportMenu.style.transform = 'translateY(-10px)';
            exportMenu.style.opacity = '0';
            exportMenu.style.visibility = 'hidden';
        }

        const participantsToExport = filteredParticipantsModal.length > 0 ? filteredParticipantsModal : participantsDataModal;
        
        if (participantsToExport.length === 0) {
            showNotificationModal('Aucun participant à exporter', 'error');
            return;
        }

        switch(format) {
            case 'csv': exportToCSV(participantsToExport); break;
            case 'excel': exportToExcel(participantsToExport); break;
            case 'pdf': exportToPDF(participantsToExport); break;
        }
    }

    function exportToCSV(participants) {
        try {
            @if($event->is_paid)
            const headers = ['Nom', 'Email', 'Téléphone', 'Date d\'inscription', 'Statut', 'Paiement', 'Montant', 'Facture', 'Date de paiement'];
            const csvContent = [
                headers.join(','),
                ...participants.map(participant => [
                    `"${participant.name}"`,
                    `"${participant.email}"`,
                    `"${participant.phone}"`,
                    `"${formatDateForExport(participant.registration_date)}"`,
                    `"${getStatusLabel(participant.status)}"`,
                    `"${getPaymentStatusLabel(participant.payment_status)}"`,
                    `"${participant.amount_paid || '0.00'} EUR"`,
                    `"${participant.invoice_number || 'N/A'}"`,
                    `"${participant.payment_completed_at ? formatDateForExport(participant.payment_completed_at) : 'N/A'}"`
                ].join(','))
            ].join('\n');
            @else
            const headers = ['Nom', 'Email', 'Téléphone', 'Date d\'inscription', 'Statut'];
            const csvContent = [
                headers.join(','),
                ...participants.map(participant => [
                    `"${participant.name}"`,
                    `"${participant.email}"`,
                    `"${participant.phone}"`,
                    `"${formatDateForExport(participant.registration_date)}"`,
                    `"${getStatusLabel(participant.status)}"`
                ].join(','))
            ].join('\n');
            @endif

            downloadFile(csvContent, 'participants.csv', 'text/csv;charset=utf-8;');
            showNotificationModal('Export CSV terminé avec succès');
        } catch (error) {
            showNotificationModal('Erreur lors de l\'export CSV', 'error');
        }
    }

    function exportToExcel(participants) {
        try {
            @if($event->is_paid)
            let excelContent = `
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Date d'inscription</th>
                            <th>Statut</th>
                            <th>Paiement</th>
                            <th>Montant</th>
                            <th>Facture</th>
                            <th>Date de paiement</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            participants.forEach(participant => {
                excelContent += `
                    <tr>
                        <td>${participant.name}</td>
                        <td>${participant.email}</td>
                        <td>${participant.phone}</td>
                        <td>${formatDateForExport(participant.registration_date)}</td>
                        <td>${getStatusLabel(participant.status)}</td>
                        <td>${getPaymentStatusLabel(participant.payment_status)}</td>
                        <td>${participant.amount_paid || '0.00'} EUR</td>
                        <td>${participant.invoice_number || 'N/A'}</td>
                        <td>${participant.payment_completed_at ? formatDateForExport(participant.payment_completed_at) : 'N/A'}</td>
                    </tr>
                `;
            });
            @else
            let excelContent = `
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Date d'inscription</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            participants.forEach(participant => {
                excelContent += `
                    <tr>
                        <td>${participant.name}</td>
                        <td>${participant.email}</td>
                        <td>${participant.phone}</td>
                        <td>${formatDateForExport(participant.registration_date)}</td>
                        <td>${getStatusLabel(participant.status)}</td>
                    </tr>
                `;
            });
            @endif

            excelContent += '</tbody></table>';

            downloadFile(excelContent, 'participants.xls', 'application/vnd.ms-excel');
            showNotificationModal('Export Excel terminé avec succès');
        } catch (error) {
            showNotificationModal('Erreur lors de l\'export Excel', 'error');
        }
    }

    function exportToPDF(participants) {
        try {
            const printWindow = window.open('', '_blank');
            const eventTitle = '{{ $event->title ?? "Événement" }}';
            
            @if($event->is_paid)
            let pdfContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Liste des participants - ${eventTitle}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h1 { color: #2E7D47; border-bottom: 2px solid #2E7D47; padding-bottom: 10px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 11px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f8f9fa; font-weight: bold; }
                        tr:nth-child(even) { background-color: #f8f9fa; }
                        .status-confirmed { color: #06D6A0; font-weight: bold; }
                        .status-registered { color: #F4A261; font-weight: bold; }
                        .status-attended { color: #2E7D47; font-weight: bold; }
                        .status-cancelled { color: #E76F51; font-weight: bold; }
                        .payment-completed { color: #06D6A0; font-weight: bold; }
                        .payment-pending_payment { color: #F4A261; font-weight: bold; }
                        .payment-failed { color: #E76F51; font-weight: bold; }
                        .payment-refunded { color: #9333ea; font-weight: bold; }
                        .footer { margin-top: 30px; text-align: center; color: #666; font-size: 12px; }
                    </style>
                </head>
                <body>
                    <h1>Liste des participants</h1>
                    <p><strong>Événement:</strong> ${eventTitle}</p>
                    <p><strong>Total des participants:</strong> ${participants.length}</p>
                    <p><strong>Date d'export:</strong> ${new Date().toLocaleDateString('fr-FR')}</p>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Inscription</th>
                                <th>Statut</th>
                                <th>Paiement</th>
                                <th>Montant</th>
                                <th>Facture</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            participants.forEach(participant => {
                const statusClass = `status-${participant.status}`;
                const paymentClass = `payment-${participant.payment_status}`;
                pdfContent += `
                    <tr>
                        <td>${participant.name}</td>
                        <td>${participant.email}</td>
                        <td>${participant.phone}</td>
                        <td>${formatDateForExport(participant.registration_date)}</td>
                        <td class="${statusClass}">${getStatusLabel(participant.status)}</td>
                        <td class="${paymentClass}">${getPaymentStatusLabel(participant.payment_status)}</td>
                        <td>${participant.amount_paid || '0.00'} EUR</td>
                        <td>${participant.invoice_number || 'N/A'}</td>
                    </tr>
                `;
            });
            @else
            let pdfContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Liste des participants - ${eventTitle}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h1 { color: #2E7D47; border-bottom: 2px solid #2E7D47; padding-bottom: 10px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
                        th { background-color: #f8f9fa; font-weight: bold; }
                        tr:nth-child(even) { background-color: #f8f9fa; }
                        .status-confirmed { color: #06D6A0; font-weight: bold; }
                        .status-registered { color: #F4A261; font-weight: bold; }
                        .status-attended { color: #2E7D47; font-weight: bold; }
                        .status-cancelled { color: #E76F51; font-weight: bold; }
                        .footer { margin-top: 30px; text-align: center; color: #666; font-size: 12px; }
                    </style>
                </head>
                <body>
                    <h1>Liste des participants</h1>
                    <p><strong>Événement:</strong> ${eventTitle}</p>
                    <p><strong>Total des participants:</strong> ${participants.length}</p>
                    <p><strong>Date d'export:</strong> ${new Date().toLocaleDateString('fr-FR')}</p>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Date d'inscription</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            participants.forEach(participant => {
                const statusClass = `status-${participant.status}`;
                pdfContent += `
                    <tr>
                        <td>${participant.name}</td>
                        <td>${participant.email}</td>
                        <td>${participant.phone}</td>
                        <td>${formatDateForExport(participant.registration_date)}</td>
                        <td class="${statusClass}">${getStatusLabel(participant.status)}</td>
                    </tr>
                `;
            });
            @endif

            pdfContent += `
                        </tbody>
                    </table>
                    <div class="footer">
                        <p>Document généré automatiquement par Waste2Product</p>
                    </div>
                </body>
                </html>
            `;

            printWindow.document.write(pdfContent);
            printWindow.document.close();
            
            setTimeout(() => {
                printWindow.print();
                showNotificationModal('Fenêtre d\'impression ouverte pour le PDF');
            }, 500);
        } catch (error) {
            showNotificationModal('Erreur lors de l\'export PDF', 'error');
        }
    }

    function downloadFile(content, filename, mimeType) {
        const blob = new Blob([content], { type: mimeType });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
    }

    function formatDateForExport(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function getStatusLabel(status) {
        const labels = {
            'confirmed': 'Confirmé',
            'registered': 'En attente',
            'attended': 'Présent',
            'cancelled': 'Annulé'
        };
        return labels[status] || status;
    }

    function getPaymentStatusLabel(status) {
        const labels = {
            'completed': 'Payé',
            'pending_payment': 'En attente',
            'failed': 'Échoué',
            'refunded': 'Remboursé',
            'partially_refunded': 'Remb. partiel',
            'expired': 'Expiré',
            'not_required': 'Non requis'
        };
        return labels[status] || status;
    }

    function getPaymentStatusBadge(status) {
        const badges = {
            'completed': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Payé</span>',
            'pending_payment': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800"><i class="fas fa-clock mr-1"></i>En attente</span>',
            'failed': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i>Échoué</span>',
            'refunded': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800"><i class="fas fa-undo mr-1"></i>Remboursé</span>',
            'partially_refunded': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800"><i class="fas fa-undo mr-1"></i>Remb. partiel</span>',
            'expired': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800"><i class="fas fa-hourglass-end mr-1"></i>Expiré</span>',
            'not_required': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800"><i class="fas fa-gift mr-1"></i>Gratuit</span>'
        };
        return badges[status] || `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">${status}</span>`;
    }

    // Search and filter functions
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
        const searchTerm = document.getElementById('search-participants-modal').value.toLowerCase();
        filteredParticipantsModal = participantsDataModal.filter(participant => 
            participant.name.toLowerCase().includes(searchTerm) ||
            participant.email.toLowerCase().includes(searchTerm)
        );
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
        
        @if($event->is_paid)
        const paymentStatusFilter = document.getElementById('payment-status-filter-modal')?.value;
        if (paymentStatusFilter) {
            filteredParticipantsModal = filteredParticipantsModal.filter(participant => 
                participant.payment_status === paymentStatusFilter
            );
        }
        @endif
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
            checkboxes.forEach(checkbox => checkbox.checked = false);
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
        
        const totalCheckboxes = document.querySelectorAll('.participant-checkbox-modal').length;
        const selectAll = document.getElementById('select-all-modal');
        if (selectAll) {
            selectAll.checked = selectedParticipantsModal.size === totalCheckboxes;
            selectAll.indeterminate = selectedParticipantsModal.size > 0 && selectedParticipantsModal.size < totalCheckboxes;
        }
        updateBulkActionsModal();
    }

    function updateBulkActionsModal() {
        const selectedCount = selectedParticipantsModal.size;
        const bulkActionBtns = document.querySelectorAll('.bulk-action-btn');
        const selectedCountEl = document.getElementById('selected-count-modal');
        const selectedNumberEl = document.getElementById('selected-number-modal');
        
        if (selectedCount > 0) {
            bulkActionBtns.forEach(btn => btn.disabled = false);
            selectedCountEl?.classList.remove('hidden');
            if (selectedNumberEl) selectedNumberEl.textContent = selectedCount;
        } else {
            bulkActionBtns.forEach(btn => btn.disabled = true);
            selectedCountEl?.classList.add('hidden');
        }
    }

    // Render participants table
    function renderParticipantsModal() {
        const tbody = document.getElementById('participants-tbody-modal');
        const emptyState = document.getElementById('empty-state-modal');
        
        if (!tbody || !emptyState) return;
        
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
            const avatarColor = getAvatarColor(participant.name);
            
            @if($event->is_paid)
            return `
                <tr class="participant-row hover:bg-gray-50 ${isSelected ? 'bg-blue-50' : ''}" data-id="${participant.id}">
                    <td class="px-4 py-3">
                        <input type="checkbox" class="participant-checkbox-modal text-primary focus:ring-primary rounded" 
                               value="${participant.id}" ${isSelected ? 'checked' : ''}
                               onchange="handleParticipantSelectModal(this)">
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3" style="background-color: ${avatarColor}">
                                ${participant.name.charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <div class="font-medium text-sm text-gray-900">${participant.name}</div>
                                <div class="text-xs text-gray-500">${participant.email}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500">
                        ${formatDateModal(participant.registration_date)}
                    </td>
                    <td class="px-4 py-3">
                        <select class="status-select text-xs border-0 bg-transparent ${getStatusClassModal(participant.status)} font-medium rounded px-2 py-1" 
                                data-id="${participant.id}" onchange="updateParticipantStatusModal(this)">
                            <option value="registered" ${participant.status === 'registered' ? 'selected' : ''}>⏳ En attente</option>
                            <option value="confirmed" ${participant.status === 'confirmed' ? 'selected' : ''}>✅ Confirmé</option>
                            <option value="attended" ${participant.status === 'attended' ? 'selected' : ''}>🎯 Présent</option>
                            <option value="cancelled" ${participant.status === 'cancelled' ? 'selected' : ''}>❌ Annulé</option>
                        </select>
                    </td>
                    <td class="px-4 py-3">
                        ${getPaymentStatusBadge(participant.payment_status)}
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm font-semibold text-gray-900">
                            ${participant.amount_paid ? parseFloat(participant.amount_paid).toFixed(2) : '0.00'} EUR
                        </span>
                        ${participant.payment_completed_at ? `
                        <div class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-calendar-check mr-1"></i>${formatDateModal(participant.payment_completed_at)}
                        </div>
                        ` : ''}
                    </td>
                    <td class="px-4 py-3">
                        ${participant.invoice_number ? `
                        <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded">${participant.invoice_number}</span>
                        ` : '<span class="text-xs text-gray-400">N/A</span>'}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex space-x-2 text-sm">
                            <a href="mailto:${participant.email}" class="text-primary hover:text-green-600" title="${participant.email}">
                                <i class="fas fa-envelope"></i>
                            </a>
                            ${participant.phone !== 'N/A' ? `
                            <a href="tel:${participant.phone}" class="text-primary hover:text-green-600" title="${participant.phone}">
                                <i class="fas fa-phone"></i>
                            </a>
                            ` : ''}
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex justify-end space-x-2 text-sm">
                            <button onclick="showEmailModalModal([${participant.id}])" 
                                    class="text-primary hover:text-green-600" title="Envoyer email">
                                <i class="fas fa-envelope"></i>
                            </button>
                            <button onclick="removeParticipantModal(${participant.id})" 
                                    class="text-accent hover:text-red-600" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            @else
            return `
                <tr class="participant-row hover:bg-gray-50 ${isSelected ? 'bg-blue-50' : ''}" data-id="${participant.id}">
                    <td class="px-4 py-3">
                        <input type="checkbox" class="participant-checkbox-modal text-primary focus:ring-primary rounded" 
                               value="${participant.id}" ${isSelected ? 'checked' : ''}
                               onchange="handleParticipantSelectModal(this)">
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3" style="background-color: ${avatarColor}">
                                ${participant.name.charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <div class="font-medium text-sm text-gray-900">${participant.name}</div>
                                <div class="text-xs text-gray-500">${participant.email}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500">
                        ${formatDateModal(participant.registration_date)}
                    </td>
                    <td class="px-4 py-3">
                        <select class="status-select text-xs border-0 bg-transparent ${getStatusClassModal(participant.status)} font-medium rounded px-2 py-1" 
                                data-id="${participant.id}" onchange="updateParticipantStatusModal(this)">
                            <option value="registered" ${participant.status === 'registered' ? 'selected' : ''}>⏳ En attente</option>
                            <option value="confirmed" ${participant.status === 'confirmed' ? 'selected' : ''}>✅ Confirmé</option>
                            <option value="attended" ${participant.status === 'attended' ? 'selected' : ''}>🎯 Présent</option>
                            <option value="cancelled" ${participant.status === 'cancelled' ? 'selected' : ''}>❌ Annulé</option>
                        </select>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex space-x-2 text-sm">
                            <a href="mailto:${participant.email}" class="text-primary hover:text-green-600" title="${participant.email}">
                                <i class="fas fa-envelope"></i>
                            </a>
                            ${participant.phone !== 'N/A' ? `
                            <a href="tel:${participant.phone}" class="text-primary hover:text-green-600" title="${participant.phone}">
                                <i class="fas fa-phone"></i>
                            </a>
                            ` : ''}
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex justify-end space-x-2 text-sm">
                            <button onclick="showEmailModalModal([${participant.id}])" 
                                    class="text-primary hover:text-green-600" title="Envoyer email">
                                <i class="fas fa-envelope"></i>
                            </button>
                            <button onclick="removeParticipantModal(${participant.id})" 
                                    class="text-accent hover:text-red-600" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            @endif
        }).join('');
        
        updatePaginationModal();
    }

    function getAvatarColor(name) {
        const colors = ['#10b981', '#3b82f6', '#8b5cf6', '#ef4444', '#f59e0b', '#06b6d4'];
        const charCode = name.charCodeAt(0);
        return colors[charCode % colors.length];
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
        const classes = {
            'confirmed': 'bg-green-100 text-green-800',
            'registered': 'bg-yellow-100 text-yellow-800',
            'cancelled': 'bg-red-100 text-red-800',
            'attended': 'bg-blue-100 text-blue-800'
        };
        return classes[status] || 'bg-gray-100 text-gray-800';
    }

    function updateResultsCountModal() {
        const countEl = document.getElementById('results-count-modal');
        if (countEl) {
            countEl.textContent = `${filteredParticipantsModal.length} participant${filteredParticipantsModal.length > 1 ? 's' : ''}`;
        }
    }

    function updatePaginationModal() {
        const total = filteredParticipantsModal.length;
        const startIndex = (currentPageModal - 1) * participantsPerPageModal;
        const endIndex = Math.min(startIndex + participantsPerPageModal, total);
        
        const fromEl = document.getElementById('showing-from-modal');
        const toEl = document.getElementById('showing-to-modal');
        const totalEl = document.getElementById('total-results-modal');
        
        if (fromEl) fromEl.textContent = total > 0 ? startIndex + 1 : 0;
        if (toEl) toEl.textContent = endIndex;
        if (totalEl) totalEl.textContent = total;
    }

    function showEmailModalModal(participantIds) {
        const selectedParticipants = participantsDataModal.filter(p => participantIds.includes(p.id));
        const recipientsList = document.getElementById('recipients-list-modal');
        
        if (recipientsList) {
            recipientsList.innerHTML = selectedParticipants.map(p => 
                `<span class="inline-block bg-primary text-white px-2 py-1 rounded text-xs mr-2 mb-1">${p.name}</span>`
            ).join('');
        }
        
        const modal = document.getElementById('emailModalModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function hideEmailModalModal() {
        const modal = document.getElementById('emailModalModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function sendBulkEmailModal(e) {
        e.preventDefault();
        
        const subject = document.getElementById('email-subject-modal').value;
        const message = document.getElementById('email-message-modal').value;
        const eventId = {{ $event->id }};
        
        if (!subject || !message) {
            showNotificationModal('Veuillez remplir tous les champs', 'error');
            return;
        }
        
        // Get selected participant IDs from the email modal
        const selectedIds = Array.from(selectedParticipantsModal);
        
        if (selectedIds.length === 0) {
            showNotificationModal('Aucun participant sélectionné', 'error');
            return;
        }
        
        // Validate CSRF token
        const csrfToken = getCSRFToken();
        if (!csrfToken) {
            return;
        }
        
        // Disable form while sending
        const submitButton = e.target.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Envoi en cours...';
        
        console.log('Sending emails to participants:', selectedIds);
        
        fetch(`/events/${eventId}/participants/send-email`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                participant_ids: selectedIds,
                subject: subject,
                message: message
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || `Erreur HTTP ${response.status}`);
                }).catch(() => {
                    throw new Error(`Erreur de serveur (${response.status})`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Email response:', data);
            
            if (data.success) {
                showNotificationModal(data.message || 'Emails envoyés avec succès', 'success');
                hideEmailModalModal();
                
                // Clear form
                document.getElementById('email-subject-modal').value = `[{{ $event->title }}] `;
                document.getElementById('email-message-modal').value = '';
            } else {
                showNotificationModal(data.message || 'Erreur lors de l\'envoi des emails', 'error');
            }
        })
        .catch(error => {
            console.error('Error sending emails:', error);
            showNotificationModal(handleApiError(error, 'Erreur lors de l\'envoi des emails'), 'error');
        })
        .finally(() => {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        });
    }

   // BACKEND INTEGRATION - UPDATE STATUS (ENHANCED ERROR HANDLING)
function updateParticipantStatusModal(select) {
    const participantId = parseInt(select.dataset.id);
    const newStatus = select.value;
    const eventId = {{ $event->id }};
    const oldValue = select.dataset.oldValue || select.value;
    
    // Validate CSRF token
    const csrfToken = getCSRFToken();
    if (!csrfToken) {
        select.value = oldValue;
        return;
    }
    
    select.disabled = true;
    select.dataset.oldValue = oldValue;
    
    fetch(`/events/${eventId}/participants/${participantId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            attendance_status: newStatus
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || `Erreur HTTP ${response.status}`);
            }).catch(() => {
                throw new Error(`Erreur de serveur (${response.status})`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const participant = participantsDataModal.find(p => p.id === participantId);
            if (participant) {
                participant.status = newStatus;
            }
            select.className = `status-select text-xs border-0 bg-transparent ${getStatusClassModal(newStatus)} font-medium rounded px-2 py-1`;
            select.dataset.oldValue = newStatus;
            showNotificationModal(data.message || 'Statut mis à jour avec succès', 'success');
        } else {
            select.value = oldValue;
            showNotificationModal(data.message || 'Erreur lors de la mise à jour', 'error');
        }
    })
    .catch(error => {
        console.error('Error updating participant status:', error);
        select.value = oldValue;
        showNotificationModal(handleApiError(error, 'Erreur lors de la mise à jour du statut'), 'error');
    })
    .finally(() => {
        select.disabled = false;
    });
}

// BACKEND INTEGRATION - BULK UPDATE STATUS (ENHANCED ERROR HANDLING)
function bulkUpdateStatusModal(newStatus) {
    const selectedIds = Array.from(selectedParticipantsModal);
    const eventId = {{ $event->id }};
    
    if (selectedIds.length === 0) {
        showNotificationModal('Aucun participant sélectionné', 'error');
        return;
    }
    
    // Validate CSRF token
    const csrfToken = getCSRFToken();
    if (!csrfToken) {
        return;
    }
    
    console.log('Updating participants:', selectedIds, 'to status:', newStatus);
    
    document.querySelectorAll('.bulk-action-btn').forEach(btn => btn.disabled = true);
    
    fetch(`/events/${eventId}/participants/bulk-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            participant_ids: selectedIds,
            attendance_status: newStatus
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || `Erreur HTTP ${response.status}`);
            }).catch(() => {
                throw new Error(`Erreur de serveur (${response.status})`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            selectedIds.forEach(id => {
                const participant = participantsDataModal.find(p => p.id === id);
                if (participant) {
                    participant.status = newStatus;
                }
            });
            
            renderParticipantsModal();
            selectedParticipantsModal.clear();
            
            // Uncheck select all
            const selectAll = document.getElementById('select-all-modal');
            if (selectAll) {
                selectAll.checked = false;
                selectAll.indeterminate = false;
            }
            
            updateBulkActionsModal();
            
            const statusLabel = {
                'confirmed': 'confirmés',
                'attended': 'marqués présents',
                'cancelled': 'annulés',
                'registered': 'marqués en attente'
            }[newStatus];
            
            const message = data.message || `${data.data?.updated_count || selectedIds.length} participant(s) ${statusLabel}`;
            showNotificationModal(message, 'success');
        } else {
            showNotificationModal(data.message || 'Erreur lors de la mise à jour', 'error');
        }
    })
    .catch(error => {
        console.error('Error bulk updating participants:', error);
        showNotificationModal(handleApiError(error, 'Erreur lors de la mise à jour des participants'), 'error');
    })
    .finally(() => {
        document.querySelectorAll('.bulk-action-btn').forEach(btn => btn.disabled = false);
    });
}

// BACKEND INTEGRATION - DELETE PARTICIPANT (ENHANCED ERROR HANDLING)
async function removeParticipantModal(participantId) {
    const eventId = {{ $event->id }};
    
    const confirmed = await showConfirmModal('Êtes-vous sûr de vouloir supprimer ce participant ? Cette action est irréversible.');
    
    if (!confirmed) {
        return;
    }
    
    // Validate CSRF token
    const csrfToken = getCSRFToken();
    if (!csrfToken) {
        return;
    }
    
    console.log('Deleting participant:', participantId, 'from event:', eventId);
    
    const participantRow = document.querySelector(`.participant-row[data-id="${participantId}"]`);
    if (participantRow) {
        participantRow.style.opacity = '0.5';
        participantRow.style.pointerEvents = 'none';
    }
    
    try {
        const response = await fetch(`/events/${eventId}/participants/${participantId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            let errorMessage;
            try {
                const data = await response.json();
                errorMessage = data.message || `Erreur HTTP ${response.status}`;
            } catch {
                errorMessage = `Erreur de serveur (${response.status})`;
            }
            throw new Error(errorMessage);
        }
        
        const data = await response.json();
        
        if (data.success) {
            const index = participantsDataModal.findIndex(p => p.id === participantId);
            if (index !== -1) {
                participantsDataModal.splice(index, 1);
            }
            filteredParticipantsModal = filteredParticipantsModal.filter(p => p.id !== participantId);
            selectedParticipantsModal.delete(participantId);
            
            renderParticipantsModal();
            updateBulkActionsModal();
            updateResultsCountModal();
            showNotificationModal(data.message || 'Participant supprimé avec succès', 'success');
        } else {
            if (participantRow) {
                participantRow.style.opacity = '1';
                participantRow.style.pointerEvents = 'auto';
            }
            showNotificationModal(data.message || 'Erreur lors de la suppression', 'error');
        }
    } catch (error) {
        console.error('Error deleting participant:', error);
        if (participantRow) {
            participantRow.style.opacity = '1';
            participantRow.style.pointerEvents = 'auto';
        }
        showNotificationModal(handleApiError(error, 'Erreur lors de la suppression du participant'), 'error');
    }
}

// BACKEND INTEGRATION - BULK DELETE (ENHANCED ERROR HANDLING)
async function bulkDeleteParticipantsModal() {
    const selectedIds = Array.from(selectedParticipantsModal);
    const eventId = {{ $event->id }};
    
    if (selectedIds.length === 0) {
        showNotificationModal('Aucun participant sélectionné', 'error');
        return;
    }
    
    const confirmed = await showConfirmModal(`Êtes-vous sûr de vouloir supprimer ${selectedIds.length} participant(s) ? Cette action est irréversible.`);
    
    if (!confirmed) {
        return;
    }
    
    // Validate CSRF token
    const csrfToken = getCSRFToken();
    if (!csrfToken) {
        return;
    }
    
    console.log('Bulk deleting participants:', selectedIds, 'from event:', eventId);
    console.log('Request URL:', `/events/${eventId}/participants/bulk-delete`);
    console.log('Request payload:', { participant_ids: selectedIds });
    
    document.querySelectorAll('.bulk-action-btn').forEach(btn => btn.disabled = true);
    
    try {
        const response = await fetch(`/events/${eventId}/participants/bulk-delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                participant_ids: selectedIds
            })
        });
        
        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);
        
        if (!response.ok) {
            let errorMessage;
            let errorData;
            try {
                errorData = await response.json();
                console.log('Error response data:', errorData);
                errorMessage = errorData.message || `Erreur HTTP ${response.status}`;
            } catch {
                const textResponse = await response.text();
                console.log('Error response text:', textResponse);
                errorMessage = `Erreur de serveur (${response.status})`;
            }
            throw new Error(errorMessage);
        }
        
        const data = await response.json();
        console.log('Success response data:', data);
        
        if (data.success) {
            selectedIds.forEach(id => {
                const index = participantsDataModal.findIndex(p => p.id === id);
                if (index !== -1) {
                    participantsDataModal.splice(index, 1);
                }
            });
            filteredParticipantsModal = filteredParticipantsModal.filter(p => !selectedIds.includes(p.id));
            selectedParticipantsModal.clear();
            
            // Uncheck select all
            const selectAll = document.getElementById('select-all-modal');
            if (selectAll) {
                selectAll.checked = false;
                selectAll.indeterminate = false;
            }
            
            renderParticipantsModal();
            updateBulkActionsModal();
            updateResultsCountModal();
            
            const message = data.message || `${data.data?.deleted_count || selectedIds.length} participant(s) supprimé(s)`;
            showNotificationModal(message, 'success');
        } else {
            showNotificationModal(data.message || 'Erreur lors de la suppression', 'error');
        }
    } catch (error) {
        console.error('Error bulk deleting participants:', error);
        showNotificationModal(handleApiError(error, 'Erreur lors de la suppression des participants'), 'error');
    } finally {
        document.querySelectorAll('.bulk-action-btn').forEach(btn => btn.disabled = false);
    }
}

// ENHANCED CONFIRM MODAL (with better styling)
function showConfirmModal(message) {
    return new Promise((resolve) => {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-[80] flex items-center justify-center p-4';
        modal.style.animation = 'fadeIn 0.2s ease-in-out';
        
        modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 transform" style="animation: slideUp 0.3s ease-out;">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3 text-center">Confirmation</h3>
                <p class="text-gray-600 mb-6 text-center">${message}</p>
                <div class="flex justify-end space-x-3">
                    <button class="cancel-btn px-5 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors font-medium">
                        Annuler
                    </button>
                    <button class="confirm-btn px-5 py-2.5 bg-red-500 text-white hover:bg-red-600 rounded-lg transition-colors font-medium">
                        Confirmer
                    </button>
                </div>
            </div>
        `;
        
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes slideUp {
                from { transform: translateY(20px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            @keyframes fadeOut {
                from { opacity: 1; }
                to { opacity: 0; }
            }
        `;
        document.head.appendChild(style);
        
        document.body.appendChild(modal);
        
        const closeModal = (result) => {
            modal.style.animation = 'fadeOut 0.2s ease-in-out';
            setTimeout(() => {
                document.body.removeChild(modal);
                document.head.removeChild(style);
                resolve(result);
            }, 200);
        };
        
        modal.querySelector('.confirm-btn').onclick = () => closeModal(true);
        modal.querySelector('.cancel-btn').onclick = () => closeModal(false);
        
        modal.onclick = (e) => {
            if (e.target === modal) closeModal(false);
        };
    });
}

// ENHANCED NOTIFICATION TOAST WITH BETTER ERROR HANDLING
function showNotificationModal(message, type = 'success') {
    // Fallback if message is null or undefined
    if (!message) {
        message = type === 'success' ? 'Opération réussie' : 'Une erreur est survenue';
    }
    
    const notification = document.createElement('div');
    notification.className = `fixed top-20 right-4 z-[70] p-4 rounded-lg shadow-lg text-white max-w-sm ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } transform translate-x-full transition-transform duration-300`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            <span class="text-sm">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    setTimeout(() => notification.classList.remove('translate-x-full'), 100);
    
    // Auto-remove after 5 seconds for error messages, 3 seconds for success
    const timeout = type === 'error' ? 5000 : 3000;
    setTimeout(() => {
        if (notification.parentElement) {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }
    }, timeout);
}

// ENHANCED ERROR HANDLING FUNCTION
function handleApiError(error, defaultMessage = 'Une erreur inattendue est survenue') {
    console.error('API Error:', error);
    
    // Check if it's a network error
    if (error.name === 'TypeError' && error.message.includes('fetch')) {
        return 'Erreur de connexion. Vérifiez votre connexion internet.';
    }
    
    // Check if it's a timeout error
    if (error.name === 'TypeError' && error.message.includes('timeout')) {
        return 'La requête a expiré. Veuillez réessayer.';
    }
    
    // Return the error message if available, otherwise use default
    return error.message || defaultMessage;
}

// ENHANCED CSRF TOKEN VALIDATION
function getCSRFToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    if (!token) {
        console.error('CSRF token not found in page meta tags');
        showNotificationModal('Erreur de sécurité: Token CSRF manquant', 'error');
        return null;
    }
    return token.getAttribute('content');
}
</script>