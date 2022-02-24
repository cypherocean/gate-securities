<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        if($this->method() == 'PATCH'){
            return [
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'role' => 'required'
            ];
        }else{
            return [
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'role' => 'required'
            ];
        }
    }

    public function messages(){
        return [
            'name.required' => 'Please enter name',
            'email.required' => 'Please enter email',
            'email.email' => 'Please enter valid email',
            'phone.required' => 'Please enter Phone number',
            'role.required' => 'Please select role'
        ];
    }
}
