<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planning extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'date', 'start_time', 'end_time', 'status', 'city', 'address', 'max_inscrit', 'benevole_id'];

    protected $casts = [
        'date' => 'date',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function benevoles()
    {
        return $this->belongsToMany(AdhesionBenevole::class, 'planning_benevole', 'planning_id', 'benevole_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'inscriptions', 'planning_id', 'user_id')
                    ->withTimestamps();
    }

}
