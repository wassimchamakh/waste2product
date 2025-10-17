@extends('FrontOffice.layout1.app')

@section('title', 'Étape ' . $step->step_number . ': ' . $step->title . ' - ' . $tutorial->title)

@push('styles')
<style>
    .progress-ring {
        transform: rotate(-90deg);
    }
    .progress-ring-circle {
        transition: stroke-dasharray 0.5s ease-in-out;
    }
    
    .step-sidebar {
        position: sticky;
        top: 1rem;
        max-height: calc(100vh - 2rem);
        overflow-y: auto;
    }
    
    .step-item {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .step-item:hover {
        background-color: #f9fafb;
        border-left-color: #e5e7eb;
    }
    .step-item.active {
        background-color: #eff6ff;
        border-left-color: #2E7D47;
    }
    .step-item.completed {
        background-color: #f0f9ff;
        border-left-color: #06D6A0;
    }

    .collapsible-section {
        transition: all 0.3s ease;
    }
    .collapsible-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    .collapsible-content.expanded {
        max-height: 1000px;
    }

    .tips-section {
        background-color: #fef3c7;
        border-color: #f59e0b;
    }
    .dark .tips-section {
        background-color: #451a03;
        border-color: #92400e;
    }

    .mistakes-section {
        background-color: #fee2e2;
        border-color: #ef4444;
    }
    .dark .mistakes-section {
        background-color: #450a0a;
        border-color: #dc2626;
    }

    .materials-section {
        background-color: #f3f4f6;
        border-color: #6b7280;
    }
    .dark .materials-section {
        background-color: #1f2937;
        border-color: #4b5563;
    }

    .mobile-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 40;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-top: 1px solid #e5e7eb;
    }
    .dark .mobile-nav {
        background: rgba(31, 41, 55, 0.95);
        border-top-color: #374151;
    }

    .font-size-small { font-size: 0.875rem; }
    .font-size-normal { font-size: 1rem; }
    .font-size-large { font-size: 1.125rem; }

    @media (max-width: 1023px) {
        .mobile-sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        .mobile-sidebar.open {
            transform: translateX(0);
        }
    }

    .video-container {
        position: relative;
        width: 100%;
        height: 0;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
    }
    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .notes-textarea {
        resize: vertical;
        min-height: 100px;
    }

    @media print {
        .no-print {
            display: none !important;
        }
        .print-only {
            display: block !important;
        }
    }
</style>
@endpush

