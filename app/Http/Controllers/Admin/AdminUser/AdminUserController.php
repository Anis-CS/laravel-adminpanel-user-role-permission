<?php

namespace App\Http\Controllers\Admin\AdminUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use PragmaRX\Google2FA\Google2FA;

class AdminUserController extends Controller
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
        if (is_null($this->user) || !$this->user->can('admin.view')) {
            abort(403, 'You are Unauthorized to access this page!');
        }

        $user = User::latest()->get();
        return view('admin.admin_user.index', ['users' => $user]);
    }

    public function getUser($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.view')) {
            abort(403, 'You are Unauthorized to access this page!');
        }

        $user = User::findOrFail($id);

        $qrCode = null;

        if ($user->google2fa_secret) {

            $google2fa = new Google2FA();

            $qrCodeUrl = $google2fa->getQRCodeUrl(
                config('app.name'),
                $user->email,
                $user->google2fa_secret
            );

            $renderer = new ImageRenderer(
                new RendererStyle(300),
                new SvgImageBackEnd()
            );

            $writer = new Writer($renderer);

            $qrCode = base64_encode(
                $writer->writeString($qrCodeUrl)
            );
        }

        return view('admin.admin_user.show', compact('user', 'qrCode'));
    }

    public function createUser()
    {
        if (is_null($this->user) || !$this->user->can('admin.create')) {
            abort(403, 'You are Unauthorized to access this page!');
        }

        $roles = Role::all();
        return view('admin.admin_user.create', ['roles' => $roles]);
    }

    public function storeUser(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.create')) {
            abort(403, 'You are Unauthorized to access this page!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:1,2',
            'contact' => 'nullable|string|unique:users,contact',
        ]);

        $google2fa = new Google2FA();

        $role_id = $request->role_id;
        $adminUser = new User();
        $adminUser->name = $request->name;
        $adminUser->email = $request->email;
        $adminUser->user_type = 1;
        $adminUser->password = Hash::make($request->password);
        $adminUser->contact = $request->contact;
        $adminUser->status = $request->status;
        $adminUser->role_id = (int) $role_id;
        $adminUser->google2fa_secret = $google2fa->generateSecretKey();
        $adminUser->google2fa_enabled = 1;
        
        if ($adminUser->save()) {
            return redirect()->route('admin.index')->with('success', 'Admin created successfully');
        } else {
            return back()->with('error', 'Failed to create admin');
        }
    }




    public function editUser($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.edit')) {
            abort(403, 'You are Unauthorized to access this page!');
        }

        $roles = Role::all();
        $user = User::findOrFail($id);
        if (!$user) {
            return redirect()->route('admin.index')->with('error', 'User not found');
        }
        return view('admin.admin_user.edit', ['user' => $user, 'roles' => $roles]);
    }

    public function updateUser(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('admin.edit')) {
            abort(403, 'You are Unauthorized to access this page!');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role_name' => 'required|exists:roles,name',
            'status' => 'required|in:1,2',
            'contact' => 'required|string|unique:users,contact,' . $user->id,
        ]);

        // check email change
        $emailChanged = $user->email !== $request->email;

        // update basic info
        $user->name = $request->name;
        $user->email = $request->email;
        $user->contact = $request->contact;
        $user->status = $request->status;

        // password update
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        /**
         * 🔥 ROLE (Spatie correct way)
         */
        $user->syncRoles([$request->role_name]);

        /**
         * 🔐 QR / 2FA regenerate logic
         * email or password change হলে নতুন secret generate
         */
        if ($emailChanged || !empty($request->password)) {

            $google2fa = new Google2FA();

            $user->google2fa_secret = $google2fa->generateSecretKey();
            $user->google2fa_enabled = 1;
        }

        if ($user->save()) {
            return redirect()
                ->route('admin.index')
                ->with('success', 'User updated successfully & QR regenerated');
        }

        return back()->with('error', 'Failed to update admin');
    }


    public function deleteUser($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.delete')) {
            abort(403, 'You are Unauthorized to access this page!');
        }
        $user = User::findOrFail($id);
        if (!$user) {
            return redirect()->route('admin.index')->with('error', 'User not found');
        }
        if ($user->id === $this->user->id) {
            return redirect()->route('admin.index')->with('error', 'You cannot delete your own account');
        }
        if ($user->delete()) {
            return redirect()->route('admin.index')->with('success', 'User deleted successfully');
        }
        return redirect()->route('admin.index')->with('error', 'Failed to delete user');
    }

    public function deactiveUser($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.status')) {
            abort(403, 'You are Unauthorized to access this page!');
        }

        $user = User::findOrFail($id);
        if (!$user) {
            return redirect()->route('admin.index')->with('error', 'User not found');
        }
        $user->status = 0;
        if ($user->save()) {
            return redirect()->route('admin.index')->with('success', 'User deactivated successfully');
        }
        return redirect()->route('admin.index')->with('error', 'Failed to deactivate user');
    }
    public function activeUser($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.status')) {
            abort(403, 'You are Unauthorized to access this page!');
        }

        $user = User::findOrFail($id);
        if (!$user) {
            return redirect()->route('admin.index')->with('error', 'User not found');
        }
        $user->status = 1;
        if ($user->save()) {
            return redirect()->route('admin.index')->with('success', 'User activated successfully');
        }
        return redirect()->route('admin.index')->with('error', 'Failed to deactivate user');
    }
}
