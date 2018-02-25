<?php

use Illuminate\Database\Seeder;
use App\Weekday;

class WeekdayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Weekday::create([
            'en_name' => 'Sunday'
        ]);
        Weekday::create([
            'en_name' => 'Monday'
        ]);
        Weekday::create([
            'en_name' => 'Tuesday'
        ]);
        Weekday::create([
            'en_name' => 'Wednesday'
        ]);
        Weekday::create([
            'en_name' => 'Thursday'
        ]);
        Weekday::create([
            'en_name' => 'Friday'
        ]);
        Weekday::create([
            'en_name' => 'Saturday'
        ]);
    }
}
