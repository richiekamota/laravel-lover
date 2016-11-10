<?php

namespace Portal\Policies;

use Portal\User;
use Portal\Location;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationPolicy
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
     * Determine whether the user can view the location.
     *
     * @param  \Portal\User  $user
     * @param  \Portal\Location  $location
     * @return mixed
     */
    public function view(User $user, Location $location)
    {
        return false;
    }

    /**
     * Determine whether the user can create locations.
     *
     * @param  \Portal\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the location.
     *
     * @param  \Portal\User  $user
     * @param  \Portal\Location  $location
     * @return mixed
     */
    public function update(User $user, Location $location)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the location.
     *
     * @param  \Portal\User  $user
     * @param  \Portal\Location  $location
     * @return mixed
     */
    public function delete(User $user, Location $location)
    {
        return false;
    }
}
