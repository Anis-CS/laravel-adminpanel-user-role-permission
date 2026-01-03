<?php

namespace App\Http\Controllers\Admin\ActionHistory;

use App\Http\Controllers\Controller;
use App\Models\ActionData;
use App\Models\ActionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class ActionHistoryController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('settings.action_history.index')) {
            abort(403, 'You are Unauthorized to access this page!');
        }

        if (session()->has('success')) {
            toast(Session('success'), 'success');
        }

        if (session()->has('error')) {
            toast(Session('error'), 'error');
        }
        $query = ActionData::query();

        // 🔍 Filter by model type (unique)
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // 🔍 Search in before/after/changes
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('before_data', 'like', "%$search%")
                    ->orWhere('after_data', 'like', "%$search%")
                    ->orWhere('changes', 'like', "%$search%")
                    ->orWhere('action_type', 'like', "%$search%")
                    ->orWhere('route', 'like', "%$search%");
            });
        }

        // 📅 Order latest first
        $logs = $query->latest()->paginate(20);

        // Get unique model types for dropdown
        $models = ActionData::select('model_type')->distinct()->pluck('model_type');


        return view('admin.action_history.index',compact('logs', 'models'));
    }

    public function getHistory()
    {
        $action_history = ActionHistory::latest()->get();

        return DataTables::of($action_history)
            ->addIndexColumn()
            ->addColumn('action_data', function ($row) {
                $limitedValue = Str::limit($row->action_data, 50); // Change 50 to your desired character limit
                return '<span>' . $limitedValue . '</span>';
            })
            ->addColumn('mobile', function ($row) {
                $data = json_decode($row->action_data, true); // Decode JSON data
                return isset($data['mobile']) ? $data['mobile'] : 'N/A'; // Return mobile field if exists
            })
            ->addColumn('booking_id', function ($row) {
                $data = json_decode($row->action_data, true); // Decode JSON data
                return isset($data['booking_id']) ? $data['booking_id'] : 'N/A';
            })
            ->addColumn('created_at', function ($row) {
                return '
                    <span>' . $row->created_at->format('Y-m-d g:i:s A') . '</span>
                ';
            })
            ->addColumn('updated_at', function ($row) {
                return '
                    <span>' . $row->updated_at->format('Y-m-d g:i:s A') . '</span>
                ';
            })
            ->addColumn('action', function ($row) {
                return
                    '<div class="btn-group mb-5">
                        <button type="button" class="waves-effect waves-light btn btn-info" onclick="viewDetails(' . $row->id . ')">View</button>
                    </div>';
            })
            ->rawColumns(['action', 'created_at', 'updated_at', 'action_data', 'mobile', 'booking_id'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $by_user_id = Auth::guard('web')->user()->id;
        $by_user_name = Auth::guard('web')->user()->full_name;

        $action_history = new ActionHistory();
        $action_history->action_type = $request->action_type;
        $action_history->action_data = $request->action_data;
        $action_history->title = $request->title;
        $action_history->by_user_id = $by_user_id;
        $action_history->by_user_name = $by_user_name;
        $action_history->save();
        return true;
    }

    public function viewDetails(int $id)
    {
        $action_history = ActionHistory::findOrFail($id);
        return view('admin.action_history.details', ['action_history' => $action_history]);
    }
}
