<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('pt_BR');
        $rand = rand(0,1);
        
        if($rand === 0){
            $document_id = $faker->cpf;
            $person_type = 'fisical';
        }
        else{
            $document_id = $faker->cnpj;
            $person_type = 'legal';
        }

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'document_id' => $document_id,
            'person_type' => $person_type,
            'phone' => fake()->phoneNumber(),
            'email_verified_at' => now(),
            'password' => 'test123', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
