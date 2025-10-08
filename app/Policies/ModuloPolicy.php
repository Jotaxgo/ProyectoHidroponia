<?php

namespace App\Policies;

use App\Models\Modulo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ModuloPolicy
{
    /**
     * Permite que un Admin haga cualquier acción.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role->nombre_rol === 'Admin') {
            return true;
        }
        return null;
    }

    /**
     * Determina si el usuario puede actualizar el módulo.
     */
    public function update(User $user, Modulo $modulo): bool
    {
        // Un dueño solo puede actualizar un módulo si el 'user_id' del vivero del módulo es el suyo.
        
        return $user->id === $modulo->vivero->user_id;
    }

    /**
     * Determina si el usuario puede eliminar el módulo.
     */
    public function delete(User $user, Modulo $modulo): bool
    {
        // La misma regla que para actualizar.
        return $user->id === $modulo->vivero->user_id;
    }

    /**
     * Determina si el usuario puede restaurar el módulo.
     */
    public function restore(User $user, Modulo $modulo): bool
    {
        return $user->id === $modulo->vivero->user_id;
    }

    /**
     * Determina si el usuario puede eliminar permanentemente el módulo.
     */
    public function forceDelete(User $user, Modulo $modulo): bool
    {
        return $user->id === $modulo->vivero->user_id;
    }
}