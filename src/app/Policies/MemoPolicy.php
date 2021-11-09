<?php

namespace App\Policies;

use App\Models\Memo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Memo  $memo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Memo $memo)
    {
        return ($user->id === $memo->author_id) || $memo->is_public;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Memo  $memo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Memo $memo)
    {
        return $user->id === $memo->author_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Memo  $memo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Memo $memo)
    {
        return $user->id === $memo->author_id;
    }
}
