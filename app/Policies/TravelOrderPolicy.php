<?php

namespace App\Policies;

use App\Models\TravelOrder;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TravelOrderPolicy
{

    public function viewAny(User $user): bool
    {
        return false;
    }


    public function view(User $user, TravelOrder $travelOrder): bool
    {
        return $user->id === $travelOrder->user_id || $user->is_admin;
    }



    public function create(User $user): bool
    {
        return false;
    }


    public function update(User $user, TravelOrder $travelOrder): bool
    {
        return false;
    }


    public function delete(User $user, TravelOrder $travelOrder): bool
    {
        return false;
    }


    public function restore(User $user, TravelOrder $travelOrder): bool
    {
        return false;
    }


    public function forceDelete(User $user, TravelOrder $travelOrder): bool
    {
        return false;
    }
}
