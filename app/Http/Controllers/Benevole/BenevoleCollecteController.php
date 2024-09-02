<?php

namespace App\Http\Controllers\Benevole;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collecte;
use App\Models\AdhesionBenevole;
use App\Models\User;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\Distribution;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BenevoleCollecteController extends Controller
{
    public function index()
{
    $adhesionBenevole = Auth::user()->adhesionsBenevoles;

    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
    }

    $user = Auth::user();

    if ($user->role !== 'benevole') {
        return redirect()->back()->with('error', 'Vous n\'êtes pas ou plus un benevole.');
    }

        if ($adhesionBenevole && $adhesionBenevole->status === 'accepté') {
            $collectes = Collecte::where('benevole_id', $adhesionBenevole->id)
                                ->whereIn('status', ['Attribué', 'En Cours', 'En Attente de Stockage'])
                                ->get();
    
            $distributions = Distribution::where('benevole_id', $adhesionBenevole->id)
                                        ->whereIn('status', ['Planifié', 'En Cours', 'Terminé'])
                                        ->get();
    
            return view('benevole.collectes.index', compact('collectes', 'distributions'));
        } else {
            return redirect()->route('benevole')->with('error', 'Vous devez être un bénévole actif pour accéder à cette page.');
        } 
}

public function show($id)
{
    $adhesionBenevole = Auth::user()->adhesionsBenevoles;

    if (!$adhesionBenevole || $adhesionBenevole->status !== 'accepté') {
        return redirect()->route('benevole')->with('error', 'Vous devez être un bénévole actif pour accéder à cette page.');
    }

    $collecte = Collecte::where('benevole_id', $adhesionBenevole->id)
                        ->where('id', $id)
                        ->firstOrFail();

    return view('benevole.collectes.show', compact('collecte'));
}

public function updateStatus(Request $request, $id)
    {
        $adhesionBenevole = Auth::user()->adhesionsBenevoles;

        if (!$adhesionBenevole || $adhesionBenevole->status !== 'accepté') {
            return redirect()->route('benevole')->with('error', 'Vous devez être un bénévole actif pour accéder à cette page.');
        }

        $collecte = Collecte::where('benevole_id', $adhesionBenevole->id)
                            ->where('id', $id)
                            ->firstOrFail();
    
        $collecte->status = $request->input('status');
        $collecte->save();
    
        return redirect()->route('benevole.collectes.show', $collecte->id)->with('success', 'Statut mis à jour avec succès.');
    }

public function stock($id)
{
    $adhesionBenevole = Auth::user()->adhesionsBenevoles;

    if (!$adhesionBenevole || $adhesionBenevole->status !== 'accepté') {
        return redirect()->route('benevole')->with('error', 'Vous devez être un bénévole actif pour accéder à cette page.');
    }

    $collecte = Collecte::where('benevole_id', $adhesionBenevole->id)
                        ->where('id', $id)
                        ->where('status', 'En Attente de Stockage')
                        ->firstOrFail();

    return view('benevole.collectes.stock', compact('collecte'))
            ->with('originalProducts', session('originalProducts')); 
}

