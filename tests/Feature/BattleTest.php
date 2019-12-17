<?php

namespace Tests\Feature;

use App\Army;
use App\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BattleTest extends TestCase
{
    use RefreshDatabase;
	/** @test */
    public function the_first_army_attacks_the_weakest_army_and_does_50_damage()
    {
        $game = factory(Game::class)->create();
        $army_one = factory(Army::class)->create(["game_id"=> $game->id, "units" => 100, "strategy"=>'weakest']);
        $army_weakest = factory(Army::class)->create(["game_id"=> $game->id, "units" => 80]);
        factory(Army::class, 3)->create(["game_id"=> $game->id,  "units" => 90]);

        $res = $this->json('POST', '/api/game/'.$game->id.'/attack');
        $res->assertStatus(200);
        $this->assertDatabaseHas('turns', [
            "game_id" => $game->id,
            "attacker_id" => $army_one->id,
            "defender_id" => $army_weakest->id,
            "damage" => 50
        ]);
    }
	/** @test */
    public function the_first_army_attacks_the_strongest_army_and_does_50_damage()
    {
        $game = factory(Game::class)->create();
        $army_one = factory(Army::class)->create(["game_id"=> $game->id, "units" => 100, "strategy"=>'strongest']);
        $army_strongest = factory(Army::class)->create(["game_id"=> $game->id, "units" => 100]);
        factory(Army::class, 3)->create(["game_id"=> $game->id,  "units" => 80]);

        $res = $this->json('POST', '/api/game/'.$game->id.'/attack');
        $res->assertStatus(200);
        $this->assertDatabaseHas('turns', [
            "game_id" => $game->id,
            "attacker_id" => $army_one->id,
            "defender_id" => $army_strongest->id,
            "damage" => 50
        ]);
    }
	/** @test */
    public function the_first_army_attacks_the_weakest_army_and_destroys_it()
    {
        $game = factory(Game::class)->create();
        $army_one = factory(Army::class)->create(["game_id"=> $game->id, "units" => 100, "strategy"=>'weakest']);
        $army_weakest = factory(Army::class)->create(["game_id"=> $game->id, "units" => 45]);
        factory(Army::class, 3)->create(["game_id"=> $game->id,  "units" => 90]);

        $res = $this->json('POST', '/api/game/'.$game->id.'/attack');
        $res->assertStatus(200);
        $this->assertDatabaseHas('turns', [
            "game_id" => $game->id,
            "attacker_id" => $army_one->id,
            "defender_id" => $army_weakest->id,
            "damage" => 50,
            "is_destroied" => 1
        ]);
        $destroyed_army = $army_weakest->toArray();
        $destroyed_army['units'] = -5;
        $this->assertDatabaseHas('armies', $destroyed_army);
    }
	/** @test */
    public function the_first_army_attacks_the_weakest_army_and_destroys_it_and_finishes_the_game()
    {
        $game = factory(Game::class)->create();
        $army_one = factory(Army::class)->create(["game_id"=> $game->id, "units" => 100, "strategy"=>'weakest']);
        $army_weakest = factory(Army::class)->create(["game_id"=> $game->id, "units" => 45]);
        factory(Army::class, 3)->create(["game_id"=> $game->id,  "units" => 0]);

        $res = $this->json('POST', '/api/game/'.$game->id.'/attack');
        $res->assertStatus(200);
        $this->assertDatabaseHas('turns', [
            "game_id" => $game->id,
            "attacker_id" => $army_one->id,
            "defender_id" => $army_weakest->id,
            "damage" => 50,
            "is_destroied" => 1
        ]);
        $finished_game = $game->toArray();
        $finished_game['status'] = 1;
        unset($finished_game['updated_at']);
        $this->assertDatabaseHas('games', $finished_game);
    }
}
