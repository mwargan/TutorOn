<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_days', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lesson_id');
            $table->foreign('lesson_id')->references('id')->on('user_class');
            $table->unsignedInteger('weekday_id');
            $table->foreign('weekday_id')->references('id')->on('weekdays');
            $table->time('meeting_time');
            $table->time('lesson_duration')->nullable();
            $table->unsignedInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('users');

            $table->unique(['lesson_id', 'weekday_id', 'meeting_time']);

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
        Schema::dropIfExists('lesson_days');
    }
}
