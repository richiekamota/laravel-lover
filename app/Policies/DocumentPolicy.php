<?php

namespace Portal\Policies;

use Portal\User;
use Portal\Document;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the application.
     *
     * @param  \Portal\User $user
     * @param \Portal\Application|Document $application
     * @return mixed
     */
    public function view(User $user, Document $application)
    {
        //return $user->role != 'tenant' || $user->id == $application->user_id;
    }

    /**
     * Determine whether the user can create applications.
     *
     * @param  \Portal\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the application.
     *
     * @param  \Portal\User $user
     * @return mixed
     * @internal param \Portal\Application $application
     */
    public function update(User $user)
    {
        //
    }

    /**
     * Determine whether the user can delete the application.
     *
     * @param  \Portal\User $user
     * @return mixed
     * @internal param \Portal\Application $application
     */
    public function delete(User $user)
    {
        //
    }
}
