<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collecte;
use App\Models\AdhesionCommercant;
use App\Models\AdhesionBenevole;
use App\Models\User;
use Carbon\Carbon;

class CollecteController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer les filtres
        $status = $request->input('status');
        $commercantId = $request->input('commercant');
        $benevoleId = $request->input('benevole');

        // Construire la requête avec les filtres
        $collectes = Collecte::query();

        if ($status) {
            $collectes->where('status', $status);
        }

        if ($commercantId) {
            $collectes->where('commercant_id', $commercantId);
        }

        if ($benevoleId) {
            $collectes->where('benevole_id', $benevoleId);
        }

        $collectes = $collectes->paginate(10);

        // Passer les données nécessaires à la vue
        return view('admin.collectes.index', [
            'collectes' => $collectes,
            'commercants' => AdhesionCommercant::all(),
            'benevoles' => AdhesionBenevole::where('status', 'accepté')->get(),
        ]);
    }

    public function create()
    {
        // Afficher le formulaire de création
        return view('admin.collectes.create_collecte', [
            'commercants' => AdhesionCommercant::all(),
        ]);
    }

    public function store(Request $request)
    {
        // Valider les données
        $request->validate([
            'commercant_id' => 'required|exists:adhesion_commercants,id',
            'date_collecte' => 'required|date|after_or_equal:today',
            'instructions' => 'nullable|string',
        ]);

        // Créer la collecte
        Collecte::create([
            'commercant_id' => $request->commercant_id,
            'date_collecte' => $request->date_collecte,
            'status' => 'En attente',
            'instructions' => $request->instructions,
        ]);

        return redirect()->route('admin.collectes.index')->with('success', 'Collecte créée avec succès.');
    }

    public function show($id)
    {
        // on recupere la collecte, on set carbon en fr pour qu'il traduit la date de la collecte en j de la semaine
        // en francais, pour recuperer que les benevoles qui sont dispo le jour de la collecte et accepté
        $collecte = Collecte::findOrFail($id);
        Carbon::setLocale('fr');

        $jourSemaine = Carbon::parse($collecte->date_collecte)->translatedFormat('l');

        $benevoles = AdhesionBenevole::where('status', 'accepté')
        ->where(function ($query) use ($jourSemaine) {
            $query->whereJsonContains("availability->{$jourSemaine}->matin", '1')
                  ->orWhereJsonContains("availability->{$jourSemaine}->midi", '1')
                  ->orWhereJsonContains("availability->{$jourSemaine}->soir", '1');
        })
        ->get();

        return view('admin.collectes.show_collecte', compact('collecte', 'benevoles'));
    }

    public function edit($id)
    {
        // Afficher le formulaire de modification
        $collecte = Collecte::findOrFail($id);

        return view('admin.collectes.edit_collecte', [
            'collecte' => $collecte,
            'commercants' => AdhesionCommercant::all(),
        ]);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'commercant_id' => 'required|exists:adhesion_commercants,id',
            'date_collecte' => 'required|date|after_or_equal:today',
            'instructions' => 'nullable|string',
        ]);

        $collecte = Collecte::findOrFail($id);
        $collecte->update($request->all());

        return redirect()->route('admin.collectes.index')->with('success', 'Collecte mise à jour avec succès.');
    }

    public function destroy($id)
    {

        $collecte = Collecte::findOrFail($id);
        $collecte->delete();

        return redirect()->route('admin.collectes.index')->with('success', 'Collecte supprimée avec succès.');
    }

    public function assign(Request $request, $id)
    {
        $collecte = Collecte::findOrFail($id);
        $collecte->benevole_id = $request->benevole_id;
        $collecte->status = 'Attribué';
        $collecte->save();

        return redirect()->route('admin.collectes.show', $collecte->id)->with('success', 'Bénévole attribué avec succès.');
    }

    public function updateStatus(Request $request, $id)
    {
        $collecte = Collecte::findOrFail($id);
        $collecte->status = $request->status;
        if($collecte->status === 'Annulé' || $collecte->status === 'En Attente'){
            $collecte->benevole_id = null;
        }
        $collecte->save();

        return redirect()->route('admin.collectes.show', $collecte->id)->with('success', 'Statut mis à jour avec succès.');
    }
}

