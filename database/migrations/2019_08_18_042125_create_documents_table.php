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
            $table->string('title');
            $table->string('introduce');
            $table->string('img');
            $table->string('answer');
            $table->string('tip');
            $table->integer('subject')->comment('学科');
            $table->integer('think')->comment('思维属性');
            $table->integer('think_difficulty')->comment('思维难度');
            $table->integer('ability')->comment('能力');
            $table->integer('ability_difficulty')->comment('能力难度');
            $table->integer('knowledge')->comment('知识');
            $table->integer('knowledge_difficulty')->comment('知识难度');
            $table->string('place');
            $table->string('scene');
            $table->string('character');
            $table->string('tool')->comment('道具');
            $table->integer('problem')->comment('问题承载');
            $table->integer('result')->comment('收获习得');
            $table->integer('user_id');
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
