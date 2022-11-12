<?php

namespace Tests\Feature\app\Http\Controllers;

use DateTime;
use App\Models\Pet;
use App\Models\Vet;
use Tests\TestCase;
use App\Models\User;
use App\Models\Schedule;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use App\Notifications\Schedules\Canceled;
use App\Notifications\Schedules\Confirmed;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Schedules\Confirmation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduleControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_without_pet_couldnt_schedule_service(): void
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

    public function test_schedule_can_only_be_assigned_when_its_open(): void
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

    public function test_client_should_receive_confirmation_notification(): void
    {
        // arrange
        Notification::fake();
        $schedule = Schedule::factory()->create();
        $token = 'Bearer ' . auth()->login($schedule->client);

        // act
        $response = $this->withHeader('Authorization', $token)
            ->post("/api/auth/schedule/{$schedule->id}/assign", [
            'client_id' => $schedule->client->id
        ]);

        // assert
        $response->assertStatus(200);
        Notification::assertSentTo($schedule->client, Confirmation::class);
    }

    public function test_client_and_vet_should_receive_notification_that_appointment_schedule_is_confirmed(): void
    {
        // arrange
        Notification::fake();
        $schedule = Schedule::factory()->create([
            'status' => 'pending'
        ]);
        $url = URL::temporarySignedRoute(
            'schedule.confirm', now()->addMinutes(60), ['id' => $schedule->id]
        );

        // act
        $response = $this->get($url);

        // assert
        $response->assertStatus(200);
        Notification::assertSentTo(
            [
                $schedule->client,
                $schedule->vet
            ], Confirmed::class
        );
    }

    public function test_client_and_vet_should_receive_notification_that_appointment_schedule_is_canceled(): void
    {
        // arrange
        Notification::fake();
        $schedule = Schedule::factory()->create([
            'status' => 'confirmed'
        ]);
        $token = 'Bearer ' . auth()->login($schedule->client);

        // act
        $response = $this->withHeader('Authorization', $token)
            ->post("/api/auth/schedule/{$schedule->id}/cancel");

        // assert
        $response->assertStatus(200);
        Notification::assertSentTo(
            [
                $schedule->client,
                $schedule->vet
            ], Canceled::class
        );
    }
}
