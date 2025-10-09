<?php
// app/Policies/GroupEventPolicy.php
namespace App\Policies;

use App\Models\GroupEvent;
use App\Models\User;

class GroupEventPolicy
{
    public function view(User $user, GroupEvent $event): bool
    {
        // group leader or a member of the group can view
        if ($event->group->leader_id === $user->id) return true;
        return $event->group->members()->where('users.id', $user->id)->exists();
    }

    public function create(User $user, \App\Models\Group $group): bool
    {
        return $group->leader_id === $user->id;
    }

    public function update(User $user, GroupEvent $event): bool
    {
        return $event->group->leader_id === $user->id || $event->created_by === $user->id;
    }

    public function delete(User $user, GroupEvent $event): bool
    {
        return $event->group->leader_id === $user->id || $event->created_by === $user->id;
    }
}
