<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
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
            'user_id' => User::factory(), // Doctor
            'start_time' => $this->faker->dateTimeBetween('now', '+1 month'),
            'end_time' => function (array $attributes) {
                return \Carbon\Carbon::parse($attributes['start_time'])->addMinutes(30);
            },
            'status' => 'scheduled',
            'notes' => $this->faker->sentence,
            'created_by' => 'system',
        ];
    }
}
