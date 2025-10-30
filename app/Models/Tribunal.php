<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tribunal extends Model
{
    protected $table = 'tribunal'; 

    protected $fillable = [
        'name',
        'number',
    ];


    public function users()
{
    return $this->hasMany(User::class);
}
}
