<?php

namespace App\Policies;

use App\Models\Denuncia;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DenunciaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('Ver-Propias Denuncias');
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function atendidas(User $user)
    {
        return $user->can('Ver-Atendidas Denuncias');
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function recibidas(User $user)
    {
        return $user->can('Ver-Recibidas Denuncias');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Denuncia  $denuncia
     * @return mixed
     */
    public function view(User $user, Denuncia $denuncia)
    {
        return $user->can('Responder Denuncias');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('Registrar Denuncias'); 
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Denuncia  $denuncia
     * @return mixed
     */
    public function update(User $user, Denuncia $denuncia)
    {
        return $user->can('Responder Denuncias');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Denuncia  $denuncia
     * @return mixed
     */
    public function forceDelete(User $user, Denuncia $denuncia)
    {
        //
    }


}
