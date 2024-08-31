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
        $benevoles = User::where('role', 'benevole')
        ->whereHas('adhesionsBenevoles', function ($query) {
            $query->whereNull('id_service');
        })
        ->get();

        
        foreach ($benevoles as $benevole) {
            $adhesion = AdhesionBenevole::where('user_id', $benevole->id)->first();
            
            if ($adhesion) {
                if (is_string($adhesion->skill_id)) {
                    $skillIds = json_decode($adhesion->skill_id, true);
        
                    if (json_last_error() === JSON_ERROR_NONE && is_array($skillIds)) {
                        $skills = Skill::whereIn('id', $skillIds)->pluck('name')->toArray();
                        $benevole->skills = $skills;
                    } else {
                        $benevole->skills = [];
                    }
                } elseif (is_array($adhesion->skill_id)) {
                    $skills = Skill::whereIn('id', $adhesion->skill_id)->pluck('name')->toArray();
                    $benevole->skills = $skills;
                } else {
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
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'user_id' => 'required|exists:users,id'
        ]);

        $adhesion = AdhesionBenevole::where('user_id', $request->user_id)->first();

        if ($adhesion) {
            $adhesion->id_service = $request->service_id;
            $adhesion->is_active = true; 
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
