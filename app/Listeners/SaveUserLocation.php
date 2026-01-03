<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\UserLocation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SaveUserLocation
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        try {
            $user = $event->user;
            $ip = request()->ip();
            
            Log::info('SaveUserLocation triggered', [
                'user_id' => $user->id,
                'ip' => $ip
            ]);
            
            // Local environment-এ test করার জন্য
            if ($ip === '127.0.0.1' || $ip === '::1' || $ip === 'localhost') {
                $ip = '203.76.126.242'; // Bangladesh IP for testing
                Log::info('Using test IP', ['ip' => $ip]);
            }
            
            $locationData = $this->getDetailedLocation($ip);
            
            if ($locationData) {
                $savedLocation = UserLocation::create([
                    'user_id' => $user->id,
                    'ip_address' => $ip,
                    'country_name' => $locationData['country_name'] ?? null,
                    'country_code' => $locationData['country_code'] ?? null,
                    'region_name' => $locationData['region_name'] ?? null,
                    'region_code' => $locationData['region_code'] ?? null,
                    'city_name' => $locationData['city_name'] ?? null,
                    'zip_code' => $locationData['zip_code'] ?? null,
                    'iso_code' => $locationData['iso_code'] ?? null,
                    'postal_code' => $locationData['postal_code'] ?? null,
                    'area_code' => $locationData['area_code'] ?? null,
                    'latitude' => $locationData['latitude'] ?? null,
                    'longitude' => $locationData['longitude'] ?? null,
                    'timezone' => $locationData['timezone'] ?? null,
                ]);
                
                Log::info('User location saved successfully', [
                    'location_id' => $savedLocation->id,
                    'user_id' => $user->id
                ]);
            } else {
                Log::warning('Location data not available', ['ip' => $ip]);
            }
            
        } catch (\Exception $e) {
            Log::error('SaveUserLocation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    
    /**
     * Get detailed location from IP
     */
    private function getDetailedLocation($ip)
    {
        try {
            Log::info('Fetching location for IP', ['ip' => $ip]);
            
            // ip-api.com (Free, no API key needed, 45 requests/minute)
            $response = Http::timeout(10)->get("http://ip-api.com/json/{$ip}?fields=status,message,country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,query");
            
            Log::info('IP API Response', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);
            
            if ($response->successful() && $response->json('status') === 'success') {
                $data = $response->json();
                
                return [
                    'country_name' => $data['country'] ?? null,
                    'country_code' => $data['countryCode'] ?? null,
                    'region_name' => $data['regionName'] ?? null,
                    'region_code' => $data['region'] ?? null,
                    'city_name' => $data['city'] ?? null,
                    'zip_code' => $data['zip'] ?? null,
                    'iso_code' => $data['countryCode'] ?? null,
                    'postal_code' => $data['zip'] ?? null,
                    'area_code' => null,
                    'latitude' => $data['lat'] ?? null,
                    'longitude' => $data['lon'] ?? null,
                    'timezone' => $data['timezone'] ?? null,
                ];
            }
            
            Log::warning('Location API failed', [
                'ip' => $ip, 
                'status' => $response->json('status') ?? 'unknown',
                'message' => $response->json('message') ?? 'No message'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Location fetch error', [
                'ip' => $ip,
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
        }
        
        return null;
    }
}