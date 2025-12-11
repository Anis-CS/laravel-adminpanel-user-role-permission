<?php

namespace App\Http\Controllers\Admin\UserLocation;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class UserLocationController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

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
        $can_view = '';
        if (!auth()->user()->can('settings.user_location.view')) {
            $can_view = "style='display:none;'";
        }

        $user_location = UserLocation::latest()->get();

        return DataTables::of($user_location)
            ->addIndexColumn()
            ->addColumn('user_id', function($row) {
                $user = User::find($row->user_id); // Use find() instead of where()->first() for better readability
                if ($user) {
                    $badge = '';
                    switch ($user->user_type) {
                        case 1:
                            $badge = '<span class="badge badge-primary">Super Admin</span>';
                            break;
                        case 2:
                            $badge = '<span class="badge badge-info">Admin</span>';
                            break;
                        case 3:
                            $badge = '<span class="badge badge-success">User</span>';
                            break;
                        case 4:
                            $badge = '<span class="badge badge-success">Customer Care</span>';
                            break;
                        case 5:
                            $badge = '<span class="badge badge-success">B2B User</span>';
                            break;
                        case 7:
                            $badge = '<span class="badge badge-info">Operation</span>';
                            break;
                        case 8:
                            $badge = '<span class="badge badge-info">Manager</span>';
                            break;
                        case 9:
                            $badge = '<span class="badge badge-info">Brand Team</span>';
                            break;
                        case 10:
                            $badge = '<span class="badge badge-info">Garibook Insurance</span>';
                            break;
                        case 12:
                            $badge = '<span class="badge badge-info">Accounts</span>';
                            break;
                        case 13:
                            $badge = '<span class="badge badge-info">Enterprise</span>';
                            break;
                        case 14:
                            $badge = '<span class="badge badge-info">Field User</span>';
                            break;
                        default:
                            $badge = '<span class="badge badge-secondary">Unknown User Type</span>';
                            break;
                    }

                    return $user->full_name . '<br>' . $user->email . '<br>' . $badge; // Return full name along with the badge
                }
                return 'User not found'; // Handle case where user does not exist
            })
            ->addColumn('view_location', function($row) {
                $latitude = $row->latitude;
                $longitude = $row->longitude;

                // Log the latitude and longitude
                Log::info("Latitude: $latitude, Longitude: $longitude");

                // Check if latitude and longitude are valid
                if ($latitude && $longitude) {
                    $mapUrl = "https://www.google.com/maps?q={$latitude},{$longitude}";
                    return '<a href="' . $mapUrl . '" target="_blank" class="btn btn-primary">View Location</a>';
                }

                return 'Location not available'; // Handle case where lat/lng are null
            })
            ->addColumn('created_at', function($row) {
                return $row->created_at->format('Y-m-d g:i:s A');
            })
            ->rawColumns(['user_id', 'view_location']) // Change to 'user_id' to allow raw HTML for this column
            ->make(true);
    }
}

