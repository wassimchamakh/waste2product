<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\Tutorial;
use App\Models\TutoStep;
use App\Models\TutoComment;
use App\Models\UserTutorialProgress;
use App\Models\UserStepCompletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Pail\ValueObjects\Origin\Console;

class TutorialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
    {
        // Available categories from enum
        $categories = [
            'Recycling' => 'Recycling',
            'Composting' => 'Composting', 
            'Energy' => 'Energy',
            'Water' => 'Water',
            'Waste Reduction' => 'Waste Reduction',
            'Gardening' => 'Gardening',
            'DIY' => 'DIY',
            'General' => 'General'
        ];

        // Available difficulty levels from enum
        $difficultyLevels = [
            'Beginner' => 'Beginner',
            'Intermediate' => 'Intermediate',
            'Advanced' => 'Advanced',
            'Expert' => 'Expert'
        ];

        $query = Tutorial::with(['creator']) // 'creator' relationship for created_by
            ->withCount(['steps', 'comments']); // Count related steps and comments

        // Handle filter parameter
        $filter = $request->get('filter');
        
        if ($filter === 'my-tutorials') {
            // Show only tutorials created by the current user
            $query->where('created_by', Auth::id());
        } elseif ($filter === 'in-progress') {
            // Show only tutorials that user has started but not completed
            $query->whereHas('userProgress', function($q) {
                $q->where('user_id', Auth::id())
                  ->where('is_completed', false)
                  ->whereNotNull('started_at');
            });
        } elseif ($filter === 'completed') {
            // Show only tutorials that user has completed
            $query->whereHas('userProgress', function($q) {
                $q->where('user_id', Auth::id())
                  ->where('is_completed', true);
            });
        } else {
            // Default: only show published tutorials
            $query->where('status', 'Published');
        }

        // Apply other filters
        $query->when($request->search, function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%')
                      ->orWhere('tags', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->category, function($query) use ($request) {
                $query->where('category', $request->category);
            })
            ->when($request->difficulty, function($query) use ($request) {
                $query->where('difficulty_level', $request->difficulty);
            })
            ->when($request->duration, function($query) use ($request) {
                switch($request->duration) {
                    case 'short':
                        $query->where('estimated_duration', '<', 120); // Under 2 hours
                        break;
                    case 'medium':
                        $query->whereBetween('estimated_duration', [120, 300]); // 2-5 hours
                        break;
                    case 'long':
                        $query->where('estimated_duration', '>', 300); // 5+ hours
                        break;
                }
            })
            ->orderBy($this->getSortColumn($request->sort), $this->getSortDirection($request->sort));

        $tutorials = $query->paginate(12);

        return view('FrontOffice.tutorials.index', compact('tutorials', 'categories', 'difficultyLevels'));
    }

    private function getSortColumn($sort)
    {
        switch($sort) {
            case 'popular':
                return 'views_count';
            case 'rated':
                return 'average_rating';
            case 'shortest':
                return 'estimated_duration';
            case 'newest':
            default:
                return 'created_at';
        }
    }

    private function getSortDirection($sort)
    {
        switch($sort) {
            case 'shortest':
                return 'asc';
            case 'popular':
            case 'rated':
            case 'newest':
            default:
                return 'desc';
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Available categories
        $categories = [
            'Recycling' => 'Recyclage',
            'Composting' => 'Compostage', 
            'Energy' => 'Énergie',
            'Water' => 'Eau',
            'Waste Reduction' => 'Réduction des Déchets',
            'Gardening' => 'Jardinage',
            'DIY' => 'Bricolage',
            'General' => 'Général'
        ];

        // Available difficulty levels
        $difficultyLevels = [
            'Beginner' => 'Débutant',
            'Intermediate' => 'Intermédiaire',
            'Advanced' => 'Avancé',
            'Expert' => 'Expert'
        ];

        return view('FrontOffice.tutorials.create', compact('categories', 'difficultyLevels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:500',
                'content' => 'required|string',
                'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'intro_video_url' => 'nullable|url',
                'difficulty_level' => 'required|in:Beginner,Intermediate,Advanced,Expert',
                'category' => 'required|in:Recycling,Composting,Energy,Water,Waste Reduction,Gardening,DIY,General',
                'estimated_duration' => 'nullable|integer|min:0', // Will be auto-calculated from steps
                'prerequisites' => 'nullable|string',
                'learning_outcomes' => 'nullable|string',
                'tags' => 'nullable|string',
                'status' => 'required|in:Draft,Published',
                'is_featured' => 'boolean'
            ]);

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail_image')) {
                $file = $request->file('thumbnail_image');
                // Store in storage/app/public/tutorials/thumbnails
                $path = $file->store('tutorials/thumbnails', 'public');
                $validated['thumbnail_image'] = $path;
            }

            // Set creator
            $validated['created_by'] = Auth::id() ?? 6; // Fallback to user ID 6 if not authenticated
            
            // Set published_at if status is Published
            if ($validated['status'] === 'Published') {
                $validated['published_at'] = now();
            }

            $tutorial = Tutorial::create($validated);

            // Create tutorial steps if provided and calculate total duration
            $totalDuration = 0;
            if ($request->has('steps')) {
                foreach ($request->steps as $index => $stepData) {
                    // Only create step if title or content is provided
                    if (!empty($stepData['title']) || !empty($stepData['content'])) {
                        $stepTime = !empty($stepData['estimated_time']) ? (int)$stepData['estimated_time'] : 0;
                        $totalDuration += $stepTime;
                        
                        // Handle step image upload
                        $stepImageUrl = null;
                        if (isset($stepData['image']) && $stepData['image'] instanceof \Illuminate\Http\UploadedFile) {
                            $stepImagePath = $stepData['image']->store('tutorials/steps', 'public');
                            $stepImageUrl = $stepImagePath;
                        }
                        
                        TutoStep::create([
                            'tutorial_id' => $tutorial->id,
                            'step_number' => $index + 1,
                            'title' => $stepData['title'] ?? 'Step ' . ($index + 1),
                            'description' => $stepData['description'] ?? null,
                            'content' => $stepData['content'] ?? '',
                            'image_url' => $stepImageUrl,
                            'video_url' => $stepData['video_url'] ?? null,
                            'estimated_time' => $stepTime,
                            'tips' => $stepData['tips'] ?? null,
                            'common_mistakes' => $stepData['common_mistakes'] ?? null,
                            'required_materials' => $stepData['required_materials'] ?? null,
                        ]);
                    }
                }
            }

            // Update tutorial with calculated duration
            $tutorial->update(['estimated_duration' => $totalDuration > 0 ? $totalDuration : 0]);

            \DB::commit();

            return redirect()->route('tutorials.show', $tutorial->slug)
                ->with('success', '✅ Tutorial created successfully!');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred while creating the tutorial: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
   public function show($slug)
{
    $tutorial = Tutorial::with(['creator', 'steps' => function($query) {
        $query->orderBy('step_number');
    }, 'comments' => function($query) {
        $query->with('user', 'replies.user')
              ->where('status', 'Approved')
              ->whereNull('parent_comment_id')
              ->orderBy('created_at', 'desc');
    }])
    ->where('slug', $slug)
    ->where('status', 'Published')
    ->firstOrFail();

    // Increment views
    $tutorial->increment('views_count');

    // Get user progress if authenticated
    $userProgress = null;
    $completedSteps = [];
    if (Auth::check()) {
        $userProgress = UserTutorialProgress::where('user_id', Auth::id())
            ->where('tutorial_id', $tutorial->id)
            ->first();
        
        // Get completed steps
        $completedSteps = UserStepCompletion::where('user_id', Auth::id())
            ->where('tutorial_id', $tutorial->id)
            ->pluck('step_id')
            ->toArray();
    }

    // Get more tutorials from the same creator
    $moreFromCreator = Tutorial::where('created_by', $tutorial->created_by)
        ->where('id', '!=', $tutorial->id)
        ->where('status', 'Published')
        ->orderBy('views_count', 'desc')
        ->limit(3)
        ->get();

    // Get related tutorials in the same category
    $relatedTutorials = Tutorial::where('category', $tutorial->category)
        ->where('id', '!=', $tutorial->id)
        ->where('status', 'Published')
        ->orderBy('average_rating', 'desc')
        ->limit(4)
        ->get();

    return view('FrontOffice.tutorials.show', compact('tutorial', 'moreFromCreator', 'relatedTutorials', 'userProgress', 'completedSteps'));
}

public function step($slug, $stepNumber)
{
    $tutorial = Tutorial::with(['creator'])
        ->where('slug', $slug)
        ->where('status', 'Published')
        ->firstOrFail();

    $step = TutoStep::where('tutorial_id', $tutorial->id)
        ->where('step_number', $stepNumber)
        ->firstOrFail();

    $allSteps = TutoStep::where('tutorial_id', $tutorial->id)
        ->orderBy('step_number')
        ->get();

    // Get next and previous steps
    $prevStep = TutoStep::where('tutorial_id', $tutorial->id)
        ->where('step_number', '<', $stepNumber)
        ->orderBy('step_number', 'desc')
        ->first();
        
    $nextStep = TutoStep::where('tutorial_id', $tutorial->id)
        ->where('step_number', '>', $stepNumber)
        ->orderBy('step_number', 'asc')
        ->first();

    // Increment views for the tutorial (only once per session)
    if (!session()->has('viewed_tutorial_' . $tutorial->id)) {
        $tutorial->increment('views_count');
        session()->put('viewed_tutorial_' . $tutorial->id, true);
    }

    // Track user progress if authenticated
    $userProgress = null;
    $completedSteps = [];
    if (Auth::check()) {
        // Get or create progress record
        $userProgress = UserTutorialProgress::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'tutorial_id' => $tutorial->id,
            ],
            [
                'current_step' => $stepNumber,
                'started_at' => now(),
                'last_accessed_at' => now(),
            ]
        );

        // Update current step and last accessed time
        $userProgress->current_step = $stepNumber;
        $userProgress->last_accessed_at = now();
        $userProgress->save();

        // Get completed steps
        $completedSteps = UserStepCompletion::where('user_id', Auth::id())
            ->where('tutorial_id', $tutorial->id)
            ->pluck('step_id')
            ->toArray();
    }

    return view('FrontOffice.tutorials.step', compact('tutorial', 'step', 'allSteps', 'prevStep', 'nextStep', 'userProgress', 'completedSteps'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $tutorial = Tutorial::where('slug', $slug)->firstOrFail();
        
        // Check if user is the creator
        if ($tutorial->created_by !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        // Available categories
        $categories = [
            'Recycling' => 'Recyclage',
            'Composting' => 'Compostage', 
            'Energy' => 'Énergie',
            'Water' => 'Eau',
            'Waste Reduction' => 'Réduction des Déchets',
            'Gardening' => 'Jardinage',
            'DIY' => 'Bricolage',
            'General' => 'Général'
        ];

        // Available difficulty levels
        $difficultyLevels = [
            'Beginner' => 'Débutant',
            'Intermediate' => 'Intermédiaire',
            'Advanced' => 'Avancé',
            'Expert' => 'Expert'
        ];

        return view('FrontOffice.tutorials.edit', compact('tutorial', 'categories', 'difficultyLevels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $slug)
    {
        $tutorial = Tutorial::where('slug', $slug)->firstOrFail();
        
        // Check if user is the creator
        if ($tutorial->created_by !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        try {
            \DB::beginTransaction();

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:500',
                'content' => 'required|string',
                'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'intro_video_url' => 'nullable|url',
                'difficulty_level' => 'required|in:Beginner,Intermediate,Advanced,Expert',
                'category' => 'required|in:Recycling,Composting,Energy,Water,Waste Reduction,Gardening,DIY,General',
                'estimated_duration' => 'nullable|integer|min:0', // Will be auto-calculated from steps
                'prerequisites' => 'nullable|string',
                'learning_outcomes' => 'nullable|string',
                'tags' => 'nullable|string',
                'status' => 'required|in:Draft,Published',
                'is_featured' => 'boolean'
            ]);

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail_image')) {
                // Delete old thumbnail if exists (handle both old and new storage formats)
                if ($tutorial->thumbnail_image && !filter_var($tutorial->thumbnail_image, FILTER_VALIDATE_URL)) {
                    // Try storage path first (new format: tutorials/thumbnails/...)
                    if (\Storage::disk('public')->exists($tutorial->thumbnail_image)) {
                        \Storage::disk('public')->delete($tutorial->thumbnail_image);
                    }
                    // Try old public path (old format: just filename)
                    elseif (file_exists(public_path('uploads/tutorials/' . $tutorial->thumbnail_image))) {
                        unlink(public_path('uploads/tutorials/' . $tutorial->thumbnail_image));
                    }
                }
                
                $file = $request->file('thumbnail_image');
                // Store in storage/app/public/tutorials/thumbnails
                $path = $file->store('tutorials/thumbnails', 'public');
                $validated['thumbnail_image'] = $path;
            }

            // Set published_at if status changed to Published
            if ($validated['status'] === 'Published' && $tutorial->status !== 'Published') {
                $validated['published_at'] = now();
            }

            // Update tutorial
            $tutorial->update($validated);
            
            // Log the slug after update
            Log::info('Tutorial slug after update: ' . $tutorial->slug);
            Log::info('Tutorial title: ' . $tutorial->title);

            // Get existing step IDs
            $existingStepIds = $tutorial->steps()->pluck('id')->toArray();
            $processedStepIds = [];

            // Create/Update tutorial steps if provided and calculate total duration
            $totalDuration = 0;
            if ($request->has('steps')) {
                foreach ($request->steps as $index => $stepData) {
                    // Only process step if title or content is provided
                    if (!empty($stepData['title']) || !empty($stepData['content'])) {
                        $stepTime = !empty($stepData['estimated_time']) ? (int)$stepData['estimated_time'] : 0;
                        $totalDuration += $stepTime;
                        
                        // Check if this is an update to existing step or new step
                        $stepId = $stepData['id'] ?? null;
                        
                        // Handle step image upload
                        $stepImageUrl = null;
                        if (isset($stepData['image']) && $stepData['image'] instanceof \Illuminate\Http\UploadedFile) {
                            // Delete old image if updating
                            if ($stepId) {
                                $existingStep = TutoStep::find($stepId);
                                if ($existingStep && $existingStep->image_url) {
                                    if (Storage::disk('public')->exists($existingStep->image_url)) {
                                        Storage::disk('public')->delete($existingStep->image_url);
                                    }
                                }
                            }
                            // Upload new image
                            $stepImagePath = $stepData['image']->store('tutorials/steps', 'public');
                            $stepImageUrl = $stepImagePath;
                        } elseif ($stepId) {
                            // Keep existing image if no new upload
                            $existingStep = TutoStep::find($stepId);
                            if ($existingStep) {
                                $stepImageUrl = $existingStep->image_url;
                            }
                        }
                        
                        $stepAttributes = [
                            'tutorial_id' => $tutorial->id,
                            'step_number' => $index + 1,
                            'title' => $stepData['title'] ?? 'Step ' . ($index + 1),
                            'description' => $stepData['description'] ?? null,
                            'content' => $stepData['content'] ?? '',
                            'image_url' => $stepImageUrl,
                            'video_url' => $stepData['video_url'] ?? null,
                            'estimated_time' => $stepTime,
                            'tips' => $stepData['tips'] ?? null,
                            'common_mistakes' => $stepData['common_mistakes'] ?? null,
                            'required_materials' => $stepData['required_materials'] ?? null,
                        ];
                        
                        if ($stepId && in_array($stepId, $existingStepIds)) {
                            // Update existing step
                            TutoStep::where('id', $stepId)->update($stepAttributes);
                            $processedStepIds[] = $stepId;
                        } else {
                            // Create new step
                            $newStep = TutoStep::create($stepAttributes);
                            $processedStepIds[] = $newStep->id;
                        }
                    }
                }
            }
            
            // Delete steps that were removed
            $stepsToDelete = array_diff($existingStepIds, $processedStepIds);
            if (!empty($stepsToDelete)) {
                // Delete images for removed steps
                $deletedSteps = TutoStep::whereIn('id', $stepsToDelete)->get();
                foreach ($deletedSteps as $deletedStep) {
                    if ($deletedStep->image_url && Storage::disk('public')->exists($deletedStep->image_url)) {
                        Storage::disk('public')->delete($deletedStep->image_url);
                    }
                }
                TutoStep::whereIn('id', $stepsToDelete)->delete();
            }

            // Update tutorial with calculated duration
            $tutorial->update(['estimated_duration' => $totalDuration > 0 ? $totalDuration : 0]);

            \DB::commit();

            Log::info('Tutorial updated: ' . $tutorial->slug);

            return redirect()->route('tutorials.show', $tutorial->slug)
                ->with('success', '✅ Tutoriel mis à jour avec succès!');

        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error updating tutorial: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour du tutoriel: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        $tutorial = Tutorial::where('slug', $slug)->firstOrFail();
        
        // Check if user is the creator
        if ($tutorial->created_by !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        // Delete thumbnail (handle both old and new storage formats)
        if ($tutorial->thumbnail_image && !filter_var($tutorial->thumbnail_image, FILTER_VALIDATE_URL)) {
            // Try storage path first (new format: tutorials/thumbnails/...)
            if (Storage::disk('public')->exists($tutorial->thumbnail_image)) {
                Storage::disk('public')->delete($tutorial->thumbnail_image);
            }
            // Try old public path (old format: just filename)
            elseif (file_exists(public_path('uploads/tutorials/' . $tutorial->thumbnail_image))) {
                unlink(public_path('uploads/tutorials/' . $tutorial->thumbnail_image));
            }
        }

        $tutorial->delete();

        return redirect()->route('tutorials.index')
            ->with('success', 'Tutoriel supprimé avec succès!');
    }

    /**
     * Store a new comment
     */
    public function storeComment(Request $request)
    {
        $validated = $request->validate([
            'tutorial_id' => 'required|exists:tutorials,id',
            'comment_text' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
            'parent_comment_id' => 'nullable|exists:tuto_comments,id'
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'Approved'; // Auto-approve for now

        $comment = TutoComment::create($validated);

        // Update tutorial ratings if rating provided
        if ($validated['rating']) {
            $tutorial = Tutorial::find($validated['tutorial_id']);
            $tutorial->updateAverageRating();
        }

        return response()->json([
            'success' => true,
            'message' => 'Commentaire ajouté avec succès!',
            'comment' => $comment->load('user')
        ]);
    }

    /**
     * Mark comment as helpful
     */
    public function markCommentHelpful(Request $request)
    {
        $validated = $request->validate([
            'comment_id' => 'required|exists:tuto_comments,id'
        ]);

        $comment = TutoComment::find($validated['comment_id']);
        $comment->increment('helpful_count');

        return response()->json([
            'success' => true,
            'helpful_count' => $comment->helpful_count
        ]);
    }

    /**
     * Toggle bookmark for a tutorial
     */
    public function toggleBookmark(Request $request)
    {
        $validated = $request->validate([
            'tutorial_id' => 'required|exists:tutorials,id'
        ]);

        // For now, just return success - implement proper bookmarks table later
        return response()->json([
            'success' => true,
            'bookmarked' => true,
            'message' => 'Tutoriel ajouté aux favoris!'
        ]);
    }

    /**
     * Mark tutorial as complete
     */
    public function markComplete($id)
    {
        $tutorial = Tutorial::findOrFail($id);
        
        // Get or create progress record
        $progress = UserTutorialProgress::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'tutorial_id' => $id,
            ],
            [
                'started_at' => now(),
            ]
        );

        // Mark as completed if not already
        if (!$progress->is_completed) {
            $progress->is_completed = true;
            $progress->completed_at = now();
            $progress->save();
            
            // Increment tutorial completion count
            $tutorial->increment('completion_count');
        }

        return response()->json([
            'success' => true,
            'message' => 'Félicitations! Tutoriel terminé!'
        ]);
    }

    /**
     * Save user notes for a tutorial/step
     */
    public function saveNotes(Request $request)
    {
        $validated = $request->validate([
            'tutorial_id' => 'required|exists:tutorials,id',
            'step_id' => 'nullable|exists:tuto_steps,id',
            'notes' => 'required|string'
        ]);

        // For now, just return success - implement proper notes table later
        return response()->json([
            'success' => true,
            'message' => 'Notes sauvegardées!'
        ]);
    }

    /**
     * Get user notes for a tutorial/step
     */
    public function getNotes(Request $request)
    {
        $validated = $request->validate([
            'tutorial_id' => 'required|exists:tutorials,id',
            'step_id' => 'nullable|exists:tuto_steps,id'
        ]);

        // For now, return empty notes - implement proper notes table later
        return response()->json([
            'success' => true,
            'notes' => ''
        ]);
    }

    /**
     * Mark a step as complete
     */
    public function markStepComplete(Request $request)
    {
        $validated = $request->validate([
            'tutorial_id' => 'required|exists:tutorials,id',
            'step_id' => 'required|exists:tuto_steps,id'
        ]);

        // Get the step to get step_number
        $step = TutoStep::findOrFail($validated['step_id']);

        // Mark step as complete
        UserStepCompletion::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'tutorial_id' => $validated['tutorial_id'],
                'step_id' => $validated['step_id'],
            ],
            [
                'step_number' => $step->step_number,
                'completed_at' => now(),
            ]
        );

        // Update progress record
        $progress = UserTutorialProgress::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'tutorial_id' => $validated['tutorial_id'],
            ],
            [
                'current_step' => $step->step_number,
                'started_at' => now(),
            ]
        );

        // Update total steps completed
        $totalCompleted = UserStepCompletion::where('user_id', Auth::id())
            ->where('tutorial_id', $validated['tutorial_id'])
            ->count();
        
        $progress->total_steps_completed = $totalCompleted;
        $progress->last_accessed_at = now();

        // Check if all steps are completed
        $totalSteps = TutoStep::where('tutorial_id', $validated['tutorial_id'])->count();
        if ($totalCompleted >= $totalSteps && !$progress->is_completed) {
            $progress->is_completed = true;
            $progress->completed_at = now();
            
            // Increment tutorial completion count
            Tutorial::find($validated['tutorial_id'])->increment('completion_count');
        }

        $progress->save();

        return response()->json([
            'success' => true,
            'message' => 'Étape marquée comme terminée!',
            'progress_percentage' => $progress->progress_percentage,
            'total_completed' => $totalCompleted,
            'total_steps' => $totalSteps,
            'is_tutorial_completed' => $progress->is_completed
        ]);
    }
}
