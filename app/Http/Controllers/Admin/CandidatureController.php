<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdhesionBenevole;
use App\Models\AdhesionCommercant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Adhesion;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Answer;




class CandidatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        // Construit la requête sur la table Adhesion
        $query = Adhesion::query();

        // Applique le filtre de statut s'il est présent
        if ($request->filled('status')) {
            $query->whereHas('fusion', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Applique le filtre de type si spécifié
        if ($request->filled('type')) {
            if ($request->type === 'Commerçant') {
                $query->whereHasMorph('fusion', [AdhesionCommercant::class]);
            } elseif ($request->type === 'Bénévole') {
                $query->whereHasMorph('fusion', [AdhesionBenevole::class]);
            }
        }

        // Charger les données avec la relation polymorphique candidature
        $allCandidatures = $query->with('fusion.user')->get();

        // $allCandidatures = $allCandidatures->shuffle();
        // inRandomOrder()-> // pour mélanger la table comme shuffle
        $allCandidatures = Adhesion::paginate(20);

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
        $adhesion = Adhesion::with(['fusion'])->find($id);

        if (!$adhesion) {
            return redirect()->route('adhesion.index')->with('error', 'Adhésion non trouvée.');
        }

        // Vérifier si la relation fusion est bien chargée
        if (!$adhesion->fusion) {
            return redirect()->route('adhesion.index')->with('error', 'Candidature spécifique non trouvée.');
        }

        $fusionId = $adhesion->fusion->id;

        // Récupérer toutes les réponses où 'candidature_id' est égal à $fusionId
        $answers = Answer::where('candidature_id', $fusionId)->get();

        // dd($fusionId, $adhesion, $adhesion->fusion, $answers);


        return view('admin.adhesions.show', [
            'adhesion' => $adhesion,
            'candidature' => $adhesion->fusion,
            'answers' => $answers
        ]);
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
        try {
            // Récupére l'adhesion
            $adhesion = Adhesion::findOrFail($id);

            // Supprime les réponses associées dans la table answers
            $adhesion->fusion->answers()->delete();

            // Vérifie le type de la candidature et supprime l'enregistrement de la bonne table
            if ($adhesion->fusion instanceof AdhesionCommercant) {
                // Supprime l'enregistrement dans adhesion_commercants
                $adhesion->fusion->delete();
            } elseif ($adhesion->fusion instanceof AdhesionBenevole) {
                $adhesion->fusion->delete();
            }

            // Supprime l'enregistrement dans la table adhesions
            $adhesion->delete();
            return redirect()->route('adhesion.index')->with('success', 'Candidature supprimée avec succès.');

        } catch (\Exception $e) {
            return redirect()->route('adhesion.index')->with('error', 'Une erreur s\'est produite lors de la suppression : ' . $e->getMessage());
        }
    }

    public function accept($id)
    {
        $adhesion = Adhesion::with('fusion')->findOrFail($id);
        $class = get_class($adhesion->fusion);
        $role = 'benevole';
        $user = $adhesion->fusion->user;

        if ($class === AdhesionCommercant::class) {
            $user->role = $role;
            $user->save();
        } elseif ($class === AdhesionBenevole::class) {
            $user->role = $role;
            $user->save();
        } else {
            return redirect()->back()->with('error', 'Type de role non reconnu.');
        }

        return $this->updateStatus($id, 'accepté', 'Candidature acceptée avec succès.');
    }

    public function refuse($id)
    {
        return $this->updateStatus($id, 'refusé', 'Candidature refusée avec succès.');
    }

    public function revoque($id)
    {
        $adhesion = Adhesion::with('fusion')->findOrFail($id);
        $newRole = 'user';
        $user = $adhesion->fusion->user;
        $user->role = $newRole;
        $user->save();

        return $this->updateStatus($id, 'revoqué', 'Candidature révoquée avec succès.');
    }

    private function updateStatus($id, $status, $message)
    {
        try {
            // Récupérer l'adhesion
            $adhesion = Adhesion::with('fusion')->findOrFail($id);
            $class = get_class($adhesion->fusion);

            if ($class === AdhesionCommercant::class) {
                $adhesionCommercant = $adhesion->fusion;
                $adhesionCommercant->status = $status;
                $adhesionCommercant->save();
            } elseif ($class === AdhesionBenevole::class) {
                $adhesionBenevole = $adhesion->fusion;
                $adhesionBenevole->status = $status;
                $adhesionBenevole->save();
            } else {
                return redirect()->back()->with('error', 'Type de candidature non reconnu.');
            }

            return redirect()->route('adhesion.index')->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->route('adhesion.index')->with('error', 'Une erreur s\'est produite lors de la mise à jour du statut : ' . $e->getMessage());
        }
    }


}
