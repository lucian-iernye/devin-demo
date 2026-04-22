<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;

class SupplierPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('suppliers.view');
    }

    public function view(User $user, Supplier $supplier): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->can('suppliers.view')
            && $supplier->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('suppliers.manage');
    }

    public function update(User $user, Supplier $supplier): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->can('suppliers.manage')
            && $supplier->user_id === $user->id;
    }

    public function delete(User $user, Supplier $supplier): bool
    {
        return $user->hasRole('admin');
    }

    public function restore(User $user, Supplier $supplier): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, Supplier $supplier): bool
    {
        return $user->hasRole('admin');
    }
}
