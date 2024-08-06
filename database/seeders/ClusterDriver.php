<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClusterDriver extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($x=31;$x<=116;$x++){
        DB::table('tms_cluster_drivers')->insert([
            'emp_id' => $x,
            'cluster_id' => 2,
            'status' => 1,
            'created_by' => 1,
        ]);
        }
    }
}
