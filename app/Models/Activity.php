<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class Activity extends Model
{
    use SoftDeletes;

    /**
     * Get the Users with active status
     */
	/*public function users()
    {
        return $this->hasMany('App\User')->where('status', 1);
    }*/

    public function user_activities()
    {
        return $this->belongsToMany('App\Models\userActivity','user_activities', 'activity_id', 'user_id')->withTimestamps();
    }


}
