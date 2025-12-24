<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Tenant;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'patient_id' => Patient::factory(),
            'visit_id' => Visit::factory(),
            'invoice_number' => $this->faker->unique()->bothify('INV-#####'),
            'date' => $this->faker->date(),
            'total_amount' => $this->faker->randomFloat(2, 100, 1000),
            'paid_amount' => 0,
            'balance' => function (array $attributes) {
                return $attributes['total_amount'];
            },
            'status' => 'draft',
            'created_by' => 'system',
        ];
    }
}
