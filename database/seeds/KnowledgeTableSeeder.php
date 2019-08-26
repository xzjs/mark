<?php

use Illuminate\Database\Seeder;

class KnowledgeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = ['数学推理', '空间想象'];
        foreach ($subjects as $subject) {
            DB::table('knowledge')->insert(['name' => $subject]);
        }
    }
}
