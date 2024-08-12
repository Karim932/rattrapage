<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = ['candidature_id', 'candidature_type', 'id_admin', 'titre', 'message'];

    public function candidature()
    {
        return $this->morphTo();
    }

}
