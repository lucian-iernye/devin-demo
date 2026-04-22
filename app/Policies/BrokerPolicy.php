<?php

namespace App\Policies;

use App\Models\Broker;
use App\Models\User;

class BrokerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('brokers.view');
    }

    public function view(User $user, Broker $broker): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->can('brokers.view')
            && $broker->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('brokers.manage');
    }

    public function update(User $user, Broker $broker): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->can('brokers.manage')
            && $broker->user_id === $user->id;
    }

    public function delete(User $user, Broker $broker): bool
    {
        return $user->hasRole('admin');
    }

    public function restore(User $user, Broker $broker): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, Broker $broker): bool
    {
        return $user->hasRole('admin');
    }
}
