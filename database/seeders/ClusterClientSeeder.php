<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClusterClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tms_cluster_clients')->insert([
            ['cluster_id' => 2, 'client_id' => 1,'is_active' => 1],
            ['cluster_id' => 2, 'client_id' => 2,'is_active' => 1],
            ['cluster_id' => 2, 'client_id' => 3,'is_active' => 1],
            ['cluster_id' => 2, 'client_id' => 4,'is_active' => 1],
            ['cluster_id' => 2, 'client_id' => 5,'is_active' => 1],
            ['cluster_id' => 2, 'client_id' => 6,'is_active' => 1],
            ['cluster_id' => 2, 'client_id' => 7,'is_active' => 1],
            ['cluster_id' => 2, 'client_id' => 8,'is_active' => 1],
            ['cluster_id' => 2, 'client_id' => 9,'is_active' => 1],
            ['cluster_id' => 2, 'client_id' => 10,'is_active' => 1],
        ]);
    }
}
