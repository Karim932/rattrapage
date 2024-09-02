<?php

namespace App\Http\Controllers\Admin;

use App\Models\ServiceBenevolePropose;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdhesionBenevole;
use Illuminate\Support\Facades\Auth;


class ServiceBenevoleProposeController extends Controller
{
    public function index()
    {
        $proposals = ServiceBenevolePropose::with('user')->paginate(10);
        return view('admin.services.propose.index', compact('proposals'));
    }

    public function show($id)
    {
        $proposal = ServiceBenevolePropose::findOrFail($id);
        return view('admin.services.propose.show', compact('proposal'));
    }

    public function update(Request $request, $id)
    {
        $proposal = ServiceBenevolePropose::findOrFail($id);
        $proposal->update(['status' => $request->input('status')]);

        return redirect()->route('propose.index')->with('success', 'Proposition mise à jour avec succès.');
    }

    public function updatePropose(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:en_attente,accepté,refusé',
        ]);

        $proposal = ServiceBenevolePropose::findOrFail($id);

        $proposal->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('propose.index')->with('success', 'Proposition mise à jour avec succès.');
    }


    

    public function destroy($id)
    {
        $proposal = ServiceBenevolePropose::findOrFail($id);
        $proposal->delete();

        return redirect()->route('service-proposals.index')->with('success', 'Proposition supprimée avec succès.');
    }

    public function create()
    {
        $benevoles = AdhesionBenevole::with('user')->get();

        return view('admin.services.propose.create', compact('benevoles'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        ServiceBenevolePropose::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'en_attente',
        ]);

        return redirect()->route('propose.index')->with('success', 'Proposition de service créée avec succès.');
    }

    public function edit($id)
    {
        $proposal = ServiceBenevolePropose::findOrFail($id);
        $benevoles = AdhesionBenevole::with('user')->get();

        return view('admin.services.propose.edit', compact('proposal', 'benevoles'));
    }


    public function createBenevole()
    {
        return view('benevole.collectes.propose-benevole');
    }

    public function storeBenevole(Request $request)
    {
        $request->validate([
            
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        ServiceBenevolePropose::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'en_attente',
        ]);

        return redirect()->route('benevole')->with('success', 'Proposition de service créée avec succès.');
    }
    

}
