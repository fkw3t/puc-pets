<?php

namespace App\Policies;

use App\Models\Vet;
use App\Models\User;
use App\Models\Schedule;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchedulePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function cancel(User $user, Schedule $target): bool
    {
        $vet = Vet::where('client_id', $user->id);
        if($vet){
            return $vet->id = $target->vet_id;
        }

        return $user->id = $target->client_id;
    }

    public function update(User $user, Schedule $target): bool
    {
        $vet = Vet::where('user_id', $user->id);
        return $vet->id === $target->vet_id;
    }

    public function destroy(User $user, Schedule $target): bool
    {
        $vet = Vet::where('user_id', $user->id);
        return $vet->id === $target->vet_id;
    }
}
