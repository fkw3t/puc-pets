<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PetPolicy
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

    public function update(User $user, Pet $pet): bool
    {
        return $user->id === $pet->owner_id;
    }

    public function destroy(User $user, Pet $pet): bool
    {
        return $user->id === $pet->owner_id;
    }
}
