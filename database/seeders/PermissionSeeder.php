<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view companies',
            'create companies',
            'manage companies',
            'delete companies',

            'view branches',
            'create branches',
            'manage branches',
            'delete branches',

            'view employees',
            'create employees',
            'manage employees',
            'delete employees',

            'view commission notes',
            'create commission notes',
            'manage commission notes',
            'delete commission notes',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
