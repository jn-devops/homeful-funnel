<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Checkin;
use Illuminate\Auth\Access\HandlesAuthorization;

class CheckinPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_checkin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Checkin $checkin): bool
    {
        return $user->can('view_checkin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_checkin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Checkin $checkin): bool
    {
        return $user->can('update_checkin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Checkin $checkin): bool
    {
        return $user->can('delete_checkin');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_checkin');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Checkin $checkin): bool
    {
        return $user->can('force_delete_checkin');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_checkin');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Checkin $checkin): bool
    {
        return $user->can('restore_checkin');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_checkin');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Checkin $checkin): bool
    {
        return $user->can('replicate_checkin');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_checkin');
    }
}
