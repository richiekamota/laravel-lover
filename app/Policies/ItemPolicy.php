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
     * Determine whether the user can view the item.
     *
     * @param  \Portal\User  $user
     * @param  \Portal\Item  $item
     * @return mixed
     */
    public function view(User $user, Item $item)
    {
        return false;
    }

    /**
     * Determine whether the user can create items.
     *
     * @param  \Portal\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the item.
     *
     * @param  \Portal\User  $user
     * @param  \Portal\Item  $item
     * @return mixed
     */
    public function update(User $user, Item $item)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the item.
     *
     * @param  \Portal\User  $user
     * @param  \Portal\Item  $item
     * @return mixed
     */
    public function delete(User $user, Item $item)
    {
        return false;
    }
}
