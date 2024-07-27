<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\TmsRole;
use App\Models\TmsUserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TMSUserRole>
 */
class TMSUserRoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = TmsUserRole::class;
    public function definition(): array
    {
        // $roles = [
        //     1, //'Dispatcher'
        //     2, //'Planner'=>
        //     3, //'System Administrator'
        //     4, //'Cluster Head'
        //     5, //'Manager'
        //     6 // 'Supervisor'
        // ];
        $roles = TmsRole::pluck('id');
        $employees = Employee::pluck('id');
        $empId = $this->faker->unique()->randomElement($employees);

        return [
            'emp_id' => $empId, // Create an Employee if not exists
            'role_id' =>  $this->faker->randomElement($roles),     // Create a Role if not exists
            'is_active' => $this->faker->boolean, // Use boolean for active status
            'created_by' => $this->faker->optional()->randomNumber(), // Optionally link to a user ID
            'updated_by' => $this->faker->optional()->randomNumber(), // Optionally link to a user ID
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
