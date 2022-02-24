<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder{

    public function run(){
        $roles = ['admin', 'society', 'apartment_owner', 'security', 'agency', 'guest', 'daily_help', 'tenant'];

        foreach ($roles as $role) {
            Role::create(['name' => $role, 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
        }
    }
}