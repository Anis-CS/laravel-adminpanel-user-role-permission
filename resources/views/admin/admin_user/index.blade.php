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
        
        <div class="col-12">
            <div class="box">
                <div class="box-header d-flex justify-content-between align-items-center">

                    <h4 class="box-title mb-0">All Admin</h4>

                    <a href="{{ route('admin.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus me-1"></i> Create Admin
                    </a>

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
                                            {{-- VIEW --}}
                                            @can('admin.view')
                                                <a href="{{ route('admin.view', $row->id) }}" class="btn btn-sm btn-info">
                                                    <i class="mdi mdi-eye"></i> View
                                                </a>
                                            @endcan

                                            {{-- EDIT --}}
                                            @can('admin.edit')
                                                <a href="{{ route('admin.edit', $row->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="mdi mdi-pencil"></i> Edit
                                                </a>
                                            @endcan

                                            {{-- DELETE --}}
                                            @can('admin.delete')
                                                <a href="{{ route('admin.delete', $row->id) }}"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this user?')">
                                                    <i class="mdi mdi-trash-can"></i> Delete
                                                </a>
                                            @endcan


                                            {{-- STATUS TOGGLE --}}
                                            @can('admin.status')

                                                @if ($row->status == 1)
                                                    <a href="{{ route('admin.deactive', $row->id) }}" class="btn btn-sm btn-warning">
                                                        <i class="mdi mdi-close"></i> Deactivate
                                                    </a>
                                                @else
                                                    <a href="{{ route('admin.active', $row->id) }}" class="btn btn-sm btn-success">
                                                        <i class="mdi mdi-check"></i> Activate
                                                    </a>
                                                @endif

                                            @endcan

                                        </td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->role->name }}</td>
                                        <td>{{ $row->email }}</td>
                                        <td>{{ $row->contact }}</td>
                                        <td>
                                            @if ($row->status == 1)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
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
