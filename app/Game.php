<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $guarded = ['id'];

    public function armies(){
        return $this->hasMany(Army::class);
    }
}
