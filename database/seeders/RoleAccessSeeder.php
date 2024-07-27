<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['role_id' => 1,'file_id' =>1, 'is_active' => 1],
            ['role_id' => 1,'file_id' =>2, 'is_active' => 1],
            ['role_id' => 1,'file_id' =>3, 'is_active' => 1],
            ['role_id' => 1,'file_id' =>4, 'is_active' => 1],
            ['role_id' => 1,'file_id' =>5, 'is_active' => 1],
        ];

        DB::table('tms_role_access')->insert($locations);
    }
}
