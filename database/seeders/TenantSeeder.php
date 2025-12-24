<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Default Tenant
        $tenant = Tenant::factory()->create([
            'domain' => 'demo',
            'name' => 'Dental Care Clinic',
            'email' => 'contact@dentalcare.com',
        ]);

        // 2. Create Admin User
        User::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Clinic Admin',
            'email' => 'admin@dental.com',
            'role' => 'admin',
        ]);

        // 3. Create Doctor User
        User::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Dr. Smith',
            'email' => 'doctor@dental.com',
            'role' => 'doctor',
        ]);
    }
}
