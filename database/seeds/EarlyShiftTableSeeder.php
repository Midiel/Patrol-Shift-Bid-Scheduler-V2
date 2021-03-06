<?php

use Illuminate\Database\Seeder;
use App\Models\EarlyShift;
use App\Models\Shift;

class EarlyShiftTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Trunkate the databse so we don't repeat the seed
        DB::table('early_shifts')->delete();

        EarlyShift::create([
            'shift_id' => Shift::where('name', 'A')->get(['id'])->pluck('id')->first(),
            'early_start_time' => '05:00:00',
            'early_end_time' => '13:00:00',
            'num_early_spot' => '3',
        ]);

        EarlyShift::create([
            'shift_id' => Shift::where('name', 'B')->get(['id'])->pluck('id')->first(),
            'early_start_time' => '13:00:00',
            'early_end_time' => '21:00:00',
            'num_early_spot' => '3',
        ]);

        EarlyShift::create([
            'shift_id' => Shift::where('name', 'C')->get(['id'])->pluck('id')->first(),
            'early_start_time' => '21:00:00',
            'early_end_time' => '05:00:00',
            'num_early_spot' => '3',
        ]);

        
        // factory(EarlyShift::class, 3)->create();
    }
}
