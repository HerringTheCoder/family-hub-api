<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('partner_1_id')->unsigned()->nullable();
            $table->foreign('partner_1_id')->references('id')->on('users');
            $table->bigInteger('partner_2_id')->unsigned()->nullable();
            $table->foreign('partner_2_id')->references('id')->on('users');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->timestamps();
        });

        
        
       Schema::table('relations', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('relations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relations');
    }
}
