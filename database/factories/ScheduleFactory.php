<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\Vet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'client_id' => User::factory()
                ->has(Pet::factory()),
            'vet_id' => Vet::factory(),
            'status' => 'open',
            'date' => $this->faker->dateTimeBetween('+1 days', '+1 month')
        ];
    }
}
