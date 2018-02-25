<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSubjectLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subject_languages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_subject_id');
            $table->foreign('user_subject_id')->references('id')->on('user_subject_pivot_table');
            $table->unsignedInteger('language_id');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->unique(['user_subject_id', 'language_id']);
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
        Schema::dropIfExists('user_subject_languages');
    }
}
