<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\Treatment;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VisitEntry>
 */
class VisitEntryFactory extends Factory
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
            'visit_id' => Visit::factory(),
            'treatment_id' => Treatment::factory(),
            'entry_type' => 'charge',
            'amount' => $this->faker->randomFloat(2, 50, 500),
            'description' => $this->faker->sentence,
            'tooth_no' => (string) $this->faker->numberBetween(1, 32),
            'created_by' => 'system',
        ];
    }
}
