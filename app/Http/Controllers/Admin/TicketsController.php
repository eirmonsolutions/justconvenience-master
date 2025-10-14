<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
use App\User;
use Yajra\Datatables\Datatables;
use App\Exports\Ticket;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class TicketsController extends Controller
{
    
   public function __construct()
	{
	    
	    $this->middleware(function ($request, $next) {
	        if(Auth::user()->user_role == 3)
	        {
	            return redirect()->route('dashboard');
	        }

	        return $next($request);
	    });
	}

    public function downloadExcel(Request $request)
    {
        $params = $request->input();
        ob_end_clean();
        return Excel::download(new Ticket($params), 'Tickets.xlsx');
    }
    
    public function index(Request $request)
    {
        if(!Auth::check())
        {
            return redirect()->route('signin');
        }

        if(Auth::user()->user_role == 1)
        {
            return redirect()->route('users');
        }

        $params = $request->input();

        $getTickets = invoiceDetail::whereHas('user', function($q) use($params) {
                        $q->where('status', 1);

                        if(isset($params['total_invoice_val']) && !empty($params['total_invoice_val']))
                        {
                            $q->where('total_invoice_val', '>=', $params['total_invoice_val']);
                        }

                        if (isset($params['start_date']) && !empty($params['start_date']) && isset($params['end_date']) && !empty($params['end_date']))
                        {
                            $start_date = date('Y-m-d', strtotime($params['start_date']));
                            $end_date = date('Y-m-d', strtotime($params['end_date']));

                            $q->whereDate('created_at' , '>=', $start_date);
                            $q->whereDate('created_at' , '<=', $end_date);
                        }
                        else if(isset($params['start_date']) && !empty($params['start_date']))
                        {
                            $start_date = date('Y-m-d', strtotime($params['start_date']));
                            $q->whereDate('created_at' , '>=', $start_date);

                        }
                        else if(isset($params['end_date']) && !empty($params['end_date']))
                        {
                            $end_date = date('Y-m-d', strtotime($params['end_date']));
                            $q->whereDate('created_at' , '<=', $end_date);
                        }

                        if (isset($params['contest_id']) && !empty($params['contest_id']))
                        {
                            $q->where('contest_id', $params['contest_id']);
                        }
                    })->limit(10)->get();
        // echo "<pre>";
        // print_r($getTickets); die();

        $getContests = Contest::orderBy('id', 'desc')->get();
         // echo "<pre>";
        // print_r($getTickets); die();

        return view('admin.tickets.tickets', ['tickets' => $getTickets, 'params' => $params, 'contests' => $getContests]);
    }

    public function get_tickets(Request $request)
    {   
// echo "-1-";die;
        $params = $request->input();

        if(isset($params['length']) && !empty($params['length'])){
            $limit = $params['length'];
        }else{
            $limit = 10;
        }
        $start = $params['start'];

       // $total_records = $total_records1 = invoiceDetail::whereHas('user', function($q) use($params) {
       //                  $q->where('status', 1);

       //                  if(isset($params['total_invoice_val']) && !empty($params['total_invoice_val']))
       //                  {
       //                      $q->where('total_invoice_val', '>=', $params['total_invoice_val']);
       //                  }

       //                  if(isset($params['search_fields']) && !empty($params['search_fields']))
       //                  {
       //                      $q->where(function($qs) use($params) {
       //                          $qs->where('email', 'Like', $params['search_fields'].'%');
       //                          $qs->orWhere('name', 'Like', $params['search_fields'].'%');
       //                          $qs->orWhere('last_name', 'Like', $params['search_fields'].'%');
       //                          $qs->orWhere('phone_number', 'Like', $params['search_fields'].'%');
       //                          $qs->orWhere('indentification_card', 'Like', $params['search_fields'].'%');
       //                          $qs->orWhere('direction', 'Like', $params['search_fields'].'%');
       //                       });
       //                  }

       //                  if (isset($params['start_date']) && !empty($params['start_date']) && isset($params['end_date']) && !empty($params['end_date']))
       //                  {
       //                      $start_date = date('Y-m-d', strtotime($params['start_date']));
       //                      $end_date = date('Y-m-d', strtotime($params['end_date']));

       //                      $q->whereDate('created_at' , '>=', $start_date);
       //                      $q->whereDate('created_at' , '<=', $end_date);
       //                  }
       //                  else if(isset($params['start_date']) && !empty($params['start_date']))
       //                  {
       //                      $start_date = date('Y-m-d', strtotime($params['start_date']));
       //                      $q->whereDate('created_at' , '>=', $start_date);

       //                  }
       //                  else if(isset($params['end_date']) && !empty($params['end_date']))
       //                  {
       //                      $end_date = date('Y-m-d', strtotime($params['end_date']));
       //                      $q->whereDate('created_at' , '<=', $end_date);
       //                  }

       //                  if (isset($params['contest_id']) && !empty($params['contest_id']))
       //                  {
       //                      $q->where('contest_id', $params['contest_id']);
       //                  }
       //              })->count();
       $total_records = invoiceDetail::whereHas('user', function($q) use($params) {
                        $q->where('status', 1);
                        
                        if(isset($params['total_invoice_val']) && !empty($params['total_invoice_val']))
                        {
                            $q->where('total_invoice_val', '>=', $params['total_invoice_val']);
                        }

                        if(isset($params['search_fields']) && !empty($params['search_fields']))
                        {
                            $q->where(function($qs) use($params) {
                                $qs->where('email', 'Like', $params['search_fields'].'%');
                                $qs->orWhere('name', 'Like', $params['search_fields'].'%');
                                $qs->orWhere('last_name', 'Like', $params['search_fields'].'%');
                                $qs->orWhere('phone_number', 'Like', $params['search_fields'].'%');
                                $qs->orWhere('indentification_card', 'Like', $params['search_fields'].'%');
                                $qs->orWhere('direction', 'Like', $params['search_fields'].'%'); 
                                $qs->orWhere('invoice_details.id', 'Like', $params['search_fields'].'%'); 
                             });
                        }
                    });

                    
                    if (isset($params['start_date']) && !empty($params['start_date']) && isset($params['end_date']) && !empty($params['end_date']))
                    {
                        $start_date = date('Y-m-d', strtotime($params['start_date']));
                        $end_date = date('Y-m-d', strtotime($params['end_date']));

                        // $q->whereDate('created_at' , '>=', $start_date);
                        // $q->whereDate('created_at' , '<=', $end_date);
                        $total_records->whereDate('created_at' , '>=', $start_date);
                        $total_records->whereDate('created_at' , '<=', $end_date);
                    }
                    else if(isset($params['start_date']) && !empty($params['start_date']))
                    {
                        $start_date = date('Y-m-d', strtotime($params['start_date']));
                        // $q->whereDate('created_at' , '>=', $start_date);
                        $total_records->whereDate('created_at' , '>=', $start_date);

                    }
                    else if(isset($params['end_date']) && !empty($params['end_date']))
                    {
                        $end_date = date('Y-m-d', strtotime($params['end_date']));
                        // $q->whereDate('created_at' , '<=', $end_date);
                        $total_records->whereDate('created_at' , '<=', $end_date);
                    }

                    if (isset($params['contest_id']) && !empty($params['contest_id']))
                    {
                        // $q->where('contest_id', $params['contest_id']);
                        $total_records->where('contest_id', $params['contest_id']);
                    }
                    $total_records = $total_records1 = $total_records->count();


// echo $total_records;

                $getTickets = invoiceDetail::whereHas('user', function($q) use($params) {
                        $q->where('status', 1);
                        
                        if(isset($params['total_invoice_val']) && !empty($params['total_invoice_val']))
                        {
                            $q->where('total_invoice_val', '>=', $params['total_invoice_val']);
                        }

                        if(isset($params['search_fields']) && !empty($params['search_fields']))
                        {
                            $q->where(function($qs) use($params) {
                                $qs->where('email', 'Like', $params['search_fields'].'%');
                                $qs->orWhere('name', 'Like', $params['search_fields'].'%');
                                $qs->orWhere('last_name', 'Like', $params['search_fields'].'%');
                                $qs->orWhere('phone_number', 'Like', $params['search_fields'].'%');
                                $qs->orWhere('indentification_card', 'Like', $params['search_fields'].'%');
                                $qs->orWhere('direction', 'Like', $params['search_fields'].'%');
                                $qs->orWhere('invoice_details.id', 'Like', $params['search_fields'].'%'); 
                             });
                        }
                    });

                    
                    if (isset($params['start_date']) && !empty($params['start_date']) && isset($params['end_date']) && !empty($params['end_date']))
                    {
                        $start_date = date('Y-m-d', strtotime($params['start_date']));
                        $end_date = date('Y-m-d', strtotime($params['end_date']));

                        // $q->whereDate('created_at' , '>=', $start_date);
                        // $q->whereDate('created_at' , '<=', $end_date);
                        $getTickets->whereDate('created_at' , '>=', $start_date);
                        $getTickets->whereDate('created_at' , '<=', $end_date);
                    }
                    else if(isset($params['start_date']) && !empty($params['start_date']))
                    {
                        $start_date = date('Y-m-d', strtotime($params['start_date']));
                        // $q->whereDate('created_at' , '>=', $start_date);
                        $getTickets->whereDate('created_at' , '>=', $start_date);

                    }
                    else if(isset($params['end_date']) && !empty($params['end_date']))
                    {
                        $end_date = date('Y-m-d', strtotime($params['end_date']));
                        // $q->whereDate('created_at' , '<=', $end_date);
                        $getTickets->whereDate('created_at' , '<=', $end_date);
                    }

                    if (isset($params['contest_id']) && !empty($params['contest_id']))
                    {
                        // $q->where('contest_id', $params['contest_id']);
                        $getTickets->where('contest_id', $params['contest_id']);
                    }
                    $getTickets = $getTickets->skip($start)->take($limit)->get();

        // echo $getTickets; die;

        //$getContests = Contest::get();
// return Datatables::of($getTickets)->make();

        return Datatables::of($getTickets)
            ->with([
                "recordsTotal" => $total_records,
                "recordsFiltered" => $total_records1,
                // "recordsFiltered" => $total_records1/$limit,
              ])
            ->addColumn('name', function($getTickets) {
                return $getTickets->user->name;
            })
            ->addColumn('last_name', function($getTickets) {
                return $getTickets->user->last_name;
            })
            ->addColumn('email', function($getTickets) {
                return $getTickets->user->email;
            })
            ->addColumn('phone_number', function($getTickets) {
                return $getTickets->user->phone_number;
            })
            ->addColumn('indentification_card', function($getTickets) {
                return $getTickets->user->indentification_card;
            })
            ->addColumn('direction', function($getTickets) {
                return $getTickets->user->direction;
            })  
            ->skipPaging() 
        ->make(); 
    }

    public function getWinners(Request $request)
    {
        if(Auth::user()->user_role == 3)
        {
            return redirect()->route('dashboard');
        }
        $params = $request->input();
        // $invoiceData = invoiceDetail::with('customer')->inRandomOrder()->limit($params['winner_count'])->get();
        $invoiceData = invoiceDetail::whereHas('user', function($q) use($params) {
                        $q->where('status', 1);
                        if(isset($params['total_invoice_val']) && !empty($params['total_invoice_val']))
                        {
                            $q->where('total_invoice_val', '>=', $params['total_invoice_val']);
                        }
                    });
                    if (isset($params['start_date']) && !empty($params['start_date']) && isset($params['end_date']) && !empty($params['end_date']))
                    {
                        $start_date = date('Y-m-d', strtotime($params['start_date']));
                        $end_date = date('Y-m-d', strtotime($params['end_date']));
                        $invoiceData->whereDate('created_at' , '>=', $start_date);
                        $invoiceData->whereDate('created_at' , '<=', $end_date);
                    }
                    else if(isset($params['start_date']) && !empty($params['start_date']))
                    {
                        $start_date = date('Y-m-d', strtotime($params['start_date']));
                        $invoiceData->whereDate('created_at' , '>=', $start_date);
                    }
                    else if(isset($params['end_date']) && !empty($params['end_date']))
                    {
                        $end_date = date('Y-m-d', strtotime($params['end_date']));
                        $invoiceData->whereDate('created_at' , '<=', $end_date);
                    }
                    if (isset($params['contest_id']) && !empty($params['contest_id']))
                    {
                        $invoiceData->where('contest_id', $params['contest_id']);
                    }
                    $invoiceData = $invoiceData->inRandomOrder()->limit($params['winner_count'])->get();
        if (sizeof($invoiceData) > 0)
        {
            $getIds = $invoiceData->pluck('id');
            return view('get_winners', ['invoiceData' => $invoiceData, 'ids' => $getIds]);
        }
        else
        {
            Session::flash('message', 'No winner exist.');
            Session::flash('class', 'danger');
        }
        return redirect()->route('tickets', ['contest_id' => $this->current_active_contest_id]);
    }

    public function randomWinner(Request $request)
    {
        $invoiceData = invoiceDetail::whereHas('user', function($q) {
            $q->where('status', 1);
        })->inRandomOrder()->first();

        return response()->json(['invoiceData' => $invoiceData, 'status' => 1]);
    }
  	// public function index(Request $request, $customer_id)
   //  {
   //      if(!Auth::check())
   //      {
   //          return redirect()->route('signin');
   //      }

   //      if(Auth::user()->user_role == 1)
   //      {
   //          return redirect()->route('users');
   //      }

   //      $params = $request->post();

   //      $getInvoices = invoiceDetail::where(array('user_id' => $customer_id))->get();
   //      return view('admin.customers.customer_tickets', ['tickets' => $getInvoices, 'params' => $params]);
   //  }




   
}
