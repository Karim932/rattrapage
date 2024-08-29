<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    // Définition du nom de la table si différent du nom standard
    protected $table = 'inscriptions';

    // Attributs qui sont mass assignable
    protected $fillable = ['user_id', 'planning_id'];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function planning()
    {
        return $this->belongsTo(Planning::class);
    }
}

