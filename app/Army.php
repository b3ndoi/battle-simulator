<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Army extends Model
{
    protected $guarded = ['id'];


    public function game(){
        return $this->belongsTo(Game::class);
    }
}
