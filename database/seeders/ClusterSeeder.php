<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClusterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['name' => 'Cluster A', 'is_active' => 1,'head_id' => 3],
            ['name' => 'Cluster B', 'is_active' => 1,'head_id' => 3],
            ['name' => 'Cluster C', 'is_active' => 1,'head_id' => 3],
            ['name' => 'Cluster D', 'is_active' => 1,'head_id' => 3],
        ];

        DB::table('tms_clusters')->insert($locations);
    }
}
