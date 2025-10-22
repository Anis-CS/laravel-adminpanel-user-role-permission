@extends('layouts.app')

@section('permission')
    active
@endsection

@section('page-title', 'Edit Permission')

@section('breadcrumb')
    <li class="breadcrumb-item">Permissions</li>
    <li class="breadcrumb-item active" aria-current="page">Edit Permission</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title">Edit Permission</h4>
                    <a href="{{ route('permissions') }}" class="btn btn-secondary btn-sm">← Back to Permissions</a>
                </div>
                <div class="box-body">
                    <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="col-xl-6 col-12">
                            <div class="form-group">
                                <label class="form-label">Permissions Group Name <span class="text-danger">*</span></label>
                                <div class="controls">
                                    <input type="text" name="group_name"
                                        class="form-control @error('group_name') is-invalid @enderror"
                                        value="{{ old('group_name', $permission->group_name) }}"
                                        required data-validation-required-message="This field is required">
                                </div>
                                @error('group_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="form-control-feedback">
                                    <small>Edit <code>Permission Group Name</code>.</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Permissions Name <span class="text-danger">*</span></label>
                                <div class="controls">
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $permission->name) }}"
                                        required data-validation-required-message="This field is required">
                                </div>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="form-control-feedback">
                                    <small>Edit the <code>Permission Name</code>.</small>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success mt-3">
                                <i class="mdi mdi-content-save"></i> Update Permission
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
