<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collecte extends Model
{
    protected $fillable = [
        'commercant_id',
        'date_collecte',
        'status',
        'benevole_id',
        'instructions',
    ];

    public function commercant()
    {
        return $this->belongsTo(AdhesionCommercant::class, 'commercant_id');
    }

    public function benevole()
    {
        return $this->belongsTo(AdhesionBenevole::class, 'benevole_id');
    }

}
