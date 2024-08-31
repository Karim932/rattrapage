<?php

namespace App\Http\Controllers\Admin;

use App\Models\Annonce;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Service;


class AnnonceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $annonces = Annonce::with(['user', 'service'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.annonces.index', compact('annonces'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $services = Service::all();
        return view('admin.annonces.create', compact('users', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id', 
            'service_id' => 'nullable|exists:services,id',
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
            'image' => 'nullable|image|max:2048', //|mimes:jpeg,png,jpg,gif
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/annonces');
            $validatedData['image'] = Storage::url($imagePath);
        }

        Annonce::create($validatedData);
        return redirect()->route('annonces.index')->with('success', 'Annonce créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Annonce $annonce)
    {
        return view('admin.annonces.show', compact('annonce'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Annonce $annonce)
    {
        $services = Service::all();
        return view('admin.annonces.edit', compact('services', 'annonce'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Annonce $annonce)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id', 
            'service_id' => 'nullable|exists:services,id',
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

        if ($request->hasFile('image')) {
            if ($annonce->image) {
                Storage::delete(Str::replaceFirst('storage', 'public', $annonce->image));
            }
            $imagePath = $request->file('image')->store('public/annonces');
            $validatedData['image'] = Storage::url($imagePath);
        }

        $annonce->update($validatedData);
        return redirect()->route('annonces.index')->with('success', 'Annonce mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Annonce $annonce)
    {
        if ($annonce->image) {
            Storage::delete(Str::replaceFirst('storage', 'public', $annonce->image));
        }
        $annonce->delete();
        return redirect()->route('annonces.index')->with('success', 'Annonce supprimée avec succès.');
    }
}
