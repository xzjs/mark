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
            $table->integer('pageNumber');
            $table->string('introduce');
            $table->string('img');
            $table->text('supplementImages');
            $table->string('answer');
            $table->string('tip');
            $table->string('subject');
            $table->string('think');
            $table->string('ability');
            $table->string('knowledge');
            $table->string('place');
            $table->string('scene');
            $table->string('character');
            $table->string('tool')->comment('道具');
            $table->string('problem')->comment('问题承载');
            $table->string('result')->comment('收获习得');
            $table->integer('user_id');
            $table->integer('cost');
            $table->timestamps();
            $table->softDeletes();
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
