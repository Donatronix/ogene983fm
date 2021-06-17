<?php

use App\Models\Programme\ProgrammeDay;
use Illuminate\Database\Seeder;

class ProgrammeDaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProgrammeDay::create(['day' => 'sunday']);
        ProgrammeDay::create(['day' => 'monday']);
        ProgrammeDay::create(['day' => 'tuesday']);
        ProgrammeDay::create(['day' => 'wednesday']);
        ProgrammeDay::create(['day' => 'thursday']);
        ProgrammeDay::create(['day' => 'friday']);
        ProgrammeDay::create(['day' => 'saturday']);
    }
}
