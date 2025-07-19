<?php

namespace App\Traits;

trait HasRoleHelpers
{
    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }

    /**
     * Check if user is a reseller
     */
    public function isReseller(): bool
    {
        return $this->hasRole('Reseller');
    }

    /**
     * Check if user is a customer
     */
    public function isCustomer(): bool
    {
        return $this->hasRole('Customer');
    }

    /**
     * Check if user can manage other users
     */
    public function canManageUsers(): bool
    {
        return $this->hasPermissionTo('manage-users');
    }

    /**
     * Check if user can access admin panel
     */
    public function canAccessAdminPanel(): bool
    {
        return $this->hasPermissionTo('view-admin-panel');
    }

    /**
     * Check if user can access reseller panel
     */
    public function canAccessResellerPanel(): bool
    {
        return $this->hasPermissionTo('view-reseller-panel');
    }

    /**
     * Get user's primary role
     */
    public function getPrimaryRole(): string
    {
        if ($this->isAdmin()) {
            return 'Admin';
        } elseif ($this->isReseller()) {
            return 'Reseller';
        } else {
            return 'Customer';
        }
    }
}
