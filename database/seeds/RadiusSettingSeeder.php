<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RadiusSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('radius_settings')->insert([
            'key' => 'store_search_radius',
            'value' => '5',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
