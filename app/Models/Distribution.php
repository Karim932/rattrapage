<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    protected $fillable = ['destinataire', 'adresse', 'telephone', 'date_souhaitee', 'benevole_id', 'status'];

    public function benevole()
    {
        return $this->belongsTo(AdhesionBenevole::class);
    }

    public function stocks()
    {
        return $this->belongsToMany(Stock::class, 'distribution_stock')->withPivot('quantite');
    }
}