public function checkProducts(Request $request, $id)
{
    $products = $request->input('products', []);
    $missingProducts = [];

    //dd($products);

    if (!is_array($products)) {
        return back()->withErrors(['error' => 'Les données des produits ne sont pas correctement formatées.']);
    }

    foreach ($products as $index => $product) {
        if (!is_array($product)) {
            return back()->withErrors(['error' => 'Les données du produit ne sont pas correctement formatées.']);
        }

        if (!Produit::where('code_barre', $product['barcode'])->exists()) {
            $missingProducts[] = $product;  
        }
    }

    if (!empty($missingProducts)) {
        session(['originalProducts' => $products]);
        return redirect()->route('benevole.collectes.addProducts', $id)
                         ->with('missingProducts', $missingProducts);      
    }

    return $this->storeStock($request, $id);
}

    

    public function addProducts($id)
{
    
    $missingProducts = session('missingProducts');
    $originalProducts = session('originalProducts');
    return view('benevole.collectes.addProducts', compact('missingProducts', 'originalProducts', 'id'));
}

    public function storeNewProducts(Request $request, $id)
{
    $newProducts = $request->input('products');

    foreach ($newProducts as $newProduct) {
        Produit::create([
            'nom' => $newProduct['name'],
            'marque' => $newProduct['marque'],
            'categorie' => $newProduct['categorie'],
            'code_barre' => $newProduct['barcode']
        ]);
    }

    $originalProducts = session('originalProducts');

    return redirect()->route('benevole.collectes.stock', $id)
                     ->with('originalProducts', $originalProducts);
}

    private function isValidLocation($emplacement)
    {
        $pattern = '/^[A-D][1-9][A-Z][1-9]$/';
        return preg_match($pattern, $emplacement);
    }

    public function storeStock(Request $request, $id)
{
    $adhesionBenevole = Auth::user()->adhesionsBenevoles;

    if (!$adhesionBenevole || $adhesionBenevole->status !== 'accepté') {
        return redirect()->route('benevole')->with('error', 'Vous devez être un bénévole actif pour accéder à cette page.');
    }

    $collecte = Collecte::where('benevole_id', $adhesionBenevole->id)
                        ->where('id', $id)
                        ->where('status', 'En Attente de Stockage')
                        ->firstOrFail();

    $products = $request->input('products', []);
    $fraisProducts = $request->input('products_frais', []);
    
    $stockEntries = [];

    foreach ($products as $product) {
        if (Carbon::parse($product['expiration_date'])->isPast()) {
            return back()->withErrors(['expiration_date' => 'Le produit avec le code-barres ' . $product['barcode'] . ' a une date d\'expiration passée.'])
                         ->with('originalProducts', $products);
        }

        $emplacement = $product['location_section'] . $product['location_allee'] . $product['location_etagere'] . $product['location_position'];

        if (!$this->isValidLocation($emplacement) && empty($product['froid'])) {
            return back()->withErrors(['location' => 'L\'emplacement ' . $emplacement . ' n\'est pas valide.'])
                         ->with('originalProducts', $products);
        }

        $produit = Produit::where('code_barre', $product['barcode'])->first();

        $stock = Stock::where('produit_id', $produit->id)
                      ->where('date_expiration', $product['expiration_date'])
                      ->first();

        if ($stock) {
            $stock->quantite += $product['quantity'];
            $stock->date_entree = Carbon::now();
            $stock->save();
        } else {
            Stock::create([
                'produit_id' => $produit->id,
                'quantite' => $product['quantity'],
                'emplacement' => $emplacement,
                'date_entree' => Carbon::now(),
                'date_expiration' => $product['expiration_date']
            ]);
        }
        
        $stockEntries[] = [
            'produit_id' => $produit->id,
            'nom' => $produit['nom'],
            'quantite' => $product['quantity'],
            'emplacement' => $emplacement,
            'date_entree' => Carbon::now()->toDateTimeString(),
            'date_expiration' => $product['expiration_date']
        ];
    }

        foreach ($fraisProducts as $product) {
            $produit = Produit::where('code_barre', $product['barcode'])->first(); 
            $poids = $product['poids'];
            $expirationDate = $product['expiration_date'];
            $emplacementFroid = $product['froid'];

            if (Carbon::parse($expirationDate)->isPast()) {
                return back()->withErrors(['expiration_date' => 'Le produit frais sélectionné a une date d\'expiration passée.'])
                            ->with('originalProducts', $fraisProducts);
            }
            
            if ($product['poids'] <= 0) {
                return back()->withErrors(['poids' => 'Le poids du produit frais ' . $product['barcode'] . ' doit être supérieur à 0.'])
                             ->with('originalProducts', $products);
            }
            
            if (empty($emplacementFroid)) {
                return back()->withErrors(['froid' => 'L\'emplacement froid est requis pour les produits frais.'])
                            ->with('originalProducts', $fraisProducts);
            }
            

            $stockEntries[] = [
                'produit_id' => $produit->id,
                'nom' => $produit['nom'],
                'quantite' => $poids,
                'emplacement' => $emplacementFroid,
                'date_entree' => Carbon::now()->toDateTimeString(),
                'date_expiration' => $expirationDate
            ];

            Stock::create([
                'produit_id' => $produit->id,
                'quantite' => $poids,
                'emplacement' => $emplacementFroid,
                'date_entree' => Carbon::now(),
                'date_expiration' => $expirationDate
            ]);
        }

        $collecte->status = 'Terminé';
        $collecte->stock_entries = json_encode($stockEntries);
        $collecte->save();

        return redirect()->route('benevole.collectes.index')->with('success', 'Produits entrés en stock avec succès.');
    }


}
