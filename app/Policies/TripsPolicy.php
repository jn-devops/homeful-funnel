<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Trips;
use Illuminate\Auth\Access\HandlesAuthorization;

class TripsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_trippings');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Trips $trips): bool
    {
        return $user->can('view_trippings');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_trippings');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Trips $trips): bool
    {
        return $user->can('update_trippings');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Trips $trips): bool
    {
        return $user->can('delete_trippings');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_trippings');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Trips $trips): bool
    {
        return $user->can('force_delete_trippings');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_trippings');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Trips $trips): bool
    {
        return $user->can('restore_trippings');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_trippings');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Trips $trips): bool
    {
        return $user->can('replicate_trippings');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_trippings');
    }
}
