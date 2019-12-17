<?php

namespace Tests\Feature;

use App\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewsTest extends TestCase
{
    use RefreshDatabase;
	/** @test */
    public function there_is_a_home_route()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

	/** @test */
    public function it_returns_a_page_if_the_game_exsists()
    {
        $game = factory(Game::class)->create();
        $response = $this->get('/game/'.$game->id);

        $response->assertStatus(200);
    }

	/** @test */
    public function it_returns_a_404_page_if_the_game_does_not_exist()
    {
        $response = $this->get('/game/32');

        $response->assertStatus(404);
    }
}
