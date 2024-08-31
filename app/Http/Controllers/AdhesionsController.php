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
use Carbon\Carbon;

class AdhesionsController extends Controller
{
    public function candidature(){
        return view('benevoles');
    }

    
    public function createCommercant()
    {
        $userId = Auth::id();
        $candidature = AdhesionCommercant::where('user_id', $userId)->first();

        $hasBenevoleCandidature = AdhesionBenevole::where('user_id', $userId)->exists();

        if ($hasBenevoleCandidature) {
            return redirect()->back()->with('error', 'Vous avez déjà une candidature en tant que Bénévole.');
        }

        
        if ($candidature) {

            if($candidature->status === 'accepté'){
                return redirect()->route('commercant.dashboard');
            }

            
            $idCandidature = $candidature->id;

            $answers = Answer::where('candidature_id', $idCandidature)->get();


            return view('page_navbar.commerçants.attente', compact('answers', 'candidature'));

        } else {
            return view('page_navbar.commerçants.adhesion');
        }
    }

   
    public function storeCommercant(Request $request)
    {
        
        if (Auth::check()) {
            $userId = Auth::id();

            
            $existingAdhesion = AdhesionCommercant::where('user_id', $userId)->first();

            if ($existingAdhesion) {
            return redirect()->back()->with('error', 'Vous avez déjà soumis une candidature.');
            }

            $request->validate([
                'company_name' => 'required|string|max:255',
                'siret' => 'required|string|size:14|unique:adhesion_commercants,siret',
                'address' => 'required|string|max:500',
                'city' => 'required|string|max:255',
                'postal_code' => 'required|string|max:10',
                'country' => 'required|string|max:255',
                'notes' => 'nullable|string|max:1000',
                'opening_hours' => 'nullable|string|max:255',
                'contract_start_date' => 'required|date|after_or_equal:now',
                'contract_end_date' => 'required|date|after_or_equal:availability_begin',
            ]);

            
            $adhesionCommercant = new AdhesionCommercant([
                'company_name' => $request->company_name,
                'siret' => $request->siret,
                'address' => $request->address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
                'notes' => $request->notes,
                'opening_hours' => $request->opening_hours,
                'contract_start_date' => $request->contract_start_date,
                'contract_end_date' => $request->contract_end_date,
                'user_id' => $userId
            ]);

            $adhesionCommercant->save();

            
            $adhesion = new Adhesion([
                'candidature_id' => $adhesionCommercant->id,
                'candidature_type' => AdhesionCommercant::class
            ]);
            $adhesion->save();
            return view('page_navbar.commerçants.attente')->with('success', 'Adhésion bénévole enregistrée avec succès.');
        } else {
            return redirect('login')->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }
    }

