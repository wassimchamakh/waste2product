<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\DechetRequest;
use App\Models\Category;
use App\Models\Dechet;
use Illuminate\Http\Request;

class DechetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Liste des déchets avec filtres
     */
    public function index(Request $request)
    {
        $userId = 4; // Replace with Auth::id() when ready

        $query = Dechet::with(['category', 'user'])
                      ->withCount([
                          'favorites',
                          'reviews',
                          'favorites as is_favorited' => function($query) use ($userId) {
                              $query->where('user_id', $userId);
                          }
                      ])
                      ->where('is_active', true);

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtre par localisation
        if ($request->filled('location')) {
            $query->where('location', 'LIKE', "%{$request->location}%");
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $Dechets = $query->latest()->paginate(12);
        $categories = Category::withCount('Dechets')->get();
        
        // Statistiques
        $stats = [
            'total' => Dechet::where('is_active', true)->count(),
            'available' => Dechet::where('is_active', true)->where('status', 'available')->count(),
            'reserved' => Dechet::where('is_active', true)->where('status', 'reserved')->count(),
        ];

        return view('FrontOffice.dechets.index', compact('Dechets', 'categories', 'stats'));
    }

    /*public function myDechets()
    {
        $Dechets = Dechet::with(['category'])
                        ->where('user_id', Auth::id())
                        ->where('is_active', true)
                        ->latest()
                        ->paginate(12);

        $stats = [
            'total' => Dechet::where('user_id', Auth::id())->where('is_active', true)->count(),
            'available' => Dechet::where('user_id', Auth::id())->where('is_active', true)->where('status', 'available')->count(),
            'reserved' => Dechet::where('user_id', Auth::id())->where('is_active', true)->where('status', 'reserved')->count(),
        ];

        return view('FrontOffice.dechets.my-dechets', compact('Dechets', 'stats'));
    }
*/

  public function myDechets()
{
    $userId = 4; // fixed user id

    $Dechets = Dechet::with(['category'])
                    ->where('user_id', $userId)
                    ->where('is_active', true)
                    ->latest()
                    ->paginate(12);

    $stats = [
        'total' => Dechet::where('user_id', $userId)->where('is_active', true)->count(),
        'available' => Dechet::where('user_id', $userId)->where('is_active', true)->where('status', 'available')->count(),
        'reserved' => Dechet::where('user_id', $userId)->where('is_active', true)->where('status', 'reserved')->count(),
    ];

    return view('FrontOffice.dechets.mesdechets', compact('Dechets', 'stats'));
}


    /**
     * Formulaire de création
     */
    public function create()
    {
        $categories = Category::all();
        
        $tunisianCities = [
            'Tunis', 'Ariana', 'Ben Arous', 'Manouba', 'La Marsa',
            'Sfax', 'Sousse', 'Kairouan', 'Bizerte', 'Gabès',
            'Monastir', 'Nabeul', 'Gafsa', 'Médenine', 'Kasserine',
            'Mahdia', 'Sidi Bouzid', 'Tataouine', 'Tozeur', 'Béja',
            'Jendouba', 'Le Kef', 'Siliana', 'Kébili', 'Zaghouan'
        ];

        return view('FrontOffice.dechets.create', compact('categories', 'tunisianCities'));
    }

    /**t
     * Enregistrer un déchet
     */
    public function store(DechetRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = 4;
            $data['status'] = 'available';
            $data['is_active'] = true;
            $data['views_count'] = 0;

            // Set default values for new rating columns
            $data['average_rating'] = 0;
            $data['reviews_count'] = 0;
            $data['favorites_count'] = 0;

            // Upload photo
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $file = $request->file('photo');

                // Ensure upload directory exists
                $uploadPath = public_path('uploads/dechets');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Use move for file upload
                $file->move($uploadPath, $filename);
                $data['photo'] = $filename;
            } else {
                $data['photo'] = null; // No photo uploaded
            }

            $Dechet = Dechet::create($data);

            return redirect()->route('dechets.show', $Dechet->id)
                           ->with('success', '✅ Déchet déclaré avec succès !');
        } catch (\Exception $e) {
            \Log::error('Dechet creation error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return back()->withInput()
                        ->with('error', '❌ Erreur lors de la création: ' . $e->getMessage());
        }
    }

     /**
     * Afficher un déchet
     */
    public function show($id)
    {
        $Dechet = Dechet::with(['category', 'user'])->findOrFail($id);
        
        if (!$Dechet->is_active) {
            abort(404);
        }
        
        // Incrémenter les vues
        $Dechet->incrementViews();

        // Déchets similaires
        $similarDechets = Dechet::where('category_id', $Dechet->category_id)
                              ->where('id', '!=', $id)
                              ->where('is_active', true)
                              ->where('status', 'available')
                              ->limit(3)
                              ->get();

        return view('FrontOffice.dechets.show', compact('Dechet', 'similarDechets'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit($id)
    {
        $Dechet = Dechet::findOrFail($id);
        //        if ($Dechet->user_id !== Auth::id()) {

        // Vérifier que l'utilisateur est le propriétaire
        if ($Dechet->user_id !== 4) {
            abort(403, 'Action non autorisée');
        }

        $categories = Category::all();
        
        $tunisianCities = [
            'Tunis', 'Ariana', 'Ben Arous', 'Manouba', 'La Marsa',
            'Sfax', 'Sousse', 'Kairouan', 'Bizerte', 'Gabès',
            'Monastir', 'Nabeul', 'Gafsa', 'Médenine', 'Kasserine',
        ];

        return view('FrontOffice.dechets.edit', compact('Dechet', 'categories', 'tunisianCities'));
    }

    /**
     * Mettre à jour un déchet
     */
    public function update(DechetRequest $request, $id)
    {
        $Dechet = Dechet::findOrFail($id);
//if ($Dechet->user_id !== Auth::id()) {
        // Vérifier que l'utilisateur est le propriétaire
        if ($Dechet->user_id !== 4) {
            abort(403, 'Action non autorisée');
        }

        $data = $request->validated();

        // Upload nouvelle photo
        if ($request->hasFile('photo')) {
            // Supprimer ancienne photo
            if ($Dechet->photo && file_exists(public_path('uploads/dechets/' . $Dechet->photo))) {
                unlink(public_path('uploads/dechets/' . $Dechet->photo));
            }

            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->extension();
            $file->move(public_path('uploads/dechets'), $filename);
            $data['photo'] = $filename;
        }

        $Dechet->update($data);

        return redirect()->route('dechets.show', $Dechet->id)
                       ->with('success', '✅ Déchet mis à jour avec succès !');
    }

    /**
     * Supprimer un déchet (soft delete)
     */
    public function destroy($id)
    {
        $Dechet = Dechet::findOrFail($id);
        //if ($Dechet->user_id !== Auth::id()) { 
        // Vérifier que l'utilisateur est le propriétaire
        if ($Dechet->user_id !== 4) {
            abort(403, 'Action non autorisée');
        }

        $Dechet->update(['is_active' => false]);

        return redirect()->route('dechets.my')
                       ->with('success', '✅ Déchet supprimé avec succès');
    }

    /**
     * Réserver un déchet
     */
    public function reserve($id)
    {
        $Dechet = Dechet::findOrFail($id);

        if ($Dechet->status !== 'available') {
            return back()->with('error', '❌ Ce déchet n\'est plus disponible');
        }

        $Dechet->update(['status' => 'reserved']);

        return back()->with('success', '✅ Déchet réservé ! Le propriétaire a été notifié.');
    }

    /**
     * Predict category from image using Google Cloud Vision API
     */
    public function predictCategory(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
            ]);

            if (!$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune image fournie'
                ], 400);
            }

            $image = $request->file('image');
            $imagePath = $image->getRealPath();
            
            // Initialize Google Cloud Vision client
            putenv('GOOGLE_APPLICATION_CREDENTIALS=' . base_path('google-vision-credentials.json'));
            
            $imageAnnotator = new \Google\Cloud\Vision\V1\ImageAnnotatorClient();
            $imageContent = file_get_contents($imagePath);
            $visionImage = (new \Google\Cloud\Vision\V1\Image())->setContent($imageContent);

            // Detect labels
            $response = $imageAnnotator->labelDetection($visionImage);
            $labels = $response->getLabelAnnotations();

            if (!$labels) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible d\'analyser l\'image'
                ], 400);
            }

            // Map detected labels to categories
            $categoryMapping = [
                'plastic' => ['plastic', 'bottle', 'container', 'packaging', 'polymer'],
                'paper' => ['paper', 'cardboard', 'newspaper', 'magazine', 'document'],
                'glass' => ['glass', 'bottle', 'jar', 'window'],
                'metal' => ['metal', 'aluminum', 'steel', 'iron', 'can'],
                'organic' => ['food', 'organic', 'vegetable', 'fruit', 'plant', 'leaf'],
                'electronic' => ['electronic', 'computer', 'phone', 'device', 'gadget', 'circuit'],
                'wood' => ['wood', 'timber', 'lumber', 'furniture', 'plank'],
                'textile' => ['textile', 'fabric', 'clothing', 'cloth', 'garment'],
            ];

            $detectedLabels = [];
            foreach ($labels as $label) {
                $detectedLabels[] = strtolower($label->getDescription());
            }

            // Find best matching category
            $bestMatch = null;
            $highestScore = 0;

            foreach ($categoryMapping as $categoryKey => $keywords) {
                $score = 0;
                foreach ($keywords as $keyword) {
                    if (in_array($keyword, $detectedLabels)) {
                        $score++;
                    }
                }
                if ($score > $highestScore) {
                    $highestScore = $score;
                    $bestMatch = $categoryKey;
                }
            }

            // Find category in database by name
            $category = Category::where('name', 'LIKE', "%{$bestMatch}%")
                               ->orWhere('name', 'LIKE', '%' . ucfirst($bestMatch) . '%')
                               ->first();

            // If no exact match, try French translations
            $frenchTranslations = [
                'plastic' => 'Plastique',
                'paper' => 'Papier',
                'glass' => 'Verre',
                'metal' => 'Métal',
                'organic' => 'Organique',
                'electronic' => 'Électronique',
                'wood' => 'Bois',
                'textile' => 'Textile',
            ];

            if (!$category && isset($frenchTranslations[$bestMatch])) {
                $frenchName = $frenchTranslations[$bestMatch];
                $category = Category::where('name', 'LIKE', "%{$frenchName}%")->first();
            }

            // If still no match, get first category as fallback
            if (!$category) {
                $category = Category::first();
            }

            $imageAnnotator->close();

            return response()->json([
                'success' => true,
                'category_id' => $category->id,
                'category_name' => $category->name,
                'detected_labels' => array_slice($detectedLabels, 0, 5),
                'confidence' => $highestScore > 0 ? round(($highestScore / count($categoryMapping[$bestMatch])) * 100) : 50
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'analyse: ' . $e->getMessage()
            ], 500);
        }
    }
}
