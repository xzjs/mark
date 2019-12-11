<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCriticalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('criticals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('text')->comment('文字');
            $table->text('images')->comment('图片');
            $table->text('analysis')->comment('分析');
            $table->integer('user_id')->comment('用户id');
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
        Schema::dropIfExists('criticals');
    }
}
