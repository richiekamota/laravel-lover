<?php

namespace Portal\Policies;

use Portal\User;
use Portal\UnitType;
use Illuminate\Auth\Access\HandlesAuthorization;

class UnitTypePolicy
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
     * Determine whether the users can view the unitType.
     *
     * @param  \Portal\User  $user
     * @param  \Portal\UnitType  $unitType
     * @return mixed
     */
    public function view(User $user, UnitType $unitType)
    {
        return false;
    }

    /**
     * Determine whether the users can create unitTypes.
     *
     * @param  \Portal\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the users can update the unitType.
     *
     * @param  \Portal\User  $user
     * @param  \Portal\UnitType  $unitType
     * @return mixed
     */
    public function update(User $user, UnitType $unitType)
    {
        return false;
    }

    /**
     * Determine whether the users can delete the unitType.
     *
     * @param  \Portal\User  $user
     * @param  \Portal\UnitType  $unitType
     * @return mixed
     */
    public function delete(User $user, UnitType $unitType)
    {
        return false;
    }
}
