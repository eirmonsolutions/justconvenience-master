<?php

namespace App\Exports;

use Illuminate\Http\Request;
use App\User;
use App\customerInvoice;
use App\invoiceDetail;
use App\Contest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
// use Maatwebsite\Excel\Concerns\WithColumnWidth;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;


class Customers implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $params_set;

    public function __construct($params_set)
    {   
        ini_set('memory_limit','3000M');
        ini_set('max_execution_time', '0');
        $this->params_set = $params_set;
     }


    public function collection()
    {
        
        $params = $this->params_set;
        $getCustomers = User::where('status', 1)->where('user_role', 4);

        if(isset($params['tag']) && !empty($params['tag']) && $params['tag'] == 'new')
        {
            $getCustomers = $getCustomers->where('is_reviewed', 0);
        }

        if(isset($params['search_fields']) && !empty($params['search_fields']))
        {
            $getCustomers->where(function($qs) use($params) {
                $qs->where('email', 'Like', '%'.$params['search_fields'].'%');
                $qs->orWhere('name', 'Like', $params['search_fields'].'%');
                $qs->orWhere('last_name', 'Like', $params['search_fields'].'%');
                $qs->orWhere('phone_number', 'Like', $params['search_fields'].'%');
                $qs->orWhere('indentification_card', 'Like', $params['search_fields'].'%');
                $qs->orWhere('direction', 'Like', $params['search_fields'].'%');
             });
        }


        if(isset($params['shop_id']) && !empty($params['shop_id']))
        {
            $getCustomers = $getCustomers->whereHas('invoices', function ($query) use ($params) {
                    $query->where('shop_id', $params['shop_id']);
            });
        }

        if(isset($params['tag']) && !empty($params['tag']) && ($params['tag'] == 'approved' || $params['tag'] == 'pending'))
        {
            $getCustomers = $getCustomers->whereHas('invoices', function ($query) use ($params) {

                if($params['tag'] == 'approved')
                {
                    $query->where('tag', $params['tag']);
                }
                else
                {
                    $query->where('tag', '!=','approved');
                }
            });
        }

        if (isset($params['start_date']) && !empty($params['start_date']) && isset($params['end_date']) && !empty($params['end_date']))
        {
            $getCustomers = $getCustomers->whereHas('invoices', function ($query) use ($params) {
                $start_date = date('Y-m-d', strtotime($params['start_date']));
                $end_date = date('Y-m-d', strtotime($params['end_date']));
                
                $query->whereDate('invoice_date' , '>=', $start_date);
                $query->whereDate('invoice_date' , '<=', $end_date);
            });
        }
        else if(isset($params['start_date']) && !empty($params['start_date']))
        {
            $getCustomers = $getCustomers->whereHas('invoices', function ($query) use ($params) {
                $start_date = date('Y-m-d', strtotime($params['start_date']));
                $query->whereDate('invoice_date' , '>=', $start_date);
            });

        }
        else if(isset($params['end_date']) && !empty($params['end_date']))
        {
            $getCustomers = $getCustomers->whereHas('invoices', function ($query) use ($params) {
                $end_date = date('Y-m-d', strtotime($params['end_date']));
                $query->whereDate('invoice_date' , '<=', $end_date);
            });
        }
        
        if(isset($params['total_invoice_val']) && !empty($params['total_invoice_val']))
        {
            $getCustomers = $getCustomers->where('total_invoice_val', '>=', $params['total_invoice_val']);
        }
        
        if(isset($params['ticket_count']) && !empty($params['ticket_count']))
        {
            $getCustomers = $getCustomers->where('ticket_count', '>=', $params['ticket_count']);
        }

     
        if (isset($params['contest_id']) && !empty($params['contest_id']))
        {
            // $getCustomers = $getCustomers->where('contest_id', $params['contest_id']);
            $getCustomers = $getCustomers->where(static function ($query) use ($params) {
                                        $query->whereHas('invoices', function ($q) use ($params) {
                                                $q->where('contest_id', $params['contest_id']);
                                            })
                                            ->orWhere('contest_id', $params['contest_id']);
                                    });
        } 

        $getCustomers = $getCustomers->orderBy('name', 'ASC')->get();
        return $getCustomers;
    }

    public function map($getCustomers) : array {
        $invoice_str = '';
        $invoice_dates = $getCustomers->invoices->pluck('invoice_date')->toArray();

        $totalCount = sizeof($invoice_dates);
        if($totalCount > 0)
        {
            foreach ($invoice_dates as $keyID => $valueID)
            {
                $invoice_str .= date('d M Y', strtotime($valueID));
                if (($totalCount - 1) > $keyID) 
                {
                    $invoice_str .= '||||';
                }
            }
        }
        return [ 
            ucwords($getCustomers->name.' '.$getCustomers->last_name),
            $getCustomers->email,
            $getCustomers->phone_number,
            $getCustomers->direction,
            implode('||||', $getCustomers->invoices->pluck('local')->toArray()),            
            $invoice_str,
            $getCustomers->invoices->sum('invoice_amount'),
            $getCustomers->invoices->count(),
            $getCustomers->ticket_count,
            $getCustomers->indentification_card,
        ] ;
 
 
    }

    public function headings() :array
    {
        return array("Customer Name", "Email", "Phone Number", "Direction", "Store", "Date of receipts", "Total Value of receipts", "No. of receipts", "No. of tickets", "Id Nubmer/Ruc/Passport");
    }

    /**
     * @return array
     */
    
    public function columnWidths(): array
    {
        return [
            'A' => 55,
            'B' => 45,            
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
                // $event->sheet->getDelegate()->getDefaultColumnDimension()->setWidth(30);
                $columns = array(
                    'A' => 35,
                    'B' => 35,
                    'C' => 25,
                    'D' => 30,
                    'E' => 45,
                    'F' => 45,
                    'G' => 20,
                    'H' => 20,
                    'I' => 20,
                    'J' => 45 
                );

                foreach($columns as $column => $val){
                    $event->sheet->getDelegate()->getColumnDimension($column)->setWidth($val);
                }
            },
        ];
    }
}
