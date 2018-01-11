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
    public function before( $user )
    {

        // If the users role is admin then allow
        // them access to this.
        if ( $user->role == 'admin' ) {
            return TRUE;
        }

    }

    /**
     * Determine whether the users can view the contract.
     *
     * @param  \Portal\User $user
     * @return mixed
     * @internal param Contract $contract
     */
    public function view( User $user )
    {
        if ( $user->role != 'tenant' ) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Determine whether the users can create contracts.
     *
     * @param  \Portal\User $user
     * @return mixed
     */
    public function create( User $user )
    {
        if ( $user->role != 'tenant' ) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Determine whether the users can delete the contract.
     *
     * @param  \Portal\User $user
     * @return mixed
     * @internal param Contract $contract
     */
    public function delete( User $user )
    {
        if ( $user->role != 'tenant' ) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Determine whether the users can edit contracts.
     *
     * @param  \Portal\User $user
     * @return mixed
     */
    public function edit( User $user )
    {
        if ( $user->role != 'tenant' ) {
            return TRUE;
        }

        return FALSE;
    }
}
