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
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('avatar')->nullable();
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
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create($name.'_relations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('partner_1_id')->unsigned()->nullable();
            $table->foreign('partner_1_id')->references('id')->on('users');
            $table->bigInteger('partner_2_id')->unsigned()->nullable();
            $table->foreign('partner_2_id')->references('id')->on('users');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->timestamps();
        });
        
        Schema::table($name.'_relations', function (Blueprint $table) use ($name) {
            $table->foreign('parent_id')->references('id')->on($name.'_relations');
        });

        Schema::create($name.'_pivot', function (Blueprint $table)  {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

    }
}
