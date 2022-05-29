<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class userActivity extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the Users
     */
    public function users()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the Activites
     */
    public function activity()
    {
        return $this->belongsTo('App\Models\Activity');
    }
}
