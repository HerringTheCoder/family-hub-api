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
            $table->string('type')->default('undefined');
            $table->string('stream_direction'); /* defines graph relation from point A to B for graphical implementation : i.e.
             non-directional horizontal(siblings, spouses),  non-d* diagonal (non-biological children), directional vertical (children), reversed directional vertical (parents)    */
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
        Schema::dropIfExists('relations');
    }
}
