<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    /**
     * Get the Customer Invoices
     */
    public function invoices()
    {
        return $this->hasMany('App\customerInvoice');
    }

    /**
     * Get the Invoice details
     */
    public function invoiceDetails()
    {
        return $this->hasMany('App\invoiceDetail');
    }

    /**
     * Get the User Details
     */
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'approved_by');
    }
}
