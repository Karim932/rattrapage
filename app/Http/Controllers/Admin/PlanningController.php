<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Planning;
use App\Models\Service;
use App\Models\User;
use App\Models\AdhesionBenevole;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



class PlanningController extends Controller
{
    public function index(Request $request)
    {
        $city = $request->input('city');
        
        // Récupérer les plannings avec les services, triés par ville
        if ($city) {
            $plannings = Planning::with('service')
                ->where('city', $city)
                ->orderBy('date')
                ->get();
        } else {
            $plannings = Planning::with('service')
                ->orderBy('city')
                ->orderBy('date')
                ->get();
        }
    
        // Renvoyer les villes distinctes pour le filtre
        $cities = Planning::select('city')->distinct()->orderBy('city')->get();

        // dd($cities, $plannings, $city);
    
        return view('admin.services.plannings.index', compact('plannings', 'cities', 'city'));
    }

    public function create()
    {
        $services = Service::all();

        return view('admin.services.plannings.create', compact('services'));
    }

    /**
     * Stocke un nouvel événement de planification dans la base de données.
     */
    public function store(Request $request)
    {

        // Validation des données du formulaire
        $validatedData = $request->validate([
            'service_id' => 'required|exists:services,id',  // Assurez-vous que le service_id existe dans la table des services
            'date' => 'required|date|after_or_equal:now',  // Assurez-vous que la date est bien une date
            'start_time' => 'required|date_format:H:i',  // Heure de début doit être une heure valide
            'end_time' => 'required|date_format:H:i|after:start_time',  // Heure de fin doit être après l'heure de début
            'city' => 'nullable|string',
            'address' => 'nullable|string',
            'max_inscrit' => 'nullable|integer',
            'benevole_id' => 'required|in:none,auto',
            
            // 'status' => 'required|in:planned,ongoing,completed,cancelled'  // Les statuts valides
        ]);


        $existingPlanning = Planning::where('service_id', $validatedData['service_id'])
        ->whereDate('date', '=', date('Y-m-d', strtotime($validatedData['date'])))
        ->where('city', $validatedData['city'])
        ->where(function ($query) use ($validatedData) {
            $query->where('start_time', '<', $validatedData['end_time'])
                  ->where('end_time', '>', $validatedData['start_time']);
        })
        ->exists();

        if ($existingPlanning) {
        return redirect()->back()->with('error', 'Un créneau existe déjà pour ce service à cette date, dans cette ville et pour cet horaire.');
        }


        // Création et sauvegarde du nouveau planning
        $planning = new Planning([
            'service_id' => $validatedData['service_id'],
            'date' => $validatedData['date'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
            'city' => $validatedData['city'],
            'address' => $validatedData['address'],
            'max_inscrit' => $validatedData['max_inscrit'],
            'benevole_id' => null, 
            
            // 'status' => $validatedData['status'],
        ]);

        if ($planning) {
            // Si l'assignation automatique est choisie
            if ($validatedData['benevole_id'] === 'auto') {
                // Logique pour assigner automatiquement un bénévole en fonction des disponibilités
                $benevole = $this->assignAutoBenevole($planning);
                
                if ($benevole) {
                    $planning->benevole_id = $benevole->id;
                    $planning->save();
                } else {
                    // Si aucun bénévole n'est disponible, retournez avec une erreur
                    return redirect()->back()->withErrors(['error' => 'Aucun bénévole disponible pour ce créneau horaire.'])->withInput();
                }
            }            
            

            return redirect()->route('plannings.index')->with('success', 'Nouvel événement ajouté avec succès.');
        }

        return redirect()->back()->with('error', 'Non c\'est une erreur.');
    }

    protected function assignAutoBenevole(Planning $planning)
    {
        // Définir les créneaux horaires
        $timeSlots = [
            'matin' => ['start' => '07:00', 'end' => '11:00'],
            'midi' => ['start' => '11:00', 'end' => '14:00'],
            'soir' => ['start' => '17:00', 'end' => '21:00']
        ];

        $planningStartTime = Carbon::parse($planning->start_time);
        $planningEndTime = Carbon::parse($planning->end_time);

        // Mapping des jours de la semaine en anglais vers le français
        $daysOfWeekMapping = [
            'monday' => 'lundi',
            'tuesday' => 'mardi',
            'wednesday' => 'mercredi',
            'thursday' => 'jeudi',
            'friday' => 'vendredi',
            'saturday' => 'samedi',
            'sunday' => 'dimanche',
        ];

        $dayOfWeek = strtolower(Carbon::parse($planning->date)->format('l'));
        $dayOfWeekFr = $daysOfWeekMapping[$dayOfWeek];  // Convertir en français


        // Filtrer les bénévoles disponibles pour le jour et l'heure du planning
        $benevoles = AdhesionBenevole::whereNotNull('availability')
            ->where('id_service', $planning->service_id)
            ->get()
            ->filter(function ($benevole) use ($planning, $planningStartTime, $planningEndTime, $timeSlots, $dayOfWeekFr) {
                // Vérifier si la disponibilité est déjà un tableau ou s'il faut la décoder
                if (is_string($benevole->availability)) {
                    $availability = json_decode($benevole->availability, true);
                } elseif (is_array($benevole->availability)) {
                    $availability = $benevole->availability;
                } else {
                    return false; // Cas où la disponibilité n'est ni une chaîne JSON ni un tableau
                }

                // Vérifier si le bénévole est disponible ce jour-là
                if (!isset($availability[$dayOfWeekFr])) {
                    return false;
                }

                // Vérifier les créneaux horaires
                foreach ($availability[$dayOfWeekFr] as $slot => $isAvailable) {
                    // Adapter pour gérer les deux formats possibles : "1" ou true
                    if (($isAvailable === "1" || $isAvailable === true) && isset($timeSlots[$slot])) {
                        $slotStart = Carbon::createFromTimeString($timeSlots[$slot]['start']);
                        $slotEnd = Carbon::createFromTimeString($timeSlots[$slot]['end']);

                        // Vérifier si le créneau couvre l'heure du planning
                        if ($planningStartTime->lessThanOrEqualTo($slotEnd) && $planningEndTime->greaterThanOrEqualTo($slotStart)) {
                            // Vérifier si le bénévole est déjà assigné à un autre planning à ce moment-là
                            $conflictingPlanning = Planning::where('benevole_id', $benevole->id)
                            ->where('date', $planning->date)
                            ->where(function ($query) use ($planningStartTime, $planningEndTime) {
                                // Vérifier si l'heure de début ou de fin est entre celles du nouveau planning
                                $query->where(function ($subQuery) use ($planningStartTime, $planningEndTime) {
                                    $subQuery->orWhereBetween('start_time', [$planningStartTime, $planningEndTime])
                                        ->orWhereBetween('end_time', [$planningStartTime, $planningEndTime]);
                                })
                                ->orWhere(function ($subQuery) use ($planningStartTime, $planningEndTime) {
                                    // Vérifier si le planning existant englobe entièrement le créneau du nouveau planning
                                    $subQuery->orWhere('start_time', '<=', $planningStartTime)
                                        ->orWhere('end_time', '>=', $planningEndTime);
                                })
                                ->orWhere(function ($subQuery) use ($planningStartTime, $planningEndTime) {
                                    // Nouvel ajout: Vérifier si les heures sont exactement les mêmes
                                    $subQuery->orWhere('start_time', $planningStartTime)
                                        ->orWhere('end_time', $planningEndTime);
                                });
                            })
                            ->exists();


                            // dd($conflictingPlanning, $planning->date);

                            // Si aucun conflit, ce bénévole est disponible
                            if (!$conflictingPlanning) {
                                // Debugging: Afficher le bénévole sélectionné
                                Log::info("Bénévole assigné: " . $benevole->user->firstname . $benevole->user->lastname);
                                return true;
                            } else {
                                // Debugging: Afficher les détails du conflit
                                Log::info("Conflit détecté pour le bénévole: " . $benevole->user->firstname . $benevole->user->lastname);
                            }
                        }
                    }
                }

                return false; // Bénévole non disponible pour ce créneau horaire
            });

        // Retourner le premier bénévole disponible ou null si aucun ne correspond
        return $benevoles->first();
    }

    public function getEvents(Request $request)
    {
        $city = $request->input('city');
        
        // Charger les plannings avec la relation 'service'
        $planningsQuery = Planning::with('service');
        
        if ($city) {
            $planningsQuery->where('city', $city);
        }

        $plannings = $planningsQuery->orderBy('city')->orderBy('date')->get();

        $events = $plannings->map(function ($planning) {
            return [
                'title' => $planning->service->name . ' - ' . ($planning->city ?? 'Ville non spécifiée'),  // Ajoutez la ville directement au titre
                'start' => Carbon::parse($planning->date)->format('Y-m-d') . 'T' . substr($planning->start_time, 0, 5),  // Prendre uniquement la date et l'heure (HH:MM)
                'end' => Carbon::parse($planning->date)->format('Y-m-d') . 'T' . substr($planning->end_time, 0, 5),  // Prendre uniquement la date et l'heure (HH:MM)
                'url' => route('plannings.show', $planning->id),
            ];
        });
        
        return response()->json($events);
        

        return response()->json($events);
    }


    public function edit($id)
    {
        // Récupération du planning spécifique par son ID avec gestion d'erreur si non trouvé
        $planning = Planning::findOrFail($id);

        // Récupération de tous les services pour les lister dans le formulaire de sélection
        $services = Service::all();

        // Passage du planning et des services à la vue
        return view('admin.services.plannings.edit', compact('planning', 'services'));
    }


    public function update(Request $request, $id)
    {
        // Validation and logic to update an event
        $planning = Planning::findOrFail($id);
        $planning->update($request->all());
        return redirect()->route('plannings.index')->with('success', 'Event updated successfully.');
    }

    public function show($id)
    {
        // Retrieve the specific planning by its ID
        $planning = Planning::with('service')->findOrFail($id);

        if (!$planning) {
            return redirect()->route('plannings.index')->with('error', 'planning non trouvée.');
        }

        // Pass the planning data to the view
        return view('admin.services.plannings.show', compact('planning'));
    }


    public function destroy($id)
    {
        try {
            $planning = Planning::findOrFail($id);
            $planning->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Event deleted successfully.',
                'redirectUrl' => route('plannings.index') // URL vers laquelle rediriger
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Une erreur s\'est produite lors de la suppression : ' . $e->getMessage(),
                'redirectUrl' => route('plannings.index') // Peut-être rediriger vers la même page avec un message d'erreur
            ]);
        }
    }


    public function showBenevole($id)
    {
        $planning = Planning::with('benevoles.user')->findOrFail($id);

        // Bénévoles déjà assignés
        $assignedBenevoles = $planning->benevoles;

        // Bénévoles disponibles pour ce service spécifique
        $benevoles = AdhesionBenevole::with('user')
            ->where('id_service', $planning->service_id)
            ->get();

        return view('admin.services.plannings.assign-benevole', compact('planning', 'assignedBenevoles', 'benevoles'));
    }

    public function addBenevole(Request $request, $id)
    {
        $planning = Planning::findOrFail($id);
        $benevole = AdhesionBenevole::findOrFail($request->benevole_id);

        // Assigner le bénévole au planning
        $planning->benevoles()->attach($benevole);

        return redirect()->back()->with('success', 'Bénévole ajouté avec succès.');
    }

    public function removeBenevole($planningId, $benevoleId)
    {
        $planning = Planning::findOrFail($planningId);
        $planning->benevoles()->detach($benevoleId);

        return redirect()->back()->with('success', 'Bénévole retiré avec succès.');
    }


    public function showInscrits($id)
    {
        $planning = Planning::findOrFail($id);
        $inscrits = $planning->users; // Supposant que vous avez une relation 'users' dans le modèle Planning

        return view('admin.services.plannings.adherent-inscrit', compact('planning', 'inscrits'));
    }

    public function showAddAdherentForm($id)
    {
        $planning = Planning::findOrFail($id);
        $users = User::all(); 

        return view('admin.services.plannings.add-adherent-inscrit', compact('planning', 'users'));
    }

    public function storeAdherent(Request $request, $id)
    {
        $planning = Planning::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $planning->users()->attach($request->user_id); // Supposant que vous avez une relation 'users' dans le modèle Planning

        return redirect()->route('plannings.inscrits', $planning->id)->with('success', 'Adhérent ajouté avec succès.');
    }

    public function destroyInscription($planningId, $userId)
    {
        $planning = Planning::findOrFail($planningId);
        $planning->users()->detach($userId);

        return redirect()->back()->with('success', 'Adhérent retiré avec succès.');
    }
}


