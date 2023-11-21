@extends('index')

@section('content')

<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-3" style="flex:1;" 
                        @if(auth()->user()->role->name == 'Project Manager' || auth()->user()->role->name == 'BOD')
                            data-bs-toggle="modal" data-bs-target="#advance"
                        @endif>
                    <div class="card card-animate card-rad">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title" style="background:#68C5FE0D">
                                        <i>
                                            <img src="{{asset('assets/images/notification-circle.svg')}}" width: 45px;>
                                        </i>
                                    </span>
                                </div>
                                <div class="ml-3 card-body flex-column align-items-center justify-content-center">
                                    <a>
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> SPK Request</p>
                                        <h4 class="fs-22 fw-semibold ff-secondary"><span class="counter-value" data-target="{{$spkrequest}}"></span></h4>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3" style="flex:1;">
                    <div class="card card-animate card-rad">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title" style="background:#68C5FE0D">
                                        <i>
                                            <img src="{{asset('assets/images/task-square.svg')}}" width: 45px;>
                                        </i>
                                    </span>
                                </div>
                                <div class="ml-3 card-body flex-column align-items-center justify-content-center">
                                    <a href="{{ route('on_progress') }}">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Progress</p>
                                        <h4 class="fs-22 fw-semibold ff-secondary"><span class="counter-value" data-target="{{$onprogress}}"></span></h4>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3" style="flex:1;">
                    <div class="card card-animate card-rad">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title" style="background:#68C5FE0D">
                                        <i>
                                            <img src="{{asset('assets/images/activity.svg')}}" width: 45px;>
                                        </i>
                                    </span>
                                </div>
                                <div class="ml-3 card-body flex-column align-items-center justify-content-center">
                                    <a href="{{ route('complete') }}">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Complete</p>
                                        <h4 class="fs-22 fw-semibold ff-secondary"><span class="counter-value" data-target="{{$complete}}"></span></h4>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3" style="flex:1;">
                    <div class="card card-animate card-rad">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title" style="background:#01E8870D">
                                        <i>
                                            <img src="{{asset('assets/images/chart-success.svg')}}" width: 45px;>
                                        </i>
                                    </span>
                                </div>
                                <div class="ml-3 card-body flex-column align-items-center justify-content-center">
                                    <a href="{{ route('customer') }}">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Customer</p>
                                        <h4 class="fs-22 fw-semibold ff-secondary"><span class="counter-value" data-target="{{$totalcustomer}}"></span></h4>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3" style="flex:1;">
                    <div class="card card-animate card-rad">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title" style="background:#01E8870D">
                                        <i>
                                            <img src="{{asset('assets/images/chart-success.svg')}}" width: 45px;>
                                        </i>
                                    </span>
                                </div>
                                <div class="ml-3 card-body flex-column align-items-center justify-content-center">
                                    <a href="{{ route('vendor') }}">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Vendor</p>
                                        <h4 class="fs-22 fw-semibold ff-secondary"><span class="counter-value" data-target="{{$totalvendor}}"></span></h4>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="col-md-3" style="flex:1;">
                    <div class="card card-animate card-rad">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Customer</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title" style="background:#01E8870D">
                                        <i>
                                            <img src="{{asset('assets/images/chart-success.svg')}}">
                                        </i>
                                    </span>
                                </div>
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="83"></span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Project</h4>
                            <div>
                                <a href="{{ route('on_request') }}" style="color: #194BFB;" class="text-reset dropdown-btn">
                                    <span class="fw-semibold text-uppercase fs-12" style="color: #194BFB;">View All
                                </a>
                            </div>
                        </div>

                        <div class="card-body" style="height: 380px;">
                            <div class="table-responsive">
                                <table class="table" id="example1">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="color:#929EAE">No.</th>
                                            <th style="color:#929EAE">Code Project</th>
                                            <th style="color:#929EAE">Project Name</th>
                                            <th style="color:#929EAE">Customer Name</th>
                                            <th style="color:#929EAE">Project Manajer</th>
                                            <th style="color:#929EAE">Start Date</th>
                                            <th style="color:#929EAE">Actual Complete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $key => $d)
                                        <tr>
                                            <td>{{ $key + 1}}</td>
                                            <td>{{ $d->code }}</td>
                                            <td>{{ $d->nama_project }}</td>
                                            <td>{{ $d->customer->name ?? ''}}</td>
                                            <td>{{ $d->pm->karyawan->name ?? ''}}</td>
                                            <td>{{ $d->start_project }}</td>
                                            <td>{{ $d->actual_selesai }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header align-items-center d-flex border-0">
                            <h4 class="card-title mb-0 flex-grow-1">Top Project Manajer</h4>
                            <div class="flex-shrink-0">
                                <div class="dropdown card-header-dropdown">
                                    <a class="text-reset dropdown-btn" href="{{ route('laporan_project_manager') }}">
                                        <span class="fw-semibold text-uppercase fs-12" style="color: #194BFB;">View All
                                        <!-- </span><span class="text-muted">Today<i class="mdi mdi-chevron-down ms-1"></i></span> -->
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body" style="height: 380px;">
                            <div class="table-responsive">
                                <table class="table" id="example2">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="color:#929EAE"><center>Project Manager</center></th>
                                            <th style="color:#929EAE"><center>On Progress</center></th>
                                            <th style="color:#929EAE"><center>Complete</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pm as $key => $v)
                                            <tr>
                                                <td>
                                                    <center>
                                                        {{ $v->karyawan->name ?? '' }}
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        @foreach ($v->projects as $project)
                                                            {{ $project->onprogress ?? '' }}
                                                        @endforeach
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        @foreach ($v->projects as $project)
                                                            {{ $project->complete ?? '' }}
                                                        @endforeach
                                                    </center>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header align-items-center d-flex border-0">
                            <h4 class="card-title mb-0 flex-grow-1">Top Vendor</h4>
                            <div class="flex-shrink-0">
                                <div class="dropdown card-header-dropdown">
                                    <a class="text-reset dropdown-btn" href="{{ route('laporan_vendor') }}">
                                        <span class="fw-semibold text-uppercase fs-12" style="color: #194BFB;">View All
                                        <!-- </span><span class="text-muted">Today<i class="mdi mdi-chevron-down ms-1"></i></span> -->
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body" style="height: 380px;">
                            <div class="table-responsive">
                                <table class="table" id="example3">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="color:#929EAE"><center>Project Manager</center></th>
                                            <th style="color:#929EAE"><center>On Progress</center></th>
                                            <th style="color:#929EAE"><center>Complete</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vendors as $keys => $v)
                                        <tr>
                                            <td><center>{{ $v->vendors->name }}</center></td>
                                            <td>
                                                <Center>
                                                @isset($progress[$keys])
                                                    {{ $progress[$keys]->onprogress }}
                                                @else
                                                    0
                                                @endisset
                                                </Center>
                                            </td>
                                            <td>
                                                <Center>
                                                @isset($progress[$keys])
                                                    {{ $progress[$keys]->complete }}
                                                @else
                                                    0
                                                @endisset
                                                </Center>
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
        </div>
    </div>
