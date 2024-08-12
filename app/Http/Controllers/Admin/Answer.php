<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdhesionBenevole;
use App\Models\AdhesionCommercant;
use App\Models\Adhesion;
use Illuminate\Support\Facades\Auth;

class Answer extends Controller
{

    public function store(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'titre' => 'required|string|max:50'
        ]);

        // Trouver l'adhésion et la candidature associée via la relation polymorphique
        $adhesion = Adhesion::find($id);

        // dd($adhesion);

        if (!$adhesion) {
            return back()->withErrors('Adhésion introuvable.');
        }

        $candidature = $adhesion->fusion;


        if (!$candidature) {
            return back()->withErrors('Candidature introuvable.');
        }

        // Créer une nouvelle réponse
        $response = new \App\Models\Answer();
        $response->id_admin = Auth::id();
        $response->message = $request->message;
        $response->titre = $request->titre;

        // Enregistrer la réponse via la relation polymorphe
        $candidature->answers()->save($response);

        // Mettre à jour le statut de la candidature à 'en cours'
        $candidature->status = 'en cours';
        $candidature->save();

        return redirect()->back()->with('success', 'Réponse envoyée avec succès.');
    }

}
