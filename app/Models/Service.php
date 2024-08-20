<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'status', 'category', 'condition', 'duration'
    ];

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'service_skill', 'service_id', 'skill_id', 'adhesion');
    }

    public function adhesionBenevole()
    {
        return $this->hasMany(AdhesionBenevole::class, 'id_service');
    }
}
