<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'produit_id',
        'quantite',
        'emplacement',
        'date_entree',
        'date_expiration',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
