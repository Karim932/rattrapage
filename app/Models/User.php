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
        'cotisation' => 'boolean',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function adhesionCommercants()
    {
        return $this->hasOne(AdhesionCommercant::class, 'user_id');
    }

    public function adhesionsBenevoles()
    {
        return $this->hasOne(AdhesionBenevole::class, 'user_id');
    }

    public function adhesions()
    {
        return $this->hasMany(Adhesion::class, 'candidature_id');
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function isRegisteredFor($planningId)
    {
        return $this->inscriptions()->where('planning_id', $planningId)->exists();
    }

    public function plannings()
    {
        return $this->belongsToMany(Planning::class, 'inscriptions', 'user_id', 'planning_id')
                    ->withTimestamps();
    }

}
