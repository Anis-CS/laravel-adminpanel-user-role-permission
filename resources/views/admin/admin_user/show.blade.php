@extends('layouts.app')

@section('admin')
active
@endsection

@section('page-title', 'Admin Profile')

@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item active">Profile</li>
@endsection

@section('content')
<div class="row">

    {{-- LEFT SIDE: PROFILE CARD --}}
    <div class="col-md-4">

        <div class="card shadow-sm text-center p-3">

            <div class="mb-3">
                <img src="https://ui-avatars.com/api/?name={{ $user->name }}&size=120&background=random"
                     class="rounded-circle"
                     width="120"
                     height="120">
            </div>

            <h4 class="mb-1">{{ $user->name }}</h4>
            <p class="text-muted mb-2">{{ $user->email }}</p>

            <span class="badge bg-{{ $user->status == 1 ? 'success' : 'danger' }}">
                {{ $user->status == 1 ? 'Active' : 'Inactive' }}
            </span>

            <hr>

            <div class="text-start">

                <p><strong>Contact:</strong> {{ $user->contact ?? 'N/A' }}</p>
                <p><strong>Role:</strong> {{ $user->role?->name ?? 'N/A' }}</p>
                <p>
                    <strong>User Type:</strong>
                    @if($user->user_type == 1)
                        Admin
                    @elseif($user->user_type == 2)
                        Superadmin
                    @elseif($user->user_type == 3)
                        Subadmin
                    @else
                        N/A
                    @endif
                </p>

                <p>
                    <strong>2FA:</strong>
                    @if($user->google2fa_enabled)
                        <span class="badge bg-success">Enabled</span>
                    @else
                        <span class="badge bg-secondary">Disabled</span>
                    @endif
                </p>

            </div>

            <a href="{{ route('admin.edit', $user->id) }}" class="btn btn-primary btn-sm mt-2">
                Edit Profile
            </a>

        </div>
    </div>

    {{-- RIGHT SIDE: DETAILS + QR --}}
    <div class="col-md-8">

        <div class="card shadow-sm p-4">

            <h5 class="mb-3">User Details</h5>

            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Name:</strong> {{ $user->name }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>Email:</strong> {{ $user->email }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>Contact:</strong> {{ $user->contact }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>Status:</strong>
                    {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                </div>
            </div>

            <hr>

            {{-- QR CODE --}}
            @if(isset($qrCode))
                <h5 class="mb-3">Google Authenticator QR Code</h5>

                <div class="text-center">
                    <img src="data:image/svg+xml;base64,{{ $qrCode }}"
                         class="border p-2"
                         width="220">
                    <p class="text-muted mt-2">
                        Scan this QR code using Google Authenticator App
                    </p>
                </div>
            @else
                <div class="alert alert-info text-black">
                    2FA QR Code not available or already configured.
                </div>
            @endif

        </div>

    </div>

</div>
@endsection