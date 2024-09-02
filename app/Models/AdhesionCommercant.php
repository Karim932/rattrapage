<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdhesionCommercant extends Model
{
    use HasFactory;

    protected $table = 'adhesion_commercants'; 

    protected $fillable = [
        'user_id', 'company_name', 'siret', 'address', 'city', 'postal_code', 'country',
        'status', 'is_active', 'notes', 'opening_hours', 'type', 'contract_start_date', 'contract_end_date'
    ];

    protected $dates = ['contract_start_date', 'contract_end_date'];

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

