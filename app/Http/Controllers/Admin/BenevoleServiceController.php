<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use App\Models\AdhesionBenevole;
use App\Models\Skill;


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

        
        foreach ($benevoles as $benevole) {
            $adhesion = AdhesionBenevole::where('user_id', $benevole->id)->first();
            
            if ($adhesion) {
                // Vérifier si le contenu de skill_id est déjà un tableau ou un JSON
                if (is_string($adhesion->skill_id)) {
                    // Tenter de décoder le JSON
                    $skillIds = json_decode($adhesion->skill_id, true);
        
                    // Vérifier si le décodage a réussi et que le résultat est un tableau
                    if (json_last_error() === JSON_ERROR_NONE && is_array($skillIds)) {
                        $skills = Skill::whereIn('id', $skillIds)->pluck('name')->toArray();
                        $benevole->skills = $skills;
                    } else {
                        // Si ce n'est pas un JSON valide, supposer qu'il peut être autre chose ou une erreur
                        $benevole->skills = [];
                    }
                } elseif (is_array($adhesion->skill_id)) {
                    // Si c'est déjà un tableau, l'utiliser directement
                    $skills = Skill::whereIn('id', $adhesion->skill_id)->pluck('name')->toArray();
                    $benevole->skills = $skills;
                } else {
                    // Si ce n'est ni un tableau ni une chaîne JSON, réinitialiser à un tableau vide
                    $benevole->skills = [];
                }
            } else {
                $benevole->skills = [];
            }
        }
        
        
        return view('admin.services.add-benevole', compact('services', 'benevoles', 'adhesion'));
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

    public function skillShow($serviceId)
    {
        $service = Service::with('skills')->findOrFail($serviceId);
        $skills = $service->skills->pluck('name', 'id');
        
        return response()->json($skills);
    }


    public function removeBenevole($service_id)
    {
        // dd($service_id);
        $benevoleService = AdhesionBenevole::findOrFail($service_id);
        $benevoleService->update(['id_service' => null]);
        return back()->with('success', 'Bénévole retiré avec succès.');
    }


}
