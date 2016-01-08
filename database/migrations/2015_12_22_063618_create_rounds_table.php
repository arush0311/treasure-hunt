<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rounds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id');
            $table->integer('no');
            $table->integer('no_of_questions');
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');
            $table->integer('duration');
            $table->integer('cutoff')->nullable();
            $table->boolean('ranked')->default(false);
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
        Schema::drop('rounds');
    }
}
