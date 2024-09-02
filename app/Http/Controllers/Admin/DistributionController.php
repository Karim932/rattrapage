<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distribution;
use App\Models\Stock;
use App\Models\AdhesionBenevole;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;



class DistributionController extends Controller
{
    public function index(Request $request)
    {
        $query = Distribution::query();

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where('destinataire', 'like', '%' . $request->search . '%')
                  ->orWhere('adresse', 'like', '%' . $request->search . '%');
        }

        $distributions = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.distributions.index', compact('distributions'));
    }

    public function create()
    {
    return view('admin.distributions.create');
    }

public function storeStep1(Request $request)
    {
    $validated = $request->validate([
        'destinataire' => 'required|string|max:255',
        'adresse' => 'required|string|max:255',
        'telephone' => 'required|string|max:20',
        'date_distribution' => 'required|date|after_or_equal:today',
    ]);

    session(['distribution_step1' => $validated]);

    
    return redirect()->route('admin.distributions.selectStock');
    }
    
    public function selectStock(Request $request)
    {
    $distributionData = session('distribution_step1');
    if (!$distributionData) {
        return redirect()->route('admin.distributions.create');
    }

    $dateDistribution = Carbon::parse($distributionData['date_distribution']);
    $jourSemaine = $dateDistribution->translatedFormat('l');
    
    $benevoles = AdhesionBenevole::where('status', 'accepté')
        ->where(function ($query) use ($jourSemaine) {
            $query->whereJsonContains("availability->{$jourSemaine}->matin", true)
                  ->orWhereJsonContains("availability->{$jourSemaine}->midi", true)
                  ->orWhereJsonContains("availability->{$jourSemaine}->soir", true);
        })
        ->get();

    $query = Stock::with('produit')
    ->selectRaw('stocks.*, (quantite - quantite_reservee) as quantite_disponible')
    ->whereRaw('(quantite - quantite_reservee) > 0');


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

    if ($request->has('expiring_soon') && $request->expiring_soon) {
        $query->where(function($q) {
            $q->where('date_expiration', '<=', now()->addDays(7));
        });
    }

    if ($request->has('search') && !empty($request->search)) {
        $query->whereHas('produit', function($q) use ($request) {
            $q->where('nom', 'like', '%' . $request->search . '%')
              ->orWhere('code_barre', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->has('sort')) {
        $direction = $request->input('direction', 'asc');
        $query->orderBy($request->sort, $direction);
    }
    
    $stocks = $query->paginate(10)->appends($request->all());

    return view('admin.distributions.selectStock', compact('benevoles', 'stocks', 'distributionData'));
    }

    public function storeStep2(Request $request)
    {
         $request->validate([
                    'benevole_id' => 'required|exists:adhesion_benevoles,id',
                    'stocks.*.stock_id' => 'required|exists:stocks,id',
                    'stocks.*.quantite' => 'nullable|integer|min:1',
                    ]);
    
        $selectedStocks = collect($request->input('stocks'))->filter(function ($stock) {
            return !is_null($stock['quantite']);  
        });
    
        if ($selectedStocks->isEmpty()) {
            return back()->withErrors(['stocks' => 'Veuillez sélectionner au moins un article pour la distribution.']);
        }

    $distributionData = session('distribution_step1');
    if (!$distributionData) {
        return redirect()->route('admin.distributions.create');
    }

    DB::transaction(function () use ($distributionData, $request, $selectedStocks) {
        $distribution = new Distribution();
        $distribution->destinataire = $distributionData['destinataire'];
        $distribution->adresse = $distributionData['adresse'];
        $distribution->telephone = $distributionData['telephone'];
        $distribution->benevole_id = $request->input('benevole_id');
        $distribution->date_souhaitee = $distributionData['date_distribution'];
        $distribution->status = 'Planifié';
        $distribution->save();

        foreach ($selectedStocks as $stockInput) {
            $stock = Stock::findOrFail($stockInput['stock_id']);
            $disponible = $stock->quantite - $stock->quantite_reservee;

            if ($stockInput['quantite'] > $disponible) {
                throw ValidationException::withMessages(['stocks' => 'La quantité demandée pour un produit dépasse la quantité disponible.']);
            }

            $stock->quantite_reservee += $stockInput['quantite'];
            $stock->save();

            $distribution->stocks()->attach($stock->id, ['quantite' => $stockInput['quantite']]);
        }
    });
    session()->forget('distribution_step1');

    return redirect()->route('admin.distributions.index')->with('success', 'Distribution créée avec succès.');
    }

    public function edit($id)
    {
        $distribution = Distribution::findOrFail($id);
        $stocks = Stock::where('quantite', '>', 0)->get();

            
        $dateDistribution = Carbon::parse($distribution['date_distribution']);
        $jourSemaine = $dateDistribution->translatedFormat('l');
        

        $benevoles = AdhesionBenevole::where('status', 'accepté')
        ->where(function ($query) use ($jourSemaine) {
            $query->whereJsonContains("availability->{$jourSemaine}->matin", true)
                  ->orWhereJsonContains("availability->{$jourSemaine}->midi", true)
                  ->orWhereJsonContains("availability->{$jourSemaine}->soir", true);
        })
        ->get();

        return view('admin.distributions.edit', compact('distribution', 'stocks', 'benevoles'));
    }

    public function update(Request $request, $id)
    {
        $distribution = Distribution::findOrFail($id);
    
        
        $validated = $request->validate([
            'benevole_id' => 'required|exists:adhesion_benevoles,id',
            'status' => 'required|in:Planifié,En Cours,Terminé',
            'stocks' => 'array',
            'stocks.*.stock_id' => 'required|exists:stocks,id',
            'stocks.*.quantite' => 'nullable|integer|min:1',
        ]);
    
        
        $selectedStocks = collect($validated['stocks'])->filter(function ($stock) {
            return isset($stock['quantite']) && $stock['quantite'] > 0;
        });
    
        
        if ($selectedStocks->isEmpty()) {
            return back()->withErrors(['stocks' => 'Veuillez sélectionner au moins un article pour la distribution.']);
        }
    
        DB::transaction(function () use ($distribution, $selectedStocks, $request) {
            foreach ($distribution->stocks as $existingStock) {
                $stockModel = Stock::find($existingStock->pivot->stock_id);
                if ($stockModel) {
                    $stockModel->quantite_reservee -= $existingStock->pivot->quantite;
                    $stockModel->save();
                }
            }
    
            $distribution->stocks()->detach();
            foreach ($selectedStocks as $stock) {
                $stockModel = Stock::findOrFail($stock['stock_id']);
    
                $disponible = $stockModel->quantite - $stockModel->quantite_reservee;
    
                if ($disponible < $stock['quantite']) {
                    throw ValidationException::withMessages([
                        'stocks' => "La quantité demandée pour l'article {$stockModel->produit->nom} dépasse la quantité disponible."
                    ]);
                }
    
                $stockModel->quantite_reservee = $stockModel->quantite_reservee + $stock['quantite'];
                $stockModel->save();
    
                $distribution->stocks()->attach($stockModel->id, ['quantite' => $stock['quantite']]);
            }
    
            $distribution->benevole_id = $request->input('benevole_id');
            $distribution->status = $request->input('status');
            $distribution->save();
        });
    
        return redirect()->route('admin.distributions.index')->with('success', 'Distribution mise à jour avec succès.');
    }

public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $distribution = Distribution::findOrFail($id);

            foreach ($distribution->stocks as $stock) {
                $stock->quantite_reservee -= $stock->pivot->quantite;
                $stock->save();
            }

            $distribution->stocks()->detach();

            $distribution->delete();
        });

        return redirect()->route('admin.distributions.index')->with('success', 'Distribution supprimée avec succès.');
    }

}
