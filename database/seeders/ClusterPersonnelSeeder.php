<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClusterPersonnelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['cluster_id' => 2, 'is_active' => 1,'emp_id' => 35],
        ];
        DB::table('tms_cluster_personnels')->insert($data);
    }

}
