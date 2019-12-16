<?php

namespace Tests\Unit;

use App\Army;
use App\Game;
use App\Turn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArmyTest extends TestCase
{
    use RefreshDatabase;

	/** @test */
    public function it_belongs_to_a_game()
    {
        $game = factory(Game::class)->create();
        $army = factory(Army::class)->create(['game_id' => $game->id]);
        $this->assertInstanceOf(Game::class, $army->game);
    }

	/** @test */
    public function it_returns_total_damage_delt_to_army_in_the_game()
    {
        $game = factory(Game::class)->create();
        $attacker = factory(Army::class)->create(['game_id' => $game->id]);
        $deffender = factory(Army::class)->create(['game_id' => $game->id]);
        factory(Turn::class, 2)->create([
            "game_id" => $game->id,
            "attacker_id" => 1,
            "defender_id" => 2,
            "damage" => 10
        ]);
        $this->assertEquals(20, $deffender->damageTaken());
    }

	/** @test */
    public function it_resets_the_damage_taken_and_deletes_turns()
    {
        $game = factory(Game::class)->create();
        $attacker = factory(Army::class)->create(['game_id' => $game->id]);
        $defender = factory(Army::class)->create(['game_id' => $game->id, "units" => -20]);
        $turns = factory(Turn::class, 2)->create([
            "game_id" => $game->id,
            "attacker_id" => 1,
            "defender_id" => 2,
            "damage" => 50
        ]);
        $defender = $defender->resetDamageTaken();
        $this->assertEquals(80, $defender->units);
    }
}
