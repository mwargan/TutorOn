<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lesson_id');
            $table->foreign('lesson_id')->references('id')->on('user_class');
            $table->point('location');

            $table->unsignedInteger('postal_code_id')->nullable();
            $table->foreign('postal_code_id')->references('id')->on('postal_codes');

            $table->unsignedInteger('locality_id');
            $table->foreign('locality_id')->references('id')->on('localities');

            $table->unsignedInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('users');

            $table->unique('lesson_id');
            $table->spatialIndex('location');

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
        Schema::dropIfExists('lesson_locations');
    }
}
