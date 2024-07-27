<?php

namespace Database\Factories;

use App\Models\EmployeePosition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeePosition>
 */
class EmployeePositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = EmployeePosition::class;
    public function definition(): array
    {
        return [
            'position_id' => \App\Models\Position::factory(), // Create a Position if not exists
            'department_id' => \App\Models\Department::factory(), // Create a Department if not exists
            'company_id' => $this->faker->numberBetween(1, 100), // Replace with valid company IDs or use a factory if you have a Company model
            'company_location_id' => $this->faker->numberBetween(1, 100), // Replace with valid company location IDs or use a factory if you have a CompanyLocation model
            'created_by' => $this->faker->optional()->randomNumber(), // Optionally link to a user ID
            'updated_by' => $this->faker->optional()->randomNumber(), // Optionally link to a user ID
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
