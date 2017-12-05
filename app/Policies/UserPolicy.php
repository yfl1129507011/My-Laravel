<?php

namespace App\Policies;

use App\Models\User;
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

    public function update(User $currUser, User $user)
    {
      return $currUser->id === $user->id;
    }

    public function destroy(User $currUser, User $user)
    {
        return $currUser->is_admin && $currUser->id !== $user->id;
    }
}
