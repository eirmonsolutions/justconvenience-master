<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthClient extends Model
{
   protected $fillable = ['user_id', 'api_token', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\User')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }
}
