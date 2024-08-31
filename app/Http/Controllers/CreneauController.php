<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Planning;
use App\Models\Inscription; 
use Illuminate\Support\Facades\Auth;

class CreneauController extends Controller
{

    public function index(Request $request)
    {

        if (Auth::user()->cotisation === false) {
            return redirect()->route('stripe.checkout'); // Assurez-vous que cette vue existe
        }

        $plannings = Planning::with(['service', 'inscriptions'])
                            ->where('service_id', $request->service_id)
                            ->get();

        foreach ($plannings as $planning) {
            $planning->inscriptions_count = $planning->inscriptions->count();
            $planning->remaining_spots = $planning->max_inscrit - $planning->inscriptions_count;
        }

        return view('page_navbar.adherent.index', compact('plannings'));
    }


    public function filter(Request $request)
    {

        if (!$request->filled('service_id')) {
            return redirect()->back()->with('error', 'Service ID est requis.');
        }

        $query = Planning::with(['service', 'inscriptions'])
                            ->where('service_id', $request->service_id);

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        if ($request->filled('start_time')) {
            $query->where('start_time', '>=', $request->start_time);
        }
        if ($request->filled('end_time')) {
            $query->where('end_time', '<=', $request->end_time);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $plannings = $query->get();

        foreach ($plannings as $planning) {
            $planning->inscriptions_count = $planning->inscriptions->count();
            $planning->remaining_spots = $planning->max_inscrit - $planning->inscriptions_count;
        }

        return view('page_navbar.adherent.index', compact('plannings'));
    }





    // public function inscrire(Request $request)
    // {
    //     $request->validate([
    //         'planning_id' => 'required|exists:plannings,id',
    //         'user_id' => 'required|exists:users,id', // Vérifiez que l'utilisateur est bien authentifié
    //     ]);

    //     // // Créer une nouvelle inscription
    //     // $inscription = new Inscription([
    //     //     'planning_id' => $request->planning_id,
    //     //     'user_id' => $request->user_id,
    //     // ]);

    //     // $inscription->save();

    //     return redirect()->back()->with('success', 'Vous êtes inscrit au créneau.');
    // }
}

