<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('day_id');
            $table->string('title');
            $table->text('description');
            $table->string('videoURL')->nullable();
            $table->boolean('video')->default(false);
            $table->string('videoBackgroundImage')->nullable();
            $table->string('slug')->nullable();
            $table->string('videoBackgroundImageURL')->nullable();
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
        Schema::dropIfExists('agendas');
    }
}
