<?php

namespace Portal\Policies;

use Portal\User;
use Portal\Unit;
use Illuminate\Auth\Access\HandlesAuthorization;

class UnitPolicy
{
    use HandlesAuthorization;

    /**
     * Check this before checking others
     *
     * @param $user
     * @return bool
     */
    public function before($user)
    {

        // If the users role is admin then allow
        // them access to this.
        if ($user->role == 'admin') {
            return true;
        }

    }

    /**
     * Determine whether the users can view the unit.
     *
     * @param  \Portal\User  $user
     * @param  \Portal\Unit  $unit
     * @return mixed
     */
    public function view(User $user)
    {
        if ($user->role != 'tenant') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the users can create units.
     *
     * @param  \Portal\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->role != 'tenant') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the users can update the unit.
     *
     * @param  \Portal\User $user
     * @return mixed
     * @internal param Unit $unit
     */
    public function update(User $user)
    {
        if ($user->role != 'tenant') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the users can delete the unit.
     *
     * @param  \Portal\User $user
     * @return mixed
     * @internal param Unit $unit
     */
    public function delete(User $user)
    {
        if ($user->role != 'tenant') {
            return true;
        }

        return false;
    }
}
