<?php
namespace App\Services;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class TableService
{
   

    public function addTables($name) : void
    {
        Schema::create($name.'_members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('family_id')->unsigned();
            $table->foreign('family_id')->references('id')->on('families'); 
            $table->string('first_name')->default('');
            $table->string('middle_name')->default('');
            $table->string('last_name')->default('');
            $table->date('day_of_birth')->nullable()->default(null);
            $table->date('day_of_death')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::create($name.'_news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('author_id')->unsigned();
            $table->foreign('author_id')->references('id')->on('users');
            $table->string('title');
            $table->string('description');
            $table->timestamps();
        });

        
        Schema::create($name.'_gallery', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('author_id')->unsigned();
            $table->foreign('author_id')->references('id')->on('users');
            $table->string('filename');
            $table->string('mime');
            $table->string('original_filename');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create($name.'_affinities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('first_user_id')->unsigned();
            $table->foreign('first_user_id')->references('id')->on('users');
            $table->bigInteger('second_user_id')->unsigned();
            $table->foreign('second_user_id')->references('id')->on('users');
            $table->bigInteger('relation_id')->unsigned();
            $table->foreign('relation_id')->references('id')->on('relations');
            $table->timestamps();
        });

        Schema::create($name.'_pivot', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

    }
}
