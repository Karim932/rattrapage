<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse | View
    {
        $messages = [
            'firstname.required' => 'Le prénom est requis.',
            'firstname.string' => 'Le prénom doit être une chaîne de caractères.',
            'firstname.max' => 'Le prénom ne peut pas dépasser 255 caractères.',
            'firstname.regex' => 'Le prénom ne peut contenir que des lettres, des espaces, des accents et des tirets.',
        
            'lastname.required' => 'Le nom de famille est requis.',
            'lastname.string' => 'Le nom de famille doit être une chaîne de caractères.',
            'lastname.max' => 'Le nom de famille ne peut pas dépasser 255 caractères.',
            'lastname.regex' => 'Le nom de famille ne peut contenir que des lettres, des espaces, des accents et des tirets.',
        
            'email.required' => 'L\'adresse email est requise.',
            'email.string' => 'L\'adresse email doit être une chaîne de caractères.',
            'email.lowercase' => 'L\'adresse email doit être en minuscules.',
            'email.email' => 'L\'adresse email doit être une adresse email valide.',
            'email.max' => 'L\'adresse email ne peut pas dépasser 255 caractères.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'email.regex' => 'L\'adresse email doit être dans un format valide.',
        
            'password.required' => 'Le mot de passe est requis.',
            'password.confirmed' => 'Le mot de passe de confirmation ne correspond pas.',
            'password.min' => 'Le mot de passe doit comporter au moins 8 caractères.',
            'password.mixedCase' => 'Le mot de passe doit contenir à la fois des majuscules et des minuscules.',
            'password.numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
            'password.symbols' => 'Le mot de passe doit contenir au moins un symbole.',
        
            'date_of_birth.required' => 'La date de naissance est requise.',
            'date_of_birth.date' => 'La date de naissance doit être une date valide.',
            'date_of_birth.before' => 'La date de naissance doit être une date antérieure à aujourd\'hui.',
        
            'address.required' => 'L\'adresse est requise.',
            'address.string' => 'L\'adresse doit être une chaîne de caractères.',
            'address.max' => 'L\'adresse ne peut pas dépasser 255 caractères.',
        
            'city.required' => 'La ville est requise.',
            'city.string' => 'Le nom de la ville doit être une chaîne de caractères.',
            'city.max' => 'Le nom de la ville ne peut pas dépasser 255 caractères.',
            'city.regex' => 'Le nom de la ville ne peut contenir que des lettres, des espaces, des accents et des tirets.',
        
            'country.required' => 'Le pays est requis.',
            'country.string' => 'Le nom du pays doit être une chaîne de caractères.',
            'country.max' => 'Le nom du pays ne peut pas dépasser 255 caractères.',
            'country.regex' => 'Le nom du pays ne peut contenir que des lettres, des espaces, des accents et des tirets.',
        
            'phone_number.required' => 'Le numéro de téléphone est requis.',
            'phone_number.regex' => 'Le numéro de téléphone doit être un numéro valide avec ou sans indicatif international.',
        ];
        
        $request->validate([
            'firstname' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÖØ-öø-ÿ\s\-]+$/'],
            'lastname' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÖØ-öø-ÿ\s\-]+$/'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'password' => ['required', 'confirmed', Password::min(8)->numbers()->symbols()],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÖØ-öø-ÿ\s\-]+$/'],
            'country' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÖØ-öø-ÿ\s\-]+$/'],
            'phone_number' => ['required', 'regex:/^\+?\d{1,3}?[-.\s]?\(?\d{1,3}?\)?[-.\s]?\d{3}[-.\s]?\d{4}$/']
        ], $messages);
        
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
            'role' => 'user',
        ]);

        // event(new Registered($user));

        // Auth::login($user);

        return redirect(route('login', absolute: false))->with('success', 'Inscription réussie. Vous pouvez maintenant vous connecter.');
    }
}
