<?php

namespace App\Http\Controllers\API\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->checkTokenExistance->user;
        return response()->json(['status' => 1, 'message' => 'User Data.', 'user' => $user])->setStatusCode(200);
    }

    public function profileupdate(Request $request)
    {
        $user = $request->checkTokenExistance->user;
        //--- Validation Section

        $rules = [
            'name'   => 'required',
            'email'   => 'required|email|unique:users,email,'.$user->id.',id,deleted_at,NULL'
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        }
        //--- Validation Section Ends

        $params = $request->post();
        $user->name = $params['name'];
        $user->email = $params['email'];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/users');
            $image->move($destinationPath, $name);
            
            $user->image = 'public/users/' . $name;

            // Alternate Option for storing image inside the storage folder
            /*$path = $request->file('image')->storeAs(
                'users', $name
            );*/
        }

        if($user->save())
        {
            return response()->json(['status' => 1, 'message' => 'Your account has been updated Successfully.'])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
        }
    }

    public function changePassword(Request $request)
    {
        if (\Request::isMethod('post'))
        {
            $user = $request->checkTokenExistance->user;

            $params = $request->post(); 
            
            // create the validation rules ------------------------
            $rules = array(
                'current_password' => 'required',
                'new_password' => 'required|min:5|different:current_password',
                'password_confirmation' => 'required|same:new_password'
            );

            // do the validation ----------------------------------
            // validate against the inputs from our form
            $validator = Validator::make($request->all(), $rules);

            // check if the validator failed -----------------------
            if ($validator->fails()) {
                return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
            }

            if (!Hash::check($params['current_password'], $user->password)) 
            {
                return response()->json(['status' => 0, 'message' => 'Current password is wrong.'])->setStatusCode(200);
            }

            $user->password = bcrypt($params['new_password']);
            if ($user->save())
            {
                return response()->json(['status' => 1, 'message' => 'Password has updated successfully.'])->setStatusCode(200);
            }
            else
            {
                return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
            }
        }

        return response()->json(['status' => 0, 'message' => 'Method not allowed.'])->setStatusCode(200);
    }

    public function activityList(Request $request)
    {
        $user = $request->checkTokenExistance->user;

        $params = $request->post();

        if((array_key_exists('start_date', $params) && array_key_exists('end_date', $params)) && (!empty($params['start_date']) && !empty($params['end_date'])))
        {
            $startDate = Carbon::parse($params['start_date'])->format('Y-m-d');
            $endDate = Carbon::parse($params['end_date'])->format('Y-m-d');
        }
        else
        {
            // Default dates are previous week
            $endDate = \Carbon\Carbon::now()->format('Y-m-d');
            $startDate = Carbon::parse($endDate)->subDays(7)->format('Y-m-d');
        }

        // \DB::enableQueryLog();
        $activityList = $user->activities()->whereBetween('created_at', [$startDate, $endDate])->get();

        // $activityList = $user->activities()->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->get();
        // return response()->json(['query' =>\DB::getQueryLog()]);

        if ($activityList)
        {
            return response()->json(['status' => 1, 'message' => 'Activity List.', 'data' => $activityList])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'No Activity List Found.'])->setStatusCode(200);
        }
    }
}