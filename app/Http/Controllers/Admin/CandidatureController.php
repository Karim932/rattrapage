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
        $query = Adhesion::query();

        if ($request->filled('status')) {
            $query->whereHas('fusion', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if ($request->filled('type')) {
            if ($request->type === 'Commerçant') {
                $query->whereHasMorph('fusion', [AdhesionCommercant::class]);
            } elseif ($request->type === 'Bénévole') {
                $query->whereHasMorph('fusion', [AdhesionBenevole::class]);
            }
        }

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

        $allCandidatures = $query->with('fusion.user')->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.adhesions.index', compact('allCandidatures'));

    }

    public function create()
    {
        $usersWithoutCandidature = User::doesntHave('adhesionsBenevoles')->doesntHave('adhesionCommercants')->get();

        $skills = Skill::all();

        return view('admin.adhesions.create', compact('skills', 'usersWithoutCandidature'));
    }

    public function store(Request $request)
    {
        if (Auth::check()) {

            if ($request->type === 'commercant') {
                $messages = [
                    'user_id.required' => 'L\'utilisateur est requis.',
                    'user_id.exists' => 'L\'utilisateur sélectionné n\'existe pas.',
                    'company_name.required' => 'Le nom de l\'entreprise est requis.',
                    'company_name.string' => 'Le nom de l\'entreprise doit être une chaîne de caractères.',
                    'company_name.max' => 'Le nom de l\'entreprise ne doit pas dépasser 255 caractères.',
                    'siret.required' => 'Le numéro SIRET est requis.',
                    'siret.string' => 'Le numéro SIRET doit être une chaîne de caractères.',
                    'siret.size' => 'Le numéro SIRET doit comporter exactement 14 caractères.',
                    'siret.unique' => 'Ce numéro SIRET est déjà utilisé.',
                    'address.required' => 'L\'adresse est requise.',
                    'address.string' => 'L\'adresse doit être une chaîne de caractères.',
                    'address.max' => 'L\'adresse ne doit pas dépasser 500 caractères.',
                    'city.required' => 'La ville est requise.',
                    'city.string' => 'La ville doit être une chaîne de caractères.',
                    'city.max' => 'La ville ne doit pas dépasser 255 caractères.',
                    'postal_code.required' => 'Le code postal est requis.',
                    'postal_code.string' => 'Le code postal doit être une chaîne de caractères.',
                    'postal_code.max' => 'Le code postal ne doit pas dépasser 10 caractères.',
                    'postal_code.regex' => 'Le code postal doit être un nombre valide (ex: 75001).',
                    'country.required' => 'Le pays est requis.',
                    'country.string' => 'Le pays doit être une chaîne de caractères.',
                    'country.max' => 'Le pays ne doit pas dépasser 255 caractères.',
                    'country.regex' => 'Le pays doit contenir uniquement des lettres et des espaces.',
                    'notes.string' => 'Les notes doivent être une chaîne de caractères.',
                    'notes.max' => 'Les notes ne doivent pas dépasser 1000 caractères.',
                    'opening_hours.string' => 'Les heures d\'ouverture doivent être une chaîne de caractères.',
                    'opening_hours.max' => 'Les heures d\'ouverture ne doivent pas dépasser 255 caractères.',
                    'contract_start_date.required' => 'La date de début du contrat est requise.',
                    'contract_start_date.date' => 'La date de début du contrat doit être une date valide.',
                    'contract_start_date.after_or_equal' => 'La date de début du contrat doit être aujourd\'hui ou plus tard.',
                    'contract_end_date.required' => 'La date de fin du contrat est requise.',
                    'contract_end_date.date' => 'La date de fin du contrat doit être une date valide.',
                    'contract_end_date.after_or_equal' => 'La date de fin du contrat doit être égale ou postérieure à la date de début.',
                ];
            
                $validated = $request->validate([
                    'user_id' => 'required|exists:users,id',
                    'company_name' => 'required|string|max:255',
                    'siret' => [
                        'required',
                        'string',
                        'size:14',
                        'unique:adhesion_commercants,siret',
                        'regex:/^\d{14}$/'
                    ],
                    'address' => 'required|string|max:500',
                    'city' => [
                        'required',
                        'string',
                        'max:255',
                        'regex:/^[a-zA-Z\s\-]+$/'
                    ],
                    'postal_code' => [
                        'required',
                        'string',
                        'max:10',
                        'regex:/^\d{5}$/'
                    ],
                    'country' => [
                        'required',
                        'string',
                        'max:255',
                        'regex:/^[a-zA-Z\s]+$/'
                    ],
                    'notes' => 'nullable|string|max:1000',
                    'opening_hours' => 'nullable|string|max:255',
                    'contract_start_date' => 'required|date|after_or_equal:today',
                    'contract_end_date' => 'required|date|after_or_equal:contract_start_date',
                ], $messages);

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
                $messages = [
                    'user_id.required' => 'L\'utilisateur est requis.',
                    'user_id.exists' => 'L\'utilisateur sélectionné n\'existe pas.',
                    'skills.required' => 'Les compétences sont requises.',
                    'skills.array' => 'Les compétences doivent être un tableau.',
                    'skills.*.exists' => 'Certaines compétences sélectionnées n\'existent pas.',
                    'motivation.required' => 'La motivation est requise.',
                    'motivation.string' => 'La motivation doit être une chaîne de caractères.',
                    'motivation.max' => 'La motivation ne doit pas dépasser 500 caractères.',
                    'experience.required' => 'L\'expérience est requise.',
                    'experience.string' => 'L\'expérience doit être une chaîne de caractères.',
                    'experience.max' => 'L\'expérience ne doit pas dépasser 500 caractères.',
                    'old_benevole.nullable' => 'L\'ancienneté du bénévole est optionnelle.',
                    'availability.required' => 'La disponibilité est requise.',
                    'availability.array' => 'La disponibilité doit être un tableau.',
                    'availability.*.*.in' => 'Les valeurs de disponibilité doivent être valides.',
                    'availability_begin.required' => 'La date de début de disponibilité est requise.',
                    'availability_begin.date' => 'La date de début de disponibilité doit être une date valide.',
                    'availability_begin.after_or_equal' => 'La date de début de disponibilité doit être aujourd\'hui ou plus tard.',
                    'availability_end.required' => 'La date de fin de disponibilité est requise.',
                    'availability_end.date' => 'La date de fin de disponibilité doit être une date valide.',
                    'availability_end.after_or_equal' => 'La date de fin de disponibilité doit être égale ou postérieure à la date de début.',
                    'permis.nullable' => 'Le permis est optionnel.',
                    'additional_notes.nullable' => 'Les notes supplémentaires sont optionnelles.',
                    'additional_notes.string' => 'Les notes supplémentaires doivent être une chaîne de caractères.',
                    'additional_notes.max' => 'Les notes supplémentaires ne doivent pas dépasser 500 caractères.',
                ];
                
                $validated = $request->validate([
                    'user_id' => 'required|exists:users,id',
                    'skills' => 'required|array',
                    'skills.*' => 'exists:skills,id',
                    'motivation' => [
                        'required',
                        'string',
                        'max:500',
                        'regex:/^[a-zA-Z0-9\s\-.,\'"!?()]+$/'
                    ],
                    'experience' => [
                        'required',
                        'string',
                        'max:500',
                        'regex:/^[a-zA-Z0-9\s\-.,\'"!?()]+$/'
                    ],
                    'old_benevole' => 'nullable|boolean',
                    'availability' => 'required|array',
                    'availability.*.*' => 'nullable|in:1',
                    'availability_begin' => 'required|date|after_or_equal:now',
                    'availability_end' => 'required|date|after_or_equal:availability_begin',
                    'permis' => 'nullable|boolean',
                    'additional_notes' => [
                        'nullable',
                        'string',
                        'max:500',
                        'regex:/^[a-zA-Z0-9\s\-.,\'"!?()]+$/'
                    ],
                ], $messages);
                

                $availability = $request->input('availability');
                $formattedAvailability = [];
                foreach ($availability as $day => $times) {
                    foreach ($times as $time => $value) {
                        $formattedAvailability[$day][$time] = ($value == '1') ? true : false;
                    }
                }

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

        if (!$adhesion->fusion) {
            return redirect()->route('adhesion.index')->with('error', 'Candidature spécifique non trouvée.');
        }

        $candidature = $adhesion->fusion;
        $fusionId = $adhesion->fusion->id;
        $skillIds = $candidature->skill_id;

        if ($candidature instanceof \App\Models\AdhesionBenevole && !is_null($candidature->skill_id)) {
            if (is_string($skillIds) && $this->isJson($skillIds)) {
                $skillIds = json_decode($skillIds, true);
            }
            $skills = \App\Models\Skill::whereIn('id', $skillIds)->pluck('name')->toArray();
        } else {
            $skills = []; 
        }

        $answers = Answer::where('candidature_id', $fusionId)->get();


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
        $selectedSkills = $candidature->skill_id;

        $availability = is_array($candidature->availability) ? $candidature->availability : json_decode($candidature->availability, true);

        if ($candidature instanceof AdhesionCommercant) {
            return view('admin.adhesions.edit', compact('candidature', 'adhesion'));
        } elseif ($candidature instanceof AdhesionBenevole) {

            $skills = Skill::all();

            if (is_string($selectedSkills) && $this->isJson($selectedSkills)) {
                $selectedSkills = json_decode($selectedSkills, true);
            } 


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
            $messages = [
                'company_name.required' => 'Le nom de l\'entreprise est requis.',
                'company_name.string' => 'Le nom de l\'entreprise doit être une chaîne de caractères.',
                'company_name.max' => 'Le nom de l\'entreprise ne doit pas dépasser 255 caractères.',
                'siret.required' => 'Le numéro SIRET est requis.',
                'siret.string' => 'Le numéro SIRET doit être une chaîne de caractères.',
                'siret.size' => 'Le numéro SIRET doit comporter exactement 14 caractères.',
                'siret.unique' => 'Ce numéro SIRET est déjà utilisé.',
                'address.required' => 'L\'adresse est requise.',
                'address.string' => 'L\'adresse doit être une chaîne de caractères.',
                'address.max' => 'L\'adresse ne doit pas dépasser 500 caractères.',
                'city.required' => 'La ville est requise.',
                'city.string' => 'La ville doit être une chaîne de caractères.',
                'city.max' => 'La ville ne doit pas dépasser 255 caractères.',
                'postal_code.required' => 'Le code postal est requis.',
                'postal_code.string' => 'Le code postal doit être une chaîne de caractères.',
                'postal_code.max' => 'Le code postal ne doit pas dépasser 10 caractères.',
                'postal_code.regex' => 'Le code postal doit être un nombre valide (ex: 75001).',
                'country.required' => 'Le pays est requis.',
                'country.string' => 'Le pays doit être une chaîne de caractères.',
                'country.max' => 'Le pays ne doit pas dépasser 255 caractères.',
                'country.regex' => 'Le pays doit contenir uniquement des lettres et des espaces.',
                'notes.string' => 'Les notes doivent être une chaîne de caractères.',
                'notes.max' => 'Les notes ne doivent pas dépasser 1000 caractères.',
                'opening_hours.string' => 'Les heures d\'ouverture doivent être une chaîne de caractères.',
                'opening_hours.max' => 'Les heures d\'ouverture ne doivent pas dépasser 255 caractères.',
                'contract_start_date.required' => 'La date de début du contrat est requise.',
                'contract_start_date.date' => 'La date de début du contrat doit être une date valide.',
                'contract_start_date.after_or_equal' => 'La date de début du contrat doit être aujourd\'hui ou plus tard.',
                'contract_end_date.required' => 'La date de fin du contrat est requise.',
                'contract_end_date.date' => 'La date de fin du contrat doit être une date valide.',
                'contract_end_date.after_or_equal' => 'La date de fin du contrat doit être égale ou postérieure à la date de début.',
            ];
        
            $validatedData = $request->validate([
                'company_name' => 'required|string|max:255',
                'siret' => [
                    'required',
                    'string',
                    'size:14',
                    'regex:/^\d{14}$/'
                ],
                'address' => 'required|string|max:500',
                'city' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[a-zA-Z\s\-]+$/'
                ],
                'postal_code' => [
                    'required',
                    'string',
                    'max:10',
                    'regex:/^\d{5}$/'
                ],
                'country' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[a-zA-Z\s]+$/'
                ],
                'notes' => 'nullable|string|max:1000',
                'opening_hours' => 'nullable|string|max:255',
                'contract_start_date' => 'required|date|after_or_equal:now',
                'contract_end_date' => 'required|date|after_or_equal:contract_start_date',
            ], $messages);

            $candidature->update($validatedData);

        } elseif ($candidature instanceof AdhesionBenevole) {
            $messages = [
                'skills.required' => 'Les compétences sont requises.',
                'skills.array' => 'Les compétences doivent être un tableau.',
                'skills.*.exists' => 'Certaines compétences sélectionnées n\'existent pas.',
                'motivation.required' => 'La motivation est requise.',
                'motivation.string' => 'La motivation doit être une chaîne de caractères.',
                'motivation.max' => 'La motivation ne doit pas dépasser 500 caractères.',
                'experience.required' => 'L\'expérience est requise.',
                'experience.string' => 'L\'expérience doit être une chaîne de caractères.',
                'experience.max' => 'L\'expérience ne doit pas dépasser 500 caractères.',
                'old_benevole.nullable' => 'L\'ancienneté du bénévole est optionnelle.',
                'availability.required' => 'La disponibilité est requise.',
                'availability.array' => 'La disponibilité doit être un tableau.',
                'availability.*.*.in' => 'Les valeurs de disponibilité doivent être valides.',
                'availability_begin.required' => 'La date de début de disponibilité est requise.',
                'availability_begin.date' => 'La date de début de disponibilité doit être une date valide.',
                'availability_begin.after_or_equal' => 'La date de début de disponibilité doit être aujourd\'hui ou plus tard.',
                'availability_end.required' => 'La date de fin de disponibilité est requise.',
                'availability_end.date' => 'La date de fin de disponibilité doit être une date valide.',
                'availability_end.after_or_equal' => 'La date de fin de disponibilité doit être égale ou postérieure à la date de début.',
                'permis.nullable' => 'Le permis est optionnel.',
                'additional_notes.nullable' => 'Les notes supplémentaires sont optionnelles.',
                'additional_notes.string' => 'Les notes supplémentaires doivent être une chaîne de caractères.',
                'additional_notes.max' => 'Les notes supplémentaires ne doivent pas dépasser 500 caractères.',
            ];
            
            $validatedData = $request->validate([
                'skills' => 'required|array',
                'skills.*' => 'exists:skills,id',
                'motivation' => [
                    'required',
                    'string',
                    'max:500',
                    'regex:/^[a-zA-Z0-9\s\-.,\'"!?()]+$/'
                ],
                'experience' => [
                    'required',
                    'string',
                    'max:500',
                    'regex:/^[a-zA-Z0-9\s\-.,\'"!?()]+$/'
                ],
                'old_benevole' => 'boolean',
                'availability' => 'required|array',
                'availability.*.*' => 'nullable|in:1',
                'availability_begin' => 'required|date|after_or_equal:now',
                'availability_end' => 'required|date|after_or_equal:availability_begin',
                'permis' => 'boolean',
                'additional_notes' => [
                    'nullable',
                    'string',
                    'max:500',
                    'regex:/^[a-zA-Z0-9\s\-.,\'"!?()]+$/'
                ],
            ], $messages);
            

            $availability = $request->input('availability');
            $formattedAvailability = [];
            foreach ($availability as $day => $times) {
                foreach ($times as $time => $value) {
                    $formattedAvailability[$day][$time] = ($value == '1') ? true : false;
                }
            }

            $candidature->availability = json_encode($formattedAvailability);
            if (is_array($validatedData['skills']) || !$this->isJson($validatedData['skills'])) {
                $candidature->skill_id = json_encode($validatedData['skills']);
            } else {
                $candidature->skill_id = $validatedData['skills'];
            }
            
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
            $adhesion = Adhesion::findOrFail($id);

            $adhesion->fusion->answers()->delete();

            if ($adhesion->fusion instanceof AdhesionCommercant) {
                $adhesion->fusion->delete();
            } elseif ($adhesion->fusion instanceof AdhesionBenevole) {
                $adhesion->fusion->delete();
            }

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


    /**
     * Vérifie si une chaîne est un JSON valide.
     *
     * @param string $string
     * @return bool
     */
    private function isJson($string)
    {
        json_decode($string);
        return (json_last_error() === JSON_ERROR_NONE);
    }

}
