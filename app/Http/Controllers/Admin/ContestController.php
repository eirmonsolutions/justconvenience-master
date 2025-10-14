<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;

use App\Contest;
use App\User;
use App\Customer;
use App\customerInvoice;
use App\invoiceDetail;
use App\Setting;

use Carbon\Carbon;

class ContestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if(Auth::user()->user_role == 3)
            {
                return redirect()->route('dashboard');
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $params = $request->post();

        $getContests = Contest::withCount('invoiceDetails', 'users')->get();

        return view('admin.contests.index', ['contests' => $getContests, 'params' => $params]);
    }

    public function addContest(Request $request)
    {
        return view('admin.contests.add_contest');
    }

    public function saveContest(Request $request)
    {
        // create the validation rules ------------------------
        $rules = array(
            'title'             => 'required',
            'description'            => 'required',
            'featured_image'         => 'required',
            'start_date'         => 'required',
            'end_date'         => 'required'
        );

        // do the validation ----------------------------------
        // validate against the inputs from our form
        $validator = Validator::make($request->all(), $rules);

        // check if the validator failed -----------------------
        if ($validator->fails()) {

            // get the error messages from the validator
            // $messages = $validator->messages();

            Session::flash('message', $validator->errors()->first()); 
            Session::flash('class', 'danger');

            return redirect()->route('add-contest')->withInput($request->all());
        }

        $params = $request->post();

        $today = strtotime(date('Y-m-d'));
        $start_date = strtotime($params['start_date']);
        $end_date = strtotime($params['end_date']);

        if ($today > $start_date)
        {
        	Session::flash('message', 'Start Date should be at least today.'); 
        	Session::flash('class', 'danger');

        	return redirect()->route('add-contest')->withInput($request->all());
        }

        if ($start_date > $end_date)
        {
        	Session::flash('message', 'Start Date should be greater than or equal to End Date.'); 
        	Session::flash('class', 'danger');

        	return redirect()->route('add-contest')->withInput($request->all());
        }

        $sDate = date('Y-m-d', $start_date);
        $eDate =  date('Y-m-d', $end_date);

        $checkConstentExistance = Contest::where(function ($query) use ($sDate, $eDate) {
        									$query->where(function ($q) use ($sDate, $eDate) {
        											$q->whereBetween('start_date', [$sDate, $eDate])
        											  	->orwhereBetween('end_date', [$sDate, $eDate]);
        									       	})->orWhere(function ($q) use ($sDate, $eDate) {
        									           $q->whereDate('start_date', '<=', $sDate)
        									           		->whereDate('end_date', '>=', $eDate);
        									       	});
										})
        								->first();
        if ($checkConstentExistance)
        {
        	Session::flash('message', 'Contest Already Running between these dates.'); 
        	Session::flash('class', 'danger');

        	return redirect()->route('add-contest')->withInput($request->all());
        }

        
        $contest = new Contest;
        $contest->title = $params['title'];
        $contest->description = $params['description'];
        $contest->start_date = date('Y-m-d', strtotime($params['start_date']));
        $contest->end_date = date('Y-m-d', strtotime($params['end_date']));

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/contests');
            $image->move($destinationPath, $name);
            
            $contest->featured_image = 'public/contests/' . $name;
        }

        if($contest->save())
        {
        	Session::flash('message', 'Contest has created successfully.'); 
        	Session::flash('class', 'success');
        }
        else
        {
            Session::flash('message', 'Something went wrong.'); 
            Session::flash('class', 'danger'); 
        }

        return redirect()->route('contests');
    }

    public function editContest(Request $request, $id)
    {
        $getContestData = Contest::find($id);
        if ($getContestData)
        {
        	return view('admin.contests.edit_contest', ['data' => $getContestData]);
        }
        else
        {
        	Session::flash('message', 'No Contest Found'); 
        	Session::flash('class', 'danger');	
        	return redirect()->route('contests');
        }

    }

    public function updateContest(Request $request)
    {
        // create the validation rules ------------------------
        $rules = array(
            'id'             => 'required',
            'title'             => 'required',
            'description'            => 'required',
            'start_date'         => 'required',
            'end_date'         => 'required'
        );

        // do the validation ----------------------------------
        // validate against the inputs from our form
        $validator = Validator::make($request->all(), $rules);

        // check if the validator failed -----------------------
        if ($validator->fails()) {

            // get the error messages from the validator
            // $messages = $validator->messages();

            Session::flash('message', $validator->errors()->first()); 
            Session::flash('class', 'danger');

            return redirect()->route('admin.edit-contest', ['contest_id' => $request->id])->withInput($request->all());
        }

        $params = $request->post();

        $contest = Contest::find($params['id']);
        if (!$contest)
        {
        	Session::flash('message', 'No Contest Found'); 
        	Session::flash('class', 'danger');	
        	return redirect()->route('contests');
        }

        $today = strtotime(date('Y-m-d'));
        $start_date = strtotime($params['start_date']);
        $end_date = strtotime($params['end_date']);

        if ($today > $end_date)
        {
        	Session::flash('message', 'End Date should be at least today.'); 
        	Session::flash('class', 'danger');

        	return redirect()->route('edit-contest', ['contest_id' => $params['id']])->withInput($request->all());
        }

        if ($start_date > $end_date)
        {
        	Session::flash('message', 'Start Date should be greater than or equal to End Date.'); 
        	Session::flash('class', 'danger');

        	return redirect()->route('edit-contest', ['contest_id' => $params['id']])->withInput($request->all());
        }

        $sDate = date('Y-m-d', $start_date);
        $eDate =  date('Y-m-d', $end_date);

        $checkConstentExistance = Contest::where(function ($query) use ($sDate, $eDate) {
        									$query->where(function ($q) use ($sDate, $eDate) {
        											$q->whereBetween('start_date', [$sDate, $eDate])
        											  	->orwhereBetween('end_date', [$sDate, $eDate]);
        									       	})->orWhere(function ($q) use ($sDate, $eDate) {
        									           $q->whereDate('start_date', '<=', $sDate)
        									           		->whereDate('end_date', '>=', $eDate);
        									       	});
										})
										->whereNotIn('id', [$params['id']])
        								->first();

        if ($checkConstentExistance)
        {
        	Session::flash('message', 'Contest Already Running between these dates.'); 
        	Session::flash('class', 'danger');

        	return redirect()->route('edit-contest', ['contest_id' => $params['id']])->withInput($request->all());
        }

        
        $contest->title = $params['title'];
        $contest->description = $params['description'];
        $contest->start_date = date('Y-m-d', strtotime($params['start_date']));
        $contest->end_date = date('Y-m-d', strtotime($params['end_date']));

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/contests');
            $image->move($destinationPath, $name);
            
            $contest->featured_image = 'public/contests/' . $name;
        }

        if($contest->save())
        {
        	Session::flash('message', 'Contest has updated successfully.'); 
        	Session::flash('class', 'success');
        }
        else
        {
            Session::flash('message', 'Something went wrong.'); 
            Session::flash('class', 'danger'); 
        }

        return redirect()->route('contests');
    }

    public function contestDetails(Request $request, $id)
    {
        $getContestData = Contest::with(['TodayCustomerInvoices', 'customerInvoices', 'Last30DaysCustomerInvoices', 'Last7DaysCustomerInvoices'])->withCount(['invoiceDetails', 'todayInvoiceDetails'])->where('id', $id)->orderBy('id', 'desc')->first();
        if ($getContestData) 
        {
            $getContestData->todayInvoiceAmountSum = $getContestData->TodayCustomerInvoices->sum('invoice_amount');
            $getContestData->todayInvoiceAmountAvg = $getContestData->TodayCustomerInvoices->avg('invoice_amount');
            $getContestData->todayInvoiceAmountMax = $getContestData->TodayCustomerInvoices->max('invoice_amount');
            $getContestData->todayCustomerCount = $getContestData->TodayCustomerInvoices->unique('user_id')->count();
            $getContestData->todayTotalReciepts = $getContestData->TodayCustomerInvoices->count(); 

            $getContestData->invoiceAmountSum = $getContestData->customerInvoices->sum('invoice_amount');
            $getContestData->invoiceAmountAvg = $getContestData->customerInvoices->avg('invoice_amount');
            $getContestData->invoiceAmountMax = $getContestData->customerInvoices->max('invoice_amount');
            $getContestData->customerCount = $getContestData->customerInvoices->unique('user_id')->count();
            $getContestData->totalReciepts = $getContestData->customerInvoices->count();

            $getContestData->lastWeekTotalReciepts = $getContestData->Last7DaysCustomerInvoices->count();

            $getContestData->weeklyRecieptCountAvg = '';
            if ($getContestData->lastWeekTotalReciepts > 0)  
            {
                $getContestData->weeklyRecieptCountAvg = number_format($getContestData->lastWeekTotalReciepts/7, 2);
            }

            $getContestData->customerCountAvg = '';
            $getLast30DaysCustomerCount = $getContestData->Last30DaysCustomerInvoices->unique('user_id')->count();
            if($getLast30DaysCustomerCount > 0)
            {
                $getContestData->customerCountAvg = number_format($getLast30DaysCustomerCount/30, 2);
            }

            $getContestData->recieptCountAvg = '';
            $getLast30DaysRecieptCount = $getContestData->Last30DaysCustomerInvoices->count();
            if($getLast30DaysRecieptCount > 0)
            {
                $getContestData->recieptCountAvg = number_format($getLast30DaysRecieptCount/30, 2);
            }


            return view('admin.contests.contest_details', ['contest' => $getContestData]);
        }
        else
        {
            Session::flash('message', 'No Contest Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('contests');
        }

    }

    public function updateContestStatus(Request $request, $id, $status)
    {
        if (\Request::isMethod('post'))
        {
            return redirect()->route('contests');
        }

        $getContestData = Contest::find($id);
        if (!$getContestData)
        {
            Session::flash('message', 'No Contest Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('contests');
        }

        $message = 'Contest Deactivated successfully';
        if ($status)
        {
            $message = 'Contest Activated successfully';
        }

        $getContestData->status = $status;

        if ($getContestData->save())
        {
            return response()->json(['message' => $message, 'status' => 1]);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.']);
        }
    }

}