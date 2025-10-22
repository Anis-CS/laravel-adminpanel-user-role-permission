<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    public function index()
    {
        if (is_null($this->user) || !$this->user->can('role.view')) {
            abort(403, 'You are Unauthorized to access this page!');
        }

        $roles = Role::all();
        return view('admin.roles.index', ['roles' => $roles]);
    }

    public function create()
    {
        if(is_null($this->user) || !$this->user->can('role.create')){
            abort(403, 'You are Unauthorized to access this page!');
        }

        $permissions = Permission::all();
        $permissionGroups = User::getPermissionGroups();
        return view('admin.roles.create', ['permissions' => $permissions, 'permissionGroups' => $permissionGroups]);
    }

    public function store(Request $request)
    {
        if(is_null($this->user) || !$this->user->can('role.create')){
            abort(403, 'You are Unauthorized to access this page!');
        }

        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        $role = Role::create(['name' => $request->name]);
        $permissions = $request->permissions;
        if ($permissions) {
            $role->syncPermissions($permissions);
            return redirect()->route('roles')->with('success', 'Role created successfully with permissions.');
        }
        return redirect()->route('roles')->with('success', 'Role created successfully without permissions.');
    }

    public function edit($id)
    {
        if(is_null($this->user) || !$this->user->can('role.edit')){
            abort(403, 'You are Unauthorized to access this page!');
        }

        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $permissionGroups = User::getPermissionGroups();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', [
            'role' => $role,
            'permissions' => $permissions,
            'permissionGroups' => $permissionGroups,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    public function update(Request $request, $id)
    {
        if(is_null($this->user) || !$this->user->can('role.edit')){
            abort(403, 'You are Unauthorized to access this page!');
        }

        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();

        $permissions = $request->permissions;
        if ($permissions) {
            $role->syncPermissions($permissions);
            return redirect()->route('roles')->with('success', 'Role updated successfully with permissions.');
        } else {
            $role->syncPermissions([]);
            return redirect()->route('roles')->with('success', 'Role updated successfully without permissions.');
        }
    }
}
