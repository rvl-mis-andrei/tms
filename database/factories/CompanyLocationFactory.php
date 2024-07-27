<?php

namespace Database\Factories;

use App\Models\CompanyLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyLocation>
 */
class CompanyLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = CompanyLocation::class;
    public function definition(): array
    {
        return [
            'company_id' => \App\Models\Company::factory(), // Create a Company if not exists
            'name' => $this->faker->city,
            'description' => $this->faker->optional()->text,
            'created_by' => $this->faker->optional()->randomNumber(), // Optionally link to a user ID
            'updated_by' => $this->faker->optional()->randomNumber(), // Optionally link to a user ID
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
