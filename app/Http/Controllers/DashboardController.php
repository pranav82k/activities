<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use Session;

use App\Models\Contest;
use App\Models\User;
use App\Models\Activity;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $current_active_contest_id;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index(Request $request)
    {
        if($request->ajax()) {

            $data = Activity::whereDate('created_at', '>=', $request->start)
                       ->whereDate('created_at',   '<=', $request->end)
                       ->get()->toArray();

            if(sizeof($data) > 0) {
                foreach ($data as $key => $value) {
                    $data[$key]['start'] = Carbon::parse($value['created_at'])->format('Y-m-d');
                }
            }
            
            return response()->json($data);
        }

        $params = $request->post();

        $stats['usersCount'] = User::where('user_role', 4)->where('status', 1)->count();
        $stats['activitiesCount'] = Activity::count();

        return view('dashboard', ['params' => $params, 'stats' => $stats]);
    }
}