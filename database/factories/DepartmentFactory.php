<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'is_active' => $this->faker->boolean,
            'created_by' => $this->faker->optional()->randomNumber(), // Optionally link to a user ID
            'updated_by' => $this->faker->optional()->randomNumber(), // Optionally link to a user ID
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
