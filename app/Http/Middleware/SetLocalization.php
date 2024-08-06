<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SetLocalization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale(session('localization', config('app.locale')));
        return $next($request);
    }
}


// aide et alternatif
// app()->setLocale($request->segment(1));
// URL::defaults(['locale' => $request->segment(1)]);
// $locale = $request->session()->get('locale', config('app.fallback_locale'));
// dd(App::getLocale());
// App::setLocale($locale);
// $request->session()->save();
//Log::info('Locale set in middleware', ['locale' => $locale]);
//dd(App::getLocale()); // Pour débugger





