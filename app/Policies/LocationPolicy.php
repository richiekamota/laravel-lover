<?php

namespace Portal\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Portal\Location;
use Portal\User;

class LocationPolicy
{
    use HandlesAuthorization;

    /**
     * Check this before checking others
     *
     * @param $user
     *
     * @return bool
     */
    public function before( $user )
    {

        // If the users role is admin then allow
        // them access to this.
        if ($user->role == 'admin') {
            return true;
        }

    }

    /**
     * Determine whether the users can view the location.
     *
     * @param  \Portal\User $user
     *
     * @return mixed
     * @internal param Location $location
     */
    public function view( User $user )
    {

        if ($user->role != 'tenant') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the users can create locations.
     *
     * @param  \Portal\User $user
     *
     * @return mixed
     */
    public function create( User $user )
    {

        if ($user->role != 'tenant') {
            return true;
        }

        return false;

    }

    /**
     * Determine whether the users can update the location.
     *
     * @param  \Portal\User $user
     *
     * @return mixed
     * @internal param Location $location
     *
     */
    public function update( User $user )
    {

        if ($user->role != 'tenant') {
            return true;
        }

        return false;

    }

    /**
     * Determine whether the users can delete the location.
     *
     * @param  \Portal\User $user
     *
     * @return mixed
     * @internal param Location $location
     *
     */
    public function delete( User $user )
    {

        if ($user->role != 'tenant') {
            return true;
        }

        return false;

    }
}
