<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComputersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('computers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url')->comment('网址');
            $table->text('images')->comment('图片');
            $table->string('point')->comment('知识点');
            $table->text('target')->comment('目标');
            $table->text('contents')->comment('内容');
            //pisa
            $table->integer('communication')->comment('沟通');
            $table->integer('strategy')->comment('策略');
            $table->integer('mathematicization')->comment('数学化');
            $table->integer('symbol')->comment('符号');
            $table->integer('representation')->comment('表征');
            $table->integer('reasoning')->comment('推理论证');
            //计算思维
            $table->integer('abstract')->comment('抽象');
            $table->integer('summarize')->comment('总结');
            $table->integer('disassemble')->comment('分解');
            $table->integer('assessment')->comment('评估');
            //算法思维
            $table->integer('distinguish')->comment('辨明');
            $table->integer('understand')->comment('理解');
            $table->integer('improvement')->comment('改进');
            $table->integer('transformation')->comment('转换');

            $table->string('programThink')->comment('编程思维');
            $table->string('user_id')->comment('用户id');
            $table->integer('cost')->comment('花费时间');
            $table->softDeletes();
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
        Schema::dropIfExists('computers');
    }
}
