<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetitionVenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competitionVenues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('address')->default('')->nullable();
            $table->string('phone')->default('')->nullable();
            $table->string('type');
            $table->string('round')->default('0');
            $table->string('topTeamChoice')->default('0');
            $table->string('start_date');
            $table->string('winnerTeamId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competitionVenues');
    }
}
