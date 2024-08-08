<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $users = User::all();
        // Récupérer les utilisateurs avec pagination
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'role' => 'required|string',
            'profile_picture' => 'nullable|image'
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'phone_number' => $request->phone_number,
            'role' => $request->role,
            'banned' => false
        ]);

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
            $user->save();
        }

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'role' => 'required|string',
            'profile_picture' => 'nullable|image'
        ]);

        $user->fill($request->except(['password', 'profile_picture']));
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
            $user->save();
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }


    public function banUser($id)
    {
        $user = User::findOrFail($id);
        $user->banned = true;
        $user->role = 'banned';
        $user->save();

        return response()->json(['success' => true, 'message' => 'User banned successfully']);
    }

    public function unbanUser($id)
    {
        $user = User::findOrFail($id);
        $user->banned = false;
        $user->role = 'user';
        $user->save();

        return response()->json(['success' => true, 'message' => 'User unbanned successfully']);
    }

    // public function filterUsers(Request $request)
    // {

    //     $query = User::query();

    //     $role = $request->input('role');
    //     $users = User::when($role, function ($query, $role) {
    //                 return $query->where('role', $role);
    //             })->paginate(10);

    //     // if ($request->ajax()) {
    //     //     return view('admin.users.partialsTable.user-and-role', compact('users'))->render();
    //     // } else {
    //     //     return view('some.main.view', compact('users'));
    //     // }

    //     if ($sort = $request->input('sort')) {
    //         $order = $request->input('order', 'asc');
    //         $query->orderBy($sort, $order);
    //     }

    //     return response()->json([
    //         'html' => view('admin.users.partialsTable.user-and-role', compact('users'))->render(),
    //         'pagination' => $users->links()->toHtml(),
    //     ]);
    // }

    public function filterUsers(Request $request)
    {
        $role = $request->input('role');
        $sortField = $request->input('sort', 'id'); // Default sorting field
        $sortOrder = $request->input('order', 'asc'); // Default sorting order

        $users = User::when($role, function ($query, $role) {
            return $query->where('role', $role);
        })
        ->orderBy($sortField, $sortOrder)
        ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.users.partialsTable.user-and-role', compact('users'))->render(),
                'pagination' => $users->links()->toHtml(),
            ]);
        }

        return view('admin.users.index', compact('users'));
    }



}
