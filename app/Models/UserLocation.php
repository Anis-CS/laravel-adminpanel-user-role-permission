<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLocation extends Model
{
    use HasFactory;

    protected $table = 'user_locations'; // Table name explicitly define করুন

    protected $fillable = [
        'user_id',
        'ip_address',
        'country_name',
        'country_code',
        'region_name',
        'region_code',
        'city_name',
        'zip_code',
        'iso_code',
        'postal_code',
        'area_code',
        'latitude',
        'longitude',
        'timezone',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the location
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}