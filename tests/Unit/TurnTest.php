<?php

namespace Tests\Unit;

use App\Army;
use App\Game;
use App\Turn;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TurnTest extends TestCase
{
    use RefreshDatabase;
	/** @test */
    public function it_returns_a_game_model()
    {
        $game = factory(Game::class)->create();
        $attacker = factory(Army::class)->create(['game_id'=> $game->id]);
        $deffender = factory(Army::class)->create(['game_id'=> $game->id]);
        $turn = factory(Turn::class)->create([
            'game_id'=> $game->id,
            "attacker_id"=> $attacker->id,
            "defender_id"=>$deffender->id
        ]);
        $this->assertInstanceOf(Game::class, $turn->game);
    }

	/** @test */
    public function it_returns_a_army_model_as_attacker()
    {
        $game = factory(Game::class)->create();
        $attacker = factory(Army::class)->create(['game_id'=> $game->id]);
        $deffender = factory(Army::class)->create(['game_id'=> $game->id]);
        $turn = factory(Turn::class)->create([
            'game_id'=> $game->id,
            "attacker_id"=> $attacker->id,
            "defender_id"=>$deffender->id
        ]);
        $this->assertInstanceOf(Army::class, $turn->attacker);
    }

    /** @test */
    public function it_returns_a_army_model_as_defender()
    {
        $game = factory(Game::class)->create();
        $attacker = factory(Army::class)->create(['game_id'=> $game->id]);
        $deffender = factory(Army::class)->create(['game_id'=> $game->id]);
        $turn = factory(Turn::class)->create([
            'game_id'=> $game->id,
            "attacker_id"=> $attacker->id,
            "defender_id"=>$deffender->id
        ]);
        $this->assertInstanceOf(Army::class, $turn->defender);
    }
}
