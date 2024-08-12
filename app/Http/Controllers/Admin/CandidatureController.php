<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdhesionBenevole;
use App\Models\AdhesionCommercant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Adhesion;



class CandidatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        // Construire la requête sur la table Adhesion
        $query = Adhesion::query();

        // Appliquer le filtre de statut s'il est présent
        if ($request->filled('status')) {
            $query->whereHas('fusion', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Appliquer le filtre de type si spécifié
        if ($request->filled('type')) {
            if ($request->type === 'Commerçant') {
                $query->whereHasMorph('fusion', [AdhesionCommercant::class]); // Filtre uniquement les commercants
            } elseif ($request->type === 'Bénévole') {
                $query->whereHasMorph('fusion', [AdhesionBenevole::class]); // Filtre uniquement les bénévoles
            }
        }

        // Charger les données avec la relation polymorphique candidature
        $allCandidatures = $query->with('fusion.user')->get();


        // Mélanger les résultats si nécessaire
        $allCandidatures = $allCandidatures->shuffle();

        return view('admin.adhesions.index', compact('allCandidatures'));
    }



    public function create()
    {
        return view('adhesions.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            // autres règles de validation...
        ]);

        //$adhesion = Adhesion::create($validatedData);
        return redirect()->route('adhesion.index');
    }

    public function show($id)
    {
        $adhesion = Adhesion::find($id);

        if (!$adhesion) {
            return redirect()->route('adhesion.index')->with('error', 'Adhésion non trouvée.');
        }
        $candidature = $adhesion->fusion;

        if (!$candidature) {
            return redirect()->route('adhesion.index')->with('error', 'Candidature spécifique non trouvée.');
        }
        // Récupération du type de candidature pour un usage dans la vue, basé sur la classe du modèle de la candidature
        $type = class_basename($candidature);

        return view('admin.adhesions.show', compact('candidature', 'adhesion', 'type'));
    }


    public function edit(string $id)
    {
        $adhesion = AdhesionBenevole::find($id);
        $adhesion = AdhesionCommercant::find($id);

        return view('adhesions.edit', compact('adhesion'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            // autres règles de validation...
        ]);

        $request->update($validatedData);
        return redirect()->route('adhesion.index');
    }

    public function destroy($id)
    {
        $adhesion = Adhesion::find($id);

        if (!$adhesion) {
            return redirect()->route('adhesion.index')->with('error', 'Candidature introuvable.');
        }

        // Récupérer le modèle associé via la relation polymorphique
        $candidature = $adhesion->fusion;

        if ($candidature) {
            // Supprimer la candidature spécifique
            $candidature->delete();
        }

        // Supprimer l'entrée dans la table Adhesion
        $adhesion->delete();

        return redirect()->route('adhesion.index')->with('success', 'Candidature supprimée avec succès.');
    }


    public function accept($id)
    {
        $adhesion = Adhesion::find($id);
        if (!$adhesion || !$adhesion->fusion) {
            return redirect()->back()->with('error', 'Candidature non trouvée.');
        }

        $adhesion->fusion->status = 'accepté';
        $adhesion->fusion->save();

        return redirect()->route('adhesion.index')->with('success', 'Candidature acceptée avec succès.');
    }

    public function refuse($id)
    {
        $adhesion = Adhesion::find($id);
        if (!$adhesion || !$adhesion->fusion) {
            return redirect()->back()->with('error', 'Candidature non trouvée.');
        }

        $adhesion->fusion->status = 'refusé';
        $adhesion->fusion->save();

        return redirect()->route('adhesion.index')->with('success', 'Candidature refusée avec succès.');
    }

}
