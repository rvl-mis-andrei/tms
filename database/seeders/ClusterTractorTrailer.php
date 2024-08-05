<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClusterTractorTrailer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $id = 1;
        $array = [];
        for($id;$id<=32;$id++){
            $array[] =[
                'cluster_id' => 2,
                'tractor_trailer_id' => $id,
                'status' => 1
            ];
        }
        DB::table('tms_cluster_tractor_trailers')->insert($array);
    }
}
