<?php

namespace App\Exports;

use Illuminate\Http\Request;
use App\User;
use App\customerInvoice;
use App\invoiceDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
// use Maatwebsite\Excel\Concerns\WithColumnWidth;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class Ticket implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $params_set;

    public function __construct($params_set)
    {   
        ini_set('memory_limit','2000M');
        ini_set('max_execution_time', '0');
        $this->params_set = $params_set;
     }


    public function collection()
    {
        
        $params = $this->params_set;
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
                    return $total_records = $total_records->get();
    }

    public function map($registration) : array {
        return [
            $registration->id,
            $registration->user->name,
            $registration->user->last_name,
            $registration->user->email,
            $registration->user->phone_number,
            $registration->user->indentification_card,
            $registration->user->direction,
        ] ;
 
 
    }

    public function headings() :array
    {
        return array("Ticket Number", "Name", "Last Name", "Email","Phone Number", "Id Nubmer/Ruc/Passport", "Direction");
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
                    'A' => 20,
                    'B' => 20,
                    'C' => 20,
                    'D' => 30,
                    'E' => 20,
                    'F' => 30,
                    'G' => 45,
                );

                foreach($columns as $column => $val){
                    $event->sheet->getDelegate()->getColumnDimension($column)->setWidth($val);
                }
            },
        ];
    }
}
