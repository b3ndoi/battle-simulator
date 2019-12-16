<?php

namespace Tests\Feature;

use App\Army;
use App\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameCreationTest extends TestCase
{
    use RefreshDatabase;
	/** @test */
    public function it_creates_a_game_and_set_its_status_to_active()
    {
        $res = $this->json('POST', '/api/game');
        $res->assertStatus(201);
        $res->assertJsonPath('id', '1');
        $res->assertJsonPath('status', '0');
        $this->assertDatabaseHas('games', [
            "id"=> 1,
            "status" => 0
        ]);
    }

	/** @test */
    public function it_throws_an_error_if_there_are_5_games_in_progress()
    {
        factory(Game::class, 5)->create();
        $res = $this->json('POST', '/api/game');
        $res->assertStatus(422);
        $res->assertJson([
            "message" => "There can be only 5 games active"
        ]);
        $this->assertDatabaseMissing('games', [
            "id"=> 6,
            "status" => 0
        ]);
    }
    
    /** @test */
    public function it_returnes_a_list_of_all_games_with_the_armys_in_it(){
        $games = factory(Game::class, 5)->create();
        $res = $this->json('GET', '/api/game');
        $res->assertStatus(200);
        $res->assertJsonCount(5);

    }

	/** @test */
    public function a_game_can_not_start_if_there_are_less_than_5_armies()
    {
        $this->withoutExceptionHandling();

        $game = factory(Game::class)->create();
        $armies = factory(Army::class, 4)->create(['game_id' => $game->id]);
        $res = $this->json('POST', '/api/game/'.$game->id.'/attack')
            ->assertStatus(422);
    }
	/** @test */
    public function a_game_can_not_be_restart_if_there_are_no_turns_played()
    {
        $game = factory(Game::class)->create();
        $armies = factory(Army::class, 4)->create(['game_id' => $game->id]);
        $this->json('PUT', '/api/game/'.$game->id.'/reset')
            ->assertStatus(422);
    }
}
