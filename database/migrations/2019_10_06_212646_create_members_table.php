<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//this is example of one of many multitenant schemas (familyname_memmbers)
class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('family_id')->unsigned();
            $table->foreign('family_id')->references('id')->on('families'); 
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->date('day_of_birth');
            $table->date('day_of_death')->nullable(); //nullable added because of the error General error: 1364 Field 'day_of_death' doesn't have a default value
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
