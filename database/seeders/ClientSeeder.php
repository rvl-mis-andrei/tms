<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tms_clients')->insert([
            ['name' => 'TOYOTA', 'is_active' => 1],
            ['name' => 'LEXUS', 'is_active' => 1],
            ['name' => 'ATI', 'is_active' => 1],
            ['name' => 'BIPI', 'is_active' => 1],
            ['name' => 'FUJI', 'is_active' => 1],
            ['name' => '2GO', 'is_active' => 1],
            ['name' => 'PSACC CY7', 'is_active' => 1],
            ['name' => 'MERIDIAN', 'is_active' => 1],
            ['name' => 'MORETA CY2', 'is_active' => 1],
            ['name' => 'VITAS CY', 'is_active' => 1],
        ]);

    }
}
