<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adhesion extends Model
{
    use HasFactory;

    public function fusion()
    {
        return $this->morphTo(__FUNCTION__, 'candidature_type', 'candidature_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'candidature_id');
    }

}
