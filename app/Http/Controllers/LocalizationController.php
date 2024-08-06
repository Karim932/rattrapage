<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocalizationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($locale)
    {
        if (!in_array($locale, config('localization.locales'))){
            abort(400);
        }

        session(['localization' => $locale]);
        //dd($locale);
        return redirect()->back();
    }
}


// aide et alternatif
//dd(Session::get('locale'));
// if (in_array($locale, config('app.available_locales'))) {
//     App::setLocale($locale);
//     $resquest->session()->put('locale', $locale);
//     $resquest->session()->save();
//     dd(Session::get('locale'));

// }
// return redirect()->back();
