<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PersonFilmPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_film_pivot', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('person_id')->comment('belongsTo people table id');
            $table->integer('film_id')->comment('belongsTo films table id');
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
        Schema::dropIfExists('person_film_pivot');
    }
}
