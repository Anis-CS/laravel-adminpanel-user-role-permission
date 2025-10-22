@extends('layouts.app')
@section('admin')
    active
@endsection
@section('page-title', 'Create Admin')
@section('breadcrumb')
    <li class="breadcrumb-item">Admins</li>
    <li class="breadcrumb-item active" aria-current="page">Create Admin</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title">Create Admin</h4>
                    <a href="{{ route('admin.index') }}" class="btn btn-secondary btn-sm">← Back to Admin</a>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.store') }}" method="POST">
                        @csrf
                        <div class="col-xl-6 col-12">
                            <div class="form-group">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <div class="controls">
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                        required data-validation-required-message="This field is required">
                                </div>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <div class="controls">
                                    <input type="text" name="email"
                                        class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                        required data-validation-required-message="This field is required">
                                </div>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Contact <span class="text-danger">*</span></label>
                                <div class="controls">
                                    <input type="text" name="contact"
                                        class="form-control @error('contact') is-invalid @enderror" value="{{ old('contact') }}"
                                        required data-validation-required-message="This field is required">
                                </div>
                                @error('contact')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="controls">
                                    <input type="text" name="password"
                                        class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}"
                                        required data-validation-required-message="This field is required">
                                </div>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <div class="controls">
                                    <input type="password" name="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        required data-validation-required-message="This field is required">
                                </div>
                                @error('password_confirmation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Assign Role <span class="text-danger">*</span></label>
                                <select class="form-control select2" style="width: 100%;" name="role_id">
                                    <option value="" selected disabled>Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Assign Status <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="status" id="">
                                        <option value="" selected disabled>Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="2">Inactive</option>
                                    </select>
                                @error('role_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success mt-3">
                                <i class="mdi mdi-plus-circle"></i> Create Admin
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



