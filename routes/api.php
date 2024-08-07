<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route pour la recherche des utilisateurs
Route::get('/users/search', [UserController::class, 'search'])->name('api.users.search');

// Route pour filtrer les utilisateurs par rôle
Route::get('/users/role', [UserController::class, 'filterByRole'])->name('api.users.role');

Route::get('/users/all', [UserController::class, 'getAllUsers'])->name('api.users.all');

