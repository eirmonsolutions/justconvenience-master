<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class subCategory extends Model
{
    use SoftDeletes;

    /**
     * Get the Cateogory
     */
    public function category()
    {
        // return $this->belongsTo('App\Category')->where('status', 1);
        return $this->belongsTo('App\Category');
    }
}