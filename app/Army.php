<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Army extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'units' => 'integer',
        'order' => 'integer',
        'game_id' => 'integer',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function damageTaken()
    {
        return $this->defending()->sum('damage');
    }

    public function defending()
    {
        return $this->hasMany(Turn::class, 'defender_id');
    }
    public function resetDamageTaken()
    {
        $this->units += $this->damageTaken();
        $this->save();
        $this->defending()->delete();
        return $this;
    }
}
