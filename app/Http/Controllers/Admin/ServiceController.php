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
        // Récupérer tous les services et les passer à la vue
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
        // Personnalisation des messages d'erreur
        $messages = [
            'name.required' => 'Le nom du service est requis.',
            'name.max' => 'Le nom du service ne peut excéder 255 caractères.',
            'description.required' => 'La description du service est requise.',
            'condition.required' => 'La condition du service est requise.',
            'category.required' => 'La catégorie du service est requise.',
            'duration.integer' => 'La durée doit être un nombre entier.',
            'duration.nullable' => 'La durée du service est facultative.',
        ];

        // Validation des données envoyées via le formulaire
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'nullable|string',
            'category' => 'required|string',
            'condition' => 'required|string',
            'duration' => 'nullable|integer',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',

        ], $messages);

        // Créer un nouveau service en utilisant la méthode d'instanciation avec un tableau associatif
        $service = new Service($validatedData);
        $serviceSaveSuccess = $service->save();

        // Vérification que l'ID du service est bien défini après la sauvegarde
        if (!$serviceSaveSuccess || is_null($service->id)) {
            return back()->withErrors('Failed to save the service. Please check your data and try again.');
        }

        // Traitement des compétences existantes
        if (!empty($request->skills)) {
            $service->skills()->sync($request->skills);
        }

        // Ajout de nouvelles compétences si spécifié
        if (!empty($request->new_skills)) {
            foreach ($request->new_skills as $skillName) {
                if (!empty($skillName)) {
                    $skill = Skill::firstOrCreate(['name' => $skillName]);
                    $service->skills()->attach($skill->id);
                }
            }
        }

        // Rediriger vers la liste des services avec un message de succès
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
        // Utiliser la méthode findOrFail pour garantir que le service existe ou retourner une erreur 404
        $service = Service::with(['adhesionBenevole.user'])->findOrFail($service->id);

        // Debug pour voir la structure de données récupérée
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
        return view('admin.services.edit', compact('skills', 'service'));
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
            'name.max' => 'Le nom du service ne peut excéder 500 caractères.',
            'description.required' => 'La description du service est requise.',
            'condition.required' => 'La condition du service est requise.',
            'category.required' => 'La catégorie du service est requise.',
            'duration.integer' => 'La durée doit être un nombre entier.',
            'duration.nullable' => 'La durée du service est facultative.',
            'skills.*.exists' => 'La compétence sélectionnée doit exister dans la base de données.',
        ];

        // Validation des données envoyées via le formulaire
        $validatedData = $request->validate([
            'name' => 'required|string|max:500',
            'description' => 'required|string',
            'status' => 'nullable|string',
            'category' => 'required|string',
            'condition' => 'required|string',
            'duration' => 'nullable|integer',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
        ], $messages);

        $service = Service::findOrFail($id);
        $service->update($validatedData);

        // Traitement des compétences existantes
        if (!empty($request->skills)) {
            $service->skills()->sync($request->skills);
        }

        // Ajout de nouvelles compétences si spécifié
        if (!empty($request->new_skills)) {
            foreach ($request->new_skills as $skillName) {
                if (!empty($skillName)) {
                    $skill = Skill::firstOrCreate(['name' => $skillName]);
                    $service->skills()->attach($skill->id);
                }
            }
        }

        // Rediriger vers la liste des services avec un message de succès
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
    
        // Détacher les compétences liées (pour une relation many-to-many)
        $service->skills()->detach();

        // Récupérer toutes les adhésions associées à ce service
        $adhesions = AdhesionBenevole::where('id_service', $service->id)->get();

        // dd($adhesions);
        // Parcourir chaque adhésion et mettre à jour le service_id à null
        foreach ($adhesions as $adhesion) {
            $adhesion->id_service = null;
            $adhesion->save();
        }

        // if (!$adhesion) {
        //     return redirect()->back()->with('error', 'Adhesion non trouvée.');
        // }

        // // Réinitialiser le champ service_id
        // $adhesion->update(['service_id' => null]);

        // Supprimer le service
        $service->delete();

        // Rediriger vers la liste des services avec un message de succès
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

