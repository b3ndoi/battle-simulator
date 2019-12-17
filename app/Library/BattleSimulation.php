<?php

namespace App\Library;

use App\Turn;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class BattleSimulation
{

    protected $armies;
    
    protected $logs = [];

    protected $destroiedArmies = [];

    public function __construct($armies)
    {
        $this->armies = $armies;
    }

    public function getTurnLogs()
    {
        foreach ($this->armies as $attacker) {
            // only if army is not destroied continiue
            if (!collect($this->destroiedArmies)->contains($attacker->id)) {
                $strategy = $attacker->strategy;
                $defender = $this->$strategy($attacker->id);
                if (!$defender) {
                    $this->logs[] = $defender;
                }
                if ($this->attack($attacker->units)) {
                    // calculate damage
                    $damage = $this->calculateDamage($attacker->units);
                    // Remove destroied units
                    $defender->units -= $damage;
                    $defender->save();
                    if ($defender->units <= 0) {
                        // Log hit
                        $turn = Turn::create([
                            "game_id" => 1,
                            "attacker_id" => $attacker->id,
                            "defender_id" => $defender->id,
                            "damage" => $damage,
                            "is_destroied" => 1
                        ]);
                        $dateTime = "<span class='text-yellow-400'>[".Carbon::parse($turn->created_at)->format('d.m.Y. H:i:s')."]</span>  ";
                        $this->logs[] = $dateTime.$attacker->name." ARMY 🚀 attacked ".$defender->name." ARMY and DESTROYED IT 🏴‍☠️";
                        
                        // Add to destroied array
                        $this->destroiedArmies[] = $defender->id;
                        // Remove army from active armies
                        $this->removeDestroyedArmy($defender->id);
                    } else {
                        // Log hit
                        $turn = Turn::create([
                            "game_id" => 1,
                            "attacker_id" => $attacker->id,
                            "defender_id" => $defender->id,
                            "damage" => $damage,
                        ]);

                        $dateTime = "<span class='text-yellow-400'>[".Carbon::parse($turn->created_at)->format('d.m.Y. H:i:s')."]</span>  ";
                        $this->logs[] = $dateTime.$attacker->name." ARMY 🚀 attacked ".$defender->name." ARMY and DESTROYED ".$damage." UNITS 💥";
                        // Update attacked army units
                        $this->updateDefenderUnits($defender);
                    }
                } else {
                    // Log miss
                    $turn = Turn::create([
                        "game_id" => 1,
                        "attacker_id" => $attacker->id,
                        "defender_id" => $defender->id,
                        "damage" => 0,
                    ]);
                    $dateTime = "<span class='text-yellow-400'>[".Carbon::parse($turn->created_at)->format('d.m.Y. H:i:s')."]</span>  ";
                    $this->logs[] = $dateTime.$attacker->name." ARMY 🚀 attacked ".$defender->name." ARMY and MISSED 🙊";
                }
                // Wait for reload
                sleep($this->calculateReloadTime($attacker->units));
            }
        }
        if ($this->armies->count() == 1) {
            $winner = $this->armies->first();
            $this->logs[] = "🏆 ".$winner->name." ARMY WON THE GAME 🏆";
        }
        return $this->logs;
    }

    public function getArmies()
    {
        return collect($this->armies)->values();
    }

    protected function random($id)
    {
        return $this->armies
            ->where('id', '!=', $id)->random();
    }
    protected function weakest($id)
    {
        return $this->armies
            ->where('id', '!=', $id)
            ->sortBy('units')->first();
    }
    protected function strongest($id)
    {
        // remove active attacker and get the target army
        return $this->armies
            ->where('id', '!=', $id)
            ->sortByDesc('units')->first();
    }
    protected function removeDestroyedArmy($id)
    {
        $this->armies = collect($this->armies->where('id', '!=', $id))->values();
    }
    protected function updateDefenderUnits($defender)
    {
        $this->armies = $this->armies->map(function ($army) use ($defender) {
            if ($army->id == $defender->id) {
                return $defender;
            }
            return $army;
        });
        return;
    }

    /**
     * Returns if the attack was succesfull or not
     *
     * @param [int] $chance
     * @return boolean
     */
    protected function attack($chance)
    {
        $attack = rand(1, 100);
        if ($attack <= $chance) {
            return true;
        }
        return false;
    }

    /**
     * Returns the damage done
     *
     * @param [int] $numOfUnits
     * @return integer
     */
    protected function calculateDamage($numOfUnits)
    {
        if ($numOfUnits == 1) {
            return 1;
        }
        return floor($numOfUnits/2);
    }

    /**
     * Returns the reload time
     *
     * @param [int] $numOfUnits
     * @return float
     */
    protected function calculateReloadTime($numOfUnits)
    {
        return $numOfUnits*0.01;
    }
}
