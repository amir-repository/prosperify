<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = collect([
            'rescues-index',
            'rescues-create',
            'rescues-store',
            'rescues-show',
            'rescues-edit',
            'rescues-update',
            'rescues-destroy',
            'donations-index',
            'donations-create',
            'donations-store',
            'donations-show',
            'donations-edit',
            'donations-update',
            'donations-destroy',
            'foods-index',
            'foods-create',
            'foods-store',
            'foods-show',
            'foods-edit',
            'foods-update',
            'foods-destroy',
        ]);

        $permissions->each(fn ($permission) => Permission::create(['name' => $permission]));

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo($permissions);

        $donor = Role::create(['name' => 'donor']);
        $donorPermissions = [
            'rescues-index',
            'rescues-create',
            'rescues-store',
            'rescues-show',
            'rescues-edit',
            'rescues-update',
            'rescues-destroy'
        ];
        $donor->givePermissionTo($donorPermissions);

        $volunteer = Role::create(['name' => 'volunteer']);
        $volunteerPermissions = [
            'rescues-index',
            'rescues-show',
            'rescues-edit',
            'rescues-update',
            'rescues-destroy'
        ];
        $volunteer->givePermissionTo($volunteerPermissions);
    }
}
