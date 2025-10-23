<!-- Reusable Delete Confirmation Modal Component -->
<!-- Usage: @include('FrontOffice.Events.partials.delete-confirmation-modal', ['modalId' => 'myModal']) -->

<div id="{{ $modalId ?? 'deleteConfirmModal' }}" 
     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[70] hidden items-center justify-center p-4 opacity-0 transition-all duration-300">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform scale-95 transition-transform duration-300" 
         id="{{ $modalId ?? 'deleteConfirmModal' }}-dialog">
        <div class="p-6">
            <!-- Icon -->
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            
            <!-- Title -->
            <h3 class="text-xl font-bold text-gray-900 text-center mb-2" 
                id="{{ $modalId ?? 'deleteConfirmModal' }}-title">
                Confirmer la suppression
            </h3>
            
            <!-- Message -->
            <p class="text-gray-600 text-center mb-6" 
               id="{{ $modalId ?? 'deleteConfirmModal' }}-message">
                Cette action est irréversible. Voulez-vous vraiment continuer ?
            </p>
            
            <!-- Actions -->
            <div class="flex space-x-3">
                <button type="button" 
                        onclick="hideDeleteConfirmModal('{{ $modalId ?? 'deleteConfirmModal' }}')"
                        class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Annuler
                </button>
                <button type="button"
                        id="{{ $modalId ?? 'deleteConfirmModal' }}-confirm-btn"
                        class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                    Supprimer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Show delete confirmation modal
 * @param {string} modalId - The modal ID
 * @param {string} title - Modal title
 * @param {string} message - Confirmation message
 * @param {function} onConfirm - Callback function when confirmed
 */
function showDeleteConfirmModal(modalId, title, message, onConfirm) {
    const modal = document.getElementById(modalId);
    const dialog = document.getElementById(modalId + '-dialog');
    const titleEl = document.getElementById(modalId + '-title');
    const messageEl = document.getElementById(modalId + '-message');
    const confirmBtn = document.getElementById(modalId + '-confirm-btn');
    
    if (!modal) return;
    
    // Set content
    if (titleEl) titleEl.textContent = title || 'Confirmer la suppression';
    if (messageEl) messageEl.textContent = message || 'Cette action est irréversible. Voulez-vous vraiment continuer ?';
    
    // Show modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Trigger animation
    requestAnimationFrame(() => {
        modal.style.opacity = '1';
        if (dialog) dialog.style.transform = 'scale(1)';
    });
    
    // Set up confirm button
    if (confirmBtn) {
        // Remove old listeners
        const newConfirmBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
        
        // Add new listener
        newConfirmBtn.addEventListener('click', () => {
            if (typeof onConfirm === 'function') {
                onConfirm();
            }
            hideDeleteConfirmModal(modalId);
        });
    }
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

/**
 * Hide delete confirmation modal
 * @param {string} modalId - The modal ID
 */
function hideDeleteConfirmModal(modalId) {
    const modal = document.getElementById(modalId);
    const dialog = document.getElementById(modalId + '-dialog');
    
    if (!modal) return;
    
    // Hide with animation
    modal.style.opacity = '0';
    if (dialog) dialog.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }, 300);
}

// Close on ESC key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        // Close all delete confirmation modals
        document.querySelectorAll('[id$="deleteConfirmModal"]').forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                hideDeleteConfirmModal(modal.id);
            }
        });
    }
});

// Close on backdrop click
document.addEventListener('click', (e) => {
    if (e.target.id && e.target.id.includes('deleteConfirmModal') && e.target.classList.contains('backdrop-blur-sm')) {
        hideDeleteConfirmModal(e.target.id);
    }
});
</script>

<style>
    [id$="deleteConfirmModal"].show {
        opacity: 1 !important;
    }
</style>