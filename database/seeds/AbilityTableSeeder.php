<?php

use Illuminate\Database\Seeder;

class AbilityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = ['观察力', '分析力', '实践力'];
        foreach ($subjects as $subject) {
            DB::table('abilities')->insert(['name' => $subject]);
        }
    }
}
