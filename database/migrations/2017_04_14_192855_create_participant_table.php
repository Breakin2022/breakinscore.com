<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('country')->nullable();
            $table->string('email')->default('')->nullable();
            $table->string('phone')->default('')->nullable();
            $table->string('nick')->default('')->nullable();
            $table->string('address')->default('')->nullable();
            $table->string('join_date')->default('')->nullable();
            $table->string('color')->default('')->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participant');
    }
}
