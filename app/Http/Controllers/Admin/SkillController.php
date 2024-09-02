<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Skill;
use App\Http\Controllers\Controller;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skills = Skill::all();
        return view('admin.skills.index', compact('skills'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:skills,name',
        ]);

        Skill::create($validatedData);

        return back()->with('success', 'Compétence ajoutée avec succès.');
    }

    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return back()->with('success', 'Compétence supprimée avec succès.');
    }

}
