<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Auth;
use Session;
use App\Models\User;

class AuthController extends Controller
{
    public function signin(Request $request)
    {
        if(Auth::check())
        {
            if(Auth::user()->user_role == 1)
            {
                return redirect()->route('admins');
            }
            else if(Auth::user()->user_role == 2 || Auth::user()->user_role == 5)
            {
                return redirect()->route('dashboard');
            }
        }
        if (\Request::isMethod('post'))
        {
            // create the validation rules ------------------------
            $rules = array(
                // 'name'             => 'required',                        // just a normal required validation
                'email'            => 'required|email',     // required and must be unique in the ducks table
                'password'         => 'required',
            );

            // do the validation ----------------------------------
            // validate against the inputs from our form
            $validator = Validator::make($request->all(), $rules);

            // check if the validator failed -----------------------
            if ($validator->fails()) {

                // get the error messages from the validator
                // $messages = $validator->messages();

                Session::flash('message', $validator->errors()->first()); 
                Session::flash('class', 'danger');

                return redirect()->route('signin')->withInput($request->all());
            }

            $params = $request->post();
            if(Auth::attempt(array('email' => $params['email'], 'password' => $params['password'])))
            {
                $request->session()->regenerate();
                Session::flash('message', 'Logged in successfully'); 
                Session::flash('class', 'success');

                if(Auth::user()->user_role == 1)
                {
                    return redirect()->route('admins');
                }
                else if(Auth::user()->user_role == 2)
                {
                    return redirect()->route('dashboard');
                }
            }
            else
            {
                Session::flash('message', 'Invalid Credentials'); 
                Session::flash('class', 'danger'); 
                return redirect()->route('signin')->withInput($request->all());
            }
        }

        return view('signin');
    }

    public function signOut(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Session::flash('message', 'Logout successfully.'); 
        Session::flash('class', 'success');

        return redirect()->route('signin');   
    }
}
