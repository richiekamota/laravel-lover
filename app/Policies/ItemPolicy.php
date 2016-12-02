<?php

namespace Portal\Policies;

use Portal\User;
use Portal\Item;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemPolicy
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
     * Determine whether the users can view the item.
     *
     * @param  \Portal\User $user
     * @return mixed
     * @internal param Item $item
     */
    public function view(User $user)
    {
        if ($user->role != 'tenant') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the users can create items.
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
     * Determine whether the users can update the item.
     *
     * @param  \Portal\User $user
     * @return mixed
     * @internal param Item $item
     */
    public function update(User $user)
    {
        if ($user->role != 'tenant') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the users can delete the item.
     *
     * @param  \Portal\User $user
     * @return mixed
     * @internal param Item $item
     */
    public function delete(User $user)
    {
        if ($user->role != 'tenant') {
            return true;
        }

        return false;
    }
}
