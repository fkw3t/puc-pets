<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\Pet;
use App\Models\Schedule;
use App\Models\Vet;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduleControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function testUserWithoutPetCouldntScheduleService()
    {
        // arrange
        $client = User::factory()->create();
        $token = 'Bearer ' . auth()->login($client);
        $vet = Vet::factory()->create();
        $schedule = Schedule::factory()->create([
                'vet_id' => $vet->id
            ]);
        $id = $schedule->id;

        // act
        $response = $this->withHeader('Authorization', $token)
            ->post("/api/auth/schedule/{$schedule->id}/assign", [
            'client_id' => $client->id
        ]);

        // assertions
        $response->assertStatus(400)
            ->assertExactJson([
                'message' => 'User cannot schedule service without having a pet registered'
            ]);
    }

    public function testScheduleCanOnlyBeAssignedWhenItsOpen()
    {
        // arrange
        $pet = Pet::factory()->create();
        $client = $pet->owner;
        $token = 'Bearer ' . auth()->login($client);
        $vet = Vet::factory()->create();
        $schedule = Schedule::factory()->create([
                'vet_id' => $vet->id,
                'status' => 'pending'                
            ]);
        $id = $schedule->id;

        // act
        $response = $this->withHeader('Authorization', $token)
            ->post("/api/auth/schedule/{$schedule->id}/assign", [
            'client_id' => $client->id
        ]);

        // assertions
        $response->assertStatus(400)
            ->assertExactJson([
                'message' => 'This schedule cannot be assigned as its status is no longer open'
            ]);
    }
}
