<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/game', 'GameController@index');
Route::middleware(['active.games'])->post('/game', 'GameController@store');
Route::post('/game/{game}/armies', 'GameController@addArmy');
Route::post('/game/{game}/attack', 'GameController@runAttack');
Route::put('/game/{game}/reset', 'GameController@resetGame');
