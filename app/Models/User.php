<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'date_of_birth',
        'address', 'city', 'country', 'phone_number', 'role', 'profile_picture', 'banned'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'banned' => 'boolean',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Relation avec AdhesionCommercant
    public function adhesionCommercants()
    {
        return $this->hasMany(AdhesionCommercant::class);
    }

    // Relation avec AdhesionBenevole
    public function adhesionBenevoles()
    {
        return $this->hasMany(AdhesionBenevole::class);
    }
}
