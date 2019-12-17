<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('game_id')->unsigned();
            $table->bigInteger('attacker_id')->unsigned();
            $table->bigInteger('defender_id')->unsigned();
            $table->integer('damage');
            $table->boolean('is_destroied')->default(false);
            $table->timestamps();
            $table->foreign('game_id')->references('id')->on('games');
            $table->foreign('attacker_id')->references('id')->on('armies');
            $table->foreign('defender_id')->references('id')->on('armies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('turns');
    }
}
