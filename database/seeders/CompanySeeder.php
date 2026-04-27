<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Facades\Crypt;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            'Axiomatic Greenfield',
            'IC Distrubutors',
            'Umbrella Co.',
        ];

        foreach ($companies as $company) {
            Company::firstOrCreate([
                    'name' => $company
                ]);
        }

        $branches = [
            'Axiomatic Greenfield - Head Office',
            'Axiomatic Greenfield - Natal',
            'Axiomatic Greenfield - Cape Town',
        ];

        foreach ($branches as $branch) {
            Branch::firstOrCreate([
                'company_id' => 1,
                'name' => $branch,
            ]);
        }
        
        $branches = [
            'IC Distrubutors - Head Office',
            'IC Distrubutors - Natal',
            'IC Distrubutors - Cape Town',
        ];

        foreach ($branches as $branch) {
            Branch::firstOrCreate([
                'company_id' => 2,
                'name' => $branch,
            ]);
        }
        
        $branches = [
            'Umbrella Co. - Head Office',
            'Umbrella Co. - Natal',
            'Umbrella Co. - Cape Town',
        ];

        foreach ($branches as $branch) {
            Branch::firstOrCreate([
                'company_id' => 3,
                'name' => $branch,
            ]);
        }
    }
}
