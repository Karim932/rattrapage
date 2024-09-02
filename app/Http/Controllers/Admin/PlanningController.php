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
        $serviceId = $request->input('service_id');

        $query = Planning::with('service');

        if (!empty($city)) {
            $query->where('city', $city);
        }

        if (!empty($serviceId)) {
            $query->where('service_id', $serviceId);         
        }

        $plannings = $query->orderBy('service_id')
                   ->orderBy('city')
                   ->orderBy('date')
                   ->get();

        $cities = Planning::select('city')->distinct()->orderBy('city')->get();

        $services = Service::orderBy('name')->get();

        return view('admin.services.plannings.index', compact('plannings', 'cities', 'services', 'city', 'serviceId'));
    }



    public function create()
    {
        $services = Service::all();

        return view('admin.services.plannings.create', compact('services'));
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'service_id' => 'required|exists:services,id', 
            'date' => 'required|date|after_or_equal:now',  
            'start_time' => 'required|date_format:H:i',  
            'end_time' => 'required|date_format:H:i|after:start_time',  
            'city' => 'nullable|string',
            'address' => 'nullable|string',
            'max_inscrit' => 'nullable|integer',
            'benevole_id' => 'required|in:none,auto',
            
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


        $planning = new Planning([
            'service_id' => $validatedData['service_id'],
            'date' => $validatedData['date'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
            'city' => $validatedData['city'],
            'address' => $validatedData['address'],
            'max_inscrit' => $validatedData['max_inscrit'],
            'benevole_id' => null, 
            
        ]);

        if ($planning) {
            if ($validatedData['benevole_id'] === 'auto') {
                $benevole = $this->assignAutoBenevole($planning);
                
                if ($benevole) {
                    $planning->benevole_id = $benevole->id;
                    $planning->save();
                } else {
                    return redirect()->back()->withErrors(['error' => 'Aucun bénévole disponible pour ce créneau horaire.'])->withInput();
                }
            }            
            

            return redirect()->route('plannings.index')->with('success', 'Nouvel événement ajouté avec succès.');
        }

        return redirect()->back()->with('error', 'Non c\'est une erreur.');
    }

    protected function assignAutoBenevole(Planning $planning)
    {
        $timeSlots = [
            'matin' => ['start' => '07:00', 'end' => '11:00'],
            'midi' => ['start' => '11:00', 'end' => '14:00'],
            'soir' => ['start' => '17:00', 'end' => '21:00']
        ];

        $planningStartTime = Carbon::parse($planning->start_time);
        $planningEndTime = Carbon::parse($planning->end_time);

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
        $dayOfWeekFr = $daysOfWeekMapping[$dayOfWeek];  


        $benevoles = AdhesionBenevole::whereNotNull('availability')
            ->where('id_service', $planning->service_id)
            ->get()
            ->filter(function ($benevole) use ($planning, $planningStartTime, $planningEndTime, $timeSlots, $dayOfWeekFr) {
                if (is_string($benevole->availability)) {
                    $availability = json_decode($benevole->availability, true);
                } elseif (is_array($benevole->availability)) {
                    $availability = $benevole->availability;
                } else {
                    return false; 
                }

                if (!isset($availability[$dayOfWeekFr])) {
                    return false;
                }

                foreach ($availability[$dayOfWeekFr] as $slot => $isAvailable) {
                    if (($isAvailable === "1" || $isAvailable === true) && isset($timeSlots[$slot])) {
                        $slotStart = Carbon::createFromTimeString($timeSlots[$slot]['start']);
                        $slotEnd = Carbon::createFromTimeString($timeSlots[$slot]['end']);

                        if ($planningStartTime->lessThanOrEqualTo($slotEnd) && $planningEndTime->greaterThanOrEqualTo($slotStart)) {
                            $conflictingPlanning = Planning::where('benevole_id', $benevole->id)
                            ->where('date', $planning->date)
                            ->where(function ($query) use ($planningStartTime, $planningEndTime) {
                                $query->where(function ($subQuery) use ($planningStartTime, $planningEndTime) {
                                    $subQuery->orWhereBetween('start_time', [$planningStartTime, $planningEndTime])
                                        ->orWhereBetween('end_time', [$planningStartTime, $planningEndTime]);
                                })
                                ->orWhere(function ($subQuery) use ($planningStartTime, $planningEndTime) {
                                    $subQuery->orWhere('start_time', '<=', $planningStartTime)
                                        ->orWhere('end_time', '>=', $planningEndTime);
                                })
                                ->orWhere(function ($subQuery) use ($planningStartTime, $planningEndTime) {
                                    $subQuery->orWhere('start_time', $planningStartTime)
                                        ->orWhere('end_time', $planningEndTime);
                                });
                            })
                            ->exists();


                            // dd($conflictingPlanning, $planning->date);

                            if (!$conflictingPlanning) {
                                Log::info("Bénévole assigné: " . $benevole->user->firstname . $benevole->user->lastname);
                                return true;
                            } else {
                                Log::info("Conflit détecté pour le bénévole: " . $benevole->user->firstname . $benevole->user->lastname);
                            }
                        }
                    }
                }

                return false; 
            });

        return $benevoles->first();
    }

    public function getEvents(Request $request)
    {
        $city = $request->input('city');
        $service = $request->input('service_id');

        $planningsQuery = Planning::with('service');

        if ($city) {
            $planningsQuery->where('city', $city);
        }

        if ($service) {
            $planningsQuery->where('service_id', $service);
        }

        $plannings = $planningsQuery->orderBy('city')
                                    ->orderBy('service_id')
                                    ->orderBy('date')
                                    ->get();

        $events = $plannings->map(function ($planning) {
            return [
                'title' => $planning->service->name . ' - ' . ($planning->city ?? 'Ville non spécifiée'),  
                'start' => Carbon::parse($planning->date)->format('Y-m-d') . 'T' . substr($planning->start_time, 0, 5),  
                'end' => Carbon::parse($planning->date)->format('Y-m-d') . 'T' . substr($planning->end_time, 0, 5), 
                'url' => route('plannings.show', $planning->id),
            ];
        });

        return response()->json($events);
    }


    public function edit($id)
    {
        $planning = Planning::findOrFail($id);

        $services = Service::all();

        return view('admin.services.plannings.edit', compact('planning', 'services'));
    }


    public function update(Request $request, $id)
    {
        $planning = Planning::findOrFail($id);
        $planning->update($request->all());
        return redirect()->route('plannings.index')->with('success', 'Le planning a bien été modifié.');
    }

    public function show($id)
    {
        $planning = Planning::with('service')->findOrFail($id);

        if (!$planning) {
            return redirect()->route('plannings.index')->with('error', 'planning non trouvée.');
        }

        return view('admin.services.plannings.show', compact('planning'));
    }


    public function destroy($id)
    {
        try {
            $planning = Planning::findOrFail($id);
            $planning->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Le planning a bien été supprimé.',
                'redirectUrl' => route('plannings.index') 
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Une erreur s\'est produite lors de la suppression : ' . $e->getMessage(),
                'redirectUrl' => route('plannings.index') 
            ]);
        }
    }


    public function showBenevole($id)
    {
        $planning = Planning::with('benevoles.user')->findOrFail($id);

        $assignedBenevoles = $planning->benevoles;

        $benevoles = AdhesionBenevole::with('user')
            ->where('id_service', $planning->service_id)
            ->get();

        return view('admin.services.plannings.assign-benevole', compact('planning', 'assignedBenevoles', 'benevoles'));
    }

    public function addBenevole(Request $request, $id)
    {
        $planning = Planning::findOrFail($id);
        $benevole = AdhesionBenevole::findOrFail($request->benevole_id);

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
        $inscrits = $planning->users; 

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

        $planning->users()->attach($request->user_id); 

        return redirect()->route('plannings.inscrits', $planning->id)->with('success', 'Adhérent ajouté avec succès.');
    }

    public function destroyInscription($planningId, $userId)
    {
        $planning = Planning::findOrFail($planningId);
        $planning->users()->detach($userId);

        return redirect()->back()->with('success', 'Adhérent retiré avec succès.');
    }
}


