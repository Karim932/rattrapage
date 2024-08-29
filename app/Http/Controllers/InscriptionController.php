<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscription;
use App\Models\Planning;
use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

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
            'user_id' => 'required|exists:users,id', // Vérifiez que l'utilisateur est bien authentifié
        ]);

        // Récupérer les informations du planning souhaité
        $planning = Planning::where('id', $request->planning_id)->first();
        $serviceId = $planning->service_id;
        $planningDate = $planning->date;
        $planningStartTime = $planning->start_time;
        $planningEndTime = $planning->end_time;

        // Vérifiez si l'utilisateur est déjà inscrit à un événement du même service
        $existingServiceInscription = Inscription::where('user_id', $request->user_id)
            ->whereHas('planning', function($query) use ($serviceId) {
                $query->where('service_id', $serviceId);
            })->exists();

        if ($existingServiceInscription) {
            return redirect()->back()->with('error', 'Vous êtes déjà inscrit à un événement de ce service.');
        }

        // Vérifiez les chevauchements de date et d'heure avec d'autres inscriptions
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

        // Créer une nouvelle inscription
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
            $user = User::find(Auth::id()); // Récupère l'utilisateur par son ID

            // Utilisation correcte de la relation avec ->plannings()
            $plannings = $user->plannings()->orderBy('date', 'desc')->get();
            
            return view('page_navbar.adherent.historique', compact('plannings'));
        } else {
            // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
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

        // Supprimer l'inscription
        $user->plannings()->detach($id);

        return redirect()->back()->with('success', 'Vous avez annulé votre participation à l\'événement.');
    }

}
