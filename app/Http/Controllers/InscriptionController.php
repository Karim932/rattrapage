<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscription;
use App\Models\Planning;
use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class InscriptionController extends Controller
{

    public function service() {
        
        $services = Service::all();
        return view('page_navbar/services', compact('services'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'planning_id' => 'required|exists:plannings,id',
            'user_id' => 'required|exists:users,id', 
        ]);

        $planning = Planning::where('id', $request->planning_id)->first();
        $serviceId = $planning->service_id;
        $planningDate = $planning->date;
        $planningStartTime = $planning->start_time;
        $planningEndTime = $planning->end_time;

        $existingServiceInscription = Inscription::where('user_id', $request->user_id)
            ->whereHas('planning', function($query) use ($serviceId) {
                $query->where('service_id', $serviceId);
            })->exists();

        if ($existingServiceInscription) {
            return redirect()->back()->with('error', 'Vous êtes déjà inscrit à un événement de ce service.');
        }

        $timeOverlap = Inscription::where('user_id', $request->user_id)
            ->whereHas('planning', function($query) use ($planningDate, $planningStartTime, $planningEndTime) {
                $query->where('date', $planningDate)
                    ->where(function($q) use ($planningStartTime, $planningEndTime) {
                        $q->whereBetween('start_time', [$planningStartTime, $planningEndTime])
                            ->orWhereBetween('end_time', [$planningStartTime, $planningEndTime])
                            ->orWhere(function($q) use ($planningStartTime, $planningEndTime) {
                                $q->where('start_time', '<', $planningStartTime)
                                ->where('end_time', '>', $planningEndTime);
                            });
                    });
            })->exists();


        if ($timeOverlap) {
            return redirect()->back()->with('error', 'L\'inscription chevauche un autre événement planifié pour la même date et heure.');
        }

        $inscription = new Inscription([
            'planning_id' => $request->planning_id,
            'user_id' => $request->user_id,
        ]);

        $inscription->save();

        return redirect()->back()->with('success', 'Vous êtes inscrit au créneau.');
    }

    public function historique()
    {
        if (Auth::check()) {
            $user = User::with(['plannings', 'annonces']) 
                    ->find(Auth::id());

            $plannings = $user->plannings()->orderBy('date', 'desc')->get();
            $annonces = $user->annonces()->orderBy('created_at', 'desc')->get(); 

            return view('page_navbar.adherent.historique', compact('plannings', 'annonces'));
        } else {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }
    }


    public function cancel($id)
    {
        $user = User::find(Auth::id());
        $planning = Planning::find($id);

        if (!$planning) {
            return redirect()->back()->with('error', 'L\'événement n\'existe pas.');
        }

        $now = \Carbon\Carbon::now();

        $startTime = $planning->start_time;
        $date = \Carbon\Carbon::parse($planning->date)->format('Y-m-d');
        
        try {
        
            if (strlen($startTime) === 5) { 
                $eventTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $startTime);
            } elseif (strlen($startTime) === 8) { 
                $eventTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $startTime);
            } else {
                throw new \Exception("Format d'heure inattendu: $startTime");
            }
    
            $hoursDifference = $now->diffInHours($eventTime, false); 
        
            if ($hoursDifference < 48) {
                return redirect()->back()->with('error', 'Vous ne pouvez pas annuler votre participation à l\'événement moins de 48 heures avant son début.');
            }
        
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors du calcul du temps pour l\'événement.');
        }

        $user->plannings()->detach($id);

        return redirect()->back()->with('success', 'Vous avez annulé votre participation à l\'événement.');
    }



}
