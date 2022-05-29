<?php

namespace App\Http\Controllers\API\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Validator;

class RegisterController extends Controller
{
	public function registration(Request $request)
    {
        //--- Validation Section
        $rules = [
	        'name' => 'required',
	        'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
	        'password' => 'required|min:4',
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
        	return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        }
        //--- Validation Section Ends

        $params = $request->post();

        $user = new User;
        $user->name = $params['name'];
        $user->email = $params['email'];
        $user->password = bcrypt($params['password']);
        
        $user->user_role = 4;
        if($user->save())
        {
        	return response()->json(['status' => 1, 'message' => 'Your account has been created Successfully.'])->setStatusCode(200);
        }
        else
        {
        	return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
        }
    }
}