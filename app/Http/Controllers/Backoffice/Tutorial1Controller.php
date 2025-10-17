<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Tutorial;
use App\Models\TutoStep;
use App\Models\TutoComment;
use App\Models\UserTutorialProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Tutorial1Controller extends Controller
{
    /**
     * Display a listing of all tutorials with statistics
     */
    public function index(Request $request)
    {
        $query = Tutorial::with(['creator', 'steps'])
            ->withCount(['steps', 'comments'])
            ->withAvg('comments', 'rating');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by level (difficulty)
        if ($request->filled('level')) {
            $query->where('difficulty_level', $request->level);
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        if ($sortBy === 'popularity') {
            $query->orderBy('views_count', $sortOrder);
        } elseif ($sortBy === 'rating') {
            $query->orderBy('comments_avg_rating', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $tutorials = $query->paginate(15);

        // Get statistics
        $stats = [
            'total' => Tutorial::count(),
            'published' => Tutorial::where('status', 'Published')->count(),
            'draft' => Tutorial::where('status', 'Draft')->count(),
            'total_views' => Tutorial::sum('views_count'),
            'total_completions' => Tutorial::sum('completion_count'),
            'avg_rating' => Tutorial::join('tuto_comments', 'tutorials.id', '=', 'tuto_comments.tutorial_id')
                ->avg('tuto_comments.rating'),
        ];

        return view('BackOffice.tutorials.index', compact('tutorials', 'stats'));
    }

    /**
     * Display the specified tutorial with all details
     */
    public function show($id)
    {
        $tutorial = Tutorial::with([
            'creator',
            'steps' => function($query) {
                $query->orderBy('step_number', 'asc');
            },
            'comments' => function($query) {
                $query->with(['user', 'replies.user'])
                      ->whereNull('parent_comment_id')
                      ->orderBy('created_at', 'desc');
            }
        ])
        ->withCount('steps', 'comments')
        ->findOrFail($id);

        // Get basic statistics
        $stepsCount = $tutorial->steps_count;
        $commentsCount = $tutorial->comments_count;
        $totalViews = $tutorial->views_count ?? 0;
        
        // Get rating statistics
        $avgRating = TutoComment::where('tutorial_id', $id)
            ->whereNotNull('rating')
            ->avg('rating') ?? 0;

        // Get progress statistics
        $progressStats = UserTutorialProgress::where('tutorial_id', $id)
            ->selectRaw('
                COUNT(*) as total_users,
                SUM(CASE WHEN is_completed = 1 THEN 1 ELSE 0 END) as completed_users
            ')
            ->first();

        $totalProgress = $progressStats->total_users ?? 0;
        $completedCount = $progressStats->completed_users ?? 0;
        $completionRate = $totalProgress > 0 ? ($completedCount / $totalProgress * 100) : 0;

        // Get recent comments (limit to 5)
        $recentComments = TutoComment::with('user')
            ->where('tutorial_id', $id)
            ->whereNull('parent_comment_id')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get top users by progress
        $topUsers = UserTutorialProgress::with('user')
            ->where('tutorial_id', $id)
            ->orderBy('total_steps_completed', 'desc')
            ->orderBy('completed_at', 'asc')
            ->limit(5)
            ->get()
            ->map(function($progress) use ($stepsCount) {
                $progress->completed_steps = $progress->total_steps_completed;
                $progress->progress_percentage = $stepsCount > 0 
                    ? ($progress->total_steps_completed / $stepsCount * 100) 
                    : 0;
                return $progress;
            });

        return view('BackOffice.tutorials.show', compact(
            'tutorial',
            'stepsCount',
            'commentsCount',
            'totalViews',
            'avgRating',
            'totalProgress',
            'completionRate',
            'recentComments',
            'topUsers'
        ));
    }

    /**
     * Show all steps for a tutorial
     */
    public function steps($id)
    {
        $tutorial = Tutorial::with(['steps' => function($query) {
            $query->orderBy('step_number', 'asc');
        }])->findOrFail($id);

        $steps = $tutorial->steps;
        
        // Get total users who started this tutorial
        $totalUsers = UserTutorialProgress::where('tutorial_id', $id)->count();
        
        // Calculate statistics for each step
        foreach ($steps as $step) {
            // Count how many users completed this step
            $step->completions_count = DB::table('user_step_completions')
                ->where('step_id', $step->id)
                ->count();
            
            // Get recent completions
            $step->recent_completions = DB::table('user_step_completions')
                ->join('users', 'user_step_completions.user_id', '=', 'users.id')
                ->where('step_id', $step->id)
                ->orderBy('user_step_completions.completed_at', 'desc')
                ->limit(6)
                ->select('users.name', 'users.id as user_id', 'user_step_completions.completed_at')
                ->get()
                ->map(function($completion) {
                    $completion->user = (object)['name' => $completion->name];
                    $completion->completed_at = \Carbon\Carbon::parse($completion->completed_at);
                    return $completion;
                });
        }
        
        // Calculate overall statistics
        $totalCompletions = DB::table('user_step_completions')
            ->join('tuto_steps', 'user_step_completions.step_id', '=', 'tuto_steps.id')
            ->where('tuto_steps.tutorial_id', $id)
            ->count();
            
        $avgDuration = $steps->avg('estimated_duration') ?? 0;
        
        // Prevent division by zero
        $stepsCount = $steps->count();
        $avgCompletionRate = ($totalUsers > 0 && $stepsCount > 0)
            ? ($totalCompletions / ($totalUsers * $stepsCount) * 100)
            : 0;

        return view('BackOffice.tutorials.steps', compact('tutorial', 'steps', 'totalUsers', 'totalCompletions', 'avgDuration', 'avgCompletionRate'));
    }

    /**
     * Show all comments for a tutorial
     */
    public function comments(Request $request, $id)
    {
        $tutorial = Tutorial::findOrFail($id);

        $query = TutoComment::with(['user', 'replies.user'])
            ->where('tutorial_id', $id)
            ->whereNull('parent_comment_id');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', ucfirst($request->status));
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $comments = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get statistics
        $totalComments = TutoComment::where('tutorial_id', $id)->whereNull('parent_comment_id')->count();
        $approvedCount = TutoComment::where('tutorial_id', $id)->whereNull('parent_comment_id')->where('status', 'Approved')->count();
        $pendingCount = TutoComment::where('tutorial_id', $id)->whereNull('parent_comment_id')->where('status', 'Pending')->count();
        $rejectedCount = TutoComment::where('tutorial_id', $id)->whereNull('parent_comment_id')->where('status', 'Rejected')->count();

        return view('BackOffice.tutorials.comments', compact('tutorial', 'comments', 'totalComments', 'approvedCount', 'pendingCount', 'rejectedCount'));
    }

    /**
     * Show all user progress for a tutorial
     */
    public function progress(Request $request, $id)
    {
        $tutorial = Tutorial::with('steps')->findOrFail($id);
        $totalSteps = $tutorial->steps->count();

        $query = UserTutorialProgress::with(['user'])
            ->where('tutorial_id', $id);

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->where('is_completed', true);
            } elseif ($request->status === 'in_progress') {
                $query->where('is_completed', false)
                      ->where('total_steps_completed', '>', 0);
            } elseif ($request->status === 'started') {
                $query->where('total_steps_completed', 0);
            }
        }

        // Search by user
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->get('sort', 'progress_desc');
        if ($sort === 'progress_desc') {
            $query->orderBy('total_steps_completed', 'desc');
        } elseif ($sort === 'progress_asc') {
            $query->orderBy('total_steps_completed', 'asc');
        } elseif ($sort === 'recent') {
            $query->orderBy('last_accessed_at', 'desc');
        }

        $progressData = $query->paginate(20);

        // Add calculated fields to each progress record
        foreach ($progressData as $progress) {
            $progress->completed_steps = $progress->total_steps_completed;
            $progress->total_steps = $totalSteps;
            $progress->progress_percentage = $totalSteps > 0 
                ? ($progress->total_steps_completed / $totalSteps * 100)
                : 0;
            $progress->time_spent = 0; // No time tracking in current schema
            $progress->rating = null; // Get from comments if available
            
            // Check if user rated this tutorial
            $userComment = TutoComment::where('tutorial_id', $id)
                ->where('user_id', $progress->user_id)
                ->whereNotNull('rating')
                ->first();
            if ($userComment) {
                $progress->rating = $userComment->rating;
            }
        }

        // Statistics
        $totalUsers = UserTutorialProgress::where('tutorial_id', $id)->count();
        $completedUsers = UserTutorialProgress::where('tutorial_id', $id)->where('is_completed', true)->count();
        $inProgressUsers = UserTutorialProgress::where('tutorial_id', $id)
            ->where('is_completed', false)
            ->where('total_steps_completed', '>', 0)
            ->count();
        $completionRate = $totalUsers > 0 ? ($completedUsers / $totalUsers * 100) : 0;
        $avgTimeSpent = 0; // No time tracking

        return view('BackOffice.tutorials.progress', compact(
            'tutorial',
            'progressData',
            'totalUsers',
            'completedUsers',
            'inProgressUsers',
            'completionRate',
            'avgTimeSpent'
        ));
    }

    /**
     * Update tutorial status (Publish/Draft)
     */
    public function updateStatus(Request $request, $id)
    {
        $tutorial = Tutorial::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:Published,Draft'
        ]);

        $tutorial->update([
            'status' => $request->status,
            'published_at' => $request->status === 'Published' ? now() : null
        ]);

        return back()->with('success', 'Statut du tutoriel mis à jour avec succès!');
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured($id)
    {
        $tutorial = Tutorial::findOrFail($id);
        $tutorial->update(['is_featured' => !$tutorial->is_featured]);

        return back()->with('success', 'Statut "En vedette" mis à jour!');
    }

    /**
     * Moderate comment (approve/reject/delete)
     */
    public function moderateComment(Request $request, $id)
    {
        $comment = TutoComment::findOrFail($id);
        
        $action = $request->input('action');
        
        if ($action === 'approve') {
            $comment->update(['status' => 'Approved']);
            return back()->with('success', 'Commentaire approuvé!');
        } elseif ($action === 'reject') {
            $comment->update(['status' => 'Rejected']);
            return back()->with('success', 'Commentaire rejeté!');
        } elseif ($action === 'delete') {
            $comment->delete();
            return back()->with('success', 'Commentaire supprimé!');
        }

        return back()->with('error', 'Action invalide!');
    }

    /**
     * Delete tutorial
     */
    public function destroy($id)
    {
        $tutorial = Tutorial::findOrFail($id);
        
        // Delete associated files
        if ($tutorial->thumbnail_image && \Storage::disk('public')->exists($tutorial->thumbnail_image)) {
            \Storage::disk('public')->delete($tutorial->thumbnail_image);
        }

        // Delete step images
        foreach ($tutorial->steps as $step) {
            if ($step->image_url && \Storage::disk('public')->exists($step->image_url)) {
                \Storage::disk('public')->delete($step->image_url);
            }
        }

        $tutorial->delete();

        return redirect()->route('admin.tutorials.index')
            ->with('success', 'Tutoriel supprimé avec succès!');
    }

    /**
     * Show form to create a new tutorial
     */
    public function create()
    {
        // Get all users to select as creator
        $users = User::orderBy('name', 'asc')->get();
        
        return view('BackOffice.tutorials.create', compact('users'));
    }

    /**
     * Store a new tutorial
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'intro_video_url' => 'nullable|url',
            'difficulty_level' => 'required|in:Beginner,Intermediate,Advanced,Expert',
            'category' => 'required|in:Recycling,Composting,Energy,Water,Waste Reduction,Gardening,DIY,General',
            'estimated_duration' => 'nullable|integer|min:1',
            'prerequisites' => 'nullable|string',
            'learning_outcomes' => 'nullable|string',
            'tags' => 'nullable|string',
            'status' => 'required|in:Published,Draft',
            'is_featured' => 'boolean',
            'created_by' => 'required|exists:users,id',
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail_image')) {
            $file = $request->file('thumbnail_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('tutorials/thumbnails', $filename, 'public');
            $validated['thumbnail_image'] = $path;
        }

        // Generate slug from title
        $validated['slug'] = \Str::slug($validated['title']);

        // Set published_at if status is Published
        if ($validated['status'] === 'Published') {
            $validated['published_at'] = now();
        }

        $tutorial = Tutorial::create($validated);

        return redirect()->route('admin.tutorials.show', $tutorial->id)
            ->with('success', 'Tutoriel créé avec succès!');
    }

    /**
     * Show form to edit a tutorial
     */
    public function edit($id)
    {
        $tutorial = Tutorial::with('steps')->findOrFail($id);
        
        // Get all users to select as creator
        $users = User::orderBy('name', 'asc')->get();
        
        return view('BackOffice.tutorials.edit', compact('tutorial', 'users'));
    }

    /**
     * Update a tutorial
     */
    public function update(Request $request, $id)
    {
        $tutorial = Tutorial::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'intro_video_url' => 'nullable|url',
            'difficulty_level' => 'required|in:Beginner,Intermediate,Advanced,Expert',
            'category' => 'required|in:Recycling,Composting,Energy,Water,Waste Reduction,Gardening,DIY,General',
            'estimated_duration' => 'nullable|integer|min:1',
            'prerequisites' => 'nullable|string',
            'learning_outcomes' => 'nullable|string',
            'tags' => 'nullable|string',
            'status' => 'required|in:Published,Draft',
            'is_featured' => 'boolean',
            'created_by' => 'required|exists:users,id',
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail_image')) {
            // Delete old thumbnail
            if ($tutorial->thumbnail_image && \Storage::disk('public')->exists($tutorial->thumbnail_image)) {
                \Storage::disk('public')->delete($tutorial->thumbnail_image);
            }
            
            $file = $request->file('thumbnail_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('tutorials/thumbnails', $filename, 'public');
            $validated['thumbnail_image'] = $path;
        }

        // Update slug if title changed
        if (isset($validated['title']) && $validated['title'] !== $tutorial->title) {
            $validated['slug'] = \Str::slug($validated['title']);
        }

        // Set published_at if status changes to Published
        if ($validated['status'] === 'Published' && $tutorial->status !== 'Published') {
            $validated['published_at'] = now();
        }

        $tutorial->update($validated);

        return redirect()->route('admin.tutorials.show', $tutorial->id)
            ->with('success', 'Tutoriel mis à jour avec succès!');
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,draft,delete,feature,unfeature',
            'tutorial_ids' => 'required|array',
            'tutorial_ids.*' => 'exists:tutorials,id'
        ]);

        $action = $request->action;
        $ids = $request->tutorial_ids;

        switch ($action) {
            case 'publish':
                Tutorial::whereIn('id', $ids)->update([
                    'status' => 'Published',
                    'published_at' => now()
                ]);
                return back()->with('success', count($ids) . ' tutoriels publiés!');

            case 'draft':
                Tutorial::whereIn('id', $ids)->update(['status' => 'Draft']);
                return back()->with('success', count($ids) . ' tutoriels mis en brouillon!');

            case 'feature':
                Tutorial::whereIn('id', $ids)->update(['is_featured' => true]);
                return back()->with('success', count($ids) . ' tutoriels mis en vedette!');

            case 'unfeature':
                Tutorial::whereIn('id', $ids)->update(['is_featured' => false]);
                return back()->with('success', count($ids) . ' tutoriels retirés de la vedette!');

            case 'delete':
                $tutorials = Tutorial::whereIn('id', $ids)->get();
                foreach ($tutorials as $tutorial) {
                    // Delete files
                    if ($tutorial->thumbnail_image && \Storage::disk('public')->exists($tutorial->thumbnail_image)) {
                        \Storage::disk('public')->delete($tutorial->thumbnail_image);
                    }
                    foreach ($tutorial->steps as $step) {
                        if ($step->image_url && \Storage::disk('public')->exists($step->image_url)) {
                            \Storage::disk('public')->delete($step->image_url);
                        }
                    }
                }
                Tutorial::whereIn('id', $ids)->delete();
                return back()->with('success', count($ids) . ' tutoriels supprimés!');
        }

        return back()->with('error', 'Action invalide!');
    }

    /**
     * Show form to create a new step
     */
    public function createStep($tutorialId)
    {
        $tutorial = Tutorial::findOrFail($tutorialId);
        
        // Get the next step number
        $nextStepNumber = TutoStep::where('tutorial_id', $tutorialId)->max('step_number') + 1;
        
        return view('BackOffice.tutorials.steps.create', compact('tutorial', 'nextStepNumber'));
    }

    /**
     * Store a new step
     */
    public function storeStep(Request $request, $tutorialId)
    {
        $tutorial = Tutorial::findOrFail($tutorialId);
        
        $validated = $request->validate([
            'step_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'estimated_time' => 'nullable|integer|min:1',
            'tips' => 'nullable|string',
            'common_mistakes' => 'nullable|string',
            'required_materials' => 'nullable|string',
        ]);

        $validated['tutorial_id'] = $tutorialId;

        // Convert empty strings to null for nullable fields
        $validated['estimated_time'] = !empty($validated['estimated_time']) ? $validated['estimated_time'] : null;
        $validated['description'] = !empty($validated['description']) ? $validated['description'] : null;
        $validated['video_url'] = !empty($validated['video_url']) ? $validated['video_url'] : null;
        $validated['tips'] = !empty($validated['tips']) ? $validated['tips'] : null;
        $validated['common_mistakes'] = !empty($validated['common_mistakes']) ? $validated['common_mistakes'] : null;
        $validated['required_materials'] = !empty($validated['required_materials']) ? $validated['required_materials'] : null;

        // Handle image upload
        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('tutorials/steps', $filename, 'public');
            $validated['image_url'] = $path;
        }

        TutoStep::create($validated);

        return redirect()->route('admin.tutorials.steps', $tutorialId)
            ->with('success', 'Étape créée avec succès!');
    }

    /**
     * Show form to edit a step
     */
    public function editStep($tutorialId, $stepId)
    {
        $tutorial = Tutorial::findOrFail($tutorialId);
        $step = TutoStep::where('tutorial_id', $tutorialId)->findOrFail($stepId);
        
        return view('BackOffice.tutorials.steps.edit', compact('tutorial', 'step'));
    }

    /**
     * Update a step
     */
    public function updateStep(Request $request, $tutorialId, $stepId)
    {
        $tutorial = Tutorial::findOrFail($tutorialId);
        $step = TutoStep::where('tutorial_id', $tutorialId)->findOrFail($stepId);
        
        $validated = $request->validate([
            'step_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'estimated_time' => 'nullable|integer|min:1',
            'tips' => 'nullable|string',
            'common_mistakes' => 'nullable|string',
            'required_materials' => 'nullable|string',
        ]);

        // Convert empty strings to null for nullable fields
        $validated['estimated_time'] = !empty($validated['estimated_time']) ? $validated['estimated_time'] : null;
        $validated['description'] = !empty($validated['description']) ? $validated['description'] : null;
        $validated['video_url'] = !empty($validated['video_url']) ? $validated['video_url'] : null;
        $validated['tips'] = !empty($validated['tips']) ? $validated['tips'] : null;
        $validated['common_mistakes'] = !empty($validated['common_mistakes']) ? $validated['common_mistakes'] : null;
        $validated['required_materials'] = !empty($validated['required_materials']) ? $validated['required_materials'] : null;

        // Handle image upload
        if ($request->hasFile('image_url')) {
            // Delete old image
            if ($step->image_url && \Storage::disk('public')->exists($step->image_url)) {
                \Storage::disk('public')->delete($step->image_url);
            }
            
            $file = $request->file('image_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('tutorials/steps', $filename, 'public');
            $validated['image_url'] = $path;
        }

        $step->update($validated);

        return redirect()->route('admin.tutorials.steps', $tutorialId)
            ->with('success', 'Étape mise à jour avec succès!');
    }

    /**
     * Delete a step
     */
    public function destroyStep($tutorialId, $stepId)
    {
        $step = TutoStep::where('tutorial_id', $tutorialId)->findOrFail($stepId);
        
        // Delete image
        if ($step->image_url && \Storage::disk('public')->exists($step->image_url)) {
            \Storage::disk('public')->delete($step->image_url);
        }
        
        $step->delete();

        return redirect()->route('admin.tutorials.steps', $tutorialId)
            ->with('success', 'Étape supprimée avec succès!');
    }
}
