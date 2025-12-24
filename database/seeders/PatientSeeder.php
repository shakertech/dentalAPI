<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Tenant;
use App\Models\Treatment;
use App\Models\Visit;
use App\Models\VisitEntry;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant = Tenant::first();
        if (!$tenant) {
            return;
        }

        $doctor = $tenant->users()->where('role', 'doctor')->first();

        // Create 3 Patients
        Patient::factory()
            ->count(3)
            ->create(['tenant_id' => $tenant->id])
            ->each(function ($patient) use ($tenant, $doctor) {

                // Create Appointment
                Appointment::factory()->create([
                    'tenant_id' => $tenant->id,
                    'patient_id' => $patient->id,
                    'user_id' => $doctor ? $doctor->id : null,
                ]);

                // Create Completed Visit
                $visit = Visit::factory()->create([
                    'tenant_id' => $tenant->id,
                    'patient_id' => $patient->id,
                    'status' => 'completed',
                    'date' => now()->subDays(rand(1, 30)),
                ]);

                // Add Entries
                $treatment = Treatment::inRandomOrder()->first();
                if ($treatment) {
                    VisitEntry::factory()->create([
                        'tenant_id' => $tenant->id,
                        'visit_id' => $visit->id,
                        'treatment_id' => $treatment->id,
                        'amount' => $treatment->default_price,
                    ]);

                    // Invoice
                    Invoice::factory()->create([
                        'tenant_id' => $tenant->id,
                        'patient_id' => $patient->id,
                        'visit_id' => $visit->id,
                        'total_amount' => $treatment->default_price,
                        'paid_amount' => $treatment->default_price,
                        'status' => 'paid',
                    ]);
                }
            });
    }
}
