@extends('layouts.app')

@section('permission')
    active
@endsection
@section('page-title', 'Permissions')

@section('breadcrumb')
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active" aria-current="page">Permissions</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mb-3 d-flex justify-content-end">
            <a href="{{ route('permissions.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus me-1"></i> Create Permission
            </a>
        </div>

        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">All Permission</h4>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered">
                            <thead>
                                <tr class="text-dark">
                                    <th>Sl</th>
                                    <th>Action</th>
                                    <th>Group Name</th>
                                    <th>Name</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $row)
                                    <tr>
                                        <td class="text-dark">{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('permissions.edit', $row->id) }}"
                                                class="btn btn-sm btn-info ">
                                                <i class="mdi mdi-pencil"></i>
                                                <span>Edit</span>
                                            </a>
                                        </td>
                                        <td>{{ $row->group_name }}</td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->created_at }}</td>
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
