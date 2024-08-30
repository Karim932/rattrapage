<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdhesionBenevole extends Model
{
    use HasFactory;

    protected $table = 'adhesion_benevoles';

    protected $fillable = [
        'user_id', 'status', 'old_benevole', 'motivation', 'experience',
        'permis', 'is_active', 'additional_notes', 'type',
        'availability_begin', 'availability_end', 'availability', 'skill_id', 'id_service'
    ];

    protected $dates = ['availability_begin', 'availability_end'];

     // est traitée comme un tableau
    protected $casts = [
        'availability' => 'array',
        'skill_id' => 'array',
        'old_benevole' => 'boolean',  
        'permis' => 'boolean',
        
    ];

    // Relation avec le modèle User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->morphMany(Answer::class, 'candidature');
    }

    public function adhesion()
    {
        return $this->morphOne(Adhesion::class, 'fusion');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'service_skill', 'service_id'); // , 'skill_id', 'adhesion'
    }

    public function plannings()
    {
        return $this->belongsToMany(Planning::class, 'planning_benevole', 'benevole_id', 'planning_id');
    }

}
