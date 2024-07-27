<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemFileLayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Client List',
                'href' => 'client_list',
                'icon'=>'',
                'folder'=>null,
                'status'=>1,
                'created_by'=>1,
                'file_id'=>4,
            ],
            [
                'name' => 'Tractor Trailer List',
                'href' => 'tractor_trailer_list',
                'icon'=>'',
                'folder'=>null,
                'created_by'=>1,
                'status'=>1,
                'file_id'=>4,
            ],
            [
                'name' => 'Driver List',
                'href' => 'driver_list',
                'icon'=>'',
                'folder'=>null,
                'created_by'=>1,
                'status'=>1,
                'file_id'=>4,
            ],
            [
                'name' => 'Car List',
                'href' => 'car_list',
                'icon'=>'',
                'folder'=>null,
                'created_by'=>1,
                'status'=>1,
                'file_id'=>4,
            ],
            [
                'name' => 'Cycle Time',
                'href' => 'cycle_time',
                'icon'=>'',
                'folder'=>null,
                'created_by'=>1,
                'status'=>1,
                'file_id'=>4,
            ],
            [
                'name' => 'Location',
                'href' => 'location',
                'icon'=>'',
                'folder'=>null,
                'created_by'=>1,
                'status'=>1,
                'file_id'=>5,
            ],
            [
                'name' => 'Garage',
                'href' => 'garage',
                'icon'=>'',
                'folder'=>null,
                'created_by'=>1,
                'status'=>1,
                'file_id'=>5,
            ],
            [
                'name' => 'Car Color',
                'href' => 'car_color',
                'icon'=>'',
                'folder'=>null,
                'created_by'=>1,
                'status'=>1,
                'file_id'=>5,
            ],
            [
                'name' => 'Trailer Type',
                'href' => 'trailer_type',
                'icon'=>'',
                'folder'=>null,
                'created_by'=>1,
                'status'=>1,
                'file_id'=>5,
            ],
        ];
        DB::table('system_file_layers')->insert($data);
    }
}
