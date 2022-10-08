<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\Schedule;
use App\Models\Vet;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduleControllerTest extends TestCase
{

    # TODO
    public function testUserWithoutPetCouldntScheduleService()
    {
        // arrange
        $client = User::factory()->make();
        $vet = Vet::factory()->make();
        $schedule = Schedule::factory()->make([
                'vet_id' => $vet->id
            ]);

        // act
        $response = $this->post('/api/auth/schedule/{$schedule->id}/apply', [
            'client_id' => $client->id
        ]);

        // assertions
        $response->assertStatus(400)
            ->assertExactJson([
                'message' => ['User cannot schedule service without having a pet registered.']
            ]);
    }
}
