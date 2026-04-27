<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\CommissionNote;
use Spatie\Permission\Models\Permission;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BootSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Seed Permissions
       $permissions = [
        'view commission notes',
        'manage commission notes',
        'view companies',
        'manage companies',
       ];

       foreach ($permissions as $permission) {
           Permission::firstOrCreate(['name' => $permission]);
       }

        // Seed Users and Assign Permissions

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@axiomatic.co.za',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $admin->givePermissionTo(['view companies', 'manage companies', 'view commission notes', 'manage commission notes']);

        $viewer = User::factory()->create([
            'name' => 'Viewer User',
            'email' => 'viewer@axiomatic.co.za',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $viewer->givePermissionTo(['view companies', 'view commission notes']);

        // Seed Companies, Branches, Employees, and Commission Notes

        // Create Company
        $company = Company::factory()->create([
            'name' => 'Axiomatic Greenfield',
        ]);

        // Create Branches
        $jhb = Branch::factory()->create([
            'company_id' => $company->id,
            'name' => 'Johannesburg',
        ]);

        $natal = Branch::factory()->create([
            'company_id' => $company->id,
            'name' => 'KwaZulu-Natal',
        ]);

        // Create Employees
        $employees_jhb = Employee::factory()->count(2)->create([
            'company_id' => $company->id,
            'branch_id' => $jhb->id,
        ]);


        $employees_natal = Employee::factory()->count(2)->create([
            'company_id' => $company->id,
            'branch_id' => $natal->id,
        ]);

        // Create Commission Notes
        foreach ($employees_jhb as $employee) {
            CommissionNote::factory()->create([
                'company_id' => $company->id,
                'branch_id' => $jhb->id,
                'employee_id' => $employee->id,
                'author_id' => $admin->id,
                'amount' => '10000.00',
            ]);
        }
        foreach ($employees_natal as $employee) {
            CommissionNote::factory()->create([
                'company_id' => $company->id,
                'branch_id' => $natal->id,
                'employee_id' => $employee->id,
                'author_id' => $admin->id,
                'amount' => '20000.00',
            ]);
        }
    }
}
