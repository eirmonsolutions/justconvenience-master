<?php

namespace App\Http\Controllers\API\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;

use App\User;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $mapsApiKey = 'AIzaSyDAMHOGEbJAHAW7m-aZrX1MOqzRLlSdEuQ';
        $radius = 5;
        $lat = $lng = '';
        $params = $request->all();

        if(isset($params['q']) && !empty($params['q']))
        {
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($params['q']) . "&key=" . $mapsApiKey;
            
            $result_string = @file_get_contents($url);
            if($result_string)
            {
                // convert into readable format
                $result = json_decode($result_string, true);
                // print_r($result); die();
                if($result && $result['results'])
                {
                    $commonVar = $result['results'][0]['geometry']['location'];
                    if($commonVar)
                    {
                        // print_r($commonVar); die();
                        $lat = $commonVar['lat'];
                        $lng = $commonVar['lng'];
                    }
                    else
                    {
                        return response()->json(['status' => 0, 'message' => 'Something wrong while fetching location.'])->setStatusCode(200);
                    }
                }
                else
                {
                    return response()->json(['status' => 0, 'message' => 'Something wrong with API Key.'])->setStatusCode(200);
                }
            }
            else
            {
                return response()->json(['status' => 0, 'message' => 'Something wrong with API Key.'])->setStatusCode(200);
            }
        }
        else if((isset($params['lat']) && !empty($params['lat'])) && (isset($params['lng']) && !empty($params['lng'])))
        {
            $lat = $params['lat']; // user's latitude
            $lng = $params['lng']; // user's longitude
        }

        if(empty($lat) || empty($lng))
        {
            return response()->json(['status' => 0, 'message' => 'Unable to fetch location.'])->setStatusCode(200);
        }

        $stores = User::select('*', \DB::raw("3959* acos( cos( radians($lat) ) * cos( radians( latitudes ) ) * cos( radians( longitudes ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( latitudes ) ) ) AS distance"))
            ->where(['user_role' => 3, 'status' => 1, 'is_store_paid' => 1])
            ->having('distance', '<=', $radius)
            ->orderBy('distance', 'ASC')
            ->paginate();

        return response()->json(['status' => 1, 'message' => 'Store List.', 'data' => $stores])->setStatusCode(200);
    }

    public function indexOld(Request $request)
    {
        $params = $request->all();

        if(isset($params['q']) && !empty($params['q']))
        {
            $stores = User::select('*')->where(['user_role' => 3, 'status' => 1, 'is_store_paid' => 1])->where(function($query) use($params) {
                    $query->where('zipcode', 'Like', $params['q'].'%');
                });
        }
        else if((isset($params['lat']) && !empty($params['lat'])) && (isset($params['lng']) && !empty($params['lng'])))
        {
            $radius = 5;
            $lat = $params['lat']; // user's latitude
            $lng = $params['lng']; // user's longitude
            $stores = User::select('*', \DB::raw("6371* acos( cos( radians($lat) ) * cos( radians( latitudes ) ) * cos( radians( longitudes ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( latitudes ) ) ) AS distance"))
                ->where(['user_role' => 3, 'status' => 1, 'is_store_paid' => 1])
                ->having('distance', '<', $radius)
                ->orderBy('distance', 'ASC');
        }
        else
        {
            $stores = User::select('*')->where(['user_role' => 3, 'status' => 1, 'is_store_paid' => 1]);
        }

        $stores = $stores->paginate();
        return response()->json(['status' => 1, 'message' => 'Store List.', 'data' => $stores])->setStatusCode(200);
    }
}