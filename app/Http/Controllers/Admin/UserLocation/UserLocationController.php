<?php

namespace App\Http\Controllers\Admin\UserLocation;

use App\Http\Controllers\Controller; // <-- Ei line-ti add korun

use App\Models\User;
use App\Models\UserLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;


class UserLocationController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = null;
            
            // Jekono ekta shothik guard theke user-ke ber korun
            if (Auth::guard('web')->check()) {
                $user = Auth::guard('web')->user();
            } elseif (Auth::guard('auth')->check()) { 
                // Dhore nilam 'auth' apnar onno ekta valid guard (eta sadharonoto use hoy na)
                $user = Auth::guard('auth')->user();
            }
            
            // $this->user property-te user-ke assign korun
            $this->user = $user; 
            
            return $next($request);
        });
    }

    /**
     * User Location Page
     */
    public function user_location()
    {
        if (is_null($this->user) || !$this->user->can('settings.user_location.view')) {
            abort(403, 'You are Unauthorized to access this page!');
        }

        if (session()->has('success')) {
            toast(Session('success'), 'success');
        }

        if (session()->has('error')) {
            toast(Session('error'), 'error');
        }
        return view('admin.user_location.user_location');
    }

    public function getData()
    {
        try {
            // রিলেশনসহ ডেটা গেট করা
            $locations = UserLocation::with('user')->select('user_locations.*')->get();
            
            return response()->json([
                'data' => $locations
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
