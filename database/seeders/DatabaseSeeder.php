<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Company;
use App\Models\CompanyLocation;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeAccount;
use App\Models\EmployeePosition;
use App\Models\Position;
use App\Models\TmsRole;
use App\Models\TmsUserRole;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Employee::factory()->count(50)->create();
        // EmployeeAccount::factory()->count(50)->create();

        // Position::factory()->count(10)->create();
        // Department::factory()->count(10)->create();
        // EmployeePosition::factory()->count(50)->create();

        // Company::factory()->count(10)->create();
        // CompanyLocation::factory()->count(10)->create();

        $roles = [
            'Dispatcher',
            'Planner',
            'System Administrator',
            'Cluster Head',
            'Manager',
            'Supervisor'
        ];

        foreach ($roles as $role) {
            TmsRole::updateOrCreate(
                ['name' => $role],
                ['is_active' => true, 'created_by' => 1, 'updated_by' => null]
            );
        }
        // TmsUserRole::factory()->count(50)->create();
    }
}
