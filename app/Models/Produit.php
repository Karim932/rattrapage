<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        'nom',
        'marque',
        'categorie',
        'code_barre',
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
