<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medicine>
 */
class MedicineFactory extends Factory
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
            'name' => $this->faker->word . ' ' . $this->faker->randomElement(['500mg', '250mg', '10ml']),
            'generic_name' => $this->faker->word,
            'dosage_form' => $this->faker->randomElement(['Tablet', 'Capsule', 'Syrup', 'Injection']),
            'strength' => $this->faker->randomElement(['500mg', '250mg', '100mg', '10ml']),
            'created_by' => 'system',
        ];
    }
}
