<?php

use Illuminate\Database\Seeder;

class SubjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = ['数学', '编程', 'AI'];
        foreach ($subjects as $subject) {
            DB::table('subjects')->insert(['name' => $subject]);
        }
    }
}
