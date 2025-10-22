@extends('layouts.app')
@section('role')
    active
@endsection
@section('page-title', 'Roles Create')
@section('breadcrumb')
    <li class="breadcrumb-item">Roles</li>
    <li class="breadcrumb-item active" aria-current="page">Create Role</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title">Create Role</h4>
                    <a href="{{ route('roles') }}" class="btn btn-secondary btn-sm">← Back to Roles</a>
                </div>
                <div class="box-body">
                    <form action="{{ route('roles.store') }}" method="POST">
                        @csrf
                        <div class="col-xl-6 col-12">
                            <div class="form-group">
                                <label class="form-label">Role Name <span class="text-danger">*</span></label>
                                <div class="controls">
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                        required data-validation-required-message="This field is required">
                                </div>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="form-control-feedback">
                                    <small>Add <code>Role Name</code> to create a new role.</small>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Permissions <span class="text-danger">*</span></label>
                                    <div class="controls mb-2">
                                        <input type="checkbox" id="checkPermissionAll" class="filled-in chk-col-info">
                                        <label for="checkPermissionAll"><strong>ALL PERMISSIONS</strong></label>
                                    </div>
                                    <hr>

                                    @php $i = 1; @endphp
                                    @foreach ($permissionGroups as $permissionGroup)
                                        @php
                                            $permissions = App\Models\User::getPermissionByGroupName($permissionGroup->name);
                                        @endphp

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="controls mb-2">
                                                    <input type="checkbox" id="groupCheck{{ $i }}" class="group-check" data-group="group-{{ $i }}">
                                                    <label class="text-info fw-bold" for="groupCheck{{ $i }}">
                                                        {{ $loop->index + 1 }}. {{ $permissionGroup->name }} (Select All)
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-8 group-{{ $i }}">
                                                @foreach ($permissions as $permission)
                                                    <div class="controls d-inline-block me-3 mb-1">
                                                        <input type="checkbox" name="permissions[]"
                                                            id="checkPermission{{ $permission->id }}"
                                                            value="{{ $permission->name }}"
                                                            class="filled-in chk-col-info permission-checkbox group-{{ $i }}">
                                                        <label for="checkPermission{{ $permission->id }}">{{ $permission->name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <hr>
                                        @php $i++; @endphp
                                    @endforeach
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success mt-3">
                                <i class="mdi mdi-plus-circle"></i> Create Role
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
        // Master "ALL PERMISSIONS" toggle
        $('#checkPermissionAll').change(function () {
            const isChecked = $(this).is(':checked');
            $('.permission-checkbox').prop('checked', isChecked);
            $('.group-check').prop('checked', isChecked);
        });

        // Sync ALL checkbox when any individual changes
        $('.permission-checkbox').change(function () {
            const total = $('.permission-checkbox').length;
            const checked = $('.permission-checkbox:checked').length;
            $('#checkPermissionAll').prop('checked', total === checked);
        });

        // Group-wise toggle
        $('.group-check').change(function () {
            const groupClass = $(this).data('group');
            const isChecked = $(this).is(':checked');
            $('.' + groupClass).prop('checked', isChecked);
        });

        // Sync group checkbox when its permissions change
        $('.permission-checkbox').change(function () {
            $('.group-check').each(function () {
                const group = $(this).data('group');
                const groupCheckboxes = $('.' + group);
                const allChecked = groupCheckboxes.length === groupCheckboxes.filter(':checked').length;
                $(this).prop('checked', allChecked);
            });
        });
    });
</script>
@endsection



