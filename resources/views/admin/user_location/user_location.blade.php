@extends('layouts.app')

@section('location.user-view')
    active
@endsection
@section('title', 'User Location')


@section('page-name', 'User Location')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">User Location</li>
@endsection

@section('extra-css')
<style>
    #map { height: 100vh; width: 100%; }
    #driver-count {
        top: 10px; left: 10px; background: rgba(255, 255, 255, 0.8);
        padding: 10px; border-radius: 5px; font-size: 16px; z-index: 1000;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="box">
            <div class="box-body">
                <div class="table-responsive">
                    <table id="UserLocationTable" class="table table-bordered table-hover display nowrap margin-top-10 w-p100 text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>IP Address</th>
                                <th>Country Name</th>
                                <th>Region</th>
                                <th>City</th>
                                <th>Zip code</th>
                                <th>Stored At</th>
                                <th>View Location</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // অন্য সব এরর ইগনোর করার জন্য
    window.onerror = function() { return true; };

    $(document).ready(function() {
        console.log("Starting DataTable Initialization...");
        
        try {
            var table = $('#UserLocationTable').DataTable({
                "processing": true,
                "serverSide": false,
                "ajax": {
                    "url": "{{ route('location.user-get') }}",
                    "type": "GET",
                    "dataSrc": "data"
                },
                "columns": [
                    { "data": "id" },
                    { 
                        "data": "user",
                        "render": function(data) {
                            return data ? data.name : "Guest";
                        }
                    },
                    { "data": "ip_address" },
                    { "data": "country_name" },
                    { "data": "region_name", "defaultContent": "-" },
                    { "data": "city_name", "defaultContent": "-" },
                    { "data": "zip_code", "defaultContent": "-" },
                    { 
                        "data": "created_at",
                        "render": function(data) {
                            return data ? new Date(data).toLocaleString() : "-";
                        }
                    },
                    { 
                        "data": null, 
                        "render": function(data, type, row) {
                            return `<a href="https://www.google.com/maps?q=${row.latitude},${row.longitude}" target="_blank" class="btn btn-sm btn-primary">View</a>`;
                        }
                    }
                ]
            });
            console.log("DataTable Initialized Successfully!");
        } catch (e) {
            console.error("DataTable Error: ", e);
        }
    });
</script>
@endsection
