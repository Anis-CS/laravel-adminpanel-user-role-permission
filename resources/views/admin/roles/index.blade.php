@extends('layouts.app')

@section('role')
    active
@endsection
@section('page-title', 'Roles')

@section('breadcrumb')
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active" aria-current="page">Roles</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mb-3 d-flex justify-content-end">
            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus me-1"></i> Create Role
            </a>
        </div>

        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Roles</h4>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered">
                            <thead>
                                <tr class="text-dark">
                                    <th>Sl</th>
                                    <th>Action</th>
                                    <th>Name</th>
                                    <th>Permissions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $row)
                                    <tr>
                                        <td class="text-dark">{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('roles.edit', $row->id) }}" class="btn btn-sm btn-info d-flex align-items-center gap-1">
                                                <i class="mdi mdi-pencil"></i>
                                                <span>Edit</span>
                                            </a>
                                        </td>
                                        <td>{{ $row->name }}</td>
                                        <td class="text-start">
                                            @foreach ($row->permissions()->pluck('name') as $permission)
                                            <span class="badge badge-primary me-1 mb-1">{{ $permission }}</span>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
