<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLocation extends BaseModel
{
    use HasFactory;

    protected $table = 'user_locations';
    protected $fillable = ['ip_address', 'country_name', 'country_code', 'region_code', 'region_name', 'city_name', 'zip_code', 'iso_code', 'postal_code', 'latitude', 'longitude', 'area_code', 'timezone'];
}
