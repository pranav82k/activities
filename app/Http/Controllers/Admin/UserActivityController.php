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

class UserActivityController extends Controller
{
    protected $userWhereCondition;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userWhereCondition = ['user_role' => 4];
        // $this->middleware('auth');
    }

    public function index(Request $request, $id)
    {
        $params = $request->post();

        $activities = User::with(['activities' => function ($query) use ($id) {
            $query->where(['user_id' => $id, 'status' => 1]);
        }])->where(['id' => $id, 'user_role' => 4])->first();

        if(empty($activities))
        {
            return redirect()->route('activities');
        }

        return view('admin.user_activities.index', ['activities' => $activities, 'params' => $params]);
    }

    public function add(Request $request, $id)
    {
        $checkUserExistance =  $this->checkUserExistance(['id' => $id]);

        return view('admin.user_activities.add', ['user' => $checkUserExistance]);
    }

    public function save(Request $request)
    {
        // create the validation rules ------------------------
        $rules = array(
            'user_id'           => 'required',
            'title'             => 'required',
            'description'            => 'required',
            'featured_image'         => 'required',
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

            return redirect()->route('add-user-activity', ['user_id' => $params['user_id']])->withInput($request->all());
        }

        $params = $request->post();

        $today = Carbon::now()->format('Y-m-d');

        $checkTodayAcitivityCount = userActivity::whereHas('activity')->where(['user_id' => $params['user_id']])->count();
        
        if ($checkTodayAcitivityCount >= 4)
        {
        	Session::flash('message', 'You have been reached to maximum limit for today.'); 
        	Session::flash('class', 'danger');

        	return redirect()->route('add-user-activity', ['user_id' => $params['user_id']])->withInput($request->all());
        }

        
        $activity = new Activity;
        $activity->type = 2;
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
                $userActivity = new userActivity;
                $userActivity->user_id = $params['user_id'];
                $userActivity->activity_id = $activity->id;
                $userActivity->title = $params['title'];
                $userActivity->description = $params['description'];
                $userActivity->featured_image = $path;
                $userActivity->save();

                DB::commit();

            	Session::flash('message', 'User Activity has created successfully.'); 
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

        return redirect()->route('user-activities', ['id' => $params['user_id']]);
    }

    public function edit(Request $request, $id)
    {
        $getActivityData = userActivity::whereHas('activity')->where(['id' => $id])->first();
        if ($getActivityData)
        {
        	return view('admin.user_activities.edit', ['data' => $getActivityData]);
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
            'user_id'             => 'required',
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

            return redirect()->route('edit-user-activity', ['id' => $request->id])->withInput($request->all());
        }

        $params = $request->post();

        $activity = userActivity::whereHas('activity')->where(['id' => $params['id'], 'user_id' => $params['user_id']])->first();
        if (!$activity)
        {
        	Session::flash('message', 'No Activity Found'); 
        	Session::flash('class', 'danger');	
        	return redirect()->route('activities');
        }
        
        $activity->title = $params['title'];
        $activity->description = $params['description'];
        $activity->particular_edited = 1;

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
                    // Changes in activity table if required
                    // $upateUserAcitivities = Activity::where(['id' => $activity->id, 'type' => 2])->update($changedFields);
                }

                DB::commit();
            	Session::flash('message', 'User Activity has updated successfully.'); 
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

        return redirect()->route('user-activities', ['id' => $params['user_id']]);
    }

    public function delete(Request $request, $id)
    {
        $params = $request->post();

        $activity = userActivity::whereHas('activity')->where(['id' => $id])->first();
        if (!$activity)
        {
            Session::flash('message', 'No Activity Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('activities');
        }

        if ($activity->delete())
        {
            Session::flash('message', 'User Activity has deleted successfully.'); 
            Session::flash('class', 'success');
            return redirect()->route('user-activities', ['user_id' => $activity->user_id]);
        }
        else
        {
            Session::flash('message', 'No User Activity Found'); 
            Session::flash('class', 'danger');  
        }
    }

    public function checkUserExistance($requestedWhere)
    {
        $whereCondition = array_merge($this->userWhereCondition, $requestedWhere);
        $checkUserExistance = User::where($whereCondition)->first();
        if (!$checkUserExistance)
        {
            Session::flash('message', 'No User Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('users');
        }

        return $checkUserExistance;
    }
}