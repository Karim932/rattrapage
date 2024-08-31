<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the user that owns the annonce.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * Get the service associated with the annonce.
     */
    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'service_id');
    }
}
