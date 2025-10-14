<?php
namespace App;

use DB;
use \App\User;
use Illuminate\Database\Eloquent\Model;

class PushNotify extends Model
{
    public static function send_notif($uid, $msg_arr, $notification_type, $extra_data=false)
    {
    	$condition = array(
        	'id' => $uid,
        );

    	$user = User::where($condition)->first();
    	if($user)
    	{
    		$getLoggedInDevices = $user->oauthClients->whereNotNull('device_token');
    		
    		foreach ($getLoggedInDevices as $key => $getLoggedInDevice) {
		    	if ($getLoggedInDevice->device_platform == DEVICE_PLATFORM_IOS || $getLoggedInDevice->device_platform == DEVICE_PLATFORM_ANDROID)
		    	{
			        $device_token = $getLoggedInDevice->device_token;

			        if ($getLoggedInDevice->device_platform == DEVICE_PLATFORM_ANDROID) 
			        {
				        $fcmMsg = array('body' => $msg_arr["body"],
				                        'title' => $msg_arr["title"],
				                        'color' => '#f43b3e',
				                        'icon' => 'myicon',
				                        'sound' => 'default'
				                    );

				        $data = [
				        	'notification_type' => $notification_type,
				        	"id" => 1
				        ];

				        if($notification_type == ORDER_NOTIFICATION_CASE)
				        {
				        	$data['order_details'] = $extra_data;
				        }

				        $fcmFields = array('to' => $device_token, 'priority' => 'high', 'notification' => $fcmMsg, 'data' => $data);

				        $headers = array('Content-Type: application/json',
				                        'Authorization: key=' . FCM_SERVER_KEY);

				        $ch = curl_init();
				        curl_setopt($ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
				        curl_setopt($ch,CURLOPT_POST, true );
				        curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
				        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
				        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
				        curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fcmFields));

				        try {
				        	$result = curl_exec($ch);
					        curl_close($ch);
				        } catch (Exception $e) {
				        	continue;
				        }
			        }

			        else if ($getLoggedInDevice->device_platform == DEVICE_PLATFORM_IOS) 
		            {
		    	        $fcmMsg = array('body' => $msg_arr["body"],
				                        'title' => $msg_arr["title"],
				                        'color' => '#f43b3e',
				                        'icon' => 'myicon',
				                        'sound' => 'default'
				                    );

				        $data = [
				        	'notification_type' => $notification_type,
				        	"id" => 1
				        ];

				        if($notification_type == ORDER_NOTIFICATION_CASE)
				        {
				        	$data['order_details'] = $extra_data;
				        }

		    	        $fcmFields = array('to' => $device_token, 'priority' => 'high', 'notification' => $fcmMsg, 'data' => $data, 'content-available' => true);

		    	        $headers = array('Content-Type: application/json',
		    	                        'Authorization: key=' . FCM_SERVER_KEY);

		    	        $ch = curl_init();
		    	        curl_setopt($ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		    	        curl_setopt($ch,CURLOPT_POST, true );
		    	        curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
		    	        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		    	        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
		    	        curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields));

		    	        try {
				        	$result = curl_exec($ch);
					        curl_close($ch);
				        } catch (Exception $e) {
				        	continue;
				        }
		            }
		    	}

		    }
	    }
    }
}