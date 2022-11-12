<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $size = [
            'small',
            'medium',
            'large'
        ];

        return [
            'name' => fake()->firstName(),
            'owner_id' => User::factory(),
            'size' => $size[array_rand($size, 1)],
            'birth_date' => fake()->date('Y-m-d')
        ];
    }
}
