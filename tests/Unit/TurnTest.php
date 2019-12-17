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
        $turn = factory(Turn::class)->create();
        $this->assertInstanceOf(Game::class, $turn->game);
    }

	/** @test */
    public function it_returns_a_army_model_as_attacker()
    {
        $turn = factory(Turn::class)->create();
        $this->assertInstanceOf(Army::class, $turn->attacker);
    }

    /** @test */
    public function it_returns_a_army_model_as_defender()
    {
        $turn = factory(Turn::class)->create();
        $this->assertInstanceOf(Army::class, $turn->defender);
    }
}
