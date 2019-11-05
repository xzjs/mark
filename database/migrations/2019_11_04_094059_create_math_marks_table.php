<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMathMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('math_marks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('book_id');
            $table->string('scene')->comment('情景');
            $table->integer('communication')->comment('沟通');
            $table->integer('strategy')->comment('策略');
            $table->integer('mathematicization')->comment('数学化');
            $table->integer('symbol')->comment('符号');
            $table->integer('representation')->comment('表征');
            $table->integer('reasoning')->comment('推理论证');
            $table->string('knowledge')->comment('知识情景');
            $table->string('point')->comment('知识点');
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
        Schema::dropIfExists('math_marks');
    }
}
