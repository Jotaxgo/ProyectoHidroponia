<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vivero;
use Illuminate\Auth\Access\HandlesAuthorization;

class ViveroPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Vivero  $vivero
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Vivero $vivero)
    {
        return $user->id === $vivero->user_id || $user->role->nombre_rol === 'Admin';
    }
}
