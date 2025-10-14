<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class Contest extends Model
{
    use SoftDeletes;

    /**
     * Get the Customers
     */
    // public function customers()
    // {
    //     return $this->hasMany('App\Customer')->where('status', 1);
    // }
	 public function users()
    {
        return $this->hasMany('App\User')->where('status', 1);
    }
    /**
     * Get the Invoice details
     */
    public function invoiceDetails()
    {
        return $this->hasMany('App\invoiceDetail')->whereHas('user', function ($query) {
                                    $query->where('status', 1)
                                    ->where('user_role', 4);
                                });
    }

    /**
     * Get the Today Customer Invoices
     */
    public function TodayCustomerInvoices()
    {
        return $this->hasMany('App\customerInvoice')->whereDate('created_at', date('Y-m-d'))->whereHas('user', function ($query) {
                                    $query->where('status', 1)
                                    ->where('user_role', 4);
                                });
    }

    /**
     * Get the Today Invoice details
     */
    public function todayInvoiceDetails()
    {
        return $this->hasMany('App\invoiceDetail')->whereDate('created_at', date('Y-m-d'))->whereHas('user', function ($query) {
                                    $query->where('status', 1)
                                    ->where('user_role', 4);
                                });
    }

    /**
     * Get the last 30 days Customer Invoices
     */
    public function Last30DaysCustomerInvoices()
    {
        return $this->hasMany('App\customerInvoice')->whereDate('created_at', '>', Carbon::now()->subDays(30))->whereHas('user', function ($query) {
                                    $query->where('status', 1)
                                    ->where('user_role', 4);
                                });
    }

    /**
     * Get the last 7 days Customer Invoices
     */
    public function Last7DaysCustomerInvoices()
    {
        return $this->hasMany('App\customerInvoice')->whereDate('created_at', '>', Carbon::now()->subDays(7))->whereHas('user', function ($query) {
                                    $query->where('status', 1)
                                    ->where('user_role', 4);
                                });
    }

    /**
     * Get the Customer Invoices
     */
    public function customerInvoices()
    {
        return $this->hasMany('App\customerInvoice')->whereHas('user', function ($query) {
                                    $query->where('status', 1)
                                    ->where('user_role', 4);
                                });
    }
}
