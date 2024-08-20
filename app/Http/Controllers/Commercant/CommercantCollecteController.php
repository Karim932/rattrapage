<?php

namespace App\Http\Controllers\Commercant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collecte;
use App\Models\AdhesionCommercant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class CommercantCollecteController extends Controller
{
    public function create()
    {
        return view('commercant.demande_collecte');
    }

    public function store(Request $request)
    {
        // on recup l'id commercant avec l'id user car connecté et qu'il soit bien accepté
        $adhesionCommercant = AdhesionCommercant::where('user_id', auth()->user()->id)
            ->where('status', 'accepté')
            ->first();

        //dd($adhesionCommercant->id);

        if (!$adhesionCommercant) {
            return redirect()->back()->with('error', 'Vous devez d\'abord compléter votre adhésion pour demander une collecte.');
        }

        // date de demande collecte max 3 jours
        $request->validate([
            'date_collecte' => 'required|date|after_or_equal:today|before_or_equal:' . Carbon::now()->addDays(3)->toDateString(),
            'instructions' => 'nullable|string',
        ]);

        // on check si y'a pas déjà une collecte pour la date demandé
        $existingCollecte = Collecte::where('commercant_id', $adhesionCommercant->id)
            ->whereDate('date_collecte', '=', Carbon::parse($request->date_collecte)->toDateString())
            ->first();

        if ($existingCollecte) {
            return redirect()->back()->with('error', 'Vous avez déjà une collecte prévue pour cette date.');
        }


        Collecte::create([
            'commercant_id' => $adhesionCommercant->id,
            'date_collecte' => $request->date_collecte,
            'status' => 'En Attente',
            'benevole_id' => null,
            'instructions' => $request->instructions,
        ]);

        return redirect()->route('commercant.dashboard')->with('success', 'Demande de collecte soumise avec succès');
    }

    public function dashboard()
    {
        // Récupérer les collectes du commerçant connecté
        $collectes = Collecte::where('commercant_id', Auth::user()->adhesionCommercants->id)->get();

        return view('commercant.dashboard', compact('collectes'));
    }

    public function cancel($id)
    {
        $collecte = Collecte::where('id', $id)
                            ->where('commercant_id', Auth::user()->adhesionCommercants->id)
                            ->firstOrFail();

        // Vérifier que le statut est "En Attente" ou "Attribué"
        if (in_array($collecte->status, ['En Attente', 'Attribué'])) {
            $collecte->status = 'Annulé';
            $collecte->save();

            return redirect()->route('commercant.dashboard')->with('success', 'Collecte annulée avec succès.');
        }

        return redirect()->route('commercant.dashboard')->with('error', 'Cette collecte ne peut pas être annulée.');
    }

}
