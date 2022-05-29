<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Config;

class UserController extends Controller
{
    protected $current_active_contest_id;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(Auth::user()->user_role == 4)
            {
                return redirect()->route('dashboard');
            }

            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $params = $request->input();

        $users = User::where(array('user_role' => 4))->get();

        return view('admin.users.index', ['users' => $users]);
    }


    public function addUser(Request $request)
    {
        return view('admin.users.add');
    }

    public function saveUser(Request $request)
    {    
        $params = $request->post(); 
        // create the validation rules ------------------------
        $rules = array(
            'name'             => 'required',
            'email'            => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'password'         => 'required'
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

            return redirect()->route('add-user')->withInput($request->all());
        }
 
        $user = new User;
        $user->name = $params['name'];
        $user->email = $params['email']; 
        $user->password = bcrypt($params['password']);
        $user->user_role = 4;

        if($user->save())
        {
            try{
                Session::flash('message', 'User has created successfully.'); 
                Session::flash('class', 'success');
            }
            catch(\Exception $e)
            {
                $user->email_sent = 0;
                $user->save();

                Session::flash('message', $e->getMessage()); 
                Session::flash('class', 'danger');
            }
        }
        else
        {
            Session::flash('message', 'Something went wrong.'); 
            Session::flash('class', 'danger'); 
        }

        return redirect()->route('users');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function deleteUser(Request $request, $id)
    {    
        $params = $request->input();

        $checkUserExistance = User::find($id);

        if ($checkUserExistance) 
        {
            if($checkUserExistance->delete())
            {
                Session::flash('message', 'user deleted successfully.'); 
                Session::flash('class', 'success');
            }
            else
            {
                Session::flash('message', 'Something went wrong'); 
                Session::flash('class', 'danger');

            }

            return redirect()->route('users');
        }
        else
        {
            Session::flash('message', 'No user exist.'); 
            Session::flash('class', 'danger');

            return redirect()->route('users');
        }
    }

    public function changePassword(Request $request, $id)
    {    
        if (\Request::isMethod('post'))
        {
            // create the validation rules ------------------------
            $rules = array(
                'password'             => 'required'
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

                return view('admin.user.change_password', ['id' => $id]);
            }

            $params = $request->post();

            $user = User::find($id);

            if($user)
            {
                $user->password = bcrypt($params['password']);
                if ($user->save())
                {
                    Session::flash('message', 'Password has been changed successfully.'); 
                    Session::flash('class', 'success');
                }
                else
                {
                    Session::flash('message', 'Something went wrong.'); 
                    Session::flash('class', 'danger'); 
                }
            }
            else
            {
                Session::flash('message', 'No user exist.'); 
                Session::flash('class', 'danger'); 
            }

            return redirect()->route('users');
        }

        return view('admin.users.change_password', ['id' => $id]);
    }

}