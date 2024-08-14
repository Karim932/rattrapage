<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdhesionBenevole;
use App\Models\AdhesionCommercant;
use App\Models\Adhesion;
use Illuminate\Support\Facades\Auth;
use App\Rules\MaxHoursInMonth;

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
            $userId = Auth::id();
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
        // Personnalisation des messages d'erreur
        $messages = [
            'motivation.required' => 'Votre motivation est requise pour compléter l’inscription.',
            'motivation.max' => 'La motivation ne peut excéder 500 caractères.',
            'experience.required' => 'Décrivez vos expériences précédentes en bénévolat.',
            'experience.max' => 'L’expérience ne peut excéder 500 caractères.',
            'availability_begin.required' => 'La date de début est requise.',
            'availability_begin.date' => 'Entrez une date valide pour la date de début.',
            'availability_end.required' => 'La date de fin est requise.',
            'availability_end.date' => 'Entrez une date valide pour la date de fin.',
            'availability_end.after_or_equal' => 'La date de fin doit être après ou le même jour que la date de début.',
            'availability_begin.after_or_equal' => 'La date de début doit être après ou le même jour que la date d\'aujourd\'hui',
            'hour_month.required' => 'Indiquez le nombre d’heures que vous pouvez consacrer par mois.',
            'hour_month.integer' => 'Le nombre d’heures doit être un entier.',
            'hour_month.min' => 'Le nombre d’heures par mois ne peut être négatif.',
            'additional_notes.max' => 'Les notes supplémentaires ne peuvent excéder 1000 caractères.',
        ];

        $request->validate([
            'motivation' => 'required|string|max:500',
            'experience' => 'required|string|max:500',
            'old_benevole' => 'nullable',
            'availability_begin' => 'required|date|after_or_equal:now',
            'availability_end' => 'required|date|after_or_equal:availability_begin',
            'hour_month' => ['required', 'integer', 'min:0', new MaxHoursInMonth()],
            'permis' => 'nullable',
            'additional_notes' => 'nullable|string|max:500'
        ],$messages);

        // Vérifier si l'utilisateur est connecté et récupérer son ID
        if (Auth::check()) {
            $userId = Auth::id();

            $adhesionBenevole = new AdhesionBenevole([
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
            $adhesionBenevole->save();

            // Création de l'entrée générale dans Adhesions
            $adhesion = new Adhesion([
                'candidature_id' => $adhesionBenevole->id,
                'candidature_type' => AdhesionBenevole::class
            ]);
            $adhesion->save();
            return view('page_navbar.services')->with('success', 'Adhésion bénévole enregistrée avec succès.');
        } else {
            return redirect('login')->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }
    }
}
