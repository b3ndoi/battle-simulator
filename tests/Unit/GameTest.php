<?php

namespace Tests\Unit;

use App\Army;
use App\Game;
use App\Turn;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameTest extends TestCase
{
    use RefreshDatabase;

	/** @test */
    public function it_can_have_many_armies()
    {
        $game = factory(Game::class)->create();
        $armies = factory(Army::class, 10)->create(['game_id' => $game->id]);
        $this->assertCount(10, $game->armies->toArray());
        $this->assertInstanceOf(Collection::class, $game->armies);
    }
	/** @test */
    public function it_can_have_many_turns()
    {
        $game = factory(Game::class)->create();
        factory(Army::class, 2)->create(['game_id' => $game->id]);
        factory(Turn::class, 3)->create([
            "game_id" => $game->id,
            "attacker_id" => 1,
            "defender_id" => 2,
        ]);
        $this->assertCount(3, $game->turns->toArray());
        $this->assertInstanceOf(Collection::class, $game->turns);
    }
    /** @test */
    public function it_returns_active_of_inactive_games_based_on_the_given_param(){
        factory(Game::class, 3)->create();
        factory(Game::class, 5)->create(["status"=>1]);
        $active_games = Game::isFinished(0)->get();
        $inactive_games = Game::isFinished(1)->get();
        $this->assertCount(3, $active_games);
        $this->assertCount(5, $inactive_games);
    }

	/** @test */
    public function it_creates_an_army_for_the_specified_game()
    {
        $game = factory(Game::class)->create();
        $army = factory(Army::class)->make();
        
        $game->addArmy($army->toArray());
        $this->assertCount(1,  $game->armies->toArray());
    }
}
