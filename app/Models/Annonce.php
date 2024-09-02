<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'location',
        'price',
        'status',
        'category',
        'skills_required',
        'exchange_type',
        'estimated_duration',
        'availability',
        'user_id', 'service_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'service_id');
    }
}
