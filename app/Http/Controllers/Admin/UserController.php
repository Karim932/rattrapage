<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

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
            'firstname' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ\s\-]+$/u' 
            ],
            'lastname' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ\s\-]+$/u' 
            ],
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', 
            ],
            'date_of_birth' => 'required|date|before:today',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'phone_number' => 'required|string|regex:/^\+?[0-9]{7,15}$/', 
            'role' => 'required|string|in:admin,user,benevole, commercant',
            'cotisation' => 'required|boolean',
        ], [
            'firstname.required' => 'Le prénom est obligatoire.',
            'firstname.string' => 'Le prénom doit être une chaîne de caractères.',
            'firstname.max' => 'Le prénom ne doit pas dépasser 255 caractères.',
            'firstname.regex' => 'Le prénom ne doit contenir que des lettres, des espaces ou des tirets.',
        
            'lastname.required' => 'Le nom de famille est obligatoire.',
            'lastname.string' => 'Le nom de famille doit être une chaîne de caractères.',
            'lastname.max' => 'Le nom de famille ne doit pas dépasser 255 caractères.',
            'lastname.regex' => 'Le nom de famille ne doit contenir que des lettres, des espaces ou des tirets.',
        
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.string' => 'L\'adresse e-mail doit être une chaîne de caractères.',
            'email.email' => 'L\'adresse e-mail doit être une adresse valide.',
            'email.max' => 'L\'adresse e-mail ne doit pas dépasser 255 caractères.',
            'email.unique' => 'L\'adresse e-mail est déjà utilisée.',
        
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.',
        
            'date_of_birth.date' => 'La date de naissance doit être une date valide.',
            'date_of_birth.before' => 'La date de naissance doit être une date antérieure à aujourd\'hui.',
        
            'address.string' => 'L\'adresse doit être une chaîne de caractères.',
            'address.max' => 'L\'adresse ne doit pas dépasser 255 caractères.',
        
            'city.string' => 'La ville doit être une chaîne de caractères.',
            'city.max' => 'La ville ne doit pas dépasser 255 caractères.',
        
            'country.string' => 'Le pays doit être une chaîne de caractères.',
            'country.max' => 'Le pays ne doit pas dépasser 255 caractères.',
        
            'phone_number.string' => 'Le numéro de téléphone doit être une chaîne de caractères.',
            'phone_number.regex' => 'Le numéro de téléphone doit être valide, avec entre 7 et 15 chiffres.',
        
            'role.required' => 'Le rôle est obligatoire.',
            'role.string' => 'Le rôle doit être une chaîne de caractères.',
            'role.in' => 'Le rôle doit être l\'un des suivants: admin, user, moderator.',
        ]);        

        $user = User::create([
            'cotisation' => $request->cotisation,
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

        return redirect()->route('users.index')->with('success', 'L\'utilisateur a bien été créé !');
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

        // dd($request->role);

        $request->validate([
            'firstname' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ\s\-]+$/u' 
            ],
            'lastname' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ\s\-]+$/u' 
            ],
            'email' => 'required|string|email|max:255|unique:users,email'. $user->id,
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ],
            'date_of_birth' => 'required|date|before:today',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'phone_number' => 'required|string|regex:/^\+?[0-9]{7,15}$/',  
            'role' => 'required|string|in:admin,user,benevole,commercant',
            'cotisation' => 'required|boolean',
        ], [
            'firstname.required' => 'Le prénom est obligatoire.',
            'firstname.string' => 'Le prénom doit être une chaîne de caractères.',
            'firstname.max' => 'Le prénom ne doit pas dépasser 255 caractères.',
            'firstname.regex' => 'Le prénom ne doit contenir que des lettres, des espaces ou des tirets.',
        
            'lastname.required' => 'Le nom de famille est obligatoire.',
            'lastname.string' => 'Le nom de famille doit être une chaîne de caractères.',
            'lastname.max' => 'Le nom de famille ne doit pas dépasser 255 caractères.',
            'lastname.regex' => 'Le nom de famille ne doit contenir que des lettres, des espaces ou des tirets.',
        
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.string' => 'L\'adresse e-mail doit être une chaîne de caractères.',
            'email.email' => 'L\'adresse e-mail doit être une adresse valide.',
            'email.max' => 'L\'adresse e-mail ne doit pas dépasser 255 caractères.',
            'email.unique' => 'L\'adresse e-mail est déjà utilisée.',
        
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.',
        
            'date_of_birth.date' => 'La date de naissance doit être une date valide.',
            'date_of_birth.before' => 'La date de naissance doit être une date antérieure à aujourd\'hui.',
        
            'address.string' => 'L\'adresse doit être une chaîne de caractères.',
            'address.max' => 'L\'adresse ne doit pas dépasser 255 caractères.',
        
            'city.string' => 'La ville doit être une chaîne de caractères.',
            'city.max' => 'La ville ne doit pas dépasser 255 caractères.',
        
            'country.string' => 'Le pays doit être une chaîne de caractères.',
            'country.max' => 'Le pays ne doit pas dépasser 255 caractères.',
        
            'phone_number.string' => 'Le numéro de téléphone doit être une chaîne de caractères.',
            'phone_number.regex' => 'Le numéro de téléphone doit être valide, avec entre 7 et 15 chiffres.',
        
            'role.required' => 'Le rôle est obligatoire.',
            'role.string' => 'Le rôle doit être une chaîne de caractères.',
            'role.in' => 'Le rôle doit être l\'un des suivants: admin, user, benevole, commercant.',
        ]);    
        

        $user->fill($request->except(['password', 'profile_picture']));
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        
        return redirect()->route('users.index')->with('success', 'Votre modification a bien été pris en compte.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'L\'utilisateur a bien été supprimé !');
    }


    public function banUser($id)
    {
        $user = User::findOrFail($id);
        $user->banned = true;
        $user->role = 'banned';
        $user->save();

        return response()->json(['success' => true, 'message' => 'L\'utilisateur a été banni !']);
    }

    public function unbanUser($id)
    {
        $user = User::findOrFail($id);
        $user->banned = false;
        $user->role = 'user';
        $user->save();

        return response()->json(['success' => true, 'message' => 'L\'utilisateur a été débanni !']);
    }

    public function filterUsers(Request $request)
    {

        $role = $request->input('role');
        $sortField = $request->input('sort');
        $sortOrder = $request->input('order');

        $query = User::query();

        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->orderBy($sortField, $sortOrder)->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.users.partialsTable.user-and-role', compact('users'))->render(),
                'pagination' => $users->links()->toHtml(),
            ]);
        }

        return view('admin.users.index', compact('users'));
    }

    
    public function getUsersWithoutCandidature(Request $request)
    {
        $type = $request->query('type');

        if ($type === 'benevole') {
            $users = User::doesntHave('adhesionsBenevoles')->get();
        } elseif ($type === 'commercant') {
            $users = User::doesntHave('adhesionsCommercant')->get();
        } else {
            $users = User::doesntHave('adhesionsBenevoles')->doesntHave('adhesionsCommercant')->get();
        }

        return response()->json($users);
    }
    

}
