<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(SpecialitiesSeeder::class);
        $this->call(UsersTableSeeder::class);
        //$this->call(ShiftTableSeeder::class);
        //$this->call(EarlyShiftTableSeeder::class);
        //$this->call(BiddingScheduleSeeder::class);
    }
}
