<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class customerInvoice extends Model
{
	use SoftDeletes;
    /**
     * Get the customer details
     */
    // public function customer()
    // {
    //     return $this->belongsTo('App\Customer');
    // }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function shop()
    {
        return $this->belongsTo('App\Shop');
    }
}
