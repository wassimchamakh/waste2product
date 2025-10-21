<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;

class Project1controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
    {
        $categories = Category::all();
        $query = Project::with('category');

        // Filtre recherche
        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%") ;
            });
        }

        // Filtre catégorie
        if (request()->filled('category')) {
            $query->where('category_id', request('category'));
        }

        // Filtre difficulté
        if (request()->filled('difficulty')) {
            $query->where('difficulty_level', request('difficulty'));
        }

        $projects = $query->latest()->paginate(10);

        return view('BackOffice.projects.index', compact('projects', 'categories'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        $categories = Category::all();
        return view('BackOffice.projects.create', compact('categories'));
    }

    /**
     * Store a newly created project in storage.
     */
  public function store(Request $request)
{
    $request->validate([
        'title'          => 'required|string|max:255',
        'description'    => 'nullable|string',
        'category_id'    => 'required|exists:categories,id',
        'estimated_time' => 'required',
        'photo'          => 'nullable|image|max:2048',
        'steps'          => 'array',
        'steps.*.title'  => 'required_with:steps|string|max:255',
        'steps.*.description' => 'required_with:steps|string',
    ]);

    try {
        \DB::beginTransaction();

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . \Str::random(10) . '.' . $file->extension();
            $file->move(public_path('uploads/projects'), $filename);
            $photoPath = $filename;
        }

        $project = Project::create([
            'title'          => $request->title,
            'description'    => $request->description,
            'category_id'    => $request->category_id,
            'estimated_time' => $request->estimated_time,
            'user_id'        => auth()->id(),
            'status'         => 'published',
            'photo'          => $photoPath,
        ]);

        // Ajout des étapes
        if ($request->has('steps')) {
            foreach ($request->steps as $index => $stepData) {
                if (!empty($stepData['title']) && !empty($stepData['description'])) {
                    $project->steps()->create([
                        'title' => $stepData['title'],
                        'description' => $stepData['description'],
                        'duration' => $stepData['duration'] ?? '',
                        'materials_needed' => $stepData['materials_needed'] ?? '',
                        'tools_required' => $stepData['tools_required'] ?? '',
                        'step_number' => $index + 1,
                    ]);
                }
            }
        }

        \DB::commit();

        return redirect()->route('admin.projects.index')
                         ->with('success', 'Projet créé avec succès.');
    } catch (\Exception $e) {
        \DB::rollBack();
        return back()->withErrors(['error' => 'Une erreur est survenue lors de la création du projet.'])
                    ->withInput();
    }
}
    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
        $categories = Category::all();
        return view('BackOffice.projects.edit', compact('project', 'categories'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'photo'       => 'nullable|image|max:2048',
            'steps'       => 'array',
            'steps.*.title' => 'required_with:steps|string|max:255',
            'steps.*.description' => 'required_with:steps|string',
        ]);

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . \Str::random(10) . '.' . $file->extension();
            $file->move(public_path('uploads/projects'), $filename);
            $project->photo = $filename;
        }

        $project->update([
            'title'       => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'photo'       => $project->photo,
        ]);

        // Suppression des anciennes étapes
        $project->steps()->delete();
        // Ajout des nouvelles étapes
        if ($request->has('steps')) {
            foreach ($request->steps as $index => $stepData) {
                if (!empty($stepData['title']) && !empty($stepData['description'])) {
                    $project->steps()->create([
                        'title' => $stepData['title'],
                        'description' => $stepData['description'],
                        'duration' => $stepData['duration'] ?? '',
                        'materials_needed' => $stepData['materials_needed'] ?? '',
                        'tools_required' => $stepData['tools_required'] ?? '',
                        'step_number' => $index + 1,
                    ]);
                }
            }
        }

        return redirect()->route('admin.projects.index')
                         ->with('success', 'Projet mis à jour avec succès.');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('admin.projects.index')
                         ->with('success', 'Projet supprimé avec succès.');
    }
}