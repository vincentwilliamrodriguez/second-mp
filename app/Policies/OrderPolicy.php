<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('read-orders');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        return $this->viewAny($user) && $this->isUserAssociatedWithOrder($user, $order);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-orders');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        return $user->can('update-orders') && $this->isUserAssociatedWithOrder($user, $order);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        return $user->can('delete-orders') && $this->isUserAssociatedWithOrder($user, $order);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Order $order): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Order $order): bool
    {
        return false;
    }

    public function isUserAssociatedWithOrder($user, $order) {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($order->customer_id === $user->id) {
            return true;
        }

        foreach ($order->orderItems as $item) {
            if ($item->product->seller->id === $user->id) {
                return true;
            }
        }

        return false;
    }
}
