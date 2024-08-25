<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Planning;
use App\Models\Service;
use App\Http\Controllers\Controller;

class PlanningController extends Controller
{
    public function index()
    {
        $plannings = Planning::with('service')->get();
        return view('admin.services.plannings.index', compact('plannings'));
    }

    public function create()
    {
        $services = Service::all();
        return view('admin.services.plannings.create', compact('services'));
        
    }

    /**
     * Stocke un nouvel événement de planification dans la base de données.
     */
    public function store(Request $request)
    {
        // Validation des données du formulaire
        $validatedData = $request->validate([
            'service_id' => 'required|exists:services,id',  // Assurez-vous que le service_id existe dans la table des services
            'date' => 'required|date|after_or_equal:now',  // Assurez-vous que la date est bien une date
            'start_time' => 'required|date_format:H:i',  // Heure de début doit être une heure valide
            'end_time' => 'required|date_format:H:i|after:start_time',  // Heure de fin doit être après l'heure de début
            'city' => 'nullable|string',
            'address' => 'nullable|string',
            // 'status' => 'required|in:planned,ongoing,completed,cancelled'  // Les statuts valides
        ]);

        // Création et sauvegarde du nouveau planning
        $planning = new Planning([
            'service_id' => $validatedData['service_id'],
            'date' => $validatedData['date'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
            'city' => $validatedData['city'],
            'address' => $validatedData['address'],
            // 'status' => $validatedData['status'],
        ]);

        if ($planning->save()) {
            return redirect()->route('plannings.index')->with('success', 'Nouvel événement ajouté avec succès.');
        }

        return redirect()->back()->with('error', 'Non c\'est une erreur.');
    }

    public function getEvents()
    {
        $plannings = Planning::with('service')->get();  // Pré-chargement des services liés

        $events = $plannings->map(function ($planning) {
            // dd($planning->date);
            return [
                'title' => $planning->service->name ?? 'Service non spécifié',
                'start' => $planning->date->format('Y-m-d') . ' ' . $planning->start_time,
                'end' => $planning->date->format('Y-m-d') . ' ' . $planning->end_time,
                'url' => route('plannings.show', $planning->id)  // Lien pour éditer l'événement
            ];
        });

        return response()->json($events);
    }


    public function edit($id)
    {
        // Récupération du planning spécifique par son ID avec gestion d'erreur si non trouvé
        $planning = Planning::findOrFail($id);

        // Récupération de tous les services pour les lister dans le formulaire de sélection
        $services = Service::all();

        // Passage du planning et des services à la vue
        return view('admin.services.plannings.edit', compact('planning', 'services'));
    }


    public function update(Request $request, $id)
    {
        // Validation and logic to update an event
        $planning = Planning::findOrFail($id);
        $planning->update($request->all());
        return redirect()->route('plannings.index')->with('success', 'Event updated successfully.');
    }

    public function show($id)
    {
        // Retrieve the specific planning by its ID
        $planning = Planning::with('service')->findOrFail($id);

        // Pass the planning data to the view
        return view('admin.services.plannings.show', compact('planning'));
    }


    public function destroy($id)
    {
        // Logic to delete an event
        $planning = Planning::findOrFail($id);
        $planning->delete();
        return redirect()->route('plannings.index')->with('success', 'Event deleted successfully.');
    }



}


