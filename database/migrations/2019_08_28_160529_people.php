<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class People extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('height');
            $table->integer('mass');
            $table->string('hair_color')->nullable();
            $table->string('skin_color');
            $table->string('eye_color');
            $table->string('birth_year')->nullable();
            $table->string('gender')->nullable();
            $table->string('homeworld')->nullable();
            $table->string('api_url');
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('people');
    }
}
