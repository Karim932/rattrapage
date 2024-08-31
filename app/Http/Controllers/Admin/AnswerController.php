<?php

namespace App\Http\Controllers\Admin;


use App\Models\Answer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Adhesion;
use Illuminate\Support\Facades\Auth;


class AnswerController extends Controller
{

    public function store(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'titre' => 'required|string|max:50'
        ]);

        
        $adhesion = Adhesion::find($id);

        if (!$adhesion) {
            return back()->withErrors('Adhésion introuvable.');
        }

        $candidature = $adhesion->fusion;

        if (!$candidature) {
            return back()->withErrors('Candidature introuvable.');
        }

        // Créer nouvelle réponse
        $response = new \App\Models\Answer();
        $response->id_admin = Auth::id();
        $response->message = $request->message;
        $response->titre = $request->titre;

        // Enregistrer la réponse via la relation polymorphe
        $candidature->answers()->save($response);
        $candidature->status = 'en cours';
        $candidature->save();

        return redirect()->back()->with('success', 'Réponse envoyée avec succès.');
    }

}
