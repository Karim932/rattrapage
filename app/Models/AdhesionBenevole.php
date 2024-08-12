<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdhesionBenevole extends Model
{
    use HasFactory;

    protected $table = 'adhesion_benevoles'; // Spécifier le nom de la table si nécessaire

    protected $fillable = [
        'user_id', 'status', 'old_benevole', 'motivation', 'experience',
        'hour_month', 'permis', 'is_active', 'additional_notes', 'type',
        'availability_begin', 'availability_end'
    ];

    protected $dates = ['availability_begin', 'availability_end'];

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
}
