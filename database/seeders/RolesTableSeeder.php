<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin role
        Role::create([
            'name' => 'Administrator',
            'slug' => 'admin'
        ]);

        // Create doctor role
        Role::create([
            'name' => 'Doctor',
            'slug' => 'doctor'
        ]);

        // Create patient role
        Role::create([
            'name' => 'Patient',
            'slug' => 'patient'
        ]);
    }
}
