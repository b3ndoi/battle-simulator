<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Turn extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'damage' => 'integer',
    ];
    public function game(){
        return $this->belongsTo(Game::class);
    }
    public function attacker(){
        return $this->belongsTo(Army::class, 'attacker_id');
    }
    public function defender(){
        return $this->belongsTo(Army::class, 'defender_id');
    }

}
