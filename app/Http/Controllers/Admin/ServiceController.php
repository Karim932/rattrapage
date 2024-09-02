<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adhesion;
use App\Models\AdhesionBenevole;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\Skill;
use App\Models\User;

class ServiceController extends Controller
{
    /**
     * Display a listing of the services.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::paginate(10);
        // dd($services);

        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $skills = Skill::all();
        return view('admin.services.create', compact('skills'));
    }

    /**
     * Store a newly created service in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'Le nom du service est requis.',
            'name.max' => 'Le nom du service ne peut excéder 255 caractères.',
            'name.regex' => 'Le nom du service peut uniquement contenir des lettres et des espaces.',
            'description.required' => 'La description du service est requise.',
            'description.min' => 'La description doit comporter au moins 10 caractères.',
            'type.required' => 'Le type du service est requis.',
            'type.in' => 'Le type du service fourni n\'est pas valide.',
            'condition.required' => 'La condition du service est requise.',
            'category.required' => 'La catégorie du service est requise.',
            'duration.integer' => 'La durée doit être un nombre entier.',
            'duration.between' => 'La durée doit être comprise entre 1 et 365 jours.',
            'skills.*.exists' => 'La compétence sélectionnée n\'est pas valide.',
        ];

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ\s\'\-\.\(\)\,\éÉèÈêÊëËîÎïÏôÔöÖûÛüÜçÇ]+$/',
            'description' => 'required|string|min:10',
            'status' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s]+$/',
            'category' => 'required|string',
            'condition' => 'required|string',
            'type' => 'required|string|in:reservations,postes',
            'duration' => 'nullable|integer|between:1,365',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
        ], $messages);


        $service = new Service($validatedData);
        $serviceSaveSuccess = $service->save();

        if (!$serviceSaveSuccess || is_null($service->id)) {
            return back()->withErrors('Failed to save the service. Please check your data and try again.');
        }

        if (!empty($request->skills)) {
            $service->skills()->sync($request->skills);
        }

        if (!empty($request->new_skills)) {
            foreach ($request->new_skills as $skillName) {
                if (!empty($skillName)) {
                    $skill = Skill::firstOrCreate(['name' => $skillName]);
                    $service->skills()->attach($skill->id);
                }
            }
        }

        return redirect()->route('services.index')->with('success', 'Service créé avec succès.');
    }

    /**
     * Display the specified service.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        $service = Service::with(['adhesionBenevole.user'])->findOrFail($service->id);

        // dd($service, $service->id, $service->adhesionBenevole);

        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified service.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        $skills = Skill::all();

        $currentCategory = $service->category;
        return view('admin.services.edit', compact('skills', 'service', 'currentCategory'));
    }

    /**
     * Update the specified service in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Personnalisation des messages d'erreur
        $messages = [
            'name.required' => 'Le nom du service est requis.',
            'name.max' => 'Le nom du service ne peut excéder 255 caractères.',
            'name.regex' => 'Le nom du service peut uniquement contenir des lettres et des espaces.',
            'description.required' => 'La description du service est requise.',
            'description.min' => 'La description doit comporter au moins 10 caractères.',
            'type.required' => 'Le type du service est requis.',
            'type.in' => 'Le type du service fourni n\'est pas valide.',
            'condition.required' => 'La condition du service est requise.',
            'category.required' => 'La catégorie du service est requise.',
            'duration.integer' => 'La durée doit être un nombre entier.',
            'duration.between' => 'La durée doit être comprise entre 1 et 365 jours.',
            'skills.*.exists' => 'La compétence sélectionnée n\'est pas valide.',
        ];

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ\s\'\-\.\(\)\,\éÉèÈêÊëËîÎïÏôÔöÖûÛüÜçÇ]+$/',
            'description' => 'required|string|min:10',
            'status' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s]+$/',
            'category' => 'required|string',
            'condition' => 'required|string',
            'type' => 'required|string|in:reservations,postes',
            'duration' => 'nullable|integer|between:1,365',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
        ], $messages);

        $service = Service::findOrFail($id);
        $service->update($validatedData);

        if (!empty($request->skills)) {
            $service->skills()->sync($request->skills);
        }

        if (!empty($request->new_skills)) {
            foreach ($request->new_skills as $skillName) {
                if (!empty($skillName)) {
                    $skill = Skill::firstOrCreate(['name' => $skillName]);
                    $service->skills()->attach($skill->id);
                }
            }
        }

        return redirect()->route('services.index')->with('success', 'Service mis à jour avec succès.');
    }


    /**
     * Remove the specified service from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $service = Service::find($service->id);

        if (!$service) {
            return redirect()->back()->with('error', 'Service non trouvé.');
        }
    
        $service->skills()->detach();

        $adhesions = AdhesionBenevole::where('id_service', $service->id)->get();

        // dd($adhesions);
        foreach ($adhesions as $adhesion) {
            $adhesion->id_service = null;
            $adhesion->save();
        }

        // if (!$adhesion) {
        //     return redirect()->back()->with('error', 'Adhesion non trouvée.');
        // }

        // $adhesion->update(['service_id' => null]);

        $service->delete();

        return redirect()->route('services.index')->with('success', 'Service supprimé avec succès.');
    }

    public function getSkills($serviceId)
    {
        $service = Service::with('skills')->find($serviceId);
        if (!$service) {
            return response()->json(['error' => 'Service non trouvé'], 404);
        }

        return response()->json(['skills' => $service->skills]);
    }

}

