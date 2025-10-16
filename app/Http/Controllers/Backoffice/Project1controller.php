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
        $projects = Project::with('category')
            ->latest()
            ->paginate(10);

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
        'estimated_time' => 'required|integer|min:1',
    ]);

    Project::create([
        'title'          => $request->title,
        'description'    => $request->description,
        'category_id'    => $request->category_id,
        'estimated_time' => $request->estimated_time,
        'user_id'        => 5, // Hardcoded for testing
        'status'         => 'published',
    ]);

    return redirect()->route('admin.projects.index')
                     ->with('success', 'Projet créé avec succès.');
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
        ]);

        $project->update([
            'title'       => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

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