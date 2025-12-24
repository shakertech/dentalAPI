<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Dental Clinic',
            'title' => $this->faker->catchPhrase,
            'description' => $this->faker->sentence,
            'doctor_name' => 'Dr. ' . $this->faker->firstName . ' ' . $this->faker->lastName,
            'doctor_qualification' => 'BDS, MDS',
            'doctor_specialty' => 'General Dentist',
            'domain' => $this->faker->unique()->slug,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->companyEmail,
            'subscription_plan' => 'premium',
            'subscription_status' => 'active',
            'subscription_ends_at' => now()->addYear(),
        ];
    }
}
