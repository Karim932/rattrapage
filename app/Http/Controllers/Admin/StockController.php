<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Models\Produit;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with('produit')->paginate(10); // Récupère les stocks avec pagination

        return view('admin.stock.index', compact('stocks'));
    }

    public function create()
    {
        // Récupérer la liste des produits existants pour les afficher dans un dropdown.
        $produits = Produit::orderBy('nom', 'asc')->get();

        return view('admin.stock.create', compact('produits'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
            'emplacement' => 'required|string',
            'date_entree' => 'required|date',
            'date_expiration' => 'nullable|date|after_or_equal:date_entree',
        ]);
    
        Stock::create([
            'produit_id' => $request->produit_id,
            'quantite' => $request->quantite,
            'emplacement' => $request->emplacement,
            'date_entree' => $request->date_entree,
            'date_expiration' => $request->date_expiration,
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
        // Valider la requête
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
            'emplacement' => 'required|string',
            'date_entree' => 'required|date',
            'date_expiration' => 'nullable|date|after_or_equal:date_entree',
        ]);
    
        // Récupérer l'élément du stock à mettre à jour
        $stock = Stock::findOrFail($id);
    
        // Mettre à jour les informations du stock
        $stock->update([
            'produit_id' => $request->produit_id,
            'quantite' => $request->quantite,
            'emplacement' => $request->emplacement,
            'date_entree' => $request->date_entree,
            'date_expiration' => $request->date_expiration,
        ]);
    
        return redirect()->route('admin.stock.index')->with('success', 'Stock mis à jour avec succès.');
    }
    
    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();
    
        return redirect()->route('admin.stock.index')->with('success', 'Élément du stock supprimé avec succès.');
    }
    
    
    
}
