<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use Session;

use App\Lead;
use App\Contest;
use App\Customer;
use App\customerInvoice;
use App\invoiceDetail;
use App\Setting;
use App\Shop;

use Carbon\Carbon;

class CronController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Contest Second Last Day Cron Method
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contestSecondLastDay()
    {
        $currentDate = \Carbon\Carbon::now();
        $lastDay = $currentDate->addDays(1)->format('Y-m-d');
        
        $getContestDetails = Contest::where('end_date', $lastDay)->first();
        if ($getContestDetails)
        {
            $getCustomers = Customer::where('status', 1)->whereNotIn('contest_id', [$getContestDetails->id])->distinct()->get();
            if (sizeof($getCustomers) > 0)
            {
                foreach ($getCustomers as $keyEA => $valueEA)
                {
                    try{
                        \Mail::send('finishing_contest_email_template', ['params' => $valueEA, 'contest' => $getContestDetails], function($message) use($valueEA, $getContestDetails){

                                // $message->from('pranav@1touch-dev.com', 'name');

                                $message->to($valueEA->email)->subject($getContestDetails->title);

                            });
                    }
                    catch(\Exception $e)
                    {
                        return response()->json(['status' => 0, 'message' => $e->getMessage()]);
                    }
                }

                return response()->json(['status' => 1, 'message' => 'success']);
            }
        }

    }

    /**
     * Contest Last Day Cron Method
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contestLastDay()
    {
        $currentDate = \Carbon\Carbon::now();
        $lastDay = $currentDate->format('Y-m-d');
        
        $getContestDetails = Contest::where('end_date', $lastDay)->first();
        if ($getContestDetails)
        {
            $getCustomers = Customer::where('status', 1)->whereNotIn('contest_id', [$getContestDetails->id])->distinct()->get();
            if (sizeof($getCustomers) > 0)
            {
                foreach ($getCustomers as $keyEA => $valueEA)
                {
                    try{
                        \Mail::send('finishing_contest_email_template', ['params' => $valueEA, 'contest' => $getContestDetails], function($message) use($valueEA, $getContestDetails){

                                // $message->from('pranav@1touch-dev.com', 'name');

                                $message->to($valueEA->email)->subject($getContestDetails->title);

                            });
                    }
                    catch(\Exception $e)
                    {
                        return response()->json(['status' => 0, 'message' => $e->getMessage()]);
                    }
                }

                return response()->json(['status' => 1, 'message' => 'success']);
            }
        }

    }

    public function today_insights()
    {   
        $stats = [];
        $today = Carbon::today()->subDays(1)->toDateString();
        $stats['todayCustomerCount'] = Customer::where('status', 1)->whereDate('created_at', $today)->count();
        $stats['todayTotalAmount'] = Customer::where('status', 1)->whereDate('created_at', $today)->sum('total_invoice_val');
        $stats['todayTicketsCount'] = Customer::where('status', 1)->whereDate('created_at', $today)->sum('ticket_count');

       $stats['max_invoice'] = customerInvoice::with('shop')->whereHas('customer', function ($query) {
                                    $query->where('status', 1);
                                })->whereDate('created_at', $today)->orderBy('invoice_amount', 'desc')->first();

       $stats['max_customers'] = customerInvoice::select(\DB::raw('count(DISTINCT customer_id) as customer_count'), 'id', 'shop_id')->with('shop')->whereHas('customer', function ($query) {
                                    $query->where('status', 1);
                                })->whereDate('created_at', $today)
                                ->groupBy('shop_id')
                                ->orderBy('customer_count', 'desc')
                                ->first();

       if ($stats)
        {
            try{

                $date = date('m-d-Y', strtotime($today));

                \Mail::send('insights_email_template', ['params' => $stats], function($message) use($stats, $date){

                        // $message->from('pranav@1touch-dev.com', 'name');

                        $message->to(['krishan@1touch-dev.com', 'pranav@1touch-dev.com'])
                                ->cc(['pritinder@1touch-dev.com'])
                                ->subject('Canje Formulario - Resumen Diarios (' . $date. ')*date!');

                    });

                Session::flash('message', 'Mail sent successfully.'); 
                Session::flash('class', 'success');
            }
            catch(\Exception $e)
            {
                Session::flash('message', $e->getMessage()); 
                Session::flash('class', 'danger');
            } 
        }
        else
        {
            Session::flash('message', 'Something went wrong.'); 
            Session::flash('class', 'danger');
        }
        echo "Mail sent to the admin";
        // print_r($stats);
        die;
    }
}