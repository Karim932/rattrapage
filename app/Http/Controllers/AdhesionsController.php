<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdhesionBenevole;
use App\Models\AdhesionCommercant;
use App\Models\Adhesion;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Answer;
use App\Models\Skill;

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
        $userId = Auth::id();
        $user = User::with('adhesionsBenevoles')->find($userId);
        $skills = Skill::all();

        // Sert à check si l'user a déjà postuler
        if ($user && $user->adhesionsBenevoles->isNotEmpty()) {

            $candidature = $user->adhesionsBenevoles->first();
            $idCandidature = $candidature->id;

            $answers = Answer::where('candidature_id', $idCandidature)->get();


            return view('page_navbar.benevoles.attente', compact('answers', 'candidature'));

        } else {
            return view('page_navbar.benevoles.adhesion', compact('skills'));
        }
    }

    public function changeBenevole()
    {
        $userId = Auth::id();

        // Vérifier si l'utilisateur est connecté
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté.');
        }

        // Récupérer l'utilisateur avec ses adhésions
        $user = User::with('adhesionsBenevoles')->find($userId);

        // Vérifier si l'utilisateur a une candidature
        $candidature = $user->adhesionsBenevoles->first();
        if (!$candidature) {
            return redirect()->route('some_route')->with('error', 'Aucune candidature trouvée.');
        }

        // Récupérer toutes les compétences disponibles
        $skills = Skill::all();

        // Décoder la chaîne JSON en tableau
        $selectedSkills = json_decode($candidature->skill_id, true);

        // Vérifier que $selectedSkills est bien un tableau
        if (!is_array($selectedSkills)) {
            $selectedSkills = [];
        };


        // Vérifier si 'availability' est déjà un tableau ou le décoder si c'est une chaîne JSON
        $availability = is_array($candidature->availability) ? $candidature->availability : json_decode($candidature->availability, true);

        // Retourner la vue avec les données nécessaires
        return view('page_navbar.benevoles.adhesion', compact('candidature', 'availability', 'skills', 'selectedSkills'));
    }


    public function updateBenevole(Request $request, $id)
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
            'additional_notes.max' => 'Les notes supplémentaires ne peuvent excéder 1000 caractères.',
        ];

        $validatedData = $request->validate([
            'skills' => 'required|array',
            'skills.*' => 'exists:skills,id',
            'motivation' => 'required|string|max:500',
            'experience' => 'required|string|max:500',
            'old_benevole' => 'nullable',
            'availability' => 'required|array',
            'availability.*.*' => 'nullable|in:1',
            'availability_begin' => 'required|date|after_or_equal:now',
            'availability_end' => 'required|date|after_or_equal:availability_begin',
            'permis' => 'nullable',
            'additional_notes' => 'nullable|string|max:500'
        ],$messages);

        try {
            // Trouver la candidature par ID
            $candidature = AdhesionBenevole::findOrFail($id);

            // Traitement des données de disponibilité
            $availability = $request->input('availability');
            $formattedAvailability = [];
            foreach ($availability as $day => $times) {
                foreach ($times as $time => $value) {
                    $formattedAvailability[$day][$time] = ($value == '1') ? true : false;
                }
            }

            // Mise à jour des propriétés de la candidature
            $candidature->motivation = $validatedData['motivation'];
            $candidature->experience = $validatedData['experience'];
            $candidature->old_benevole = $validatedData['old_benevole'] ?? false;
            $candidature->availability_begin = $validatedData['availability_begin'];
            $candidature->availability_end = $validatedData['availability_end'];
            $candidature->permis = $validatedData['permis'] ?? false;
            $candidature->additional_notes = $validatedData['additional_notes'];
            $candidature->availability = json_encode($formattedAvailability);
            $candidature->skill_id = json_encode($validatedData['skills']);
            $candidature->status = 'renvoyé';
            $candidature->save();

            return redirect()->route('benevole')->with('success', 'Candidature mise à jour avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour de la candidature: ' . $e->getMessage());
        }
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
            'additional_notes.max' => 'Les notes supplémentaires ne peuvent excéder 1000 caractères.',
        ];

        $request->validate([
            'skills' => 'required|array',
            'skills.*' => 'exists:skills,id',
            'motivation' => 'required|string|max:500',
            'experience' => 'required|string|max:500',
            'old_benevole' => 'nullable',
            'availability' => 'required|array',
            'availability.*.*' => 'nullable|in:1',
            'availability_begin' => 'required|date|after_or_equal:now',
            'availability_end' => 'required|date|after_or_equal:availability_begin',
            'permis' => 'nullable',
            'additional_notes' => 'nullable|string|max:500'
        ],$messages);

        // Vérifier si l'utilisateur est connecté et récupérer son ID
        if (Auth::check()) {
            $userId = Auth::id();

            $availability = $request->input('availability');

            // Traitement des données pour les adapter au format de stockage de votre base de données
            $formattedAvailability = [];
            foreach ($availability as $day => $times) {
                foreach ($times as $time => $value) {
                    $formattedAvailability[$day][$time] = ($value == '1') ? true : false; // Convertir en booléen
                }
            }

            // Créer l'entrée dans la table adhesions_benevoles
            // AdhesionBenevole::create($validatedData);
            $adhesionBenevole = new AdhesionBenevole([

                'skill_id' => json_encode($request->skills),
                'motivation' => $request->motivation,
                'experience' => $request->experience,
                'old_benevole' => $request->has('old_benevole'),
                'availability' => json_encode($formattedAvailability),
                'availability_begin' => $request->availability_begin,
                'availability_end' => $request->availability_end,
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

    public function dashboard()
    {
        return view('page_navbar.benevoles.dashboard');
    }
}
