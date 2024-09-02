<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    protected $table = 'inscriptions';

    protected $fillable = ['user_id', 'planning_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function planning()
    {
        return $this->belongsTo(Planning::class);
    }
}

