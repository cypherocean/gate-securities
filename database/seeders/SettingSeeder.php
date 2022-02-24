<?php

namespace Database\Seeders;
use App\Models\Setting;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SettingSeeder extends Seeder{
    public function run(){
        $general = [
            'SITE_TITLE' => 'Gate Securities', 
            'SITE_TITLE_SF' => 'GS', 
            'CONTACT_NUMBER' => '+91-9898000001',
            'MAIN_CONTACT_NUMBER' => '+91-9898000002',
            'CONTACT_EMAIL' => 'info@gatesecurities.com',
            'MAIN_CONTACT_EMAIL' => 'info@gatesecurities.com',
            'CONTACT_ADDRESS' => '<strong>Registered Address:-</strong> Plot No:22, Gulmohar Co.Op,So Ltd, Shimpoli Road, Borivali(West), Mumbai-400092',
            'MAIN_CONTACT_ADDRESS' => '<strong>Branch/Courier Address:-</strong> D-1402 Sun South Park, South Bopal, Ahmedabad-38008'
        ];

        foreach($general as $key => $value){
            Setting::create([
                'key' => $key,
                'value' => $value,
                'type' => 'general',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => 1
            ]);
        }

        $smtp = [
            'MAIL_DRIVER' => 'smtp', 
            'MAIL_HOST' => 'mail.gatesecurities.com', 
            'MAIL_PORT' => '465',
            'MAIL_USERNAME' => 'info@gatesecurities.com',
            'MAIL_PASSWORD' => 'Admin@123',
            'MAIL_ENCRYPTION' => 'ssl',
            'MAIL_FROM_NAME' => 'Gate Securities'
        ];

        foreach($smtp as $key => $value){
            Setting::create([
                'key' => $key,
                'value' => $value,
                'type' => 'smtp',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => 1
            ]);
        }

        $sms = [
            'SMS_NAME' => 'Gate Securities',
            'SMS_NUMBER' => '+91-8000080000'
        ];

        foreach($sms as $key => $value){
            Setting::create([
                'key' => $key,
                'value' => $value,
                'type' => 'sms',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => 1
            ]);
        }

        $social = [
            'FACEBOOK' => 'www.facebook.com/gatesecurities',
            'INSTAGRAM' => 'www.instagram.com/gatesecurities',
            'YOUTUBE' => 'www.youtube.com/gatesecurities',
            'GOOGLE' => 'www.google.com/gatesecurities'
        ];

        foreach($social as $key => $value){
            Setting::create([
                'key' => $key,
                'value' => $value,
                'type' => 'social',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => 1
            ]);
        }

        $logo = [
            'FEVICON' => 'fevicon.png',
            'LOGO' => 'logo.png',
            'SMALL_LOGO' => 'small_logo.png'
        ];

        foreach($logo as $key => $value){
            Setting::create([
                'key' => $key,
                'value' => $value,
                'type' => 'logo',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => 1
            ]);
        }

        $folder_to_upload = public_path().'/uploads/logo/';

        if (!\File::exists($folder_to_upload)) {
            \File::makeDirectory($folder_to_upload, 0777, true, true);
        }

        if(file_exists(public_path('/dummy/fevicon.png')) && !file_exists(public_path('/uploads/logo/fevicon.png')) ){
            File::copy(public_path('/dummy/fevicon.png'), public_path('/uploads/logo/fevicon.png'));
        }

        if(file_exists(public_path('/dummy/logo.png')) && !file_exists(public_path('/uploads/logo/logo.png')) ){
            File::copy(public_path('/dummy/logo.png'), public_path('/uploads/logo/logo.png'));
        }

        if(file_exists(public_path('/dummy/small_logo.png')) && !file_exists(public_path('/uploads/logo/small_logo.png')) ){
            File::copy(public_path('/dummy/small_logo.png'), public_path('/uploads/logo/small_logo.png'));
        }
    }
}
