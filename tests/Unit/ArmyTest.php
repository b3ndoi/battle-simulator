<?php

namespace Tests\Unit;

use App\Army;
use App\Game;
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
}
