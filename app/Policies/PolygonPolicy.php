<?php

namespace App\Policies;
use App\Models\Polygon;
use App\Models\User;

class PolygonPolicy
{
    /**
     * Allow listing (we'll still filter with scopeVisibleTo).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * A user can view a polygon if:
     * - they own it, OR
     * - they are in the same group as the polygon.
     */
    public function view(User $user, Polygon $polygon): bool
    {
        if ($polygon->user_id === $user->id) {
            return true;
        }

        // user->groups() must exist on User model
        return $user->groups()->whereKey($polygon->group_id)->exists();
    }

    /**
     * Creating polygons is allowed for any authenticated user.
     * (Adjust if you need per-group permission checks.)
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only the owner (or admins, if you add that logic) can update.
     */
    public function update(User $user, Polygon $polygon): bool
    {
        return $polygon->user_id === $user->id;
    }

    /**
     * Only the owner (or admins) can delete.
     */
    public function delete(User $user, Polygon $polygon): bool
    {
        return $polygon->user_id === $user->id;
    }
}