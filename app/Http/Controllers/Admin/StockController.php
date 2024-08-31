<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Models\Produit;
use Carbon\Carbon;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::query();
    
        // Filtre par emplacement
        if ($request->has('emplacement') && !empty($request->emplacement)) {
            if ($request->emplacement == 'FROID') {
                $query->where(function($q) {
                    $q->where('emplacement', 'like', 'FRIGO_%')
                      ->orWhere('emplacement', 'like', 'CONGELATEUR_%');
                });
            } elseif ($request->emplacement == 'STANDARD') {
                $query->where(function($q) {
                    $q->where('emplacement', 'not like', 'FRIGO_%')
                      ->where('emplacement', 'not like', 'CONGELATEUR_%');
                });
            }
        }
    
        // Filtre par date d'expiration proche
        if ($request->has('expiring_soon') && $request->expiring_soon) {
            $query->where(function($q) {
                $q->where('date_expiration', '<=', now()->addDays(7));
            });
        }
    
        // Filtre par recherche (nom ou code-barre)
        if ($request->has('search') && !empty($request->search)) {
            $query->whereHas('produit', function($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('code_barre', 'like', '%' . $request->search . '%');
            });
        }
    
        // Filtre par tri
        if ($request->has('sort')) {
            $direction = $request->input('direction', 'asc');
            $query->orderBy($request->sort, $direction);
        }
    
        $stocks = $query->paginate(10)->appends($request->all());
    
        return view('admin.stock.index', compact('stocks'));
    }
        
    public function create()
    {
    // Récupérer la liste des produits disponibles
    $produits = Produit::all();

    // Retourner la vue pour créer un nouvel élément de stock
    return view('admin.stock.create', compact('produits'));
    }




    public function store(Request $request)
    {
    $isProduitFrais = $request->has('produit_frais') && $request->produit_frais == 'on';

    // Définir les règles de validation conditionnelle
    $rules = [
        'produit_id' => 'required|exists:produits,id',
        'date_entree' => 'required|date',
        'date_expiration' => 'nullable|date|after_or_equal:date_entree',
    ];

    if ($isProduitFrais) {
        $rules['quantite'] = 'required|numeric|min:0.01'; // Pour le poids en kg
        $rules['emplacement'] = 'required|string'; // Emplacement froid requis pour les produits frais
    } else {
        $rules['quantite'] = 'required|integer|min:1'; // Pour la quantité en unités
        $rules['location_section'] = 'required|string';
        $rules['location_allee'] = 'required|string';
        $rules['location_etagere'] = 'required|string';
        $rules['location_position'] = 'required|string';
    }

    $validatedData = $request->validate($rules);

    // Si ce n'est pas un produit frais, construire l'emplacement standard
    if (!$isProduitFrais) {
        $validatedData['emplacement'] = $validatedData['location_section'] 
            . $validatedData['location_allee'] 
            . $validatedData['location_etagere'] 
            . $validatedData['location_position'];
    }

    Stock::create([
        'produit_id' => $validatedData['produit_id'],
        'quantite' => $validatedData['quantite'],
        'emplacement' => $validatedData['emplacement'],
        'date_entree' => $validatedData['date_entree'],
        'date_expiration' => $validatedData['date_expiration'],
    ]);

    return redirect()->route('admin.stock.index')->with('success', 'Produit ajouté au stock avec succès.');
    }

    

    public function edit($id)
    {
        // Récupérer l'élément du stock à modifier et la liste des produits
        $stock = Stock::findOrFail($id);
        $produits = Produit::all();
        return view('admin.stock.edit', compact('stock', 'produits'));
    }

    public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);
        $isProduitFrais = $request->has('produit_frais') && $request->produit_frais == 'on';
    
        // Définir les règles de validation conditionnelle
        $rules = [
            'produit_id' => 'required|exists:produits,id',
            'date_entree' => 'required|date',
            'date_expiration' => 'nullable|date|after_or_equal:date_entree',
        ];
    
        if ($isProduitFrais) {
            $rules['poids'] = 'required|numeric|min:0.01';
            $rules['emplacement'] = 'required|string';
        } else {
            $rules['quantite'] = 'required|integer|min:1';
            $rules['location_section'] = 'required|string';
            $rules['location_allee'] = 'required|string';
            $rules['location_etagere'] = 'required|string';
            $rules['location_position'] = 'required|string';
        }
    
        $validatedData = $request->validate($rules);
    
        if (!$isProduitFrais) {
            $validatedData['emplacement'] = $validatedData['location_section'] 
                . $validatedData['location_allee'] 
                . $validatedData['location_etagere'] 
                . $validatedData['location_position'];
        } else {
            $validatedData['quantite'] = $validatedData['poids']; // Utiliser poids comme quantité pour les produits frais
        }
    
        // Vérifier que la nouvelle quantité n'est pas inférieure à la quantité réservée
        if ($validatedData['quantite'] < $stock->quantite_reservee) {
            return back()->withErrors([
                'quantite' => "Impossible de réduire la quantité à moins de {$stock->quantite_reservee}. " .
                              "Il y a actuellement {$stock->quantite_reservee} quantités réservées pour des distributions. " .
                              "Veuillez modifier ou annuler les distributions concernées avant de réduire la quantité en stock."
            ])->withInput();
        }
    
        // Mettre à jour le stock avec les nouvelles données validées
        $stock->update([
            'produit_id' => $validatedData['produit_id'],
            'quantite' => $validatedData['quantite'],
            'emplacement' => $validatedData['emplacement'],
            'date_entree' => $validatedData['date_entree'],
            'date_expiration' => $validatedData['date_expiration'],
        ]);
    
        return redirect()->route('admin.stock.index')->with('success', 'Produit mis à jour avec succès.');
    }
    



    
    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();
    
        return redirect()->route('admin.stock.index')->with('success', 'Élément du stock supprimé avec succès.');
    }
    
    
    
}
