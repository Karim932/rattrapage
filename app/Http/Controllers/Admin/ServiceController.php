<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

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
        return view('admin.services.create');
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

        ], $messages);

        // Créer un nouveau service en utilisant la méthode d'instanciation avec un tableau associatif
        $service = new Service($validatedData);
        $service->save();


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
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified service in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        // Valider les données envoyées via le formulaire
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'capacity' => 'required|integer',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Mettre à jour le service existant
        $service->name = $validatedData['name'];
        $service->description = $validatedData['description'];
        $service->capacity = $validatedData['capacity'];
        $service->location = $validatedData['location'];
        $service->start_date = $validatedData['start_date'];
        $service->end_date = $validatedData['end_date'];
        $service->save();

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

