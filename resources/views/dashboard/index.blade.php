@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <h6 class="">Statistics</h6>
                </div>
                <div class="w-chart">
                    <div class="w-chart-section">
                        <div class="w-detail">
                            <p class="w-title">Total Quotaions</p>
                            <p class="w-stats">353</p>
                        </div>
                        <div class="w-chart-render-one">
                            <div id="total-users"></div>
                        </div>
                    </div>

                    <div class="w-chart-section">
                        <div class="w-detail">
                            <p class="w-title">Total Booking</p>
                            <p class="w-stats">800</p>
                        </div>
                        <div class="w-chart-render-one">
                            <div id="paid-visits"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-card-four">
                <div class="widget-content">
                    <div class="w-content">
                        <div class="w-info">
                            <h6 class="value">141</h6>
                            <p class="">Open Quotaions</p>
                        </div>
                        <div class="">
                            <div class="w-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-home">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 57%"
                            aria-valuenow="57" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-chart-three">
                <div class="widget-heading">
                    <div class="">
                        <h5 class="">Quotaions Status</h5>
                    </div>

                </div>

                <div class="widget-content">
                    <div id="uniqueVisits"></div>
                </div>
            </div>
        </div> -->



        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
            <div class="widget-four">
                <div class="widget-heading">
                    <h5 class="">Pending Quotaions by Department</h5>
                </div>
                <div class="widget-content">
                    <div class="vistorsBrowser">
                        <div class="browser-list">
                            <div class="w-icon">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                            </div>
                            <div class="w-browser-details">
                                <div class="w-browser-info">
                                    <h6>Financial</h6>
                                    <p class="browser-count">80%</p>
                                </div>
                                <div class="w-browser-stats">
                                    <div class="progress">
                                        <div class="progress-bar bg-gradient-primary" role="progressbar"
                                            style="width: 80%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="browser-list">
                            <div class="w-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-compass">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                                </svg>
                            </div>
                            <div class="w-browser-details">

                                <div class="w-browser-info">
                                    <h6>Packaging</h6>
                                    <p class="browser-count">70%</p>
                                </div>

                                <div class="w-browser-stats">
                                    <div class="progress">
                                        <div class="progress-bar bg-gradient-danger" role="progressbar"
                                            style="width: 70%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="browser-list">
                            <div class="w-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-globe">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                    <path
                                        d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                    </path>
                                </svg>
                            </div>
                            <div class="w-browser-details">

                                <div class="w-browser-info">
                                    <h6>Logistics</h6>
                                    <p class="browser-count">75%</p>
                                </div>

                                <div class="w-browser-stats">
                                    <div class="progress">
                                        <div class="progress-bar bg-gradient-warning" role="progressbar"
                                            style="width: 75%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
            <div class="widget widget-five">
                <div class="widget-content">

                    <div class="header">
                        <div class="header-body">
                            <h6>Pending Requests</h6>
                            <p class="meta-date">April 2020</p>
                        </div>
                        <div class="task-action">
                            <div class="dropdown  custom-dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="pendingTask"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-more-horizontal">
                                        <circle cx="12" cy="12" r="1"></circle>
                                        <circle cx="19" cy="12" r="1"></circle>
                                        <circle cx="5" cy="12" r="1"></circle>
                                    </svg>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="pendingTask">
                                    <a class="dropdown-item" href="javascript:void(0);">View</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-content">
                        <div class="">
                            <p class="task-left">8</p>
                            <p class="task-completed"><span>12 Done</span></p>
                            <p class="task-hight-priority"><span>Total 20</span></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
            <div class="widget widget-five">
                <div class="widget-content">

                    <div class="header">
                        <div class="header-body">
                            <h6>Open Quotaions</h6>
                            <p class="meta-date">April 2020</p>
                        </div>
                        <div class="task-action">
                            <div class="dropdown  custom-dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="pendingTask"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-more-horizontal">
                                        <circle cx="12" cy="12" r="1"></circle>
                                        <circle cx="19" cy="12" r="1"></circle>
                                        <circle cx="5" cy="12" r="1"></circle>
                                    </svg>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="pendingTask">
                                    <a class="dropdown-item" href="javascript:void(0);">View</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-content">
                        <div class="">
                            <p class="task-left">75</p>
                            <p class="task-completed"><span>26 Done</span></p>
                            <p class="task-hight-priority"><span>Total 101</span> </p>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link href="{{asset('plugins/apex/apexcharts.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/dashboard/dash_1.css')}}" rel="stylesheet" type="text/css" />
@endpush
@push('scripts')
    <script src="{{asset('plugins/apex/apexcharts.min.js')}}"></script>
    <script src="{{asset('assets/js/dashboard/dash_1.js')}}"></script>
@endpush