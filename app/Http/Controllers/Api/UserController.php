<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{
    /**
     * Filter users by role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filterByRole(Request $request)
    {
        // Initialisation d'une variable `$role` pour stocker le paramètre de rôle issu de la chaîne de requête de la requête HTTP entrante.
        $role = $request->query('role');

        // Récupération de tous les utilisateurs de la base de données où le champ 'role' correspond au rôle spécifié.
        // `User::where('role', $role)` crée une requête pour sélectionner les utilisateurs avec le rôle spécifié.
        // `get()` exécute la requête et récupère la collection des enregistrements d'utilisateurs qui correspondent à la condition.
        $users = User::where('role', $role)->get();

        // Retourne la collection des utilisateurs sous forme de réponse JSON.
        // `response()->json($users)` convertit la collection des utilisateurs en format JSON et l'envoie comme une réponse HTTP.
        // Cela est utile pour les API où le client attend une réponse formatée en JSON pour la manipuler côté frontend.
        return response()->json($users);

    }

    // fonction recherche
    // public function search(Request $request)
    // {
    //     $query = User::query();

    //     if ($search = $request->input('search')) {
    //         $query->where('firstname', 'like', "%{$search}%")
    //             ->orWhere('lastname', 'like', "%{$search}%");
    //     }

    //     if ($role = $request->input('role')) {
    //         $query->where('role', $role);
    //     }

    //     return $query->get();
    // }

    public function getAllUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

}
