<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \App\Setting;
use \App\Contest;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $data = [];
        $getHomeSettings = \App\Setting::where(['meta_key' => 'home_page'])->first();
        if($getHomeSettings)
        {
            $data['homeData'] = unserialize($getHomeSettings->meta_value);
        }

        $getTncSettings = \App\Setting::where(['meta_key' => 'tnc'])->first();
        if($getTncSettings)
        {
            $data['tnc'] = unserialize($getTncSettings->meta_value);
        }

        $getImageUploadingSettings = \App\Setting::where(['meta_key' => 'image_upload'])->first();
        if($getImageUploadingSettings)
        {
            $data['image_upload'] = $getImageUploadingSettings->meta_value;
        }

        $todayDate = date('Y-m-d');
        $activeContestRecord = Contest::where(function ($query) use ($todayDate) {
                                            $query->whereDate('start_date', '<=', $todayDate)
                                                    ->whereDate('end_date', '>=', $todayDate);
                                        })
                                        ->first();
        if($activeContestRecord)
        {
            $data['current_active_contest_id'] = $activeContestRecord->id;
            $data['contest'] = $activeContestRecord;
            $data['is_contest_running'] = 1;
        }
        else
        {
            $data['current_active_contest_id'] = '';
            $data['contest'] = NULL;
            $data['is_contest_running'] = 0;
        }

        $data['max_value_limit'] = 1000;

        \View::share('data', $data);
    }
}
