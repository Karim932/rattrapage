<?php

namespace App\Http\Controllers\Benevole;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collecte;
use App\Models\AdhesionBenevole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BenevoleCollecteController extends Controller
{
    public function index()
{
    $collectes = Collecte::where('benevole_id', auth()->user()->id)
                        ->whereIn('status', ['attribuée', 'en cours', 'en attente de stockage'])
                        ->get();

    return view('benevole.collectes.index', compact('collectes'));
}

}
