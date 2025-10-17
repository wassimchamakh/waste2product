@extends('FrontOffice.layout1.app')

@section('title', 'Create Tutorial')

@section('content')
<div class="container mx-auto p-5 max-w-6xl">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Create a New Tutorial</h1>
        <p class="text-gray-600">Share your knowledge and help others learn sustainable practices</p>
    </div>

    <!-- Stepper Indicator -->
    <div class="mb-8">
        <div class="flex items-center justify-center">
            <div class="flex items-center">
                <div id="step1Indicator" class="flex items-center bg-blue-500 text-white rounded-full w-10 h-10 justify-center font-bold">
                    1
                </div>
                <span id="step1Label" class="ml-2 font-semibold text-blue-500">Tutorial Info</span>
            </div>
            <div class="w-24 h-1 bg-gray-300 mx-2"></div>
            <div class="flex items-center">
                <div id="step2Indicator" class="flex items-center bg-gray-300 text-white rounded-full w-10 h-10 justify-center font-bold">
                    2
                </div>
                <span id="step2Label" class="ml-2 font-semibold text-gray-400">Tutorial Steps</span>
            </div>
        </div>
    </div>
    
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <strong>Please fix the following errors:</strong>
            </div>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tutorials.store') }}" method="POST" enctype="multipart/form-data" id="tutorialForm">
        @csrf

        <!-- SECTION 1: Tutorial Basic Info -->
        <div id="tutorialBasicInfo" class="tutorial-section">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="lg:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2" for="title">
                        Tutorial Title <span class="text-red-500">*</span>
                        <span class="text-sm font-normal text-gray-500 ml-2">Make it clear and descriptive</span>
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        maxlength="255"
                        placeholder="e.g., How to Start Composting at Home" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror" 
                        value="{{ old('title') }}" 
                        required
                    />
                    <div class="flex justify-between items-center mt-1">
                        @error('title')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @else
                            <span class="text-gray-400 text-sm">This will be visible to all users</span>
                        @enderror
                        <span class="text-gray-400 text-sm" id="titleCounter">0/255</span>
                    </div>
                </div>

                <!-- Description -->
                <div class="lg:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2" for="description">
                        Short Description <span class="text-red-500">*</span>
                        <span class="text-sm font-normal text-gray-500 ml-2">Brief overview (500 characters max)</span>
                    </label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="3" 
                        maxlength="500"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror" 
                        placeholder="A concise summary that appears in search results and listings..."
                        required
                    >{{ old('description') }}</textarea>
                    <div class="flex justify-between items-center mt-1">
                        @error('description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @else
                            <span class="text-gray-400 text-sm">This appears in preview cards</span>
                        @enderror
                        <span class="text-gray-400 text-sm" id="descCounter">0/500</span>
                    </div>
                </div>

                <!-- Content (Full) -->
                <div class="lg:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2" for="content">
                        Full Content <span class="text-red-500">*</span>
                        <span class="text-sm font-normal text-gray-500 ml-2">Detailed introduction and overview</span>
                    </label>
                    <div class="border border-gray-300 rounded-lg overflow-hidden @error('content') border-red-500 @enderror">
                        <textarea 
                            name="content" 
                            id="content" 
                            rows="8" 
                            class="w-full p-3 focus:outline-none" 
                            placeholder="Provide a comprehensive introduction to your tutorial. Explain what learners will accomplish, why it's important, and what they'll need to get started..."
                            required
                        >{{ old('content') }}</textarea>
                    </div>
                    @error('content')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @else
                        <span class="text-gray-400 text-sm mt-1 block">You can format this text using the editor toolbar</span>
                    @enderror
                </div>

                <!-- Thumbnail Image -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2" for="thumbnail_image">
                        Thumbnail Image
                        <span class="text-sm font-normal text-gray-500 ml-2">JPG, PNG (max 2MB)</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition @error('thumbnail_image') border-red-500 @enderror" id="thumbnailArea">
                        <input type="file" name="thumbnail_image" id="thumbnail_image" accept="image/*" class="hidden" />
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="text-gray-600 mb-1">Drag & drop your image here or click to upload</p>
                        <p class="text-gray-400 text-sm">Recommended: 1200x630px</p>
                    </div>
                    <img id="thumbnailPreview" class="mt-3 rounded-lg shadow-md hidden max-h-48 mx-auto" />
                    @error('thumbnail_image')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Intro Video URL -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2" for="intro_video_url">
                        Intro Video URL
                        <span class="text-sm font-normal text-gray-500 ml-2">Optional</span>
                    </label>
                    <input 
                        type="url" 
                        name="intro_video_url" 
                        id="intro_video_url" 
                        placeholder="https://youtube.com/watch?v=..." 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('intro_video_url') border-red-500 @enderror" 
                        value="{{ old('intro_video_url') }}" 
                    />
                    @error('intro_video_url')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @else
                        <span class="text-gray-400 text-sm mt-1 block">YouTube or Vimeo links supported</span>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2" for="category">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="category" 
                        id="category" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror"
                        required
                    >
                        <option value="">Select a category</option>
                        <option value="Recycling" {{ old('category') == 'Recycling' ? 'selected' : '' }}>♻️ Recycling</option>
                        <option value="Composting" {{ old('category') == 'Composting' ? 'selected' : '' }}>🌱 Composting</option>
                        <option value="Energy" {{ old('category') == 'Energy' ? 'selected' : '' }}>⚡ Energy</option>
                        <option value="Water" {{ old('category') == 'Water' ? 'selected' : '' }}>💧 Water</option>
                        <option value="Waste Reduction" {{ old('category') == 'Waste Reduction' ? 'selected' : '' }}>🗑️ Waste Reduction</option>
                        <option value="Gardening" {{ old('category') == 'Gardening' ? 'selected' : '' }}>🌿 Gardening</option>
                        <option value="DIY" {{ old('category') == 'DIY' ? 'selected' : '' }}>🔨 DIY</option>
                        <option value="General" {{ old('category') == 'General' ? 'selected' : '' }}>📚 General</option>
                    </select>
                    @error('category')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Difficulty Level -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        Difficulty Level <span class="text-red-500">*</span>
                    </label>
                    <div class="flex flex-wrap gap-3">
                        @foreach (['Beginner' => '🟢', 'Intermediate' => '🟡', 'Advanced' => '🟠', 'Expert' => '🔴'] as $level => $icon)
                            <label class="flex items-center bg-gray-50 border-2 border-gray-200 rounded-lg px-4 py-3 cursor-pointer hover:border-blue-500 transition @error('difficulty_level') border-red-500 @enderror">
                                <input 
                                    type="radio" 
                                    name="difficulty_level" 
                                    value="{{ $level }}" 
                                    class="form-radio text-blue-500 mr-2" 
                                    {{ old('difficulty_level') == $level ? 'checked' : '' }}
                                    required
                                >
                                <span class="mr-1">{{ $icon }}</span>
                                <span class="font-medium">{{ $level }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('difficulty_level')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Estimated Duration -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2" for="estimated_duration">
                        Estimated Duration <span class="text-red-500">*</span>
                        <span class="text-sm font-normal text-gray-500 ml-2">Total time to complete</span>
                    </label>
                    <div class="flex items-center">
                        <input 
                            type="number" 
                            name="estimated_duration" 
                            id="estimated_duration" 
                            min="1" 
                            max="999" 
                            placeholder="30"
                            class="w-32 border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('estimated_duration') border-red-500 @enderror" 
                            value="{{ old('estimated_duration') }}" 
                            required
                        />
                        <span class="ml-3 text-gray-600 font-medium">minutes</span>
                    </div>
                    @error('estimated_duration')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Prerequisites -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2" for="prerequisites">
                        Prerequisites
                        <span class="text-sm font-normal text-gray-500 ml-2">Optional</span>
                    </label>
                    <textarea 
                        name="prerequisites" 
                        id="prerequisites" 
                        rows="3" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('prerequisites') border-red-500 @enderror" 
                        placeholder="What should users know before starting? e.g., Basic gardening knowledge, Access to outdoor space"
                    >{{ old('prerequisites') }}</textarea>
                    @error('prerequisites')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Learning Outcomes -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2" for="learning_outcomes">
                        Learning Outcomes
                        <span class="text-sm font-normal text-gray-500 ml-2">One per line</span>
                    </label>
                    <textarea 
                        name="learning_outcomes" 
                        id="learning_outcomes" 
                        rows="4" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('learning_outcomes') border-red-500 @enderror" 
                        placeholder="What will users learn? Enter each outcome on a new line:&#10;- Understand composting basics&#10;- Build a compost bin&#10;- Maintain healthy compost"
                    >{{ old('learning_outcomes') }}</textarea>
                    @error('learning_outcomes')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tags -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2" for="tags">
                        Tags
                        <span class="text-sm font-normal text-gray-500 ml-2">Comma-separated</span>
                    </label>
                    <input 
                        type="text" 
                        name="tags" 
                        id="tags" 
                        placeholder="composting, recycling, organic, sustainable" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tags') border-red-500 @enderror" 
                        value="{{ old('tags') }}" 
                    />
                    @error('tags')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @else
                        <span class="text-gray-400 text-sm mt-1 block">Help users find your tutorial</span>
                    @enderror
                </div>

                <!-- Status -->
                <div class="lg:col-span-2 bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex flex-wrap items-center gap-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Status</label>
                            <div class="flex gap-3">
                                <label class="flex items-center bg-white border-2 border-gray-300 rounded-lg px-4 py-2 cursor-pointer hover:border-blue-500 transition">
                                    <input type="radio" name="status" value="Draft" class="form-radio text-blue-500 mr-2" {{ old('status', 'Draft') == 'Draft' ? 'checked' : '' }}>
                                    <span class="font-medium">📝 Draft</span>
                                </label>
                                <label class="flex items-center bg-white border-2 border-gray-300 rounded-lg px-4 py-2 cursor-pointer hover:border-green-500 transition">
                                    <input type="radio" name="status" value="Published" class="form-radio text-green-500 mr-2" {{ old('status') == 'Published' ? 'checked' : '' }}>
                                    <span class="font-medium">✅ Published</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="border-l border-gray-300 pl-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_featured" value="1" class="form-checkbox text-yellow-500 rounded h-5 w-5 mr-3" {{ old('is_featured') ? 'checked' : '' }}>
                                <div>
                                    <span class="font-semibold text-gray-700">⭐ Feature this tutorial</span>
                                    <p class="text-sm text-gray-500">Display on homepage and featured section</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons for Section 1 -->
            <div class="mt-8 flex justify-between items-center border-t border-gray-200 pt-6">
                <button 
                    type="button" 
                    onclick="if(confirm('Discard this tutorial?')) window.location='{{ route('tutorials.index') }}'" 
                    class="bg-white border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition"
                >
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                
                <button 
                    type="button" 
                    id="nextToSteps"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition flex items-center"
                >
                    Next: Add Steps
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- SECTION 2: Tutorial Steps -->
        <div id="tutorialSteps" class="tutorial-section hidden">
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm text-blue-700 font-semibold mb-1">Add Tutorial Steps</p>
                        <p class="text-sm text-blue-600">
                            Break down your tutorial into clear, actionable steps. Each step should guide users through one specific action or concept.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Steps Container -->
            <div id="stepsContainer" class="space-y-4 mb-6">
                <!-- Steps will be dynamically added here -->
            </div>

            <!-- Add Step Button -->
            <button 
                type="button" 
                id="addStepBtn"
                class="w-full border-2 border-dashed border-gray-300 rounded-lg p-6 text-gray-600 hover:border-blue-500 hover:text-blue-500 transition flex items-center justify-center"
            >
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="font-semibold">Add New Step</span>
            </button>

            <!-- Navigation Buttons for Section 2 -->
            <div class="mt-8 flex justify-between items-center border-t border-gray-200 pt-6">
                <button 
                    type="button" 
                    id="backToBasic"
                    class="bg-white border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition flex items-center"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Basic Info
                </button>
                
                <div class="flex gap-3">
                    <button 
                        type="submit" 
                        name="action" 
                        value="draft" 
                        class="bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-700 transition flex items-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        Save as Draft
                    </button>
                    
                    <button 
                        type="submit" 
                        name="action" 
                        value="publish" 
                        class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition flex items-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Create & Publish
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@section('scripts')
<script>
    let stepCounter = 0;

    // Section Navigation - Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('\n🚀 =================================================');
        console.log('🚀 TUTORIAL CREATE PAGE INITIALIZED');
        console.log('🚀 =================================================');
        console.log('📅 Timestamp:', new Date().toLocaleString());
        console.log('🌐 URL:', window.location.href);
        
        const tutorialBasicInfo = document.getElementById('tutorialBasicInfo');
        const tutorialSteps = document.getElementById('tutorialSteps');
        const nextToStepsBtn = document.getElementById('nextToSteps');
        const backToBasicBtn = document.getElementById('backToBasic');
        const step1Indicator = document.getElementById('step1Indicator');
        const step2Indicator = document.getElementById('step2Indicator');
        const step1Label = document.getElementById('step1Label');
        const step2Label = document.getElementById('step2Label');

        // Debug: Check if elements exist
        console.log('\n🔍 Checking for required DOM elements...');
        const elementsStatus = {
            tutorialBasicInfo: !!tutorialBasicInfo,
            tutorialSteps: !!tutorialSteps,
            nextToStepsBtn: !!nextToStepsBtn,
            backToBasicBtn: !!backToBasicBtn,
            step1Indicator: !!step1Indicator,
            step2Indicator: !!step2Indicator,
            step1Label: !!step1Label,
            step2Label: !!step2Label
        };
        
        console.table(elementsStatus);
        
        const allElementsFound = Object.values(elementsStatus).every(status => status === true);
        if (allElementsFound) {
            console.log('✅ All required elements found!');
        } else {
            console.warn('⚠️ Some elements are missing!');
        }

        if (!nextToStepsBtn) {
            console.error('❌ CRITICAL: Next button not found! Cannot proceed.');
            console.error('   Make sure element with id="nextToSteps" exists in the HTML');
            return;
        }
        
        console.log('\n✅ Event listeners will now be attached...');
        console.log('=================================================\n');

        nextToStepsBtn.addEventListener('click', function() {
            console.log('=================================================');
            console.log('🔵 NEXT BUTTON CLICKED - Starting validation...');
            console.log('=================================================');
            
            // Validate basic info before proceeding
            const form = document.getElementById('tutorialForm');
            console.log('📋 Form element found:', !!form);
            
            let isValid = true;
            let missingFields = [];

            // Check text inputs and textareas
            console.log('\n📝 Checking text fields, textareas, and selects...');
            const textFields = tutorialBasicInfo.querySelectorAll('input[required]:not([type="radio"]), textarea[required], select[required]');
            console.log('   Found', textFields.length, 'required text fields');
            
            textFields.forEach((field, index) => {
                const fieldName = field.name || field.id || 'unnamed';
                const fieldValue = field.value;
                const isEmpty = !fieldValue || !fieldValue.trim();
                
                console.log(`   ${index + 1}. ${fieldName}:`, {
                    value: fieldValue ? `"${fieldValue.substring(0, 30)}${fieldValue.length > 30 ? '...' : ''}"` : 'EMPTY',
                    isEmpty: isEmpty,
                    type: field.tagName.toLowerCase()
                });
                
                if (isEmpty) {
                    isValid = false;
                    field.classList.add('border-red-500');
                    const label = field.closest('div').querySelector('label');
                    if (label) {
                        const labelText = label.textContent.trim().split('*')[0].trim();
                        missingFields.push(labelText);
                        console.log(`      ❌ MISSING: ${labelText}`);
                    }
                } else {
                    field.classList.remove('border-red-500');
                    console.log(`      ✅ Valid`);
                }
            });

            // Check radio buttons (difficulty level)
            console.log('\n🔘 Checking radio buttons (difficulty level)...');
            const difficultyRadios = tutorialBasicInfo.querySelectorAll('input[name="difficulty_level"]');
            console.log('   Found', difficultyRadios.length, 'difficulty level radio buttons');
            
            const difficultyChecked = Array.from(difficultyRadios).some(radio => {
                console.log(`   - ${radio.value}: ${radio.checked ? '✅ CHECKED' : '⚪ not checked'}`);
                return radio.checked;
            });
            
            console.log('   Difficulty level selected:', difficultyChecked);
            
            if (!difficultyChecked) {
                isValid = false;
                missingFields.push('Difficulty Level');
                console.log('   ❌ MISSING: Difficulty Level');
                difficultyRadios.forEach(radio => {
                    radio.closest('label').classList.add('border-red-500');
                });
            } else {
                console.log('   ✅ Valid');
                difficultyRadios.forEach(radio => {
                    radio.closest('label').classList.remove('border-red-500');
                });
            }

            console.log('\n📊 VALIDATION SUMMARY:');
            console.log('   Overall valid:', isValid);
            console.log('   Missing fields count:', missingFields.length);
            if (missingFields.length > 0) {
                console.log('   Missing fields:', missingFields);
            }

            if (!isValid) {
                console.log('\n❌ VALIDATION FAILED - Showing alert');
                alert('Please fill in all required fields before proceeding:\n- ' + missingFields.join('\n- '));
                console.log('=================================================\n');
                return;
            }
            
            console.log('\n✅ VALIDATION PASSED - Proceeding to next step...');

            // Switch sections
            console.log('\n🔄 Switching sections...');
            console.log('   Hiding: tutorialBasicInfo');
            tutorialBasicInfo.classList.add('hidden');
            console.log('   Showing: tutorialSteps');
            tutorialSteps.classList.remove('hidden');
            
            // Update stepper
            console.log('\n🎨 Updating stepper indicators...');
            console.log('   Step 1 -> Green (completed)');
            step1Indicator.classList.remove('bg-blue-500');
            step1Indicator.classList.add('bg-green-500');
            step1Indicator.innerHTML = '✓';
            step1Label.classList.remove('text-blue-500');
            step1Label.classList.add('text-green-500');
            
            console.log('   Step 2 -> Blue (active)');
            step2Indicator.classList.remove('bg-gray-300');
            step2Indicator.classList.add('bg-blue-500');
            step2Label.classList.remove('text-gray-400');
            step2Label.classList.add('text-blue-500');

            // Add first step if none exist
            console.log('\n➕ Checking if we need to add first step...');
            console.log('   Current stepCounter:', stepCounter);
            if (stepCounter === 0) {
                console.log('   Adding first step automatically');
                addStep();
            } else {
                console.log('   Steps already exist, skipping');
            }

            // Scroll to top
            console.log('\n⬆️ Scrolling to top of page');
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            console.log('\n✅ NAVIGATION COMPLETE!');
            console.log('=================================================\n');
        });

    if (backToBasicBtn) {
        backToBasicBtn.addEventListener('click', function() {
            console.log('=================================================');
            console.log('⬅️ BACK BUTTON CLICKED - Returning to basic info...');
            console.log('=================================================');
            
            tutorialSteps.classList.add('hidden');
            tutorialBasicInfo.classList.remove('hidden');
            
            // Update stepper
            step1Indicator.classList.remove('bg-green-500');
            step1Indicator.classList.add('bg-blue-500');
            step1Indicator.innerHTML = '1';
            step1Label.classList.remove('text-green-500');
            step1Label.classList.add('text-blue-500');
            
            step2Indicator.classList.remove('bg-blue-500');
            step2Indicator.classList.add('bg-gray-300');
            step2Label.classList.remove('text-blue-500');
            step2Label.classList.add('text-gray-400');

            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            console.log('✅ Returned to basic info section');
            console.log('=================================================\n');
        });
    } else {
        console.error('❌ Back button not found!');
    }

    // Add Step Functionality
    const addStepBtn = document.getElementById('addStepBtn');
    const stepsContainer = document.getElementById('stepsContainer');

    if (addStepBtn) {
        addStepBtn.addEventListener('click', addStep);
    }

    function addStep() {
        stepCounter++;
        const stepDiv = document.createElement('div');
        stepDiv.className = 'bg-white border-2 border-gray-200 rounded-lg p-6 step-item';
        stepDiv.dataset.stepId = stepCounter;
        
        stepDiv.innerHTML = `
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800 flex items-center">
                    <span class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm font-bold">${stepCounter}</span>
                    Step ${stepCounter}
                </h3>
                <div class="flex gap-2">
                    <button type="button" onclick="moveStepUp(${stepCounter})" class="text-gray-500 hover:text-blue-500 transition" title="Move Up">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                        </svg>
                    </button>
                    <button type="button" onclick="moveStepDown(${stepCounter})" class="text-gray-500 hover:text-blue-500 transition" title="Move Down">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <button type="button" onclick="removeStep(${stepCounter})" class="text-gray-500 hover:text-red-500 transition" title="Remove Step">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <!-- Step Order -->
                <input type="hidden" name="steps[${stepCounter}][step_order]" value="${stepCounter}" class="step-order-input">

                <!-- Step Title -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        Step Title <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="steps[${stepCounter}][title]" 
                        placeholder="e.g., Prepare Your Materials" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        required
                    />
                </div>

                <!-- Step Content -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        Step Instructions <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="steps[${stepCounter}][content]" 
                        rows="4" 
                        placeholder="Provide detailed instructions for this step..."
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        required
                    ></textarea>
                </div>

                <!-- Step Image -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        Step Image
                        <span class="text-sm font-normal text-gray-500 ml-2">Optional</span>
                    </label>
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <input 
                                type="file" 
                                name="steps[${stepCounter}][image]" 
                                accept="image/*"
                                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                onchange="previewStepImage(this, ${stepCounter})"
                            />
                        </div>
                    </div>
                    <img id="stepImagePreview${stepCounter}" class="mt-3 rounded-lg shadow-md hidden max-h-32" />
                </div>

                <!-- Step Video URL -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        Step Video URL
                        <span class="text-sm font-normal text-gray-500 ml-2">Optional</span>
                    </label>
                    <input 
                        type="url" 
                        name="steps[${stepCounter}][video_url]" 
                        placeholder="https://youtube.com/watch?v=..." 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                    />
                </div>

                <!-- Duration -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        Step Duration
                        <span class="text-sm font-normal text-gray-500 ml-2">Minutes</span>
                    </label>
                    <input 
                        type="number" 
                        name="steps[${stepCounter}][duration]" 
                        min="1" 
                        max="999" 
                        placeholder="5"
                        class="w-32 border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                    />
                </div>

                <!-- Tips -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        Tips & Notes
                        <span class="text-sm font-normal text-gray-500 ml-2">Optional</span>
                    </label>
                    <textarea 
                        name="steps[${stepCounter}][tips]" 
                        rows="2" 
                        placeholder="Any helpful tips or warnings for this step..."
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                    ></textarea>
                </div>
            </div>
        `;
        
        stepsContainer.appendChild(stepDiv);
        updateStepNumbers();
    }

    function removeStep(stepId) {
        if (confirm('Are you sure you want to remove this step?')) {
            const stepDiv = document.querySelector(`[data-step-id="${stepId}"]`);
            if (stepDiv) {
                stepDiv.remove();
                updateStepNumbers();
            }
        }
    }

    function moveStepUp(stepId) {
        const stepDiv = document.querySelector(`[data-step-id="${stepId}"]`);
        const previousStep = stepDiv.previousElementSibling;
        
        if (previousStep) {
            stepsContainer.insertBefore(stepDiv, previousStep);
            updateStepNumbers();
        }
    }

    function moveStepDown(stepId) {
        const stepDiv = document.querySelector(`[data-step-id="${stepId}"]`);
        const nextStep = stepDiv.nextElementSibling;
        
        if (nextStep) {
            stepsContainer.insertBefore(nextStep, stepDiv);
            updateStepNumbers();
        }
    }

    function updateStepNumbers() {
        const steps = stepsContainer.querySelectorAll('.step-item');
        steps.forEach((step, index) => {
            const orderInput = step.querySelector('.step-order-input');
            const stepNumber = step.querySelector('h3 span');
            const stepTitle = step.querySelector('h3');
            
            if (orderInput) orderInput.value = index + 1;
            if (stepNumber) stepNumber.textContent = index + 1;
            if (stepTitle) {
                const titleText = stepTitle.childNodes[2];
                if (titleText) titleText.textContent = ` Step ${index + 1}`;
            }
        });
    }

    function previewStepImage(input, stepId) {
        const file = input.files[0];
        const preview = document.getElementById(`stepImagePreview${stepId}`);
        
        if (file && preview) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    // Character counters
    const titleInput = document.getElementById('title');
    const titleCounter = document.getElementById('titleCounter');
    const descInput = document.getElementById('description');
    const descCounter = document.getElementById('descCounter');

    function updateCounter(input, counter, max) {
        if (!input || !counter) return;
        const count = input.value.length;
        counter.textContent = `${count}/${max}`;
        counter.classList.toggle('text-red-500', count > max * 0.9);
    }

    if (titleInput && titleCounter) {
        titleInput.addEventListener('input', () => updateCounter(titleInput, titleCounter, 255));
        updateCounter(titleInput, titleCounter, 255);
    }

    if (descInput && descCounter) {
        descInput.addEventListener('input', () => updateCounter(descInput, descCounter, 500));
        updateCounter(descInput, descCounter, 500);
    }

    // Thumbnail upload and preview
    const thumbnailArea = document.getElementById('thumbnailArea');
    const thumbnailInput = document.getElementById('thumbnail_image');
    const thumbnailPreview = document.getElementById('thumbnailPreview');

    if (thumbnailArea && thumbnailInput && thumbnailPreview) {
        // Click to upload
        thumbnailArea.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            thumbnailInput.click();
        });

        // Drag and drop
        thumbnailArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.stopPropagation();
            thumbnailArea.classList.add('border-blue-500', 'bg-blue-50');
        });

        thumbnailArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            e.stopPropagation();
            thumbnailArea.classList.remove('border-blue-500', 'bg-blue-50');
        });

        thumbnailArea.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
            thumbnailArea.classList.remove('border-blue-500', 'bg-blue-50');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                // Validate file
                if (validateImageFile(files[0])) {
                    // Create a new FileList-like object
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(files[0]);
                    thumbnailInput.files = dataTransfer.files;
                    previewImage(files[0]);
                }
            }
        });

        thumbnailInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                if (validateImageFile(file)) {
                    previewImage(file);
                } else {
                    // Clear the input
                    event.target.value = '';
                }
            }
        });

        function validateImageFile(file) {
            // Check if it's an image
            if (!file.type.startsWith('image/')) {
                alert('Please upload an image file (JPG, PNG, GIF, etc.)');
                return false;
            }

            // Check file size (max 2MB)
            const maxSize = 2 * 1024 * 1024; // 2MB in bytes
            if (file.size > maxSize) {
                alert('Image file size must be less than 2MB. Your file is ' + (file.size / (1024 * 1024)).toFixed(2) + 'MB');
                return false;
            }

            return true;
        }

        function previewImage(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                thumbnailPreview.src = e.target.result;
                thumbnailPreview.classList.remove('hidden');
                thumbnailArea.style.display = 'none';
            }
            reader.onerror = function() {
                alert('Error reading file. Please try again.');
            }
            reader.readAsDataURL(file);
        }

        // Allow clicking preview to change image
        thumbnailPreview.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            thumbnailInput.click();
        });

        // Optional: Add a reset/remove button behavior
        thumbnailPreview.style.cursor = 'pointer';
        thumbnailPreview.title = 'Click to change image';
    }

    // Form submission handling
    const form = document.getElementById('tutorialForm');
    
    form.addEventListener('submit', function(e) {
        const steps = stepsContainer.querySelectorAll('.step-item');
        
        if (steps.length === 0) {
            e.preventDefault();
            alert('Please add at least one step to your tutorial.');
            return false;
        }

        // Validate all step required fields
        let isValid = true;
        steps.forEach(step => {
            const requiredFields = step.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields in all steps.');
            return false;
        }

        console.log('Form submitted successfully');
    });

    // Warn before leaving if form has data
    let formChanged = false;
    form.addEventListener('input', () => formChanged = true);
    
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    }); // End of DOMContentLoaded
</script>
@endsection
@endsection