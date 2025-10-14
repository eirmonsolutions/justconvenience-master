<?php

namespace App\Exports;

use Illuminate\Http\Request;
use App\User;
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
        $getCustomers = User::where('user_role', 4);

        if(isset($params['search_fields']) && !empty($params['search_fields']))
        {
            $getCustomers->where(function($qs) use($params) {
                $qs->where('email', 'Like', $params['search_fields'].'%');
                $qs->orWhere('name', 'Like', $params['search_fields'].'%');
                $qs->orWhere('phone_number', 'Like', $params['search_fields'].'%');
                $qs->orWhere('city', 'Like', $params['search_fields'].'%');
                $qs->orWhere('state', 'Like', $params['search_fields'].'%'); 
                $qs->orWhere('zipcode', 'Like', $params['search_fields'].'%'); 
                $qs->orWhere('id', 'Like', $params['search_fields'].'%');
             });
        }

        if (isset($params['start_date']) && !empty($params['start_date']) && isset($params['end_date']) && !empty($params['end_date']))
        {
            $start_date = date('Y-m-d', strtotime($params['start_date']));
            $end_date = date('Y-m-d', strtotime($params['end_date']));

            $getCustomers->whereDate('created_at' , '>=', $start_date);
            $getCustomers->whereDate('created_at' , '<=', $end_date);
        }
        else if(isset($params['start_date']) && !empty($params['start_date']))
        {
            $start_date = date('Y-m-d', strtotime($params['start_date']));
            $getCustomers->whereDate('created_at' , '>=', $start_date);
        }
        else if(isset($params['end_date']) && !empty($params['end_date']))
        {
            $end_date = date('Y-m-d', strtotime($params['end_date']));
            $getCustomers->whereDate('created_at' , '<=', $end_date);
        } 

        $getCustomers = $getCustomers->orderBy('name', 'ASC')->get();
        return $getCustomers;
    }

    public function map($getCustomers) : array {
        return [ 
            $getCustomers->id,
            ucwords($getCustomers->name),
            $getCustomers->email,
            $getCustomers->phone_number,
            $getCustomers->city,
            $getCustomers->state,
        ];
    }

    public function headings() :array
    {
        return array("Id", "Name", "Email", "Phone Number", "City", "State");
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
                    'F' => 45
                );

                foreach($columns as $column => $val){
                    $event->sheet->getDelegate()->getColumnDimension($column)->setWidth($val);
                }
            },
        ];
    }
}