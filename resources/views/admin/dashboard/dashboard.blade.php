@extends('layouts.app')
@section('dashboard')
active
@endsection
@section('content')
    <div class="row">
        <div class="col-xxl-3 col-xl-6 col-lg-6 col-12">
            <div class="box box-body pull-up fill-icon" style="min-height: 230px;">
                <!-- Header Title -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fa-solid fa-car text-primary me-1"></i> Overview
                    </h5>
                </div>

                <!-- Vehicle Stats List -->
                <div>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>Total Driver</span>
                            <span class="fw-bold text-primary">20</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>Total Vehicle</span>
                            <span class="fw-bold text-primary">20</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>On Request</span>
                            <span class="fw-bold text-warning">30</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>Available</span>
                            <span class="fw-bold text-success">4</span>
                        </li>
                        {{-- <li class="d-flex justify-content-between align-items-center py-2">
                            <span>Maintenance</span>
                            <span class="fw-bold text-danger">05(not dynamic)</span>
                        </li> --}}
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-6 col-lg-6 col-12">
            <div class="box box-body pull-up fill-icon" style="min-height: 230px;">
                <!-- Header Title -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fa-solid fa-car text-primary me-1"></i> Todays requisition
                    </h5>
                </div>

                <!-- Vehicle Stats List -->
                <div>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>Pick & drop requisition</span>
                            <span class="fw-bold text-primary">59</span>
                        </li>
                        {{-- <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>Maintenance requisition</span>
                            <span class="fw-bold text-warning">15(not dynamic)</span>
                        </li> --}}
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>Fuel requisition</span>
                            <span class="fw-bold text-success">5000</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-6 col-lg-6 col-12">
            <div class="box box-body pull-up fill-icon" style="min-height: 230px;">
                <!-- Header Title -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fa-solid fa-car text-primary me-1"></i> Reminder
                    </h5>
                </div>

                <!-- Vehicle Stats List -->
                <div>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>Vehicle Legal doc expired</span>
                            <span class="fw-bold text-danger">vvv</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>Vehicle Legal doc expire soon</span>
                            <span class="fw-bold text-primary">vvv</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>Driving License expired</span>
                            <span class="fw-bold text-danger">vv</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>Driving License expire soon</span>
                            <span class="fw-bold text-warning">vv</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-6 col-lg-6 col-12">
            <div class="box box-body pull-up fill-icon" style="min-height: 230px;">
                <!-- Header Title -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fa-solid fa-car text-primary me-1"></i> Others activities
                    </h5>
                </div>

                <!-- Vehicle Stats List -->
                <div>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>Stock In</span>
                            <span class="fw-bold text-primary">20 (not dynamic)</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>Stock Out</span>
                            <span class="fw-bold text-danger">15 (not dynamic)</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Pick & drop Status --}}
        <div class="col-xxl-4 col-xl-5 col-12">
            <div class="box">
                <div class="box-header d-flex justify-content-between align-items-center">
                    <h4 class="box-title mb-0">Pick & Drop Status</h4>
                    <a href="">
                        <button type="button" class="btn btn-md border border-0 bg-secondary-light rounded-2 text-dark">
                            View List
                        </button>
                    </a>
                </div>
                <div class="box-body">
                    <canvas id="pickDropChart" style="width: 100%; height: 200px;"></canvas>
                </div>
            </div>
        </div>


        <div class="col-xxl-8 col-xl-7 col-12">
            <div class="box">
                <div class="box-header d-flex justify-content-between align-items-center">
                    <h4 class="box-title">Trip Status Line Chart</h4>
                </div>
                <div class="box-body" style="height: 252px;">
                    <canvas id="tripStatusLineChart" style="width: 100%; height: 100%;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xxl-6 col-xl-6 col-12">
            <div class="box">
                <div class="box-header d-flex justify-content-between align-items-center">
                    <h4 class="box-title">Expense Chart</h4>
                    <a href="#">
                        <button type="button" class="btn btn-md border border-0 bg-secondary-light rounded-2 text-dark">
                            View List
                        </button>
                    </a>
                </div>
                <div class="box-body" style="height: 329px;">
                    <canvas id="expenseChart" style="width: 100%; height: 200px;"></canvas>
                </div>
            </div>
        </div>

        {{-- vehicle trip status --}}

        <div class="col-xl-6 col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Vechicle Running Status</h4>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="vehicle_trip_status">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Vehicle Type</th>
                                    <th>Registration Number</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                              
                                    <tr>
                                        <td>vv</td>
                                        <td>vv</td>
                                        <td>vv</td>
                                        <td>vv</td>
                                    </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    
     

        {{-- Driver License Expire Status --}}

        <div class="col-xl-12 col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Driver License Expire Status</h4>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="driver_license_table">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Driver Image</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Paper Type</th>
                                    <th>Expire At</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                              
                                <tr>
                                    <td class="text-dark">iteration</td>
                                    <td>
                                        <img src="" alt="Driver Image" width="60"
                                            height="60" class="rounded-circle" style="object-fit: cover;">
                                    </td>
                                    <td>
                                        <a href="#" target="_blank"
                                            rel="noopener noreferrer">
                                            full_name
                                            <i class="fa fa-external-link ms-1" title="View Driver"></i>
                                        </a>
                                    </td>
                                    <td>mobile</td>
                                    <td>type</td>
                                    <td>date</td>
                                    <td>
                                        <span class="badge badge_class">status</span>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



@endsection
