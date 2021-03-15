<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
// add_dob_to_participants
class AddDOBToPARTICIPANTS extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::table('participant', function($table) {
             $table->string('dob');
         });
     }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('participant', function($table) {
        $table->dropColumn('dob');
      });

    }
}
