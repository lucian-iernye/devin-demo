<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Role catalog for the Voltaro platform.
     *
     * - admin:    Platform operator (full access).
     * - supplier: Energy producer that publishes tariffs.
     * - broker:   Intermediary that turns RFQs into quotes and earns commission.
     * - buyer:    Buying company that publishes RFQs and signs contracts.
     */
    public function run(): void
    {
        Artisan::call('permission:cache-reset');

        $permissions = [
            // Suppliers
            'suppliers.view', 'suppliers.manage',
            // Tariffs
            'tariffs.view', 'tariffs.manage',
            // Brokers
            'brokers.view', 'brokers.manage',
            // Buyers
            'buyers.view', 'buyers.manage',
            // RFQs
            'rfqs.view', 'rfqs.manage',
            // Quotes
            'quotes.view', 'quotes.manage',
            // Contracts
            'contracts.view', 'contracts.manage',
            // Invoices
            'invoices.view', 'invoices.manage',
        ];

        foreach ($permissions as $name) {
            Permission::findOrCreate($name);
        }

        $roles = [
            'admin' => $permissions,
            'supplier' => [
                'suppliers.view', 'suppliers.manage',
                'tariffs.view', 'tariffs.manage',
                'contracts.view', 'invoices.view',
            ],
            'broker' => [
                'brokers.view', 'tariffs.view', 'rfqs.view',
                'quotes.view', 'quotes.manage',
                'contracts.view', 'invoices.view', 'invoices.manage',
            ],
            'buyer' => [
                'buyers.view', 'rfqs.view', 'rfqs.manage',
                'quotes.view', 'contracts.view', 'contracts.manage',
                'invoices.view',
            ],
        ];

        foreach ($roles as $name => $perms) {
            $role = Role::findOrCreate($name);
            $role->syncPermissions($perms);
        }
    }
}
