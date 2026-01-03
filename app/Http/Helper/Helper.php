<?php

namespace App\Http\Helper;

use App\Models\UserLocation;


function SaveCurrentLocation()
{
    // $ip = $_SERVER['REMOTE_ADDR'];
    $ip = request()->ip();

    if ($ip == "127.0.0.1") {
        $ip = "203.76.126.217";
    }

    $user = Auth()->user()->id;
    $data = Location::get($ip);
    $user_location_check = UserLocation::where('ip_address', $ip)
        ->where('user_id', $user)
        ->whereDate('created_at', Carbon::today())
        ->first();

    // if($user_location_check == null && $data != false)
    if (is_null($user_location_check) && $data) {
        if (!empty($data->countryName)) {
            $location = new UserLocation();
            $location->user_id = $user;
            $location->ip_address = $data->ip;
            $location->country_name = $data->countryName;
            $location->country_code = $data->countryCode;
            $location->region_name = $data->regionName;
            $location->region_code = $data->regionCode;
            $location->city_name = $data->cityName;
            $location->zip_code = $data->zipCode;
            $location->iso_code = $data->isoCode;
            $location->postal_code = $data->postalCode;
            $location->area_code = $data->areaCode;
            $location->timezone = $data->timezone;
            $location->latitude = $data->latitude;
            $location->longitude = $data->longitude;
            $location->save();
        } else {
            Log::info('Location data not available for IP: ' . $ip);
        }
    }
}