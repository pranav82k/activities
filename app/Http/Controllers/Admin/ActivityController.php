<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Activity;
use App\Models\userActivity;

use Carbon\Carbon;

class ActivityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $params = $request->post();

        $activities = Activity::where(['type' => 1])->get();

        return view('admin.activities.index', ['activities' => $activities, 'params' => $params]);
    }

    public function add(Request $request)
    {
        return view('admin.activities.add');
    }

    public function save(Request $request)
    {
        // create the validation rules ------------------------
        $rules = array(
            'title' => 'required',
            'description' => 'required',
            'featured_image' => 'required',
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

            return redirect()->route('add-activity')->withInput($request->all());
        }

        $params = $request->post();

        $today = Carbon::now()->format('Y-m-d');

        $checkTodayAcitivityCount = Activity::where(function ($query) use ($today) {
        									           	$query->whereDate('created_at', $today);
        									       	})->count();
        if ($checkTodayAcitivityCount >= 4)
        {
        	Session::flash('message', 'You have been reached to maximum limit for today.'); 
        	Session::flash('class', 'danger');

        	return redirect()->route('add-activity')->withInput($request->all());
        }

        
        $activity = new Activity;
        $activity->title = $params['title'];
        $activity->description = $params['description'];

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $name = time().'.'.$image->getClientOriginalExtension();
            
            $path = $request->file('featured_image')->storeAs(
                'activities', $name
            );

            $activity->featured_image = $path;
        }

        DB::beginTransaction();

        try {
            if($activity->save())
            {
                $userIds = [];
                $getUserIds = User::where(['user_role' => 4, 'status' => 1])->pluck('id');
                if(sizeof($getUserIds) > 0) {
                    $userIds = $getUserIds->toArray();

                    // Alternate way of below working process
                    /*$dataCombinedArr = [];

                    foreach ($userIds as $valueU) 
                    {
                        $dataCombinedArr[$valueU] = [
                            'title' => $params['title'],
                            'description'  => $params['description'],
                            'featured_image' => $path
                        ];
                    }*/

                    $attach = collect($userIds)->mapWithKeys(function ($id) use ($params, $path) {
                        return [
                            $id => [
                                'title' => $params['title'],
                                'description'  => $params['description'],
                                'featured_image' => $path
                            ]
                        ];
                    });

                    $activity->user_activities()->sync($attach);
                    // $activity->user_activities()->syncWithPivotValues($userIds, ['title' => $params['title'], 'description'  => $params['description'], 'featured_image' => $path]);
                }

                DB::commit();

            	Session::flash('message', 'Activity has created successfully.'); 
            	Session::flash('class', 'success');
            }
            else
            {
                Session::flash('message', 'Something went wrong.'); 
                Session::flash('class', 'danger'); 
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('message', $e->getMessage()); 
            Session::flash('class', 'danger');
        }

        return redirect()->route('activities');
    }

    public function edit(Request $request, $id)
    {
        $getActivityData = Activity::find($id);
        if ($getActivityData)
        {
        	return view('admin.activities.edit', ['data' => $getActivityData]);
        }
        else
        {
        	Session::flash('message', 'No Activity Found'); 
        	Session::flash('class', 'danger');	
        	return redirect()->route('activities');
        }
    }

    public function update(Request $request)
    {
        // create the validation rules ------------------------
        $rules = array(
            'id'             => 'required',
            'title'             => 'required',
            'description'            => 'required'
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

            return redirect()->route('edit-activity', ['id' => $request->id])->withInput($request->all());
        }

        $params = $request->post();

        $activity = Activity::find($params['id']);
        if (!$activity)
        {
        	Session::flash('message', 'No Activity Found'); 
        	Session::flash('class', 'danger');	
        	return redirect()->route('activities');
        }
        
        $activity->title = $params['title'];
        $activity->description = $params['description'];

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $name = time().'.'.$image->getClientOriginalExtension();
            
            $path = $request->file('featured_image')->storeAs(
                'activities', $name
            );

            $activity->featured_image = $path;
        }

        DB::beginTransaction();

        try {
            if($activity->save())
            {
                $changedFields = $activity->getChanges();
                if(sizeof($changedFields) > 1)
                {
                    // Changes in user activity table
                    $upateUserAcitivities = userActivity::where(['activity_id' => $activity->id, 'particular_edited' => 0])->update($changedFields);
                }

                DB::commit();
            	Session::flash('message', 'Activity has updated successfully.'); 
            	Session::flash('class', 'success');
            }
            else
            {
                Session::flash('message', 'Something went wrong.'); 
                Session::flash('class', 'danger'); 
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('message', $e->getMessage()); 
            Session::flash('class', 'danger');
        }

        return redirect()->route('activities');
    }

    public function updateStatus(Request $request, $id, $status)
    {
        if (\Request::isMethod('post'))
        {
            return redirect()->route('activities');
        }

        $getActivityData = Activity::find($id);
        if (!$getActivityData)
        {
            Session::flash('message', 'No Activity Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('activities');
        }

        $message = 'Activity Deactivated successfully';
        if ($status)
        {
            $message = 'Activity Activated successfully';
        }

        $getActivityData->status = $status;

        if ($getActivityData->save())
        {
            return response()->json(['message' => $message, 'status' => 1]);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.']);
        }
    }

}