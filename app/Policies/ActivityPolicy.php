<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    public function view(User $user, Activity $activity)
    {
        return $user->isAdmin() || $activity->user_id === $user->id;
    }

    public function create(User $user)
    {
        return true; // Users can create their own activities
    }

    public function update(User $user, Activity $activity)
    {
        return $user->isAdmin() || $activity->user_id === $user->id;
    }

    public function delete(User $user, Activity $activity)
    {
        return $user->isAdmin() || $activity->user_id === $user->id;
    }
}
