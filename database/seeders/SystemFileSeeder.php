<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Schema::create('system_files', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name')->nullable();
        //     $table->string('href')->nullable();
        //     $table->string('icon')->nullable();
        //     $table->string('folder')->nullable();
        //     $table->tinyInteger('status')->nullable();
        //     $table->unsignedBigInteger('created_by')->nullable();
        //     $table->unsignedBigInteger('updated_by')->nullable();
        //     $table->timestamp('created_at')->useCurrent();
        //     $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
        // });
        $data = [
            [
                'name' => 'Dashboard',
                'href' => 'dashboard',
                'icon'=>'',
                'folder'=>null,
                'created_by'=>1,
                'status'=>1,
            ],
            [
                'name' => 'Dispatch',
                'href' => 'dispatch',
                'icon'=>'',
                'folder'=>null,
                'created_by'=>1,
                'status'=>1,
            ],
            [
                'name' => 'Reports',
                'href' => 'reports',
                'icon'=>'',
                'folder'=>null,
                'created_by'=>1,
                'status'=>1,
            ],
            [
                'name' => 'Resources',
                'href' => 'resources',
                'icon'=>'',
                'folder'=>'resources',
                'created_by'=>1,
                'status'=>1,
            ],
            [
                'name' => 'Settings',
                'href' => 'settings',
                'icon'=>'',
                'folder'=>null,
                'created_by'=>1,
                'status'=>1,
            ],
            [
                'name' => 'FAQ',
                'href' => 'faq',
                'icon'=>'',
                'folder'=>null,
                'created_by'=>1,
                'status'=>1,
            ],
            [
                'name' => 'Hauling Plan',
                'href' => 'hauling_plan',
                'icon'=>'',
                'folder'=>'hauling_plan',
                'created_by'=>1,
                'status'=>1,
            ],
        ];

        //Client List
        //Client Details

        DB::table('system_files')->insert($data);
    }
}
