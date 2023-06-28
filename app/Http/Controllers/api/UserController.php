<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //admin login api
     public function login(Request $request) {
           
              //check validation 
              $validator=Validator::make($request->all(),[
                  'email'=>'required|email',
                   'password'=>'required'
              ]);
                if($validator->fails()){
                    return $this->jsonResponse(false,$validator->errors(),422);
                }
                elseif (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
                       
                    $user=Auth::guard('web')->user();
                    $token = $user->createToken('UserToken', ['web'])->plainTextToken;
                    $message=[
                        'user'=>$user,
                         'token'=>$token
                    ];

                    return $this->jsonResponse(true,$message,200);

                } else{
                     $message="Invalid Email or Password";
                     return $this->jsonResponse(false,$message,401);
                }

     }

     public function jsonResponse($status,$message,$status_code){
                    return response()->json([
            'status'=>$status,
            'message'=>$message
       ],$status_code);
     }
}