<?php

namespace Tests\Feature;

use App\Army;
use App\Game;
use App\Turn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArmyControllerTest extends TestCase
{
    use RefreshDatabase;
	/** @test */
    public function it_creates_an_army_for_a_specific_game()
    {
        $game = factory(Game::class)->create();
        
        $army = factory(Army::class)->make()->toArray();
        unset($army['order']);
        $res = $this->json('POST', '/api/game/'.$game->id.'/armies', $army);
        $res->assertStatus(201);
        $res->assertJsonFragment($army);
        $this->assertDatabaseHas('armies', $army);
    }
	/** @test */
    public function it_creates_an_army_for_a_specific_game_and_if_the_game_has_turns_the_order_number_is_negative()
    {
        $game = factory(Game::class)->create();
        $amies = factory(Army::class, 5)->create(["game_id"=> $game->id]);
        factory(Turn::class, 3)->create([
            "game_id" => $game->id,
            "attacker_id" => 1,
            "defender_id" => 2,
        ]);
        $army = factory(Army::class)->make()->toArray();
        unset($army['order']);
        $res = $this->json('POST', '/api/game/'.$game->id.'/armies', $army);
        $res_army = $res->original->first();
        $this->assertLessThan(0, $res_army->order);
        $res->assertStatus(201);
        $res->assertJsonFragment($army);
        $this->assertDatabaseHas('armies', $army);
    }

	/** @test */
    public function it_throws_an_error_when_adding_an_army_if_a_the_game_is_finished()
    {
        $game = factory(Game::class)->create(['status' => 1]);
        
        $army = factory(Army::class)->make()->toArray();

        $res = $this->json('POST', '/api/game/'.$game->id.'/armies', $army);
        $res->assertStatus(422);
        $this->assertDatabaseMissing('armies', $army);
    }
	/** @test */
    public function it_throws_an_error_when_adding_an_army_if_there_is_no_name()
    {
        $game = factory(Game::class)->create();
        
        $army = factory(Army::class)->make(["name" => null])->toArray();

        $res = $this->json('POST', '/api/game/'.$game->id.'/armies', $army);
        $res->assertStatus(422);
        $res->assertJsonPath('errors.name', ["The name field is required."]);
        $this->assertDatabaseMissing('armies', $army);
    }
	/** @test */
    public function it_throws_an_error_when_adding_an_army_if_there_is_no_units()
    {
        $game = factory(Game::class)->create();
        
        $army = factory(Army::class)->make(["units" => null])->toArray();

        $res = $this->json('POST', '/api/game/'.$game->id.'/armies', $army);
        $res->assertStatus(422);
        $res->assertJsonPath('errors.units', ["The units field is required."]);
        $this->assertDatabaseMissing('armies', $army);
    }
	/** @test */
    public function it_throws_an_error_when_adding_an_army_if_units_are_less_then_specified()
    {
        $game = factory(Game::class)->create();
        
        $army = factory(Army::class)->make(["units" => 55])->toArray();

        $res = $this->json('POST', '/api/game/'.$game->id.'/armies', $army);
        $res->assertStatus(422);
        $res->assertJsonPath('errors.units', ["The units must be between 80 and 100."]);
        $this->assertDatabaseMissing('armies', $army);
    }
	/** @test */
    public function it_throws_an_error_when_adding_an_army_if_units_are_more_then_specified()
    {
        $game = factory(Game::class)->create();
        
        $army = factory(Army::class)->make(["units" => 101])->toArray();

        $res = $this->json('POST', '/api/game/'.$game->id.'/armies', $army);
        $res->assertStatus(422);
        $res->assertJsonPath('errors.units', ["The units must be between 80 and 100."]);
        $this->assertDatabaseMissing('armies', $army);
    }
	/** @test */
    public function it_throws_an_error_when_adding_an_army_if_units_are_not_an_int()
    {
        $game = factory(Game::class)->create();
        $army = factory(Army::class)->make(["units" => "asdsa"])->toArray();

        $res = $this->json('POST', '/api/game/'.$game->id.'/armies', $army);
        
        $res->assertStatus(422);
        $this->assertDatabaseMissing('armies', $army);
    }
	/** @test */
    public function it_throws_an_error_when_adding_an_army_if_there_is_no_strategy()
    {
        $game = factory(Game::class)->create();
        $army = factory(Army::class)->make(["strategy" => null])->toArray();

        $res = $this->json('POST', '/api/game/'.$game->id.'/armies', $army);
        $res->assertJsonPath('errors.strategy', ["The strategy field is required."]);
        $res->assertStatus(422);
        $this->assertDatabaseMissing('armies', $army);
    }
	/** @test */
    public function it_throws_an_error_when_adding_an_army_if_strategy_does_not_exist()
    {
        $game = factory(Game::class)->create();
        $army = factory(Army::class)->make(["strategy" => "test"])->toArray();

        $res = $this->json('POST', '/api/game/'.$game->id.'/armies', $army);
        $res->assertStatus(422);
        $res->assertJsonPath('errors.strategy', ["The selected strategy is invalid."]);
        $this->assertDatabaseMissing('armies', $army);
    }
}
