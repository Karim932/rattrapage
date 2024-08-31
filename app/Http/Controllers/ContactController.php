<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function showForm()
    {
        return view('contact'); 
    }

    public function show($id)
    {
        $ticket = Contact::findOrFail($id);

        return view('admin.contact.show', compact('ticket'));
    }

    public function index()
    {
        $tickets = Contact::paginate(10);

        return view('admin.contact.index', compact('tickets'));
    }

    public function edit($id)
    {
        $ticket = Contact::findOrFail($id);

        return view('admin.contact.edit', compact('ticket'));
    }

    public function destroy($id)
    {
        $ticket = Contact::findOrFail($id);
        $ticket->delete();

        return redirect()->route('admin.contact.index')->with('success', 'Ticket supprimé avec succès.');
    }
    
    public function submit(Request $request)
    {
        $request->validate([
            'categorie' => 'nullable|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        $ticket = Contact::create([
            'user_id' => Auth::id(), 
            'statut' => 'Ouvert', 
            'categorie' => $request->categorie, 
            'message' => $request->message,
        ]);

        return redirect()->route('contact')->with('success', 'Votre message a été envoyé avec succès!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'statut' => 'required|string|in:ouvert,en cours,fermé',
        ]);

        $ticket = Contact::findOrFail($id);
        $ticket->update([
            'message' => $request->message,
            'statut' => $request->statut,
        ]);

        return redirect()->route('admin.contact.index')->with('success', 'Ticket mis à jour avec succès.');
    }
    
    
}