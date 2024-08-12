<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdhesionBenevole;
use App\Models\AdhesionCommercant;
use App\Models\Adhesion;
use Illuminate\Support\Facades\Auth;

class AdhesionsController extends Controller
{
    // Afficher le formulaire pour les commerçants
    public function createCommercant()
    {
        return view('page_navbar.commerçants.adhesion');
    }

    // Traiter l'enregistrement des commerçants
    public function storeCommercant(Request $request)
    {
        // Vérifier si l'utilisateur est connecté et récupérer son ID
        if (Auth::check()) {
            $userId = Auth::id(); // Obtient l'ID de l'utilisateur connecté
            $request->validate([
                'company_name' => 'required|string|max:255',
                'siret' => 'required|string|size:14|unique:adhesion_commercants,siret',
                'address' => 'required|string|max:500',
                'city' => 'required|string|max:255',
                'postal_code' => 'required|string|max:10',
                'country' => 'required|string|max:255',
                'notes' => 'nullable|string|max:1000',
                'product_type' => 'nullable|string|max:255',
                'opening_hours' => 'nullable|string|max:255',
                'participation_frequency' => 'nullable|string|max:255'
            ]);

            // $adhesion = new AdhesionCommercant($request->all()); -> pareil que en bas mais raccourci)
            $adhesionCommercant = new AdhesionCommercant([
                'company_name' => $request->company_name,
                'siret' => $request->siret,
                'address' => $request->address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
                'notes' => $request->notes,
                'product_type' => $request->product_type,
                'opening_hours' => $request->opening_hours,
                'participation_frequency' => $request->participation_frequency,
                'user_id' => $userId
            ]);

            $adhesionCommercant->save();

            // Création de l'entrée générale dans Adhesions
            $adhesion = new Adhesion([
                'candidature_id' => $userId,
                'candidature_type' => AdhesionCommercant::class
            ]);
            $adhesion->save();
            return view('page_navbar.services')->with('success', 'Adhésion bénévole enregistrée avec succès.');
        } else {
            return redirect('login')->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }
    }

    // Afficher le formulaire pour les bénévoles
    public function createBenevole()
    {
        return view('page_navbar.benevoles.adhesion');
    }

    // Traiter l'enregistrement des bénévoles
    public function storeBenevole(Request $request)
    {
        $request->validate([
            'motivation' => 'required|string|max:1000',
            'experience' => 'required|string|max:1000',
            'old_benevole' => 'nullable',
            'availability_begin' => 'required|date',
            'availability_end' => 'required|date|after_or_equal:availability_begin',
            'hour_month' => 'required|integer|min:0',
            'permis' => 'nullable',
            'additional_notes' => 'nullable|string|max:1000'
        ]);

        // Convertir la valeur de 'permis' en booléen
        //$validated['permis'] = $request->has('permis');
        //$validated['old_benevole'] = $request->has('old_benevole');


        // Vérifier si l'utilisateur est connecté et récupérer son ID
        if (Auth::check()) {
            $userId = Auth::id(); // Obtient l'ID de l'utilisateur connecté

            $adhesion = new AdhesionBenevole([
                'motivation' => $request->motivation,
                'experience' => $request->experience,
                'old_benevole' => $request->has('old_benevole'),
                'availability_begin' => $request->availability_begin,
                'availability_end' => $request->availability_end,
                'hour_month' => $request->hour_month,
                'permis' => $request->has('permis'),
                'additional_notes' => $request->additional_notes,
                'user_id' => $userId,
            ]);

            $adhesion->save();

            // Création de l'entrée générale dans Adhesions
            $adhesion = new Adhesion([
                'candidature_id' => $userId,
                'candidature_type' => AdhesionBenevole::class
            ]);
            $adhesion->save();
            return view('page_navbar.services')->with('success', 'Adhésion bénévole enregistrée avec succès.');
        } else {
            return redirect('login')->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }
    }
}
