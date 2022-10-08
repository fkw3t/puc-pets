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
        return [
            'name' => fake()->firstName(),
            'owner_id' => User::factory(),
            'size' => array_rand([
                'small',
                'medium',
                'large'
            ], 1)[0],
            'type' => array_rand([
                'dog',
                'cat',
                'bird',
                'rat'
            ], 1)[0]
        ];
    }
}
