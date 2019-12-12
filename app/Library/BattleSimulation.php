<?php

use Illuminate\Database\Eloquent\Collection;

class BattleSimulation{

    protected $armies;
    
    protected $logs = [];

    public function __construct(Collection $armies)
    {
        $this->armies = $armies;
    }

    public function getTurnLogs(){
        $attacker = new Object;
        $strategy = $attacker->strategy;
        $defender = $this->$strategy();
        if($this->attack($attacker->units)){
            // calculate damage

            // log hit
            if($defender->units <= 0){
                $this->logs[] = $attacker->name." ARMY attacked ".$defender->name." ARMY and DESTROYED IT";
                $this->removeDestroyedArmy($defender->id);
            }else{
                $this->logs[] = $attacker->name." ARMY attacked ".$defender->name." ARMY and DESTROYED ".$this->calculateDamage($attacker)." UNITS";
            }
        }
        // Log miss
        $this->logs[] = $attacker->name." ARMY attacked ".$defender->name." ARMY and MISSED";
    }

    protected function random($attacker){
        $availableArmies = $this->armies
            ->where('id', '!=', $attacker->id);
        $indexOfArmy = rand(0, $availableArmies->count() - 1);
        return $availableArmies[$indexOfArmy];
    }
    protected function weakest($attacker){
        return $this->armies
            ->where('id', '!=', $attacker->id)
            ->sortBy('units')->first();
    }
    protected function strongest($attacker){
        // remove active attacker and get the target army
        return $this->armies
            ->where('id', '!=', $attacker->id)
            ->sortByDesc('units')->first();
    }
    protected function removeDestroyedArmy($id){
        $this->armies = $this->armies->where('id', '!=', $id );
        return;
    }
    /**
     * Returns if the attack was succesfull or not
     *
     * @param [int] $chance
     * @return boolean
     */
    protected function attack($chance){
        $attack = rand(1, 100);
        if($attack <= $chance){
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
    protected function calculateDamage($numOfUnits){
        if($numOfUnits == 1){
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
    protected function calculateReloadTime($numOfUnits){
        return $numOfUnits*0.01;
    }
}