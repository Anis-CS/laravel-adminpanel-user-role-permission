<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ActionHistory\ActionHistoryController;
use App\Http\Controllers\Admin\UserLocation\UserLocationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboard\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUser\AdminUserController;
use App\Http\Controllers\Admin\Permissions\PermissionsController;
use App\Http\Controllers\Admin\Roles\RoleController;
use GeoSot\EnvEditor\Controllers\EnvController;


// Route::get('/', function () {
//     return view('welcome');
// });


Route::middleware(['auth','web'])->group(function () {
     Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

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

     //=================== Settings routes ===================
    Route::get('settings/smtp-config', [SMTPConfigController::class, 'create'])->name('settings.smtp-config');
    Route::post('settings/smtp-config/store', [SMTPConfigController::class, 'store'])->name('settings.smtp-config.store');
    Route::post('/settings/smtp/test-email', [SMTPConfigController::class, 'testEmail'])->name('settings.smtp-config.test-email');

    //=================== Action History ===================

    Route::get('action-history', [ActionHistoryController::class, 'index'])->name('action-history.index');
    Route::get('action-history/get', [ActionHistoryController::class, 'getHistory'])->name('action-history.get');
    Route::post('action-history/store', [ActionHistoryController::class, 'store'])->name('action-history.store');
    Route::get('action-history/view/{id}', [ActionHistoryController::class, 'viewDetails'])->name('action-history.view');

    //=================== UserLocationController ===================
    Route::get('/user/location', [UserLocationController::class, 'user_location'])->name('location.user');
    Route::get('/user/location/get', [UserLocationController::class, 'getData'])->name('location.user-get');


});

require __DIR__.'/auth.php';
