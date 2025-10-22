@extends('layouts.app')

@section('admin')
    active
@endsection
@section('page-title', 'All Admin')

@section('breadcrumb')
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active" aria-current="page">All Admin</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mb-3 d-flex justify-content-end">
            <a href="{{ route('admin.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus me-1"></i> Create Admin
            </a>
        </div>

        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">All Admin</h4>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered">
                            <thead>
                                <tr class="text-dark">
                                    <th>Sl</th>
                                    <th>Action</th>
                                    <th>Name</th>
                                    <th>User Type</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>status</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $row)
                                    <tr>
                                        <td class="text-dark">{{ $loop->iteration }}</td>
                                        <td>
                                            @if (Auth::guard('web')->user()->can('admin.edit'))
                                            <a href="{{ route('admin.edit', $row->id) }}" class="btn btn-sm btn-info">
                                                <i class="mdi mdi-pencil"></i>
                                                <span>Edit</span>
                                            </a>
                                            @endif
                                            @if (Auth::guard('web')->user()->can('admin.delete'))
                                            <a href="{{ route('admin.delete', $row->id) }}" class="btn btn-sm btn-danger">
                                                <i class="mdi mdi-trash-can"></i>
                                                <span>Delete</span>
                                            </a>
                                            @endif
                                            @if (Auth::guard('web')->user()->can('admin.status'))
                                                @if ($row->status == 1)
                                                    <a href="{{ route('admin.deactive', $row->id) }}" class="btn btn-sm btn-warning">
                                                        <i class="mdi mdi-close"></i>
                                                        <span>Deactivate</span>
                                                    </a>
                                                @else
                                                <a href="{{ route('admin.active', $row->id) }}" class="btn btn-sm btn-success">
                                                    <i class="mdi mdi-close"></i>
                                                    <span>Activate</span>
                                                </a>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->role->name }}</td>
                                        <td>{{ $row->email }}</td>
                                        <td>{{ $row->contact }}</td>
                                        <td>
                                            @if ($row->status == 1)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
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
