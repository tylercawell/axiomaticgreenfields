<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Employee;


class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $branches = Branch::all();

        foreach ($branches as $branch) {

            // Create a few employees per branch
            Employee::factory()->count(3)->create([
                'branch_id' => $branch->id,
            ]);
        }

    }
}
