<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Location extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['name' => 'LUZON', 'is_active' => 1],
            ['name' => 'METRO MANILA', 'is_active' => 1],
            ['name' => 'VISMIN', 'is_active' => 1],
            ['name' => 'BTG PORT', 'is_active' => 1],
            ['name' => 'MNL PORT', 'is_active' => 1],
        ];

        DB::table('tms_locations')->insert($locations);
    }
}
