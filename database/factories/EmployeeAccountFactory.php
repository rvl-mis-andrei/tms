<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\EmployeeAccount;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeAccount>
 */
class EmployeeAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = EmployeeAccount::class;

    public function definition(): array
    {
        $employees = Employee::pluck('id');
        $empId = $this->faker->unique()->randomElement($employees);
        return [
            'emp_id' => $empId,
            'username' => $this->faker->unique()->userName,
            'password' => Hash::make('password123'), // Use Hash facade for password hashing
            'bypass_key' => $this->faker->optional()->word,
            'is_active' => $this->faker->boolean,
            'created_by' => 1, // Assuming 1 is a valid user ID
            'updated_by' => null, // Assuming 1 is a valid user ID
            'created_at' => now(),
            'updated_at' => null,
        ];
    }
}
