<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects with filters
     */
    public function index(Request $request)
    {
        $query = Project::with(['category', 'user', 'steps'])
                       ->where('status', 'published');

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty_level', $request->difficulty);
        }

        // Filter by duration
        if ($request->filled('duration')) {
            switch ($request->duration) {
                case 'short':
                    $query->where('estimated_time', 'like', '%min%')
                          ->orWhere('estimated_time', 'like', '%1h%');
                    break;
                case 'medium':
                    $query->where(function($q) {
                        $q->where('estimated_time', 'like', '%2h%')
                          ->orWhere('estimated_time', 'like', '%3h%')
                          ->orWhere('estimated_time', 'like', '%4h%')
                          ->orWhere('estimated_time', 'like', '%5h%')
                          ->orWhere('estimated_time', 'like', '%6h%');
                    });
                    break;
                case 'long':
                    $query->where('estimated_time', 'like', '%jour%')
                          ->orWhere('estimated_time', 'like', '%semaine%');
                    break;
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Sorting
        switch ($request->get('sort', 'newest')) {
            case 'popular':
                // Fallback sorting since views_count doesn't exist
                $query->latest();
                break;
            case 'easiest':
                $query->orderByRaw("FIELD(difficulty_level, 'facile', 'intermédiaire', 'difficile')");
                break;
            case 'quickest':
                $query->orderBy('estimated_time');
                break;
            default:
                $query->latest();
        }

        $projects = $query->paginate(12);
        
        // Get featured projects - using published status since featured doesn't exist
        $featuredProjects = Project::with(['category', 'user', 'steps'])
                                  ->where('status', 'published')
                                  ->latest()
                                  ->take(3)
                                  ->get();

        $categories = Category::withCount(['projects' => function($query) {
            $query->where('status', 'published');
        }])->get();
        
        // Statistics
        $stats = [
            'total' => Project::where('status', 'published')->count(),
            'easy' => Project::where('status', 'published')->where('difficulty_level', 'facile')->count(),
            'featured' => Project::where('status', 'published')->count(), // Same as total since no featured status
            'completed' => 0,
        ];

        return view('FrontOffice.projects.index', compact(
            'projects', 
            'featuredProjects', 
            'categories', 
            'stats'
        ));
    }

    /**
     * Show the form for creating a new project
     */
    public function create()
    {
        $categories = Category::all();
        
        return view('FrontOffice.projects.create', compact('categories'));
    }

    /**
     * Store a newly created project
     */
    public function store(ProjectRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['user_id'] = auth()->id(); // Utilisateur connecté
            $data['status'] = 'draft'; // Draft status

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time() . '_' . Str::random(10) . '.' . $file->extension();
                $file->move(public_path('uploads/projects'), $filename);
                $data['photo'] = $filename;
            }

            // Create the project
            $project = Project::create($data);

            // Create project steps
            if ($request->has('steps')) {
                foreach ($request->steps as $index => $stepData) {
                    if (!empty($stepData['title']) && !empty($stepData['description'])) {
                        ProjectStep::create([
                            'project_id' => $project->id,
                            'step_number' => $index + 1,
                            'title' => $stepData['title'],
                            'description' => $stepData['description'],
                            'materials_needed' => $stepData['materials_needed'] ?? null,
                            'tools_required' => $stepData['tools_required'] ?? null,
                            'duration' => $stepData['duration'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('projects.show', $project->id)
                           ->with('success', '✅ Projet créé avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la création du projet.'])
                        ->withInput();
        }
    }

    /**
     * Display the specified project
     */
    public function show($id)
    {
        $project = Project::with(['category', 'user', 'steps' => function($query) {
                             $query->orderBy('step_number');
                         }])
                         ->findOrFail($id);

        // Incrémenter le compteur de vues
        $project->increment('views_count');

        // Get similar projects
        $similarProjects = Project::with(['category', 'user'])
                                 ->where('category_id', $project->category_id)
                                 ->where('id', '!=', $project->id)
                                 ->where('status', 'published')
                                 ->latest()
                                 ->take(3)
                                 ->get();

        // Nombre de projets du créateur
        $creatorProjectsCount = Project::where('user_id', $project->user_id)->count();

        return view('FrontOffice.projects.show', compact('project', 'similarProjects', 'creatorProjectsCount'));
    }

    /**
     * Show the form for editing the specified project
     */
    public function edit($id)
    {
        $project = Project::with('steps')->findOrFail($id);

        // Check if user owns the project
        if ($project->user_id !== auth()->id() && !(auth()->user() && auth()->user()->is_admin)) {
            abort(403, 'Action non autorisée');
        }

        $categories = Category::all();

        return view('FrontOffice.projects.edit', compact('project', 'categories'));
    }

    /**
     * Update the specified project
     */
    public function update(ProjectRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $project = Project::findOrFail($id);

            // Check if user owns the project
            if ($project->user_id !== auth()->id() && !(auth()->user() && auth()->user()->is_admin)) {
                abort(403, 'Action non autorisée');
            }

            $data = $request->validated();

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo
                if ($project->photo && file_exists(public_path('uploads/projects/' . $project->photo))) {
                    unlink(public_path('uploads/projects/' . $project->photo));
                }

                $file = $request->file('photo');
                $filename = time() . '_' . Str::random(10) . '.' . $file->extension();
                $file->move(public_path('uploads/projects'), $filename);
                $data['photo'] = $filename;
            }

            // Update project
            $project->update($data);

            // Delete existing steps
            $project->steps()->delete();

            // Create updated steps
            if ($request->has('steps')) {
                foreach ($request->steps as $index => $stepData) {
                    if (!empty($stepData['title']) && !empty($stepData['description'])) {
                        ProjectStep::create([
                            'project_id' => $project->id,
                            'step_number' => $index + 1,
                            'title' => $stepData['title'],
                            'description' => $stepData['description'],
                            'materials_needed' => $stepData['materials_needed'] ?? null,
                            'tools_required' => $stepData['tools_required'] ?? null,
                            'duration' => $stepData['duration'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('projects.show', $project->id)
                           ->with('success', '✅ Projet mis à jour avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour.'])
                        ->withInput();
        }
    }

    /**
     * Remove the specified project
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        // Check if user owns the project
        if ($project->user_id !== auth()->id() && !(auth()->user() && auth()->user()->is_admin)) {
            abort(403, 'Action non autorisée');
        }

        // Delete photo
        if ($project->photo && file_exists(public_path('uploads/projects/' . $project->photo))) {
            unlink(public_path('uploads/projects/' . $project->photo));
        }

        // Delete steps and project
        $project->steps()->delete();
        $project->delete();

        return redirect()->route('projects.my')
                       ->with('success', '✅ Projet supprimé avec succès');
    }

    /**
     * Publier un projet (rendre public)
     */
    public function publish($id)
    {
        $project = Project::findOrFail($id);
        // Vérifier que l'utilisateur est bien le propriétaire
        if ($project->user_id !== auth()->id()) {
            return redirect()->back()->with('error', "Vous n'avez pas le droit de publier ce projet.");
        }
        $project->status = 'published';
        $project->save();
        return redirect()->route('projects.show', $project->id)
            ->with('success', 'Projet publié avec succès !');
    }

    /**
     * Toggle favorite status
     */
    public function toggleFavorite($id)
    {
        // Placeholder for favorite functionality
        return response()->json(['favorited' => true, 'message' => 'Ajouté aux favoris']);
    }

    /**
     * Show user's projects
     */
    public function myProjects()
    {
        $userId = auth()->id();

        $projects = Project::with(['category', 'steps'])
                          ->where('user_id', $userId)
                          ->latest()
                          ->paginate(12);

        $stats = [
            'total' => Project::where('user_id', $userId)->count(),
            'published' => Project::where('user_id', $userId)->where('status', 'published')->count(),
            'draft' => Project::where('user_id', $userId)->where('status', 'draft')->count(),
            'archived' => Project::where('user_id', $userId)->where('status', 'archived')->count(),
        ];

        return view('FrontOffice.projects.mesprojects', compact('projects', 'stats'));
    }
}