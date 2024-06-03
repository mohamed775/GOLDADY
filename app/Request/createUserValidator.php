<?php

namespace App\Request;

class CreateUserValidato {

    public function rules():array
    {
       return [
        'name'=>'required|max:50',
         'email'=> 'required|min:5|email|unique:Users,email',
         'password'=> 'required|min:6|max:50|confirmed'
       ];
    }

    public function authorized(): bool
    {
        return true ;
    }

} 