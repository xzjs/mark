<?php

namespace App\Policies;

use App\User;
use App\MathMark;
use Illuminate\Auth\Access\HandlesAuthorization;

class MathMarkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the math mark.
     *
     * @param \App\User $user
     * @param \App\MathMark $mathMark
     * @return mixed
     */
    public function view(User $user, MathMark $mathMark)
    {
        //
    }

    /**
     * Determine whether the user can create math marks.
     *
     * @param \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the math mark.
     *
     * @param \App\User $user
     * @param \App\MathMark $mathMark
     * @return mixed
     */
    public function update(User $user, MathMark $mathMark)
    {
        //
    }

    /**
     * Determine whether the user can delete the math mark.
     *
     * @param \App\User $user
     * @param \App\MathMark $mathMark
     * @return mixed
     */
    public function delete(User $user, MathMark $mathMark)
    {
        return $mathMark->user_id == $user->id;
    }

    /**
     * Determine whether the user can restore the math mark.
     *
     * @param \App\User $user
     * @param \App\MathMark $mathMark
     * @return mixed
     */
    public function restore(User $user, MathMark $mathMark)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the math mark.
     *
     * @param \App\User $user
     * @param \App\MathMark $mathMark
     * @return mixed
     */
    public function forceDelete(User $user, MathMark $mathMark)
    {
        //
    }

    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }
}
