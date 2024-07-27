<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Company::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'description' => $this->faker->text,
            'is_active' => $this->faker->boolean,
            'created_by' => $this->faker->optional()->randomNumber(), // Optionally link to a user ID
            'updated_by' => $this->faker->optional()->randomNumber(), // Optionally link to a user ID
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