</div>

<!--modal-->
<div id="advance" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form  id="formOnRequest" method="get" enctype="multipart/form-data">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="zoomInModalLabel">SPK Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row gy-4">
                        <table id="tabelKeluhan" class="table table-bordered">
                            <thead style="background-color:#194BFB;color:#FFFFFF">
                                <tr>
                                    <th width="10px">No.</th>
                                    <th>Code</th>
                                    <th>Project Name</th>
                                    <th>Requiring Approval</th>
                                    <th>Approval</th>
                                </tr>
                            </thead>  
                            <tbody>
                                @foreach($keluhan as $key => $k)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $k['code'] }}</td>
                                        <td>{{ $k['nama_project'] }}</td>
                                        <td>{{ $k['jumlah'] }}</td>
                                        <td>
                                            <a href="{{ route('on_request.detail',$k['id']) }}" type="button" class="btn btn-primary">Approve</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- <div class="modal-footer">
                    <a class="btn btn-danger" type="button" data-bs-dismiss="modal" aria-label="Close" style="margin-right: 10px;">close</a>
                    <button class="btn btn-primary">Search</button>
                </div> -->
            </form>
        </div>
    </div>
</div>
<!--end modal-->

@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        var table = $('#tabelKeluhan').DataTable({
            ordering: false,
            fixedHeader:true,
            scrollX: false,
            searching: false,
            lengthMenu: [5, 10, 15],
            pageLength: 5,
            language: {
                processing:
                    '<div class="spinner-border text-info" role="status">' +
                    '<span class="sr-only">Loading...</span>' +
                    "</div>",
                paginate: {
                    Search: '<i class="icon-search"></i>',
                    first: "<i class='fas fa-angle-double-left'></i>",
                    next: "Next <span class='mdi mdi-chevron-right'></span>",
                    last: "<i class='fas fa-angle-double-right'></i>",
                },
                "info": "Displaying _START_ - _END_ of _TOTAL_ result",
            },
            drawCallback: function() {
                var previousButton = $('.paginate_button.previous');
                previousButton.css('display', 'none');
            },
        });
    });

    $(function() {
        $("#example1").DataTable({
            ordering: false,
            fixedHeader:true,
            lengthMenu: [5, 10, 15],
            language: {
                processing:
                    '<div class="spinner-border text-info" role="status">' +
                    '<span class="sr-only">Loading...</span>' +
                    "</div>",
                paginate: {
                    Search: '<i class="icon-search"></i>',
                    first: "<i class='fas fa-angle-double-left'></i>",
                    next: "Next <span class='mdi mdi-chevron-right'></span>",
                    last: "<i class='fas fa-angle-double-right'></i>",
                },
                "info": "Displaying _START_ - _END_ of _TOTAL_ result",
            },
            drawCallback: function() {
                var previousButton = $('.paginate_button.previous');
                previousButton.css('display', 'none');
            },
        });

        $("#example2").DataTable({
            ordering: false,
            fixedHeader:true,
            lengthMenu: [5, 10, 15],
            language: {
                processing:
                    '<div class="spinner-border text-info" role="status">' +
                    '<span class="sr-only">Loading...</span>' +
                    "</div>",
                paginate: {
                    Search: '<i class="icon-search"></i>',
                    first: "<i class='fas fa-angle-double-left'></i>",
                    next: "Next <span class='mdi mdi-chevron-right'></span>",
                    last: "<i class='fas fa-angle-double-right'></i>",
                },
                "info": "Displaying _START_ - _END_ of _TOTAL_ result",
            },
            drawCallback: function() {
                var previousButton = $('.paginate_button.previous');
                previousButton.css('display', 'none');
            },
        });

        $("#example3").DataTable({
            ordering: false,
            fixedHeader:true,
            lengthMenu: [5, 10, 15],
            language: {
                processing:
                    '<div class="spinner-border text-info" role="status">' +
                    '<span class="sr-only">Loading...</span>' +
                    "</div>",
                paginate: {
                    Search: '<i class="icon-search"></i>',
                    first: "<i class='fas fa-angle-double-left'></i>",
                    next: "Next <span class='mdi mdi-chevron-right'></span>",
                    last: "<i class='fas fa-angle-double-right'></i>",
                },
                "info": "Displaying _START_ - _END_ of _TOTAL_ result",
            },
            drawCallback: function() {
                var previousButton = $('.paginate_button.previous');
                previousButton.css('display', 'none');
            },
        });
    })
</script>
@endsection
