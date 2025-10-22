@extends('layouts.app')
@section('permission')
    active
@endsection
@section('page-title', 'Create Permission')
@section('breadcrumb')
    <li class="breadcrumb-item">Permissions</li>
    <li class="breadcrumb-item active" aria-current="page">Create Permission</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title">Create Permission</h4>
                    <a href="{{ route('permissions') }}" class="btn btn-secondary btn-sm">← Back to Permissions</a>
                </div>
                <div class="box-body">
                    <form action="{{ route('permissions.store') }}" method="POST">
                        @csrf
                        <div class="col-xl-6 col-12">
                            <div class="form-group">
                                <label class="form-label">Permissions Group Name <span class="text-danger">*</span></label>
                                <div class="controls">
                                    <input type="text" name="group_name"
                                        class="form-control @error('group_name') is-invalid @enderror" value="{{ old('group_name') }}"
                                        required data-validation-required-message="This field is required">
                                </div>
                                @error('group_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="form-control-feedback">
                                    <small>Add <code>Permission Group Name</code> to create a new Group Permission.</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Permissions Name <span class="text-danger">*</span></label>
                                <div class="controls">
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                        required data-validation-required-message="This field is required">
                                </div>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="form-control-feedback">
                                    <small>Add <code>Permission Name</code> to create a new Permission.</small>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success mt-3">
                                <i class="mdi mdi-plus-circle"></i> Create Permission
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



