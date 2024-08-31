<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AnnonceAdherentController extends Controller
{
    public function index(Request $request)
    {
        $annonces = Annonce::query();

        if ($request->filled('city')) {
            $annonces->where('location', 'like', '%' . $request->city . '%');
        }

        $annonces = $annonces->paginate(10);

        return view('page_navbar.adherent.annonce', compact('annonces'));
    }

    public function create()
    {
        return view('page_navbar.adherent.create-annonce');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'location' => 'required',
            'price' => 'required|numeric',
            'status' => 'nullable|in:draft,published',
            'category' => 'required|string',
            'skills_required' => 'nullable|string',
            'exchange_type' => 'nullable|in:service_for_service,service_for_credits',
            'estimated_duration' => 'nullable|string',
            'availability' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $annonce = new Annonce($validatedData);
        $annonce->user_id = Auth::user()->id;

        $annonce->save();

        return redirect()->route('annonces.adherent.index')->with('success', 'Annonce créée avec succès.');
    }

    public function show($id)
    {
        $annonce = Annonce::findOrFail($id);
        return view('page_navbar.adherent.show', compact('annonce'));
    }


    public function edit($id)
    {
        $annonce = Annonce::findOrFail($id);
        return view('page_navbar.adherent.edit', compact('annonce'));
    }

    public function update(Request $request, Annonce $annonce)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'skills_required' => 'nullable|string',
            'exchange_type' => 'required|string',
            'estimated_duration' => 'nullable|string', 
            'availability' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        if ($request->hasFile('image')) {
            if ($annonce->image) {
                Storage::delete($annonce->image);
            }

            $imagePath = $request->file('image')->store('public/annonces');
            $validated['image'] = Storage::url($imagePath);
        }

        $annonce->update($validated);

        return redirect()->route('adherent.historique')->with('success', 'Votre annonce a bien été modifié');
    }

    public function destroy(Annonce $annonce)
    {
        if ($annonce->image) {
            Storage::delete($annonce->image);
        }

        $annonce->delete();

        return redirect()->route('adherent.historique')->with('success', 'Annonce a bien été supprimé.');
    }

}