@section('content')
<!-- Progress Bar -->
<div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center space-x-4">
                <button onclick="toggleMobileSidebar()" class="lg:hidden p-2 text-gray-600 dark:text-gray-400">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Étape {{ $step->step_number }} sur {{ $allSteps->count() }}
                </h1>
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                {{ number_format(($step->step_number / $allSteps->count()) * 100, 1) }}% Terminé
            </div>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
            <div class="bg-gradient-to-r from-primary to-secondary h-3 rounded-full transition-all duration-500" 
                 style="width: {{ ($step->step_number / $allSteps->count()) * 100 }}%"></div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto flex">
    <!-- Mobile Sidebar Overlay -->
    <div id="mobileSidebarOverlay" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden" onclick="toggleMobileSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="mobile-sidebar lg:relative fixed inset-y-0 left-0 z-50 w-80 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 lg:block">
        <div class="step-sidebar p-6 space-y-6">
            <!-- Tutorial Info -->
            <div class="flex items-center space-x-3 pb-4 border-b border-gray-200 dark:border-gray-700">
                <img src="{{ $tutorial->thumbnail_url ?? 'https://picsum.photos/60/45?random=' . $tutorial->id }}" 
                     alt="{{ $tutorial->title }}" 
                     class="w-15 h-12 object-cover rounded">
                <div class="flex-1 min-w-0">
                    <h2 class="font-medium text-gray-900 dark:text-white text-sm line-clamp-2">
                        {{ $tutorial->title }}
                    </h2>
                    <p class="text-xs text-gray-600 dark:text-gray-400">
                        by {{ $tutorial->creator->name ?? 'Unknown' }}
                    </p>
                </div>
            </div>

            <!-- Steps List -->
            <div>
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Étapes du Tutoriel</h3>
                <div class="space-y-1 max-h-60 overflow-y-auto">
                    @foreach($allSteps as $stepItem)
                        @php
                            $isCompleted = in_array($stepItem->id, $completedSteps);
                        @endphp
                        <a href="{{ route('tutorials.step', [$tutorial->slug, $stepItem->step_number]) }}" 
                           class="step-item block p-3 rounded-lg {{ $stepItem->id === $step->id ? 'active' : '' }} {{ $isCompleted ? 'completed-step' : '' }}">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    @if($isCompleted)
                                        <div class="w-6 h-6 bg-success text-white rounded-full flex items-center justify-center text-xs">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    @elseif($stepItem->id === $step->id)
                                        <div class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs">
                                            {{ $stepItem->step_number }}
                                        </div>
                                    @else
                                        <div class="w-6 h-6 bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400 rounded-full flex items-center justify-center text-xs">
                                            {{ $stepItem->step_number }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white line-clamp-1 {{ $isCompleted ? 'line-through opacity-75' : '' }}">
                                        {{ $stepItem->title }}
                                    </p>
                                    <div class="flex items-center space-x-2 mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        @if($stepItem->estimated_time > 0)
                                            <span class="flex items-center">
                                                <i class="fas fa-clock mr-1"></i>{{ $stepItem->estimated_time }}min
                                            </span>
                                        @endif
                                        @if($stepItem->video_url)
                                            <i class="fas fa-play-circle text-secondary"></i>
                                        @endif
                                        @if($stepItem->image_url)
                                            <i class="fas fa-image text-warning"></i>
                                        @endif
                                        @if($isCompleted)
                                            <span class="text-success font-semibold">✓ Terminé</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Time Tracker -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Temps Passé</h3>
                <div class="text-2xl font-bold text-primary" id="timeTracker">00:00:00</div>
                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Cette session</p>
            </div>

            <!-- Personal Notes -->
            @auth
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <button onclick="toggleNotes()" class="flex items-center justify-between w-full text-left">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Notes Personnelles</h3>
                        <i id="notesIcon" class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                    </button>
                    <div id="notesSection" class="mt-3 space-y-3">
                        <div class="relative">
                            <textarea id="personalNotes" 
                                      class="notes-textarea w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-600 text-gray-900 dark:text-white text-sm"
                                      placeholder="Ajoutez vos notes pour cette étape..."
                                      maxlength="1000"></textarea>
                            <div class="absolute bottom-2 right-2 text-xs text-gray-400">
                                <span id="noteCharCount">0</span>/1000
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            <i class="fas fa-save mr-1"></i>Enregistré automatiquement
                            <span id="lastSaved" class="ml-1"></span>
                        </div>
                    </div>
                </div>
            @endauth

            <!-- Back to Tutorial -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <a href="{{ route('tutorials.show', $tutorial->slug) }}" 
                   class="flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Tutorial Overview
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 lg:pl-6 pb-20 lg:pb-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Step Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center text-lg font-bold">
                            {{ $step->step_number }}
                        </div>
                        <div>
                            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">
                                {{ $step->title }}
                            </h1>
                            @if($step->estimated_time > 0)
                                <p class="text-gray-600 dark:text-gray-400 mt-1">
                                    <i class="fas fa-clock mr-2"></i>Estimated time: {{ $step->estimated_time }} minutes
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Controls -->
                    <div class="no-print flex items-center space-x-2">
                        <!-- Font Size Controls -->
                        <div class="flex items-center space-x-1 bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                            <button onclick="changeFontSize('small')" class="px-2 py-1 text-xs rounded hover:bg-white dark:hover:bg-gray-600 transition-colors">A-</button>
                            <button onclick="changeFontSize('normal')" class="px-2 py-1 text-xs rounded hover:bg-white dark:hover:bg-gray-600 transition-colors font-bold">A</button>
                            <button onclick="changeFontSize('large')" class="px-2 py-1 text-xs rounded hover:bg-white dark:hover:bg-gray-600 transition-colors">A+</button>
                        </div>
                        
                        <!-- Print Button -->
                        <button onclick="window.print()" class="p-2 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors" title="Print Step">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step Image -->
            @if($step->image_url)
                <div class="mb-8">
                    <img src="{{ $step->image_full_url }}" 
                         alt="{{ $step->title }}" 
                         class="w-full rounded-xl shadow-lg">
                </div>
            @endif

            <!-- Step Video -->
            @if($step->video_url)
                <div class="mb-8">
                    <div class="video-container rounded-xl overflow-hidden shadow-lg">
                        @if(str_contains($step->video_url, 'youtube.com') || str_contains($step->video_url, 'youtu.be'))
                            @php
                                $videoId = '';
                                if (str_contains($step->video_url, 'youtube.com/watch?v=')) {
                                    $videoId = explode('v=', $step->video_url)[1];
                                    $videoId = explode('&', $videoId)[0];
                                } elseif (str_contains($step->video_url, 'youtu.be/')) {
                                    $videoId = explode('youtu.be/', $step->video_url)[1];
                                    $videoId = explode('?', $videoId)[0];
                                }
                            @endphp
                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}" 
                                    frameborder="0" 
                                    allowfullscreen></iframe>
                        @elseif(str_contains($step->video_url, 'vimeo.com'))
                            @php
                                $videoId = explode('vimeo.com/', $step->video_url)[1];
                                $videoId = explode('?', $videoId)[0];
                            @endphp
                            <iframe src="https://player.vimeo.com/video/{{ $videoId }}" 
                                    frameborder="0" 
                                    allowfullscreen></iframe>
                        @else
                            <iframe src="{{ $step->video_url }}" 
                                    frameborder="0" 
                                    allowfullscreen></iframe>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Step Content -->
            <div id="stepContent" class="mb-8">
                @if($step->description)
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Overview</h2>
                        <div class="prose max-w-none dark:prose-invert">
                            {!! nl2br(e($step->description)) !!}
                        </div>
                    </div>
                @endif

                @if($step->content)
                    <div class="prose max-w-none dark:prose-invert">
                        {!! $step->content !!}
                    </div>
                @endif
            </div>

            <!-- Collapsible Sections -->
            <div class="space-y-4 mb-8">
                <!-- Tips Section -->
                @if($step->tips)
                    <div class="collapsible-section">
                        <button onclick="toggleSection('tips')" 
                                class="flex items-center justify-between w-full p-4 tips-section border-l-4 rounded-lg">
                            <div class="flex items-center">
                                <span class="text-lg mr-2">💡</span>
                                <h3 class="font-semibold text-gray-900 dark:text-white">Astuces & Conseils</h3>
                            </div>
                            <i id="tipsIcon" class="fas fa-chevron-down transition-transform"></i>
                        </button>
                        <div id="tipsContent" class="collapsible-content">
                            <div class="p-4 tips-section border-l-4 rounded-b-lg border-t-0">
                                <div class="prose max-w-none dark:prose-invert">
                                    {!! nl2br(e($step->tips)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Common Mistakes Section -->
                @if($step->common_mistakes)
                    <div class="collapsible-section">
                        <button onclick="toggleSection('mistakes')" 
                                class="flex items-center justify-between w-full p-4 mistakes-section border-l-4 rounded-lg">
                            <div class="flex items-center">
                                <span class="text-lg mr-2">⚠️</span>
                                <h3 class="font-semibold text-gray-900 dark:text-white">Erreurs Courantes</h3>
                            </div>
                            <i id="mistakesIcon" class="fas fa-chevron-down transition-transform"></i>
                        </button>
                        <div id="mistakesContent" class="collapsible-content">
                            <div class="p-4 mistakes-section border-l-4 rounded-b-lg border-t-0">
                                <div class="prose max-w-none dark:prose-invert">
                                    {!! nl2br(e($step->common_mistakes)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Required Materials Section -->
                @if($step->required_materials)
                    <div class="collapsible-section">
                        <button onclick="toggleSection('materials')" 
                                class="flex items-center justify-between w-full p-4 materials-section border-l-4 rounded-lg">
                            <div class="flex items-center">
                                <span class="text-lg mr-2">📋</span>
                                <h3 class="font-semibold text-gray-900 dark:text-white">Matériaux Nécessaires</h3>
                            </div>
                            <i id="materialsIcon" class="fas fa-chevron-down transition-transform"></i>
                        </button>
                        <div id="materialsContent" class="collapsible-content">
                            <div class="p-4 materials-section border-l-4 rounded-b-lg border-t-0">
                                @php
                                    $materials = explode("\n", $step->required_materials);
                                @endphp
                                <ul class="space-y-2">
                                    @foreach($materials as $material)
                                        @if(trim($material))
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-success mr-2 mt-1"></i>
                                                <span>{{ trim($material) }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Mark as Complete -->
            @auth
                <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               id="markComplete"
                               class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary focus:ring-2">
                        <span class="ml-3 text-gray-900 dark:text-white font-medium">
                            Marquer cette étape comme terminée
                        </span>
                    </label>
                </div>
            @endauth

            <!-- Navigation Buttons (Desktop) -->
            <div class="hidden lg:flex justify-between items-center pt-8 border-t border-gray-200 dark:border-gray-700">
                @if($step->step_number > 1)
                    @php $prevStep = $allSteps->where('step_number', $step->step_number - 1)->first(); @endphp
                    <a href="{{ route('tutorials.step', [$tutorial->slug, $prevStep->step_number]) }}" 
                       class="flex items-center px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-chevron-left mr-2"></i>
                        <div class="text-left">
                            <div class="text-sm font-medium">Étape Précédente</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $prevStep->title }}</div>
                        </div>
                    </a>
                @else
                    <div></div>
                @endif

                <div class="text-center">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Étape {{ $step->step_number }} sur {{ $allSteps->count() }}
                    </div>
                </div>

                @if($step->step_number < $allSteps->count())
                    @php $nextStep = $allSteps->where('step_number', $step->step_number + 1)->first(); @endphp
                    <a href="{{ route('tutorials.step', [$tutorial->slug, $nextStep->step_number]) }}" 
                       class="flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-green-600 transition-colors">
                        <div class="text-right">
                            <div class="text-sm font-medium">Étape Suivante</div>
                            <div class="text-xs text-green-200">{{ $nextStep->title }}</div>
                        </div>
                        <i class="fas fa-chevron-right ml-2"></i>
                    </a>
                @else
                    <a href="{{ route('tutorials.complete', $tutorial->id) }}" 
                       class="flex items-center px-6 py-3 bg-secondary text-white rounded-lg hover:bg-teal-400 transition-colors">
                        <div class="text-right">
                            <div class="text-sm font-medium">Terminer le Tutoriel</div>
                            <div class="text-xs text-teal-200">🎉 Félicitations!</div>
                        </div>
                        <i class="fas fa-trophy ml-2"></i>
                    </a>
                @endif
            </div>

            <!-- Progress Indicator -->
            <div class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
                {{ $step->step_number - 1 }} sur {{ $allSteps->count() }} étapes terminées
            </div>
        </div>
    </main>
</div>

<!-- Mobile Navigation -->
<div class="mobile-nav lg:hidden">
    <div class="flex items-center justify-between p-4">
        @if($step->step_number > 1)
            @php $prevStep = $allSteps->where('step_number', $step->step_number - 1)->first(); @endphp
            <a href="{{ route('tutorials.step', [$tutorial->slug, $prevStep->step_number]) }}" 
               class="flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg">
                <i class="fas fa-chevron-left mr-2"></i>Précédent
            </a>
        @else
            <div></div>
        @endif

        <div class="text-sm text-gray-600 dark:text-gray-400">
            {{ $step->step_number }}/{{ $allSteps->count() }}
        </div>

        @if($step->step_number < $allSteps->count())
            @php $nextStep = $allSteps->where('step_number', $step->step_number + 1)->first(); @endphp
            <a href="{{ route('tutorials.step', [$tutorial->slug, $nextStep->step_number]) }}" 
               class="flex items-center px-4 py-2 bg-primary text-white rounded-lg">
                Suivant<i class="fas fa-chevron-right ml-2"></i>
            </a>
        @else
            <a href="{{ route('tutorials.complete', $tutorial->id) }}" 
               class="flex items-center px-4 py-2 bg-secondary text-white rounded-lg">
                Terminer<i class="fas fa-trophy ml-2"></i>
            </a>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    let startTime = Date.now();
    let timerInterval;

    // Time tracker
    function updateTimer() {
        const elapsed = Date.now() - startTime;
        const hours = Math.floor(elapsed / 3600000);
        const minutes = Math.floor((elapsed % 3600000) / 60000);
        const seconds = Math.floor((elapsed % 60000) / 1000);
        
        document.getElementById('timeTracker').textContent = 
            `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    // Start timer
    timerInterval = setInterval(updateTimer, 1000);

    // Mobile sidebar toggle
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileSidebarOverlay');
        
        sidebar.classList.toggle('open');
        overlay.classList.toggle('hidden');
    }

    // Collapsible sections
    function toggleSection(section) {
        const content = document.getElementById(section + 'Content');
        const icon = document.getElementById(section + 'Icon');
        
        content.classList.toggle('expanded');
        icon.classList.toggle('rotate-180');
    }

    // Notes functionality
    @auth
    let notesExpanded = false;
    let saveTimeout;

    function toggleNotes() {
        const section = document.getElementById('notesSection');
        const icon = document.getElementById('notesIcon');
        
        notesExpanded = !notesExpanded;
        
        if (notesExpanded) {
            section.style.maxHeight = section.scrollHeight + 'px';
            icon.classList.add('rotate-180');
        } else {
            section.style.maxHeight = '0px';
            icon.classList.remove('rotate-180');
        }
    }

    function saveNotes() {
        const notes = document.getElementById('personalNotes').value;
        
        fetch('{{ route("tutorials.notes.save") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                tutorial_id: {{ $tutorial->id }},
                step_id: {{ $step->id }},
                notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('lastSaved').textContent = 'just now';
            }
        })
        .catch(error => console.error('Error saving notes:', error));
    }

    // Auto-save notes with debounce
    document.getElementById('personalNotes').addEventListener('input', function() {
        const charCount = this.value.length;
        document.getElementById('noteCharCount').textContent = charCount;
        
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(saveNotes, 1000);
    });

    // Load existing notes
    fetch('{{ route("tutorials.notes.get") }}?tutorial_id={{ $tutorial->id }}&step_id={{ $step->id }}')
        .then(response => response.json())
        .then(data => {
            if (data.notes) {
                document.getElementById('personalNotes').value = data.notes;
                document.getElementById('noteCharCount').textContent = data.notes.length;
            }
        });
    @endauth

    // Mark as complete
    @auth
    document.getElementById('markComplete').addEventListener('change', function() {
        const isCompleted = this.checked;
        
        fetch('{{ route("tutorials.steps.complete") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                tutorial_id: {{ $tutorial->id }},
                step_id: {{ $step->id }},
                completed: isCompleted
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(isCompleted ? 'Étape marquée comme terminée!' : 'Étape démarquée');
                
                // Update sidebar step item
                const stepItems = document.querySelectorAll('.step-item');
                stepItems.forEach(item => {
                    const href = item.getAttribute('href');
                    if (href && href.includes('step/{{ $step->step_number }}')) {
                        const icon = item.querySelector('.w-6.h-6');
                        if (isCompleted) {
                            icon.className = 'w-6 h-6 bg-success text-white rounded-full flex items-center justify-center text-xs';
                            icon.innerHTML = '<i class="fas fa-check"></i>';
                        } else {
                            icon.className = 'w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs';
                            icon.textContent = '{{ $step->step_number }}';
                        }
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.checked = !isCompleted; // Revert checkbox
        });
    });
    @endauth

    // Font size controls
    function changeFontSize(size) {
        const content = document.getElementById('stepContent');
        content.className = content.className.replace(/font-size-\w+/, '') + ' font-size-' + size;
        localStorage.setItem('preferredFontSize', size);
    }

    // Load saved font size
    const savedFontSize = localStorage.getItem('preferredFontSize');
    if (savedFontSize) {
        changeFontSize(savedFontSize);
    }

    // Toast notification
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 transform transition-all duration-300 translate-x-full ${type === 'success' ? 'bg-success' : 'bg-accent'}`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => toast.classList.remove('translate-x-full'), 100);
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => document.body.removeChild(toast), 300);
        }, 3000);
    }

    // Auto-scroll to top on step change
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Dark mode detection
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.classList.add('dark');
    }
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
        if (event.matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    });

    // Cleanup timer on page unload
    window.addEventListener('beforeunload', function() {
        clearInterval(timerInterval);
    });

    // Close mobile sidebar on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('mobileSidebarOverlay').classList.add('hidden');
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    @if($step->step_number > 1)
                        window.location.href = '{{ route("tutorials.step", [$tutorial->slug, $step->step_number - 1]) }}';
                    @endif
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    @if($step->step_number < $allSteps->count())
                        window.location.href = '{{ route("tutorials.step", [$tutorial->slug, $step->step_number + 1]) }}';
                    @endif
                    break;
            }
        }
    });
</script>
@endpush