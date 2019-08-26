<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('book_id');
            $table->string('introduce');
            $table->string('img');
            $table->string('answer');
            $table->string('tip');
            $table->string('place');
            $table->string('scene');
            $table->string('character');
            $table->string('tool')->comment('道具');
            $table->integer('problem')->comment('问题承载');
            $table->integer('result')->comment('收获习得');
            $table->integer('user_id');
            $table->integer('start_at');
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
        Schema::dropIfExists('documents');
    }
}
