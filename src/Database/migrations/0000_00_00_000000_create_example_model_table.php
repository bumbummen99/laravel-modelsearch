<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExampleModelTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('examplemodel', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->boolean('label_one')->default(false);
            $table->boolean('label_two')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('examplemodel');
    }
}
