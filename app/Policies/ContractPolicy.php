<?php

namespace Portal\Policies;

use Portal\User;
use Portal\Contract;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContractPolicy
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
     * Determine whether the users can view the contract.
     *
     * @param  \Portal\User  $user
     * @param  \Portal\Contract  $contract
     * @return mixed
     */
    public function view(User $user, Contract $contract)
    {
        return false;
    }

    /**
     * Determine whether the users can create contracts.
     *
     * @param  \Portal\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the users can update the contract.
     *
     * @param  \Portal\User  $user
     * @param  \Portal\Contract  $contract
     * @return mixed
     */
    public function update(User $user, Contract $contract)
    {
        return false;
    }

    /**
     * Determine whether the users can delete the contract.
     *
     * @param  \Portal\User  $user
     * @param  \Portal\Contract  $contract
     * @return mixed
     */
    public function delete(User $user, Contract $contract)
    {
        return false;
    }
}
