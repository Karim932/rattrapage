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
            'name' => 'required|string|max:25',
            'description' => 'required|string',
            'status' => 'nullable|string',
            'category' => 'required|string',
            'condition' => 'required|string',
            'duration' => 'nullable|integer',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
            'new_skills' => 'nullable|array',
            'new_skills.*' => 'string|max:255|unique:skills,name',

        ], $messages);

        // Créer un nouveau service en utilisant la méthode d'instanciation avec un tableau associatif
        $service = new Service($validatedData);
        $service->save();
        if (is_null($service->id)) {
            // Gérer l'erreur ici, par exemple, renvoyer une réponse ou lever une exception
            return back()->withErrors('Failed to save the service.');
        }

        // Traitement des compétences existantes
        $service->skills()->sync($request->input('skills', []));

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
        $benevole = AdhesionBenevole::all();
        // $benevoleDispos = User::where('role', 'benevole')
        // ->whereNotIn('id', AdhesionBenevole::select('user_id')->distinct())
        // ->get();

        // $service = Service::with('adhesionBenevole.user')->findOrFail($service->id);

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
            'name.max' => 'Le nom du service ne peut excéder 255 caractères.',
            'description.required' => 'La description du service est requise.',
            'condition.required' => 'La condition du service est requise.',
            'category.required' => 'La catégorie du service est requise.',
            'duration.integer' => 'La durée doit être un nombre entier.',
            'duration.nullable' => 'La durée du service est facultative.',
            'skills.*.exists' => 'La compétence sélectionnée doit exister dans la base de données.',
            'new_skills.*.unique' => 'La nouvelle compétence doit être unique.'
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
            'new_skills' => 'nullable|array',
            'new_skills.*' => 'string|max:255|unique:skills,name',
        ], $messages);

        $service = Service::findOrFail($id);
        $service->update($validatedData);

        // Gérer les compétences existantes
        if (isset($validatedData['skills'])) {
            $service->skills()->sync($validatedData['skills']);
        } else {
            $service->skills()->detach();
        }

        // Ajouter de nouvelles compétences si spécifié
        if (isset($validatedData['new_skills'])) {
            foreach ($validatedData['new_skills'] as $skillName) {
                if ($skillName) {
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
        // Supprimer le service
        $service->delete();

        // Rediriger vers la liste des services avec un message de succès
        return redirect()->route('services.index')->with('success', 'Service supprimé avec succès.');
    }

}

