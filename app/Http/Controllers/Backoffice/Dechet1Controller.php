<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\DechetRequest;
use App\Models\Category;
use App\Models\Dechet;
use Illuminate\Http\Request;

class Dechet1Controller extends Controller
{
   
    /**
     * Display a listing of the waste items.
     */
    public function index(Request $request)
    {
        $query = Dechet::with(['category', 'user']);

        // Filtering
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('location')) {
            $query->where('location', 'LIKE', "%{$request->location}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $dechets = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('BackOffice.dechets.index', compact('dechets', 'categories'));
    }

    /**
     * Show the form for creating a new waste item.
     */
    public function create()
    {
        $categories = Category::all();
        return view('BackOffice.dechets.create', compact('categories'));
    }

    /**
     * Store a newly created waste item.
     */
    public function store(DechetRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = 4; // Assuming the admin is logged in

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $filename = time() . '_' . uniqid() . '.' . $request->file('photo')->extension();
            $request->file('photo')->move(public_path('uploads/dechets'), $filename);
            $data['photo'] = $filename;
        }

        Dechet::create($data);

        return redirect()->route('admin.dechets.index')->with('success', '✅ Déchet créé avec succès !');
    }

    /**
     * Show the form for editing the specified waste item.
     */
    public function edit(Dechet $dechet)
    {
        $categories = Category::all();
        return view('BackOffice.dechets.edit', compact('dechet', 'categories'));
    }

    /**
     * Update the specified waste item.
     */
    public function update(DechetRequest $request, Dechet $dechet)
    {
        $data = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($dechet->photo && file_exists(public_path('uploads/dechets/' . $dechet->photo))) {
                unlink(public_path('uploads/dechets/' . $dechet->photo));
            }

            $filename = time() . '_' . uniqid() . '.' . $request->file('photo')->extension();
            $request->file('photo')->move(public_path('uploads/dechets'), $filename);
            $data['photo'] = $filename;
        }

        $dechet->update($data);

        return redirect()->route('admin.dechets.index')->with('success', '✅ Déchet mis à jour avec succès !');
    }

    /**
     * Remove the specified waste item.
     */
    public function destroy(Dechet $dechet)
    {
        $dechet->delete();

        return redirect()->route('admin.dechets.index')->with('success', '✅ Déchet supprimé avec succès !');
    }
}
