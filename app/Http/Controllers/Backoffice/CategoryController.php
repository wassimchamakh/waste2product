<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('dechets')->latest()->get();
        return view('BackOffice.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('BackOffice.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories',
            'description' => 'required|string',
            'icon' => 'required|string|max:50',
            'color' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'tips' => 'nullable|string',
            'certifications' => 'nullable|array',
            'certifications.*' => 'string',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $uploadPath = public_path('storage/categories');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $file->move($uploadPath, $filename);
            $validated['image'] = $filename;
        }

        // Handle certifications
        if ($request->has('certifications')) {
            $validated['certifications'] = array_filter($request->certifications);
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')
                        ->with('success', '✅ Catégorie créée avec succès !');
    }

    public function edit(Category $category)
    {
        return view('BackOffice.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'required|string',
            'icon' => 'required|string|max:50',
            'color' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'tips' => 'nullable|string',
            'certifications' => 'nullable|array',
            'certifications.*' => 'string',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image && file_exists(public_path('storage/categories/' . $category->image))) {
                unlink(public_path('storage/categories/' . $category->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $uploadPath = public_path('storage/categories');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $file->move($uploadPath, $filename);
            $validated['image'] = $filename;
        }

        // Handle certifications
        if ($request->has('certifications')) {
            $validated['certifications'] = array_filter($request->certifications);
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
                        ->with('success', '✅ Catégorie mise à jour avec succès !');
    }

    public function destroy(Category $category)
    {
        // Check if category has dechets
        if ($category->dechets()->count() > 0) {
            return back()->with('error', '❌ Impossible de supprimer cette catégorie car elle contient des déchets.');
        }

        // Delete image
        if ($category->image && file_exists(public_path('storage/categories/' . $category->image))) {
            unlink(public_path('storage/categories/' . $category->image));
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
                        ->with('success', '✅ Catégorie supprimée avec succès !');
    }
}
