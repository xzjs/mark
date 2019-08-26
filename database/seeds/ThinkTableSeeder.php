<?php

use Illuminate\Database\Seeder;

class ThinkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = ['逻辑思维', '抽象思维', '计算思维', '分布思维'];
        foreach ($subjects as $subject) {
            DB::table('thinks')->insert(['name' => $subject]);
        }
    }
}
