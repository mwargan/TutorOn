<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->point('location');

            $table->unsignedInteger('postal_code_id')->nullable();
            $table->foreign('postal_code_id')->references('id')->on('postal_codes');

            $table->unsignedInteger('locality_id');
            $table->foreign('locality_id')->references('id')->on('localities');

            $table->unique('user_id');
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
        Schema::dropIfExists('user_locations');
    }
}
