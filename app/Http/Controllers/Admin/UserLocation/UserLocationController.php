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
            toast(session('success'), 'success');
        }

        if (session()->has('error')) {
            toast(session('error'), 'error');
        }
        
        return view('admin.user_location.user_location');
    }

    public function getData()
    {
        try {
            // Check permission
            if (!auth()->user()->can('settings.user_location.view')) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Get user locations with user relationship
            $user_locations = UserLocation::with('user')->latest()->get();

            return DataTables::of($user_locations)
                ->addIndexColumn()
                ->addColumn('user_id', function($row) {
                    if ($row->user) {
                        $badge = $this->getUserTypeBadge($row->user->user_type);
                        return '<div>' .
                               '<strong>' . $row->user->full_name . '</strong><br>' . 
                               '<small>' . $row->user->email . '</small><br>' . 
                               $badge .
                               '</div>';
                    }
                    return '<span class="text-danger">User not found</span>';
                })
                ->addColumn('ip_address', function($row) {
                    return $row->ip_address ?? 'N/A';
                })
                ->addColumn('country_name', function($row) {
                    return $row->country_name ?? 'N/A';
                })
                ->addColumn('region_name', function($row) {
                    return $row->region_name ?? 'N/A';
                })
                ->addColumn('city_name', function($row) {
                    return $row->city_name ?? 'N/A';
                })
                ->addColumn('zip_code', function($row) {
                    return $row->zip_code ?? 'N/A';
                })
                ->addColumn('created_at', function($row) {
                    return $row->created_at->format('d M Y, g:i A');
                })
                ->addColumn('view_location', function($row) {
                    if ($row->latitude && $row->longitude) {
                        $mapUrl = "https://www.google.com/maps?q={$row->latitude},{$row->longitude}";
                        return '<a href="' . $mapUrl . '" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fa fa-map-marker"></i> View
                                </a>';
                    }
                    return '<span class="text-muted">N/A</span>';
                })
                ->rawColumns(['user_id', 'view_location'])
                ->make(true);
                
        } catch (\Exception $e) {
            Log::error('UserLocation getData error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get user type badge HTML
     */
    private function getUserTypeBadge($userType)
    {
        $badges = [
            1 => '<span class="badge badge-danger">Super Admin</span>',
            2 => '<span class="badge badge-info">Admin</span>',
            3 => '<span class="badge badge-success">User</span>',
            4 => '<span class="badge badge-warning">Customer Care</span>',
            5 => '<span class="badge badge-primary">B2B User</span>',
            7 => '<span class="badge badge-info">Operation</span>',
            8 => '<span class="badge badge-dark">Manager</span>',
            9 => '<span class="badge badge-purple">Brand Team</span>',
            10 => '<span class="badge badge-secondary">Garibook Insurance</span>',
            12 => '<span class="badge badge-info">Accounts</span>',
            13 => '<span class="badge badge-primary">Enterprise</span>',
            14 => '<span class="badge badge-success">Field User</span>',
        ];

        return $badges[$userType] ?? '<span class="badge badge-secondary">Unknown</span>';
    }
}