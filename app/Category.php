<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class Category extends Model
{
    use SoftDeletes;

    /**
     * Get the Customers
     */
    public function subCategories()
    {
        // return $this->hasMany('App\subCategory')->where('status', 1);
        return $this->hasMany('App\subCategory');
    }
}