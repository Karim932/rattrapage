<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\SetLocalization;
use App\Http\Middleware\CheckIfBanned;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;



Route::get('/localization/{locale}', LocalizationController::class)->name('localization');


Route::middleware(SetLocalization::class)->group(function(){

    Route::view('/test', 'test');

    Route::middleware(['banned'])->get('/test-banned', function () {
        return 'You are not banned.';
    });

    Route::middleware([CheckIfBanned::class])->get('/test-banned', function () {
        return 'You are not banned.';
    });


    Route::get('/accueil', function () {
        return view('accueil');
    })->name('accueil');


    Route::get('/', function (){
        return redirect()->route('accueil');
    });


    Route::middleware('auth')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('edit');
        Route::patch('/profile', 'update')->name('update');
        Route::delete('/profile','destroy')->name('destroy');
    });

    require __DIR__.'/auth.php';

    // Routes pour les utilisateurs authentifiés
    Route::middleware(['auth', 'verified', CheckIfBanned::class])->group(function () {

        Route::resource('users', UserController::class);
        Route::get('/filter-users', [UserController::class, 'filterUsers'])->name('users.filter');
        Route::post('/users/ban/{id}', [UserController::class, 'banUser'])->name('users.ban');
        Route::post('/users/unban/{id}', [UserController::class, 'unbanUser'])->name('users.unban');



        Route::get('services', function () {
            return view('page_navbar/services');
        })->name('services');

        Route::get('adhesions', function () {
            return view('page_navbar/adhesions');
        })->name('adhesions');

        Route::get('/collectes', function () {
            return view('collectes');
        })->name('collectes');

        Route::get('/stocks', function () {
            return view('stocks');
        })->name('stocks');

        Route::get('/tournees', function () {
            return view('tournees');
        })->name('tournees');

        Route::get('/benevoles', function () {
            return view('benevoles');
        })->name('benevoles');

        Route::get('/contact', function () {
            return view('contact');
        })->name('contact');


    });

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('can:is-admin')->name('admin.dashboard');
});







// aide et alternatif
// Route::get('/', function () {
//     // $language = $lang;
//     // App::setlocale($language);
//     return view('test');
//     //return redirect()->route('accueil');
// });
// Route::get('/', function () {
//     // $language = $lang;
//     // App::setlocale($language);
//     //return view('test');
//     return redirect()->route('accueil');
// });




