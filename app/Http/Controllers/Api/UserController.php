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
        $role = $request->query('role');

        // Retrieve users by role
        $users = User::where('role', $role)->get();

        return response()->json($users);
    }

    // fonction recherche
    public function search(Request $request)
    {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where('firstname', 'like', "%{$search}%")
                ->orWhere('lastname', 'like', "%{$search}%");
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        return $query->get();
    }

    public function getAllUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

}
