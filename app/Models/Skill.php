<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $table = 'skills';

    protected $fillable = ['name'];


    public function users()
    {
        return $this->belongsToMany(User::class, 'skill_user');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_skill');
    }



}
