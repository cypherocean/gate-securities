<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder{

    public function run(){
        $society_permissions = [
            'user-create',
            'user-edit',
            'user-view',
            'user-delete'
        ];

        // $apartment_owner_permissions = [];
        // $security_permissions = [];
        // $agency_permissions = [];
        // $guest_permissions = [];
        // $daily_help_permissions = [];
        // $paying_guest_permissions = [];

        $admin_permissions = [
            'role-create',
            'role-edit',
            'role-view',
            'role-delete',
            'permission-create',
            'permission-edit',
            'permission-view',
            'permission-delete',
            'access-view',
            'access-edit',
            'setting-view',
            'setting-edit',
        ];

        $permissions = array_merge($admin_permissions, $society_permissions);
        // $permissions = array_merge($admin_permissions, $society_permissions, $apartment_owner_permissions);

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        $society = Role::findByName('society');
        $society->givePermissionTo($society_permissions);

        $admin = Role::findByName('admin');
        $admin->givePermissionTo($permissions);

        // $apartment_owner = Role::findByName('apartment_owner');
        // $apartment_owner->givePermissionTo($apartment_owner_permissions);

        // $security = Role::findByName('security');
        // $security->givePermissionTo($security_permissions);

        // $agency = Role::findByName('agency');
        // $agency->givePermissionTo($agency_permissions);

        // $guest = Role::findByName('guest');
        // $guest->givePermissionTo($guest_permissions);

        // $daily_help = Role::findByName('daily_help_admin');
        // $daily_help->givePermissionTo($daily_help_permissions);

        // $paying_guest = Role::findByName('paying_guest');
        // $paying_guest->givePermissionTo($paying_guest_permissions);
    }
}