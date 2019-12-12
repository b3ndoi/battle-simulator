<?php

namespace Tests\Unit;

use App\Army;
use App\Game;
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
}
