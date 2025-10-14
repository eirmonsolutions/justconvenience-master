<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class Store extends Model
{
    use SoftDeletes;

    /**
     * Get the Customers
     */
    public function users()
    {
        return $this->hasMany('App\User')->where('status', 1);
    }   
}