<?php

namespace App\Policies;

use App\Models\User;
use App\Models\House;
use Illuminate\Auth\Access\HandlesAuthorization;

class HousePolicy
{
    use HandlesAuthorization;

    public function viewCommunity(User $user, House $house)
    {
        if ($user->hasPermissionTo('view community houses')) {
            return $user->id === $house->user_id;
        }

        return $user->hasPermissionTo('view community houses');
    }

    public function viewProvince(User $user, House $house)
    {
        if ($user->hasPermissionTo('view province houses')) {
            return $user->id === $house->user_id;
        }

        return $user->hasPermissionTo('view province houses');
    }

    public function view(User $user, House $house)
    {
        if ($user->hasPermissionTo('view all')) {
            return true;
        }

        if ($user->hasPermissionTo('view own houses')) {
            return $user->cdr_id === $house->cdr_id;
        }

        return false;
    }

    public function create(User $user)
    {
        return $user->hasAnyPermission(['create houses']);
    }

    public function createOwnHouses(User $user, House $house)
    {
        if ($user->hasPermissionTo('create own houses')) {
            return $user->id === $house->user_id;
        }

        return $user->hasPermissionTo('create own houses');
    }

    public function update(User $user, House $house)
    {
        if ($user->hasPermissionTo('edit houses')) {
            return $user->id == $house->user_id;
        }
        return $user->hasPermissionTo('edit houses');
    }

    public function updateOwnHouses(User $user, House $house)
    {
        if ($user->hasPermissionTo('edit own houses')) {
            return $user->id == $house->user_id;
        }
        return $user->hasPermissionTo('edit own houses');
    }

    public function delete(User $user, House $house)
    {
        if ($user->hasPermissionTo('delete houses')) {
            return $user->id === $house->user_id;
        }

        return $user->hasPermissionTo('delete houses');
    }

    public function deleteOwnHouses(User $user, House $house)
    {
        if ($user->hasPermissionTo('delete own houses')) {
            return $user->id === $house->user_id;
        }

        return $user->hasPermissionTo('delete own houses');
    }
}
