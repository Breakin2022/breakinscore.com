<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('judgeId');
            $table->string('participantId');
            $table->string('matchId')->nullable();
            $table->string('teamId')->nullable();
            $table->string('score');
            $table->string('competitionId')->nullable();
            $table->string('roundNo')->nullable();

            // $table->string('update_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scores');
    }
}
