@extends('layouts.app')
@section('admin')
    active
@endsection
@section('page-title', 'Edit Admin')
@section('breadcrumb')
    <li class="breadcrumb-item">Admin</li>
    <li class="breadcrumb-item active" aria-current="page">Edit Admin</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title">Edit Admin</h4>
                    <a href="{{ route('admin.index') }}" class="btn btn-secondary btn-sm">← Back to Admin List</a>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="col-xl-6 col-12">
                            <div class="form-group">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Contact <span class="text-danger">*</span></label>
                                <input type="text" 
                                    name="contact"
                                    class="form-control @error('contact') is-invalid @enderror"
                                    value="{{ old('contact', $user->contact) }}">
                                @error('contact')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">New Password <span class="text-danger">(Optional if need to update password)</span></label>
                                <input type="password" 
                                    name="password"
                                    autocomplete="off"
                                    class="form-control @error('password') is-invalid @enderror">
                                <small class="text-muted">Leave blank to keep current password.</small>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Assign Role <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="role_id" required>
                                    <option value="" disabled>Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Assign Status <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="status" required>
                                    <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="2" {{ $user->status == 2 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="mdi mdi-content-save"></i> Update Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>
@endsection
