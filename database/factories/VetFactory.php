<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vet>
 */
class VetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $specialization = [
            'Swine health management',
            'Canine and feline',
            'Reptile amphibian'
        ];

        $rnd = array_rand($specialization, 1);

        return [
            'user_id' => User::factory(),
            'crm' => '012345',
            'specialization' => 'asd'
        ];
    }
}
