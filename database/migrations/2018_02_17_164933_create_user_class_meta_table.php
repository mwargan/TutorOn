<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserClassMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_class_meta', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->unsignedInteger('class_id');
            $table->foreign('class_id')->references('id')->on('user_class');

            $table->string('key')->index();
            $table->text('value');
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
        Schema::dropIfExists('user_class_meta');
    }
}
