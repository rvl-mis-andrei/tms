<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Employee::class;
    public function definition(): array
    {
        return [
            'fname' => $this->faker->firstName,
            'mname' => $this->faker->firstName,
            'lname' => $this->faker->lastName,
            'ext' => $this->faker->suffix,
            'title' => $this->faker->title,
            'is_active' => 1,
            'created_by' => 1, // Adjust this as needed
            'updated_by' => null, // Adjust this as needed
            'created_at' => now(),
            'updated_at' => null,
        ];
    }
}
