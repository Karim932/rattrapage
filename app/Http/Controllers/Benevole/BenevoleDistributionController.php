<?php

namespace App\Http\Controllers\Benevole;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Distribution;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BenevoleDistributionController extends Controller
{
    public function show($id)
    {
        $distribution = Distribution::with('stocks.produit')->findOrFail($id);

        return view('benevole.distributions.show', compact('distribution'));
    }

    public function updateStatus(Request $request, $id)
    {
        $distribution = Distribution::findOrFail($id);
        $distribution->status = $request->input('status');
        $distribution->save();

        return redirect()->route('benevole.distributions.show', $distribution->id)->with('success', 'Le statut de la distribution a été mis à jour.');
    }

    public function confirmerDistribution($id)
{

    $distribution = Distribution::findOrFail($id);

    if ($distribution->status !== 'En Cours') {
        return redirect()->back()->with('error', 'La distribution ne peut pas être confirmée car elle n\'est pas en cours.');
    }

    DB::transaction(function () use ($distribution) {
        foreach ($distribution->stocks as $stock) {
            $stock->quantite -= $stock->pivot->quantite;
            $stock->quantite_reservee -= $stock->pivot->quantite;
            $stock->save();
        }

        
        $distribution->status = 'Terminé';
        $distribution->save();
    });

    $pdf = PDF::loadView('pdf.recap_distribution', compact('distribution'))
           ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
           ->save(storage_path("app/public/recaps/recap_distribution_{$distribution->id}.pdf"));

    $pdfPath = "recaps/recap_distribution_{$distribution->id}.pdf";
    Storage::disk('public')->put($pdfPath, $pdf->output());

    return redirect()->route('benevole.collectes.index')->with('success', 'Distribution confirmée et récapitulatif généré.');

}

public function showRecap($id)
{
    $distribution = Distribution::findOrFail($id);

    $pdfPath = "recaps/recap_distribution_{$distribution->id}.pdf";

    if (Storage::disk('public')->exists($pdfPath)) {
        return response()->file(storage_path('app/public/' . $pdfPath));
    } else {
        return redirect()->route('benevole.distributions.index')->with('error', 'Le PDF n\'existe pas.');
    }
}


}
