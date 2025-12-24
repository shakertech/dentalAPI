<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\Tenant;
use App\Models\Treatment;
use Illuminate\Database\Seeder;

class ClinicalSeeder extends Seeder
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

        // 1. Seed Treatments
        $treatments = [
            ['name' => 'Consultation', 'default_cost' => 500, 'default_price' => 500],
            ['name' => 'Scaling & Polishing', 'default_cost' => 1500, 'default_price' => 2000],
            ['name' => 'Root Canal Treatment', 'default_cost' => 3000, 'default_price' => 5000],
            ['name' => 'Tooth Extraction', 'default_cost' => 1000, 'default_price' => 1500],
            ['name' => 'Teeth Whitening', 'default_cost' => 4000, 'default_price' => 8000],
            ['name' => 'Dental Filling', 'default_cost' => 1000, 'default_price' => 2000],
        ];

        foreach ($treatments as $data) {
            Treatment::factory()->create(array_merge($data, ['tenant_id' => $tenant->id]));
        }

        // 2. Seed Medicines
        $medicines = [
            ['name' => 'Amoxicillin 500mg', 'generic_name' => 'Amoxicillin', 'dosage_form' => 'Tablet', 'strength' => '500mg'],
            ['name' => 'Ibuprofen 400mg', 'generic_name' => 'Ibuprofen', 'dosage_form' => 'Tablet', 'strength' => '400mg'],
            ['name' => 'Paracetamol 500mg', 'generic_name' => 'Paracetamol', 'dosage_form' => 'Tablet', 'strength' => '500mg'],
            ['name' => 'Metronidazole 400mg', 'generic_name' => 'Metronidazole', 'dosage_form' => 'Tablet', 'strength' => '400mg'],
            ['name' => 'Chlorhexidine Mouthwash', 'generic_name' => 'Chlorhexidine', 'dosage_form' => 'Liquid', 'strength' => '0.2%'],
        ];

        foreach ($medicines as $data) {
            Medicine::factory()->create(array_merge($data, ['tenant_id' => $tenant->id]));
        }
    }
}
