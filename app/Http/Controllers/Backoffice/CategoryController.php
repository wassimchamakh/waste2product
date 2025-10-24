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

    public function aiSuggest(Request $request)
    {
        try {
            $materialType = $request->input('material_type', '');
            $recyclingPurpose = $request->input('recycling_purpose', '');
            $environmentalImpact = $request->input('environmental_impact', '');
            
            $geminiKey = env('GEMINI_API_KEY');
            
            if (!$geminiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'Clé API Gemini non configurée'
                ], 500);
            }

            // Build the enhanced prompt based on user inputs
            $prompt = "En tant qu'expert en gestion environnementale, crée une suggestion de catégorie de déchets avec les informations suivantes:\n\n";
            $prompt .= "Type de matériau: {$materialType}\n";
            $prompt .= "Objectif de recyclage: {$recyclingPurpose}\n";
            $prompt .= "Impact environnemental: {$environmentalImpact}\n\n";
            $prompt .= "Génère un JSON avec:\n";
            $prompt .= "{\n";
            $prompt .= '  "name": "Nom court de la catégorie (20-40 caractères)",'."\n";
            $prompt .= '  "description": "Description détaillée (100-200 caractères)",'."\n";
            $prompt .= '  "certifications": ["2-3 certifications ISO ou environnementales pertinentes parmi: ISO 14001, ISO 14040, ISO 14044, ISO 14046, ISO 14064, FSC, PEFC, Green Dot, Cradle to Cradle, EU Ecolabel, ISO 9001, ISO 45001, GRS, RCS"],'."\n";
            $prompt .= '  "tips": "Conseils pratiques de recyclage (80-150 caractères)"'."\n";
            $prompt .= "}\n\n";
            $prompt .= "Réponds uniquement avec le JSON, sans texte supplémentaire.";

            // Call Gemini API
            $client = new \GuzzleHttp\Client([
                'verify' => false // Disable SSL verification for local dev
            ]);
            $model = config('services.gemini.model', 'gemini-2.0-flash');
            $response = $client->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$geminiKey}", [
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.8,
                        'maxOutputTokens' => 500
                    ]
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $aiResponse = $result['candidates'][0]['content']['parts'][0]['text'];
                
                // Extract JSON from the response
                preg_match('/\{[\s\S]*\}/', $aiResponse, $matches);
                
                if ($matches) {
                    $suggestedData = json_decode($matches[0], true);
                    
                    if ($suggestedData) {
                        return response()->json([
                            'success' => true,
                            'data' => $suggestedData,
                            'message' => 'Suggestions générées avec succès ✨'
                        ]);
                    }
                }
            }

            throw new \Exception('Format de réponse invalide de l\'API Gemini');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suggestion: ' . $e->getMessage()
            ], 500);
        }
    }
}
