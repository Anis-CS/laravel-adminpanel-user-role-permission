<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboard\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUser\AdminUserController;
use App\Http\Controllers\Admin\Permissions\PermissionsController;
use App\Http\Controllers\Admin\Roles\RoleController;
use GeoSot\EnvEditor\Controllers\EnvController;


// Route::get('/', function () {
//     return view('welcome');
// });


Route::middleware('auth')->group(function () {
     Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

     // Admin routes
    Route::get('roles', [RoleController::class, 'index'])->name('roles');
    Route::get('role/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('roles/store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('roles/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('roles/edit/{id}', [RoleController::class, 'update'])->name('roles.update');

    // Permissions management routes
    Route::get('permissions', [PermissionsController::class, 'index'])->name('permissions');
    Route::get('permission/create', [PermissionsController::class, 'create'])->name('permissions.create');
    Route::post('permissions/store', [PermissionsController::class, 'store'])->name('permissions.store');
    Route::get('permissions/edit/{id}', [PermissionsController::class, 'edit'])->name('permissions.edit');
    Route::put('permissions/edit/{id}', [PermissionsController::class, 'update'])->name('permissions.update');

    // Admin user management routes
    Route::get('admins', [AdminUserController::class, 'index'])->name('admin.index');
    Route::get('admin/create', [AdminUserController::class, 'createUser'])->name('admin.create');
    Route::post('admin/store', [AdminUserController::class, 'storeUser'])->name('admin.store');
    Route::get('admin/edit/{id}', [AdminUserController::class, 'editUser'])->name('admin.edit');
    Route::put('admin/edit/{id}', [AdminUserController::class, 'updateUser'])->name('admin.update');
    Route::get('admin/delete/{id}', [AdminUserController::class, 'deleteUser'])->name('admin.delete');
    Route::get('admin/show/{id}', [AdminUserController::class, 'getUser'])->name('admin.show');
    Route::get('admin/deactive/{id}', [AdminUserController::class, 'deactiveUser'])->name('admin.deactive');
    Route::get('admin/active/{id}', [AdminUserController::class, 'activeUser'])->name('admin.active');

    // Settings routes
    Route::get('settings/smtp-config', [SMTPConfigController::class, 'create'])->name('settings.smtp-config');
    Route::post('settings/smtp-config/store', [SMTPConfigController::class, 'store'])->name('settings.smtp-config.store');
    Route::post('/settings/smtp/test-email', [SMTPConfigController::class, 'testEmail'])->name('settings.smtp-config.test-email');

        // VTS configuration routes
    Route::get('settings/vts-config', [VTSConfigController::class, 'create'])->name('settings.vts-config');
    Route::post('settings/vts-config/store', [VTSConfigController::class, 'store'])->name('settings.vts-config.store');
});

require __DIR__.'/auth.php';
