<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Stock;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index(Request $request)
{
    $query = Produit::query();

    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where('nom', 'like', "%{$search}%")
              ->orWhere('code_barre', 'like', "%{$search}%");
    }

    
    if ($request->has('sort')) {
        $direction = $request->input('direction', 'asc');
        $query->orderBy($request->sort, $direction);
    }

    $produits = $query->withCount('stocks')->simplePaginate(10);

    return view('admin.produits.index', compact('produits'));
}


    public function store(Request $request)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'marque' => 'nullable|string|max:255',
        'categorie' => 'required|string|max:255',
        'code_barre' => [
            'required',
            'string',
            'min:10', 
            'regex:/^\d+$/', 
            'unique:produits,code_barre', 
        ],
    ]);

    Produit::create($request->all());

    return redirect()->route('admin.produits.index')->with('success', 'Produit créé avec succès.');
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'marque' => 'nullable|string|max:255',
            'categorie' => 'required|string|max:255',
            'code_barre' => [
                'required',
                'string',
                'min:10', 
                'regex:/^\d+$/', 
                'unique:produits,code_barre,' . $id, 
            ],
        ]);

        $produit = Produit::findOrFail($id);
        $produit->update($request->all());

        return redirect()->route('admin.produits.index')->with('success', 'Produit mis à jour avec succès.');
    }


    public function edit($id)
    {
        $produit = Produit::findOrFail($id);
        return view('admin.produits.edit', compact('produit'));
    }

    public function destroy($id)
    {
    $produit = Produit::findOrFail($id);

    if ($produit->stocks()->count() > 0) {
        return redirect()->route('admin.produits.index')->with('error', 'Impossible de supprimer ce produit car il est encore en stock.');
    }

    $produit->delete();

    return redirect()->route('admin.produits.index')->with('success', 'Produit supprimé avec succès.');
    }
}
