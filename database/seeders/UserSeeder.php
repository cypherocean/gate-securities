<?php

namespace Database\Seeders;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class UserSeeder extends Seeder{

    public function run(){
        $admin = User::create([
            'name' => 'Super Admin',
            'phone' => '1234567890',
            'email' => 'superadmin@mail.com',
            'password' => bcrypt('Admin@123'),
            'photo' => 'user-icon.jpg',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => 1
        ]);

        $admin->assignRole(Role::findByName('admin'));

        $file_to_upload = public_path().'/uploads/users/';
        if (!File::exists($file_to_upload))
            File::makeDirectory($file_to_upload, 0777, true, true);

        if(file_exists(public_path('/assets/images/users/profile-pic.jpg')) && !file_exists(public_path('/uploads/users/user-icon.jpg')) ){
            File::copy(public_path('/assets/images/users/profile-pic.jpg'), public_path('/uploads/users/user-icon.jpg'));
        }
    }
}