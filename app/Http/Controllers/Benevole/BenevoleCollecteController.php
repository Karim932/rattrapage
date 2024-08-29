<?php

namespace App\Http\Controllers\Benevole;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collecte;
use App\Models\AdhesionBenevole;
use App\Models\User;
use App\Models\Produit;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BenevoleCollecteController extends Controller
{
    public function index()
{
    $adhesionBenevole = Auth::user()->adhesionsBenevoles;

        // Vérifier l'existence de l'adhésion et son statut
        if ($adhesionBenevole && $adhesionBenevole->status === 'accepté') {
            $collectes = Collecte::where('benevole_id', $adhesionBenevole->id)
                                ->whereIn('status', ['Attribué', 'En Cours', 'En Attente de Stockage'])
                                ->get();

            return view('benevole.collectes.index', compact('collectes'));
        } else {
            // Rediriger vers la route 'benevole' si l'utilisateur n'a pas d'adhésion de bénévole ou si elle n'est pas acceptée
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
            ->with('originalProducts', session('originalProducts')); // Transmettre les produits originaux à la vue
}

    public function checkProducts(Request $request, $id)
    {
        $products = $request->input('products', []);
        $missingProducts = [];
        
        //dd($products);

        // Vérification de chaque produit soumis
        foreach ($products as $index => $product) {
            if (!Produit::where('code_barre', $product['barcode'])->exists()) {
                $missingProducts[] = $product;  // Ajouter les produits manquants à la liste
            }
        }
    
        // Si des produits manquent, enregistrer les données originales dans la session et rediriger
        if (!empty($missingProducts)) {
            session(['originalProducts' => $products]);
            //dd(session('originalProducts'));
            return redirect()->route('benevole.collectes.addProducts', $id)
                             ->with('missingProducts', $missingProducts);       // Tous les produits soumis, y compris ceux manquants
        }
    
        // Si tous les produits existent, procéder à l'entrée en stock
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

    // Assurer que 'originalProducts' est bien défini avant la redirection
    $originalProducts = session('originalProducts');

    // Rediriger vers l'entrée en stock avec les produits maintenant ajoutés
    return redirect()->route('benevole.collectes.stock', $id)
                     ->with('originalProducts', $originalProducts);
}

    private function isValidLocation($emplacement)
    {
        $pattern = '/^[A-D][1-9][A-Z][1-9]$/';
        return preg_match($pattern, $emplacement);
    }

    // Enregistrer les produits entrés en stock pour une collecte spécifique
    public function storeStock(Request $request, $id)
    {
        $adhesionBenevole = Auth::user()->adhesionsBenevoles;

        $products = $request->input('products');

        if (!$adhesionBenevole || $adhesionBenevole->status !== 'accepté') {
            return redirect()->route('benevole')->with('error', 'Vous devez être un bénévole actif pour accéder à cette page.');
        }

        $collecte = Collecte::where('benevole_id', $adhesionBenevole->id)
                            ->where('id', $id)
                            ->where('status', 'En Attente de Stockage')
                            ->firstOrFail();

        foreach ($products as $product) {

            if (Carbon::parse($product['expiration_date'])->isPast()) {
                return back()->withErrors(['expiration_date' => 'Le produit avec le code-barres ' . $product['barcode'] . ' a une date d\'expiration passée.'])
                ->with('originalProducts', $products);
            }

            $emplacement = $product['location_section'] . $product['location_allee'] . $product['location_etagere'] . $product['location_position'];

            // Valider l'emplacement
            if (!$this->isValidLocation($emplacement)) {
                return back()->withErrors(['location' => 'L\'emplacement ' . $emplacement . ' n\'est pas valide.'])
                ->with('originalProducts', $products);;
            }

            $produit = Produit::where('code_barre', $product['barcode'])->first();

            // Vérifier s'il existe déjà un lot avec la même date d'expiration
            $stock = Stock::where('produit_id', $produit->id)
                          ->where('date_expiration', $product['expiration_date'])
                          ->first();

            if ($stock) {
                // Si un lot existe, ajouter la quantité
                $stock->quantite += $product['quantity'];
                $stock->date_entree = Carbon::now();
                $stock->save();
            } else {
                // Sinon, créer un nouveau lot
                Stock::create([
                    'produit_id' => $produit->id,
                    'quantite' => $product['quantity'],
                    'emplacement' => $product['location'],
                    'date_entree' => Carbon::now(),
                    'date_expiration' => $product['expiration_date']
                ]);
            }
            
        }

        $stockEntries[] = 
        [
            'produit_id' => $product['produit_id'],
            'quantite' => $product['quantity'],
            'emplacement' => $emplacement,
            'date_entree' => Carbon::now()->toDateTimeString(),
            'date_expiration' => $product['expiration_date']
        ];

        $collecte->status = 'Terminé';
        $collecte->stock_entries = json_encode($stockEntries);
        $collecte->save();

        return redirect()->route('benevole.collectes.index')->with('success', 'Produits entrés en stock avec succès.');
    }

}
