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
        for($x=1;$x<=30;$x++){
        DB::table('tms_cluster_drivers')->insert([
            'emp_id' => $x,
            'cluster_id' => 2,
            'is_active' => 1,
            'created_by' => 1,
        ]);
        }
    }
}
