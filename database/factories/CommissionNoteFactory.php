<?php

namespace Database\Factories;

use App\Models\CommissionNote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CommissionNote>
 */
class CommissionNoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => \App\Models\Company::factory(),
            'branch_id' => \App\Models\Branch::factory(),
            'employee_id' => \App\Models\Employee::factory(),
            'author_id' => \App\Models\User::factory(),
            'amount' => fake()->randomFloat(2, 1000, 50000),
            'note' => 'Commission for ' . fake()->monthName() . ' ' . fake()->year(),
        ];
    }
}
