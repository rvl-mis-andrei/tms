<?php

namespace Database\Factories;

use App\Models\TmsRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TMSRole>
 */
class TMSRoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = TmsRole::class;
    public function definition(): array
    {
        $roles = [
            'Dispatcher',
            'Planner',
            'System Administrator',
            'Cluster Head',
            'Manager',
            'Supervisor'
        ];

        return [
            'name' => $this->faker->randomElement($roles), // Select a random role name
            'is_active' => 1, // Use boolean for active status
            'created_by' => $this->faker->optional()->randomNumber(), // Optionally link to a user ID
            'updated_by' => $this->faker->optional()->randomNumber(), // Optionally link to a user ID
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
