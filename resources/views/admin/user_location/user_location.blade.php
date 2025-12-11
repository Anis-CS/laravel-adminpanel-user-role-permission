@extends('layouts.app')

@section('location.user-view')
active
@endsection

@section('title')
User Location
@endsection

@section('page-name')
User Location
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">User Location</li>
@endsection

@section('extra-css')
<style>
    #map {
        height: 100vh;
        width: 100%;
    }

    #driver-count {
        top: 10px;
        left: 10px;
        background: rgba(255, 255, 255, 0.8);
        padding: 10px;
        border-radius: 5px;
        font-size: 16px;
        z-index: 1000;
    }
</style>
@endsection

@section('content')

<div class="row">
    <div class="col-12 col-6">
        <!-- /.box -->
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    <table id="TripsGet"
                        class="table table-bordered table-hover display nowrap margin-top-10 w-p100 text-center">
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
                        <tbody>
                        </tbody>
                        <tfoot>
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
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.1.1/js/dataTables.dateTime.min.js"></script>

<script>
    $(document).ready(function() {
        var tripdata; // Cache DataTable instance

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function TripsGet() {
            $('#TripsGet').DataTable().destroy();
            const location = '{{ route('location.user-get') }}';

            // Initialize DataTable
            tripdata = $('#TripsGet').DataTable({
                dom: 'Blfrtip',
                bSort: false,
                buttons: [
                ],
                lengthMenu: [
                    [10, 25, 50, -1],
                    ['10', '25', '50', 'All']
                ],
                serverSide: true,
                processing: true,
                deferRender: true,
                ajax: {
                    url: location,
                    type: 'get',
                },
                pageLength: 25,
                columns: [{
                        data: 'id',
                        name: 'id',
                        "render": function(data, type, row, meta) {
                            // 'meta.row' is the index of the row
                            return meta.row + 1;
                        }
                    },
                    {data: 'user_id', name: 'user_id'},
                    {data: 'ip_address', name: 'ip_address'},
                    {data: 'country_name', name: 'country_name'},
                    {data: 'region_name', name: 'region_name'},
                    {data: 'city_name', name: 'city_name'},
                    {data: 'zip_code', name: 'zip_code'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'view_location', name: 'view_location', visible:false},
                ],
                order: [
                    [0, 'dsc']
                ]
            });

            // Handle DataTable events
            tripdata.on('order.dt search.dt', function() {
                tripdata.on('draw.dt', function() {
                    var info = tripdata.page.info();
                    tripdata.column(0).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1 + info.start;
                    });
                });
            }).draw();
        }

        // Call TripsGet function
        TripsGet();
    });
</script>
@endsection
