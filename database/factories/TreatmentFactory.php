<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Treatment>
 */
class TreatmentFactory extends Factory
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
            'name' => $this->faker->words(2, true),
            'code' => $this->faker->unique()->bothify('###??'),
            'default_cost' => $this->faker->randomFloat(2, 100, 1000),
            'default_price' => $this->faker->randomFloat(2, 150, 1500),
            'created_by' => 'system',
        ];
    }
}
