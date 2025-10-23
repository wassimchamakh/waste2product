@extends('FrontOffice.layout1.app')

@section('title', 'Refund Requests - ' . $event->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('Events.show', $event->id) }}" class="text-blue-600 hover:text-blue-800 flex items-center mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Event
            </a>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Refund Requests</h1>
                    <p class="text-gray-600 mt-1">{{ $event->title }}</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg font-medium">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $refundRequests->total() }} Total Requests
                    </span>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-green-800 font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-red-800 font-medium">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <!-- Filter Tabs -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-4 px-6" aria-label="Tabs">
                    <a href="{{ route('Events.refund.list', ['event' => $event->id]) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ !request('status') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        All Requests
                        <span class="ml-2 bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs">{{ $counts['all'] }}</span>
                    </a>
                    <a href="{{ route('Events.refund.list', ['event' => $event->id, 'status' => 'pending']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') == 'pending' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Pending
                        <span class="ml-2 bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full text-xs">{{ $counts['pending'] }}</span>
                    </a>
                    <a href="{{ route('Events.refund.list', ['event' => $event->id, 'status' => 'approved']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') == 'approved' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Approved
                        <span class="ml-2 bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">{{ $counts['approved'] }}</span>
                    </a>
                    <a href="{{ route('Events.refund.list', ['event' => $event->id, 'status' => 'rejected']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') == 'rejected' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Rejected
                        <span class="ml-2 bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs">{{ $counts['rejected'] }}</span>
                    </a>
                    <a href="{{ route('Events.refund.list', ['event' => $event->id, 'status' => 'completed']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') == 'completed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Completed
                        <span class="ml-2 bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-xs">{{ $counts['completed'] }}</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Refund Requests List -->
        @if($refundRequests->count() > 0)
        <div class="space-y-4">
            @foreach($refundRequests as $refund)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
                    <!-- Left Side: Request Info -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $refund->participant->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $refund->participant->email }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($refund->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($refund->status === 'approved') bg-green-100 text-green-800
                                @elseif($refund->status === 'rejected') bg-red-100 text-red-800
                                @elseif($refund->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($refund->status === 'completed') bg-gray-100 text-gray-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($refund->status) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <span class="text-sm text-gray-600">Requested Amount:</span>
                                <span class="ml-2 text-lg font-bold text-gray-900">
                                    {{ number_format($refund->refund_amount, 3) }} {{ config('payments.currency') }}
                                </span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Refund Type:</span>
                                <span class="ml-2 font-medium text-gray-900 capitalize">{{ $refund->refund_type }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Original Payment:</span>
                                <span class="ml-2 font-medium text-gray-900">
                                    {{ number_format($refund->participant->amount_paid, 3) }} {{ config('payments.currency') }}
                                </span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Request Date:</span>
                                <span class="ml-2 font-medium text-gray-900">{{ $refund->created_at->format('M j, Y') }}</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <span class="text-sm font-medium text-gray-700">Reason:</span>
                            <span class="ml-2 text-sm text-gray-900 capitalize">{{ str_replace('_', ' ', $refund->reason_category) }}</span>
                        </div>

                        @if($refund->reason_details)
                        <div class="bg-gray-50 rounded-lg p-3 mb-4">
                            <p class="text-sm text-gray-700"><strong>Details:</strong> {{ $refund->reason_details }}</p>
                        </div>
                        @endif

                        @if($refund->admin_notes)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                            <p class="text-sm text-blue-900"><strong>Admin Notes:</strong> {{ $refund->admin_notes }}</p>
                        </div>
                        @endif

                        @if($refund->processed_at)
                        <div class="text-sm text-gray-600">
                            <strong>Processed:</strong> {{ $refund->processed_at->format('M j, Y \a\t g:i A') }}
                            @if($refund->processedBy)
                            by {{ $refund->processedBy->name }}
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- Right Side: Actions -->
                    @if($refund->status === 'pending')
                    <div class="lg:ml-6 mt-4 lg:mt-0 flex flex-col space-y-3 lg:w-48">
                        <!-- Approve Button -->
                        <button 
                            onclick="openApproveModal({{ $refund->id }}, '{{ $refund->participant->name }}', {{ $refund->refund_amount }})"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Approve
                        </button>
                        
                        <!-- Reject Button -->
                        <button 
                            onclick="openRejectModal({{ $refund->id }}, '{{ $refund->participant->name }}')"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Reject
                        </button>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $refundRequests->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Refund Requests</h3>
            <p class="text-gray-600">
                @if(request('status'))
                    No {{ request('status') }} refund requests found.
                @else
                    There are no refund requests for this event yet.
                @endif
            </p>
        </div>
        @endif
    </div>
</div>

<!-- Approve Modal -->
<div id="approve-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-2">Approve Refund Request</h3>
            <p class="text-sm text-gray-600 text-center mb-4">
                Are you sure you want to approve the refund for <strong id="approve-participant-name"></strong>?
            </p>
            <p class="text-center text-lg font-bold text-green-600 mb-4">
                <span id="approve-amount"></span> {{ config('payments.currency') }}
            </p>
            <form id="approve-form" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes (Optional)</label>
                    <textarea 
                        name="admin_notes" 
                        rows="3" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Add any notes about this approval..."
                    ></textarea>
                </div>
                <div class="flex gap-3">
                    <button 
                        type="submit" 
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                        Confirm Approval
                    </button>
                    <button 
                        type="button" 
                        onclick="closeApproveModal()"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-200">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="reject-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-2">Reject Refund Request</h3>
            <p class="text-sm text-gray-600 text-center mb-4">
                Are you sure you want to reject the refund for <strong id="reject-participant-name"></strong>?
            </p>
            <form id="reject-form" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason (Required)</label>
                    <textarea 
                        name="admin_notes" 
                        rows="3" 
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        placeholder="Please explain why this refund is being rejected..."
                    ></textarea>
                </div>
                <div class="flex gap-3">
                    <button 
                        type="submit" 
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                        Confirm Rejection
                    </button>
                    <button 
                        type="button" 
                        onclick="closeRejectModal()"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-200">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for Modals -->
<script>
    function openApproveModal(refundId, participantName, amount) {
        document.getElementById('approve-participant-name').textContent = participantName;
        document.getElementById('approve-amount').textContent = amount.toFixed(3);
        document.getElementById('approve-form').action = '{{ route("Events.refund.approve", ["event" => $event->id, "refundRequest" => ":id"]) }}'.replace(':id', refundId);
        document.getElementById('approve-modal').classList.remove('hidden');
    }

    function closeApproveModal() {
        document.getElementById('approve-modal').classList.add('hidden');
    }

    function openRejectModal(refundId, participantName) {
        document.getElementById('reject-participant-name').textContent = participantName;
        document.getElementById('reject-form').action = '{{ route("Events.refund.reject", ["event" => $event->id, "refundRequest" => ":id"]) }}'.replace(':id', refundId);
        document.getElementById('reject-modal').classList.add('hidden');
    }

    function closeRejectModal() {
        document.getElementById('reject-modal').classList.add('hidden');
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const approveModal = document.getElementById('approve-modal');
        const rejectModal = document.getElementById('reject-modal');
        
        if (event.target == approveModal) {
            closeApproveModal();
        }
        if (event.target == rejectModal) {
            closeRejectModal();
        }
    }
</script>
@endsection
