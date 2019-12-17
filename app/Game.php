<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $guarded = ['id'];

    public function armies()
    {
        return $this->hasMany(Army::class)->orderBy('order');
    }

    public function turns()
    {
        return $this->hasMany(Turn::class, 'game_id')->orderBy('created_at');
    }

    public function addArmy($army)
    {
        return $this->armies()->create($army);
    }

    public function scopeIsFinished($query, $is_active)
    {
        return $query->where('status', $is_active);
    }
}
