<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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

    public function update(User $user, User $target): bool
    {
        return $user->id === $target->id;
    }

    public function destroy(User $user, User $target): bool
    {
        return $user->id === $target->id;
    }
}
