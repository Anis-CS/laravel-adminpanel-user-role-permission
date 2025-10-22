<?php

namespace App\Http\Controllers\Admin\Permissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next){
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    //This method will show permission page
    public function index()
    {
        if(is_null($this->user) || !$this->user->can('permission.view')){
            abort(403, 'You are Unauthorized to access this page!');
        }

        $permission = Permission::latest()->get();
        return view('admin.permission.index',[
            'permissions' => $permission,
        ]);
    }

    //This method will show create permission page
    public function create()
    {
        if(is_null($this->user) || !$this->user->can('permission.create')){
            abort(403, 'You are Unauthorized to access this page!');
        }
        return view('admin.permission.create');
    }

    //This method will insert permission in a DB
    public function store(Request $request)
    {
        if(is_null($this->user) || !$this->user->can('permission.create')){
            abort(403, 'You are Unauthorized to access this page!');
        }

        $request->validate([
            'name' => 'required|unique:permissions,name',
            'group_name' => 'required',
        ]);

        $permission = new Permission();
        $permission->name = $request->name;
        $permission->group_name = $request->group_name;
        if ($permission->save()) {
            return redirect()->route('permissions')->with('success', 'Permission created successfully.');
        } else {
            return back()->with('error', 'Failed to create permission.');
        }
    }

    public function edit($id)
    {
        if(is_null($this->user) || !$this->user->can('permission.edit')){
            abort(403, 'You are Unauthorized to access this page!');
        }

        $permission = Permission::findOrFail($id);
        return view('admin.permission.edit', compact('permission'));
    }

    public function update(Request $request ,$id)
    {
        if(is_null($this->user) || !$this->user->can('permission.edit')){
            abort(403, 'You are Unauthorized to access this page!');
        }
        
        $request->validate([
            'name'       => 'required|unique:permissions,name,'. $id,
            'group_name' => 'required',
        ]);

        $permission = Permission::findOrFail($id);
        $permission->name = $request->name;
        $permission->group_name = $request->group_name;
        if($permission->save()){
            return redirect()->route('permissions')->with('success', 'Permission updated successfully.');
        }else{
            return back()->with('error', 'Failed to updated permission.');
        }
    }
}
