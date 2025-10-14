<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class storeCoupon extends Model
{
    use SoftDeletes;

    /**
     * Get the Store Details
     */
    public function stores()
    {
        return $this->hasMany('App\User', 'coupon_id', 'id')->where('user_role', 3);
    }  
}