<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Weight;
use Illuminate\Auth\Access\HandlesAuthorization;

class WeightPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    public function view(User $user, Weight $weight)
    {
        return $user->isAdmin() || $weight->user_id === $user->id;
    }

    public function create(User $user)
    {
        return true; // Users can create their own weights
    }

    public function update(User $user, Weight $weight)
    {
        return $user->isAdmin() || $weight->user_id === $user->id;
    }

    public function delete(User $user, Weight $weight)
    {
        return $user->isAdmin() || $weight->user_id === $user->id;
    }
}
