<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class orderDetail extends Model
{
    use SoftDeletes;


    /**
     * Get the Order
     */
    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    /**
     * Get the Store Details
     */
    public function store()
    {
        return $this->belongsTo('App\User', 'store_id', 'id')->where('user_role', 3);
    }

    /**
     * Get the Product
     */
    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}