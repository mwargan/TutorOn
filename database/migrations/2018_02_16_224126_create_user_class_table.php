<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('user_class');
        Schema::create('user_class', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedInteger('class_id');
            $table->foreign('class_id')->references('id')->on('user_subject_pivot_table');

            $table->unique(['user_id', 'class_id']);

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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('user_class');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
