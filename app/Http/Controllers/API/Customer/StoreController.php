<?php

namespace App\Http\Controllers\API\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;

use App\User;
use Illuminate\Support\Facades\Log;
class StoreController extends Controller
{
  public function index(Request $request)
{
    $params = $request->all();

    Log::info('Store API Request', [
        'params' => $params,
        'ip' => $request->ip(),
        'url' => $request->fullUrl(),
    ]);

    $stores = User::query()
        ->where('user_role', 3)
        ->where('status', 1)
        ->where('is_store_paid', 1);

    /* 🔍 SEARCH BY PINCODE */
    if (!empty($params['q'])) {
        $stores->where('zipcode', 'LIKE', $params['q'] . '%');
    }

    /* 📍 SEARCH BY LOCATION */
    elseif (!empty($params['lat']) && !empty($params['lng'])) {

        $lat = (float) $params['lat'];
        $lng = (float) $params['lng'];
        $radius = 5; // KM

        $stores->whereNotNull('latitudes')
               ->whereNotNull('longitudes')
               ->selectRaw(
                   "users.*, (
                       6371 * acos(
                           cos(radians(?)) 
                           * cos(radians(latitudes)) 
                           * cos(radians(longitudes) - radians(?)) 
                           + sin(radians(?)) 
                           * sin(radians(latitudes))
                       )
                   ) AS distance",
                   [$lat, $lng, $lat]
               )
               ->having('distance', '<=', $radius)
               ->orderBy('distance');
    }

    return response()->json([
        'status' => 1,
        'message' => 'Store List',
        'data' => $stores->paginate($params['per_page'] ?? 15)
    ]);
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


    public function indexOldNew(Request $request)
{
    $params = $request->all();

    $stores = User::where([
        'user_role' => 3,
        'status' => 1,
        'is_store_paid' => 1
    ]);

    if (!empty($params['q'])) {

        $stores->where('zipcode', 'LIKE', $params['q'].'%');

    } elseif (!empty($params['lat']) && !empty($params['lng'])) {

        $lat = $params['lat'];
        $lng = $params['lng'];
        $radius =  5;

        $stores->selectRaw(
            "*, 6371 * acos(
                cos(radians(?)) * cos(radians(latitudes)) *
                cos(radians(longitudes) - radians(?)) +
                sin(radians(?)) * sin(radians(latitudes))
            ) AS distance",
            [$lat, $lng, $lat]
        )
        ->whereRaw(
            "6371 * acos(
                cos(radians(?)) * cos(radians(latitudes)) *
                cos(radians(longitudes) - radians(?)) +
                sin(radians(?)) * sin(radians(latitudes))
            ) < ?",
            [$lat, $lng, $lat, $radius]
        )
        ->orderBy('distance');
    }

    return response()->json([
        'status' => 1,
        'message' => 'Store List',
        'data' => $stores->paginate($params['per_page'] ?? 15)
    ]);
}





}