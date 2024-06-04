<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Responces\ResponseHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PDO;

class authController extends Controller
{

    /*
       ResponseHelper -> class has many method that handle server response 
    */
    
    // register  new user 

    public function register(Request $request){

        $valideted =  Validator::make($request->all() , [
         'name' => 'required|string|min:2|max:100',
         'email' => 'required|string|email|max:100|unique:users',
         'password' => 'required|confirmed|min:6|string' 
        ] );
 
        if($valideted->fails())
        {
            return ResponseHelper::validateError( $valideted->errors());
        }

        $user = User::create([
             'name' =>$request->name ,
             'email' => $request->email,
             'password' => Hash::make($request->password ) 

         ]);
         return response()->json(ResponseHelper::returnData('User' , $user  ,'insert successfully'),201) ;
     }



     //login 
      
     public function login(Request $request){
         
         $valideted =  Validator::make($request->all() , [
             'email' => 'required|string|email',
             'password' => 'required'
            ] );
     
            if($valideted->fails())
            {
                return ResponseHelper::validateError( $valideted->errors());
            }
            if(!$token = auth()->attempt($valideted->validate()))
            {
                return ResponseHelper::validateError('user or password is incorrect');
            }

            return ResponseHelper::returnData('access_token' , $token ,'login success' );
     }


     // logout
      
     public function logout(){
        if(Auth::user()){
            auth()->logout();
            return ResponseHelper::returnSuccessMessage('user logged out');
        }
        return ResponseHelper::notAuthenticated();
     }

 
}