    public function updateCommercant(Request $request, $id)
    {
        $user = Auth::user(); 

        
        $hasBenevoleCandidature = $user->adhesions->contains(function($adh) {
            return $adh->candidature_type === \App\Models\AdhesionBenevole::class;
        });

        if ($hasBenevoleCandidature && $request->type === 'commercant') {
            return back()->with('error', 'Vous avez déjà une candidature en tant que bénévole. Vous ne pouvez pas soumettre en tant que commerçant.');
        }

        $messages = [
            'company_name.required' => 'Le nom de l\'entreprise est requis.',
            'siret.required' => 'Le numéro SIRET est requis.',
            'siret.size' => 'Le numéro SIRET doit contenir 14 chiffres.',
            'address.required' => 'L\'adresse est requise.',
            'city.required' => 'La ville est requise.',
            'postal_code.required' => 'Le code postal est requis.',
            'country.required' => 'Le pays est requis.',
            'contract_start_date.required' => 'La date de début du contrat est requise.',
            'contract_start_date.date' => 'Entrez une date valide pour la date de début.',
            'contract_end_date.required' => 'La date de fin du contrat est requise.',
            'contract_end_date.date' => 'Entrez une date valide pour la date de fin.',
            'contract_end_date.after_or_equal' => 'La date de fin doit être après ou le même jour que la date de début.',
            'opening_hours.required' => 'Les horaires d\'ouverture sont requis.',
            'notes.max' => 'Les notes ne peuvent excéder 1000 caractères.',
        ];

        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'siret' => 'required|string|size:14',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:255',
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'required|date|after_or_equal:contract_start_date',
            'opening_hours' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000'
        ], $messages);

        try {
            $candidature = AdhesionCommercant::findOrFail($id);

            $candidature->company_name = $validatedData['company_name'];
            $candidature->siret = $validatedData['siret'];
            $candidature->address = $validatedData['address'];
            $candidature->city = $validatedData['city'];
            $candidature->postal_code = $validatedData['postal_code'];
            $candidature->country = $validatedData['country'];
            $candidature->contract_start_date = $validatedData['contract_start_date'];
            $candidature->contract_end_date = $validatedData['contract_end_date'];
            $candidature->opening_hours = $validatedData['opening_hours'];
            $candidature->notes = $validatedData['notes'];
            $candidature->status = 'renvoyé';
            $candidature->save();

            return redirect()->route('commercant')->with('success', 'Informations du commerçant mises à jour avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour des informations du commerçant: ' . $e->getMessage());
        }
    }

    public function changeCommercant()
    {
        $userId = Auth::id();

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté.');
        }

        $candidature = AdhesionCommercant::where('user_id', $userId)->first();

        if (!$candidature) {
            return redirect()->route('some_route')->with('error', 'Aucune candidature commerciale trouvée.');
        }

        return view('page_navbar.commerçants.adhesion', [
            'candidature' => $candidature,
        ]);
    }

    public function createBenevole()
    {
        $userId = Auth::id();
        $user = User::with('adhesionsBenevoles')->find($userId);
        $candidature = $user->adhesionsBenevoles;
        $skills = Skill::all();

        $hasCommercantCandidature = AdhesionCommercant::where('user_id', $userId)->exists();

        if ($hasCommercantCandidature) {
            return redirect()->back()->with('error', 'Vous avez déjà une candidature en tant que Commerçant.');
        }

        if ($candidature && $candidature->status === 'accepté') {
            return redirect()->route('benevole.collectes.index');
        }
        

        // dd($user, $findCandidature);
        if ($user && $candidature) {

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

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté.');
        }

        $user = User::with('adhesionsBenevoles')->find($userId);


        $candidature = $user->adhesionsBenevoles;

        if (!$candidature) {
            return redirect()->route('some_route')->with('error', 'Aucune candidature trouvée.');
        }

        $skills = Skill::all();

        $selectedSkills = json_decode($candidature->skill_id, true);

        if (!is_array($selectedSkills)) {
            $selectedSkills = [];
        };



        $availability = is_array($candidature->availability) ? $candidature->availability : json_decode($candidature->availability, true);

        return view('page_navbar.benevoles.adhesion', compact('candidature', 'availability', 'skills', 'selectedSkills'));
    }



    public function updateBenevole(Request $request, $id)
    {

        // dd($id, $request->id);
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
            'availability_begin' => 'required|date|after_or_equal:' . Carbon::now()->toDateString(),
            'availability_end' => 'required|date|after_or_equal:availability_begin',
            'permis' => 'nullable',
            'additional_notes' => 'nullable|string|max:500'
        ],$messages);

        try {
            $candidature = AdhesionBenevole::findOrFail($id);

            $availability = $request->input('availability');
            $formattedAvailability = [];
            foreach ($availability as $day => $times) {
                foreach ($times as $time => $value) {
                    $formattedAvailability[$day][$time] = ($value == '1') ? true : false;
                }
            }

            $candidature->motivation = $validatedData['motivation'];
            $candidature->experience = $validatedData['experience'];
            $candidature->old_benevole = $validatedData['old_benevole'] ?? false;
            $candidature->availability_begin = $validatedData['availability_begin'];
            $candidature->availability_end = $validatedData['availability_end'];
            $candidature->permis = $validatedData['permis'] ?? false;
            $candidature->additional_notes = $validatedData['additional_notes'];
            $candidature->availability = $formattedAvailability;
            $candidature->skill_id = json_encode($validatedData['skills']);
            $candidature->status = 'renvoyé';
            $candidature->save();

            return redirect()->route('benevole')->with('success', 'Candidature mise à jour avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour de la candidature: ' . $e->getMessage());
        }
    }


    public function storeBenevole(Request $request)
    {
        $user = Auth::user(); 

        $hasCommercantCandidature = $user->adhesions->contains(function($adh) {
            return $adh->candidature_type === \App\Models\AdhesionCommercant::class;
        });

        if ($hasCommercantCandidature && $request->type === 'benevole') {
            return back()->with('error', 'Vous avez déjà une candidature en tant que commerçant. Vous ne pouvez pas soumettre en tant que bénévole.');
        }

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
            'availability_begin' => 'required|date|after_or_equal:' . Carbon::now()->toDateString(),
            'availability_end' => 'required|date|after_or_equal:availability_begin',
            'permis' => 'nullable',
            'additional_notes' => 'nullable|string|max:500'
        ],$messages);

        if (Auth::check()) {
            $userId = Auth::id();

            $existingAdhesion = AdhesionBenevole::where('user_id', $userId)->first();

            if ($existingAdhesion) {
            return redirect()->back()->with('error', 'Vous avez déjà soumis une candidature.');
            }

            $availability = $request->input('availability');

            $formattedAvailability = [];
            foreach ($availability as $day => $times) {
                foreach ($times as $time => $value) {
                    $formattedAvailability[$day][$time] = ($value == '1') ? true : false; // Convertir en booléen
                }
            }

            $adhesionBenevole = new AdhesionBenevole([

                'skill_id' => json_encode($request->skills),
                'motivation' => $request->motivation,
                'experience' => $request->experience,
                'old_benevole' => $request->has('old_benevole'),
                'availability' => $formattedAvailability,
                'availability_begin' => $request->availability_begin,
                'availability_end' => $request->availability_end,
                'permis' => $request->has('permis'),
                'additional_notes' => $request->additional_notes,
                'user_id' => $userId,
            ]);
            $adhesionBenevole->save();

            $adhesion = new Adhesion([
                'candidature_id' => $adhesionBenevole->id,
                'candidature_type' => AdhesionBenevole::class
            ]);
            $adhesion->save();

            return redirect()->route('benevole')->with('success', 'Adhésion bénévole enregistrée avec succès.');
        } else {
            return redirect('login')->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }
    }

    public function dashboard()
    {
        return view('page_navbar.benevoles.dashboard');
    }

    
}
