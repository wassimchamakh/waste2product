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
        $query = Dechet::with(['category', 'user'])
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
        $data = $request->validated();
        $data['user_id'] = 4;
        $data['status'] = 'available';

        // Upload photo
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->extension();
            $file->move(public_path('uploads/dechets'), $filename);
            $data['photo'] = $filename;
        }

        $Dechet = Dechet::create($data);

        return redirect()->route('dechets.show', $Dechet->id)
                       ->with('success', '✅ Déchet déclaré avec succès !');
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
}
