<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//this is example of one of many multitenant schemas (familyname_affinities)
class CreateAffinitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affinities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('first_user_id')->unsigned();
            $table->foreign('first_user_id')->references('id')->on('users');
            $table->bigInteger('second_user_id')->unsigned();
            $table->foreign('second_user_id')->references('id')->on('users');
            $table->bigInteger('relation_id')->unsigned();
            $table->foreign('relation_id')->references('id')->on('relations');
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
        Schema::dropIfExists('affinities');
    }
}
