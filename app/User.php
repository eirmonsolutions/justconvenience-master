<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the Customer Invoices
     */
    public function invoices()
    {
        return $this->hasMany('App\customerInvoice')->orderBy('tag','desc');
       // return $this->hasMany('App\customerInvoice');
    }

    /**
     * Get the Invoice details
     */
    public function invoiceDetails()
    {
        return $this->hasMany('App\invoiceDetail');
    }

    /**
     * Get the OauthClient details
     */
    public function oauthClients()
    {
        return $this->hasMany('App\OauthClient');
    }

    /**
     * Get the Tickets registered this week
     */
    public function thisWeekCustomerTickets()
    {
        return $this->hasMany('App\invoiceDetail')->whereDate('created_at', '>=', Carbon::now()->startOfWeek())->whereHas('user', function ($query) {
                                    $query->where('status', 1)
                                    ->where('user_role', 4);
                                });
    }

    /**
     * Get the User Details
     */
    // public function user()
    // {
    //     return $this->hasOne('App\User', 'id', 'approved_by');
    // }
}
