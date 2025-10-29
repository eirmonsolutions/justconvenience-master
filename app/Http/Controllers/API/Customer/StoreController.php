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
    $params = $request->all();

    if (!isset($params['q']) || empty($params['q'])) {
        return response()->json(['status' => 0, 'message' => 'Please enter a zipcode.'], 200);
    }

    $zipcode = $params['q'];

    $stores = User::where([
            'user_role' => 3,
            'status' => 1,
            'is_store_paid' => 1,
        ])
        ->where('zipcode', 'LIKE', "%{$zipcode}%")
        ->paginate();

    if ($stores->isEmpty()) {
        return response()->json(['status' => 0, 'message' => 'No stores found for this zipcode.'], 200);
    }

    return response()->json(['status' => 1, 'message' => 'Store List.', 'data' => $stores], 200);
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