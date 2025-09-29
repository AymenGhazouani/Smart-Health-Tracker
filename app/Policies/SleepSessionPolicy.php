<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SleepSession;
use Illuminate\Auth\Access\HandlesAuthorization;

class SleepSessionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    public function view(User $user, SleepSession $sleepSession)
    {
        return $user->isAdmin() || $sleepSession->user_id === $user->id;
    }

    public function create(User $user)
    {
        return true; // Users can create their own sleep sessions
    }

    public function update(User $user, SleepSession $sleepSession)
    {
        return $user->isAdmin() || $sleepSession->user_id === $user->id;
    }

    public function delete(User $user, SleepSession $sleepSession)
    {
        return $user->isAdmin() || $sleepSession->user_id === $user->id;
    }
}
