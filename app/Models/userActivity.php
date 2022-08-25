<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;
use App\Models\Activity;

class userActivity extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the Users
     */
    public function users()
    {
        // return $this->belongsTo('App\Models\User');
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Activites
     */
    public function activity()
    {
        // return $this->belongsTo('App\Models\Activity');
        return $this->belongsTo(Activity::class);
    }
}
