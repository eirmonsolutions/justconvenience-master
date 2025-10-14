<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class Order extends Model
{
    use SoftDeletes;

    /**
     * Get the Order Details
     */
    public function orderDetails()
    {
        return $this->hasMany('App\orderDetail');
    }

    /**
     * Get the Store Details
     */
    public function store()
    {
        return $this->belongsTo('App\User', 'store_id', 'id')->where('user_role', 3);
    }

    /**
     * Get the Customers
     */
    public function user()
    {
        return $this->belongsTo('App\User')->where('user_role', 4);
    }  
}