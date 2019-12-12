<?php

namespace Tests\Feature;

use App\Army;
use App\Game;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
