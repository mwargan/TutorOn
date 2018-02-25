<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;


class CreateUserSubjectPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('user_subject_pivot_table');
        Schema::create('user_subject_pivot_table', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedInteger('subject_id');

            $table->unique(['user_id', 'subject_id']);

            $table->integer('price');
            $table->text('description');

            $table->timestamp('active')->nullable()->useCurrent();

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
        Schema::dropIfExists('user_subject_pivot_table');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
