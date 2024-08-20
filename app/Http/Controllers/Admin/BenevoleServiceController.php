<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use App\Models\AdhesionBenevole;


class BenevoleServiceController extends Controller
{
    public function create()
    {
        $services = Service::all();
        // Récupération des bénévoles qui n'ont pas d'adhésion active avec un service assigné
        $benevoles = User::where('role', 'benevole')
        ->whereHas('adhesionsBenevoles', function ($query) {
            $query->whereNull('id_service');
        })
        ->get();

        return view('admin.services.add-benevole', compact('services', 'benevoles'));
    }

    public function store(Request $request)
    {
        // Validation des données reçues
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'user_id' => 'required|exists:users,id'
        ]);

        // Trouver l'adhésion existante du bénévole
        $adhesion = AdhesionBenevole::where('user_id', $request->user_id)->first();

        // dd($adhesion->id_service, $adhesion->is_active, $request->service_id);

        // Si l'adhésion existe, mettre à jour l'id_service
        if ($adhesion) {
            $adhesion->id_service = $request->service_id;
            $adhesion->is_active = true; // Mettre à jour is_active si nécessaire
            $adhesion->save();

            // Rediriger avec un message de succès
            return redirect()->route('services.index')->with('success', 'Bénévole assigné avec succès au service.');
        } else {
            // Si aucune adhésion n'est trouvée, renvoyer une erreur ou un autre comportement
            return redirect()->route('services.index')->with('error', 'Aucune adhésion existante trouvée pour cet utilisateur.');
        }
    }

    public function skillShow(Request $request, $serviceId)
    {
        $service = Service::with('skills')->find($serviceId);
        dd($service->skills);
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }
        return response()->json(['skills' => $service->skills]);
    }

}
