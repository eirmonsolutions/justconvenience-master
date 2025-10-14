<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shop;
use DB;
use Validator;
use Auth;
use Session;

use App\Lead;
use App\Contest;
use App\Customer;
use App\customerInvoice;
use App\invoiceDetail;
use App\Setting;

use App\Product;
use App\Category;
use App\subCategory;

use Carbon\Carbon;
 
use App\User;
use Yajra\Datatables\Datatables;
// use Excel;
use App\Exports\Ticket;
use Maatwebsite\Excel\Facades\Excel;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function downloadExcel(Request $request)
    {
        $params = $request->input();
        ob_end_clean();
        return Excel::download(new Ticket($params), 'Tickets.xlsx');
    }

    public function import_records(Request $request){ 
        
        $extension = '';
        $file = '';

        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            $extension = strtolower($request->file('file_upload')->getClientOriginalExtension());
        }

        /*$validator = Validator::make(
            [
                'file_upload'      => $file,
                'extension' => $extension,
            ],
            [
                'file_upload'          => 'required',
                'extension'      => 'required|in:csv',
            ],
            [
                'file_upload'          => 'Please upload csv file.', 
            ]
        );


        // check if the validator failed -----------------------
        if ($validator->fails()) {
            Session::flash('message', $validator->errors()->first()); 
            Session::flash('class', 'danger');
            return redirect()->back();
        }*/

        if ($request->hasFile('file_upload')) {
            $image = $request->file('file_upload'); 
             $name = time().'.'.$image->getClientOriginalExtension(); 
             $destinationPath = public_path('/csv'); 
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $image->move($destinationPath, $name); 
            $filePath = url('/') . '/public/csv/' . $name;
        }
 
        $file = public_path('csv/'.$name);
        
        /*$customerArr = $this->csvToArray($file); 
        $i = 1;
        foreach ($customerArr as $key => $shops) {
            foreach ($shops as $keys => $shop_val) {   
                if(!empty($shop_val)){
                    $shop = new Shop;
                    // $shop->setConnection('mysql');
                    $shop->shop_name = $shop_val;
                    $shop->save();
                } 
            } 
        }*/

        $customerArr = Excel::toArray(new User, $file);
        // $customerArr = $this->csvToArrayNew($file); 
        // print_r($customerArr); die();
        // echo "<pre>";
        // print_r($customerArr);
        $i = 1;
        foreach ($customerArr[0] as $key => $productK) 
        {
            if($key == 0)
            {
                continue;
            }
            // print_r($product); die();
            /*$arrayCount = sizeof($shops);
            if($arrayCount > 0)
            {*/
                $product = new Product;
                if(!empty(trim($productK[1])))
                {
                    $category = Category::where(['type' => 1, 'name' => trim($productK[1])])->first();
                    if(empty($category))
                    {

                        $category = new Category;
                        $category->name = trim($productK[1]);
                        $category->featured_image = '2';
                        $category->save();
                    }
                }

                if(!empty(trim($productK[2])))
                {
                    $subcategory = subCategory::where(['type' => 1, 'name' => trim($productK[2])])->first();
                    if(empty($subcategory))
                    {

                        $subcategory = new subCategory;
                        $subcategory->category_id = $category->id;
                        $subcategory->name = trim($productK[2]);
                        $subcategory->featured_image = '2';
                        $subcategory->save();
                    }
                }

                $product = new Product;
                $product->name = trim($productK[3]);
                $product->price = trim($productK[4]);
                $product->description = trim($productK[6]);
                $product->featured_image = trim($productK[7]);
                $product->category_id = $category->id;
                $product->subcategory_id = isset($subCategory) ? $subcategory->id : '';

                $product->save();
            /*}*/  
        }
        die('complete');

       if(file_exists($file)){
            chmod($file, 0644);
            unlink($file);
            // echo 'Deleted old image';
        }
        Session::flash('message', 'Shops updated successfully.'); 
        Session::flash('class', 'success');
        return redirect()->back();
         // die('Done'); 
    }

    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {   
                $row = array_map("utf8_encode", $row); 
                $row = str_replace(";", ", ", $row);
                if (!$header)
                    $header = $row;
                else
                    $data[] = $row;
            }
            fclose($handle);
        }
        return $data;
    }

    function csvToArrayNew($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {   
                $row = array_map("utf8_encode", $row);
                $row = explode(';', $row[0]);
                if (!$header)
                    $header = $row;
                else
                    $data[] = $row;
            }
            fclose($handle);
        }
        return $data;
    }
    
    public function shop_invoices_update(){
        // echo "here"; die;
        echo "<pre>";
        // $duplicate_names = DB::select("SELECT shop_name FROM shops group by shop_name having count(*) >= 2");
        $shops = DB::table('shops')->select("contract_number","shop_name","id", "shopping_center_id", DB::raw("CONCAT(shop_name,',',contract_number,',',shopping_center_id) AS full_name"))
        ->orderBy("shop_name", "ASC")
        ->get()->toArray();
        // $duplicate_names_array = array_column($duplicate_names, 'shop_name');
 
        // print_r($shops); 
        // die;
        // print_r($duplicate_names_array); 
        // print_r($value); 
        // die;
        // $shop = DB::table('shops')->get();
        // $exist = array();
        // $notFound = array();
        foreach($shops as $k => $shop_details){
            if($shop_details->id != 9){
                // continue;
            }
            // print_r($shop_details);
            // echo "</br>";
           // echo  utf8_decode($shop_details->full_name);
                       // echo "</br>";

           // echo  utf8_encode($shop_details->full_name);
            // $shop_details = array_map("utf8_encode", $shop_details);
            // print_r($shop_details);
            // $shop_name = utf8_encode($shop_details->full_name);
            // $shop_name1 = utf8_decode($shop_details->full_name);
            echo $shop_name = trim($shop_details->shop_name).', '.trim($shop_details->contract_number).', '.trim($shop_details->shopping_center_id);
            // $shop_name = $shop_details->full_name;
            echo "</br>";
            echo "SHOP ID =  ".$shop_details->id;
            echo "</br>";

            // echo "SHOP NAME old =  ".$shop_details->full_name;
            //             echo "</br>";

            // echo "SHOP NAME decode =  ".$shop_name1;
            //             echo "</br>";

            echo "SHOP NAME =  ".$shop_name;
            echo "</br>";
            echo "</br>";
            // die;
            // continue;
            $invioces = DB::table('customer_invoices')->select("id","local","shop_id")
            ->where('local', 'like',  '%' .$shop_name .'%')
            ->get()->toArray();
            // echo "</br>";
            // echo "SHop Name = ". $shop_name->shop_name;
            // echo "</br>";
            $id_list = array_column($invioces, 'id');
            // echo implode(',', $id_list);
            // echo "</br>";
            // print_r($id_list);
            // echo "</br>";
            // print_r($invioces);
            $data_array = array(
                'shop_id' => $shop_details->id,
                'local' => $shop_name,
            );
            // print_r($data_array);
            // echo "</br>";
            // die;
           echo $record =  DB::table('customer_invoices')->whereIn('id', $id_list)->update($data_array);
           echo "</br>";
           // echo "================</br>";
            // die('in loop');
        }
        // echo implode(',', $notFound);
        // echo "</br>";
        // echo "</br>=========Not Found======</br>";
        // print_r($notFound); 
        // echo "</br>=========Found======</br>";
        // print_r($exist); 
        echo "Complete";
        die;
    }
}
