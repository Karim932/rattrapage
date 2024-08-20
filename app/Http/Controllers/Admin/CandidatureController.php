<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdhesionBenevole;
use App\Models\AdhesionCommercant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Adhesion;
use App\Models\Answer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Skill;
use App\Models\User;

class CandidatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //     // $allCandidatures = $allCandidatures->shuffle();
    //     // inRandomOrder()-> // pour mélanger la table comme shuffle

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

        // Joindre la table users via la relation polymorphique
        $query->leftJoin('adhesion_benevoles', function($join) {
            $join->on('adhesion_benevoles.id', '=', 'adhesions.candidature_id')
                ->where('adhesions.candidature_type', '=', AdhesionBenevole::class);
        })
        ->leftJoin('adhesion_commercants', function($join) {
            $join->on('adhesion_commercants.id', '=', 'adhesions.candidature_id')
                ->where('adhesions.candidature_type', '=', AdhesionCommercant::class);
        })
        ->leftJoin('users', function($join) {
            $join->on('users.id', '=', DB::raw('COALESCE(adhesion_benevoles.user_id, adhesion_commercants.user_id)'));
        })
        ->select('adhesions.*', 'users.email');


        if ($request->has('sort') && $request->has('direction')) {
            $sort = $request->input('sort');
            $direction = $request->input('direction');

            switch ($sort) {
                case 'id':
                    $query->orderBy('adhesions.id', $direction);
                    break;
                case 'name':
                    $query->orderByRaw("
                        COALESCE(
                            users.firstname,
                            adhesion_commercants.company_name
                        ) $direction
                    ");
                    break;
                case 'email':
                    $query->orderBy('users.email', $direction);
                    break;
                case 'type':
                    $query->orderBy('adhesions.candidature_type', $direction);
                    break;
                case 'status':
                    $query->orderByRaw("
                        COALESCE(
                            adhesion_benevoles.status,
                            adhesion_commercants.status
                        ) $direction
                    ");
                    break;
                case 'created_at':
                    $query->orderBy('adhesions.created_at', $direction);
                    break;
                default:
                    $query->orderBy('adhesions.id', 'asc');
            }
        }

        // Charger les données avec la relation polymorphique fusion et paginer
        $allCandidatures = $query->with('fusion.user')->paginate(20);

        // Retourner la vue avec les données paginées et triées
        return view('admin.adhesions.index', compact('allCandidatures'));

    }

    public function create()
    {
        // Récupérer tous les utilisateurs qui n'ont pas de candidature
        $usersWithoutCandidature = User::doesntHave('adhesionsBenevoles')->doesntHave('adhesionCommercants')->get();

        // Récupérer toutes les compétences disponibles
        $skills = Skill::all();

        // Passer les compétences à la vue
        return view('admin.adhesions.create', compact('skills', 'usersWithoutCandidature'));
    }

    public function store(Request $request)
    {
        // Personnalisation des messages d'erreur
        $messages = [
            'company_name.required' => 'Le nom de l\'entreprise est requis.',
            'siret.required' => 'Le numéro SIRET est requis.',
            'siret.size' => 'Le numéro SIRET doit contenir exactement 14 chiffres.',
            'siret.unique' => 'Ce numéro SIRET est déjà enregistré.',
            'address.required' => 'L\'adresse est requise.',
            'city.required' => 'La ville est requise.',
            'postal_code.required' => 'Le code postal est requis.',
            'country.required' => 'Le pays est requis.',
            'motivation.required' => 'Votre motivation est requise pour compléter l’inscription.',
            'motivation.max' => 'La motivation ne peut excéder 500 caractères.',
            'experience.required' => 'Décrivez vos expériences précédentes en bénévolat.',
            'experience.max' => 'L’expérience ne peut excéder 500 caractères.',
            'availability_begin.required' => 'La date de début est requise.',
            'availability_begin.date' => 'Entrez une date valide pour la date de début.',
            'availability_end.required' => 'La date de fin est requise.',
            'availability_end.date' => 'Entrez une date valide pour la date de fin.',
            'availability_end.after_or_equal' => 'La date de fin doit être après ou le même jour que la date de début.',
            'availability_begin.after_or_equal' => 'La date de début doit être après ou le même jour que la date d\'aujourd\'hui.',
            'additional_notes.max' => 'Les notes supplémentaires ne peuvent excéder 1000 caractères.',
        ];

        // Vérifier si l'utilisateur est connecté et récupérer son ID
        if (Auth::check()) {

            if ($request->type === 'commercant') {
                $validated = $request->validate([
                    'user_id' => 'required|exists:users,id',
                    'company_name' => 'required|string|max:255',
                    'siret' => 'required|string|size:14|unique:adhesion_commercants,siret',
                    'address' => 'required|string|max:500',
                    'city' => 'required|string|max:255',
                    'postal_code' => 'required|string|max:10',
                    'country' => 'required|string|max:255',
                    'notes' => 'nullable|string|max:1000',
                    'opening_hours' => 'nullable|string|max:255',
                    'contract_start_date' => 'required|date|after_or_equal:now',
                    'contract_end_date' => 'required|date|after_or_equal:contract_start_date',
                ], $messages);

                // Utiliser l'ID de l'utilisateur choisi du formulaire
                $userId = $request->user_id;

                $adhesionCommercant = new AdhesionCommercant($validated);
                $adhesionCommercant->user_id = $userId;
                $adhesionCommercant->save();

                $adhesion = new Adhesion([
                    'candidature_id' => $adhesionCommercant->id,
                    'candidature_type' => AdhesionCommercant::class
                ]);
                $adhesion->save();

                return redirect()->route('adhesion.index')->with('success', 'Candidature commerciale enregistrée avec succès.');

            } else if ($request->type === 'benevole') {
                $validated = $request->validate([
                    'user_id' => 'required|exists:users,id',
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
                ], $messages);

                // Traitement des données de disponibilité
                $availability = $request->input('availability');
                $formattedAvailability = [];
                foreach ($availability as $day => $times) {
                    foreach ($times as $time => $value) {
                        $formattedAvailability[$day][$time] = ($value == '1') ? true : false;
                    }
                }

                // Utiliser l'ID de l'utilisateur choisi du formulaire
                $userId = $request->user_id;

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

                return redirect()->route('adhesion.index')->with('success', 'Candidature bénévole enregistrée avec succès.');
            }

            return back()->with('error', 'Type de candidature non spécifié ou non supporté.');
        }
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

        $candidature = $adhesion->fusion;
        $fusionId = $adhesion->fusion->id;

        if ($candidature instanceof \App\Models\AdhesionBenevole && !is_null($candidature->skill_id)) {
            $skillIds = json_decode($candidature->skill_id, true);
            $skills = \App\Models\Skill::whereIn('id', $skillIds)->pluck('name')->toArray();
        } else {
            $skills = []; // Ou vous pouvez laisser $skills non défini si vous ne prévoyez pas de l'utiliser pour les commerçants
        }

        // Récupérer toutes les réponses où 'candidature_id' est égal à $fusionId
        $answers = Answer::where('candidature_id', $fusionId)->get();

        // dd($fusionId, $adhesion, $adhesion->fusion, $answers);

        return view('admin.adhesions.show', [
            'adhesion' => $adhesion,
            'candidature' => $adhesion->fusion,
            'answers' => $answers,
            'skills' => $skills,
            'availability' => $candidature->availability
        ]);
    }

    public function edit(string $id)
    {

        $adhesion = Adhesion::with('fusion')->findOrFail($id);
        $candidature = $adhesion->fusion;

        // Vérifier si 'availability' est déjà un tableau ou le décoder si c'est une chaîne JSON
        $availability = is_array($candidature->availability) ? $candidature->availability : json_decode($candidature->availability, true);

        if ($candidature instanceof AdhesionCommercant) {
            return view('admin.adhesions.edit', compact('candidature', 'adhesion'));
        } elseif ($candidature instanceof AdhesionBenevole) {

            // Récupérer toutes les compétences disponibles
            $skills = Skill::all();

            // Décoder la chaîne JSON en tableau
            $selectedSkills = json_decode($candidature->skill_id, true);

            // Vérifier que $selectedSkills est bien un tableau
            if (!is_array($selectedSkills)) {
                $selectedSkills = [];
            };

            return view('admin.adhesions.edit', compact('candidature', 'adhesion', 'availability', 'skills', 'selectedSkills'));
        }

        return back()->with('error', 'Type de candidature non supporté.');

    }

    public function update(Request $request, $id)
    {
        $adhesion = Adhesion::with('fusion')->findOrFail($id);
        $candidature = $adhesion->fusion;

        if ($candidature instanceof AdhesionCommercant) {
            $validatedData = $request->validate([
                'company_name' => 'required|string|max:255',
                'siret' => 'required|numeric|digits:14',
                'address' => 'required|string|max:500',
                'city' => 'required|string|max:255',
                'postal_code' => 'required|string|max:10',
                'country' => 'required|string|max:255',
                'contract_start_date' => 'required|date|after_or_equal:now',
                'contract_end_date' => 'required|date|after_or_equal:availability_begin',
            ], [
                'company_name.required' => 'Le nom de l’entreprise est obligatoire.',
                'siret.required' => 'Le numéro SIRET est obligatoire.',
                'siret.digits' => 'Le numéro SIRET doit comporter 14 chiffres.',
                'address.required' => 'L’adresse est obligatoire.',
                'city.required' => 'La ville est obligatoire.',
                'postal_code.required' => 'Le code postal est obligatoire.',
                'country.required' => 'Le pays est obligatoire.',
            ]);

            // Mise à jour spécifique pour les commerçants
            $candidature->update($validatedData);

        } elseif ($candidature instanceof AdhesionBenevole) {
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
                'additional_notes' => 'nullable|string|max:1000',
            ], [
                'motivation.required' => 'Votre motivation est requise pour compléter l’inscription.',
                'motivation.max' => 'La motivation ne peut excéder 500 caractères.',
                'experience.required' => 'Décrivez vos expériences précédentes en bénévolat.',
                'experience.max' => 'L’expérience ne peut excéder 500 caractères.',
                'availability_begin.required' => 'La date de début est requise.',
                'availability_begin.date' => 'Entrez une date valide pour la date de début.',
                'availability_begin.after_or_equal' => 'La date de début doit être après ou le même jour que la date d\'aujourd\'hui',
                'availability_end.required' => 'La date de fin est requise.',
                'availability_end.date' => 'Entrez une date valide pour la date de fin.',
                'availability_end.after_or_equal' => 'La date de fin doit être après ou le même jour que la date de début.',
                'additional_notes.max' => 'Les notes supplémentaires ne peuvent excéder 1000 caractères.',
            ]);

            $availability = $request->input('availability');
            $formattedAvailability = [];
            foreach ($availability as $day => $times) {
                foreach ($times as $time => $value) {
                    $formattedAvailability[$day][$time] = ($value == '1') ? true : false;
                }
            }

            $candidature->availability = json_encode($formattedAvailability);
            $candidature->skill_id = json_encode($validatedData['skills']);

            // Mise à jour spécifique pour les bénévoles
            $candidature->update($validatedData);
            $candidature->status = 'renvoyé';
            $candidature->save();
        } else {
            return back()->with('error', 'Type de candidature non supporté.');
        }

        return redirect()->route('adhesion.index')->with('success', 'Candidature mise à jour avec succès.');
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
        $user = $adhesion->fusion->user;

        if ($class === AdhesionCommercant::class) {
            $role = 'commercant';
            $user->role = $role;
            $user->save();
        } elseif ($class === AdhesionBenevole::class) {
            $role = 'benevole';
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
