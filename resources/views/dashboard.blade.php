@extends('index')

@section('content')

<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                        <!-- <div class="flex-grow-1">
                            <h4 class="fs-16 mb-1">Good Morning, Anna!</h4>
                            <p class="text-muted mb-0">Here's what's happening with your store today.</p>
                        </div> -->
                        <!-- <div class="mt-3 mt-lg-0">
                            <form action="javascript:void(0);">
                                <div class="row g-3 mb-0 align-items-center">
                                    <div class="col-sm-auto">
                                        <div class="input-group">
                                            <input type="text" class="form-control border-0 dash-filter-picker shadow" data-provider="flatpickr" data-range-date="true" data-date-format="d M, Y" data-deafult-date="01 Jan 2022 to 31 Jan 2022">
                                            <div class="input-group-text bg-primary border-primary text-white">
                                                <i class="ri-calendar-2-line"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-soft-success"><i class="ri-add-circle-line align-middle me-1"></i> Add Product</button>
                                    </div>
                                
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-soft-info btn-icon waves-effect waves-light layout-rightside-btn"><i class="ri-pulse-line"></i></button>
                                    </div>
                                
                                </div>
                            </form>
                        </div> -->
                    </div><!-- end card header -->
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="row">
                
                <div class="col-md-3" style="flex:1;">
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
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Project Aktif</p>
                                    <h4 class="fs-22 fw-semibold ff-secondary"><span class="counter-value" data-target="18"></span></h4>
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
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Request</p>
                                    <h4 class="fs-22 fw-semibold ff-secondary"><span class="counter-value" data-target="{{$request}}"></span></h4>
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
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Progress</p>
                                    <h4 class="fs-22 fw-semibold ff-secondary"><span class="counter-value" data-target="13"></span></h4>
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
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Complete</p>
                                    <h4 class="fs-22 fw-semibold ff-secondary"><span class="counter-value" data-target="135"></span></h4>
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
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Customer</p>
                                    <h4 class="fs-22 fw-semibold ff-secondary"><span class="counter-value" data-target="83"></span></h4>
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
                                <a href="" style="color: #194BFB;">View All</a>
                                <!-- <button type="button" class="btn btn-soft-secondary btn-sm">
                                    View ALL
                                </button> -->
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="example1">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="color:#929EAE">Kode Project</th>
                                            <th style="color:#929EAE">Nama Project</th>
                                            <th style="color:#929EAE">Nama Customer</th>
                                            <th style="color:#929EAE">Project Manajer</th>
                                            <th style="color:#929EAE">Tanggal Mulai</th>
                                            <th style="color:#929EAE">Tanggal Selesai</th>
                                            <th style="color:#929EAE">Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>P210823-001</td>
                                            <td>Nama Project</td>
                                            <td>PT Bomas Tiga</td>
                                            <td>Bagus Ampito</td>
                                            <td>28 Ags 2023</td>
                                            <td>28 Sept 2023</td>
                                            <td>133/436</td>
                                        </tr>
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
                                    <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="fw-semibold text-uppercase fs-12" style="color: #194BFB;">View All 
                                        <!-- </span><span class="text-muted">Today<i class="mdi mdi-chevron-down ms-1"></i></span> -->
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
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
                                        <tr>
                                            <td><center>Bagus Ampito</center></td>
                                            <td><Center>2</Center></td>
                                            <td><center>25</center></td>
                                        </tr>
                                        <tr>
                                            <td><center>Fina G Bastian</center></td>
                                            <td><center>1</center></td>
                                            <td><center>25</center></td>
                                        </tr>
                                        <tr>
                                            <td><center>Adam Makmur</center></td>
                                            <td><center>3</center></td>
                                            <td><center>25</center></td>
                                        </tr>
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
                                    <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="fw-semibold text-uppercase fs-12" style="color: #194BFB;">View All 
                                        <!-- </span><span class="text-muted">Today<i class="mdi mdi-chevron-down ms-1"></i></span> -->
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
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
                                        <tr>
                                            <td><center>Bagus Ampito</center></td>
                                            <td><Center>2</Center></td>
                                            <td><center>25</center></td>
                                        </tr>
                                        <tr>
                                            <td><center>Fina G Bastian</center></td>
                                            <td><center>1</center></td>
                                            <td><center>25</center></td>
                                        </tr>
                                        <tr>
                                            <td><center>Adam Makmur</center></td>
                                            <td><center>3</center></td>
                                            <td><center>25</center></td>
                                        </tr>
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
@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(function() {
            $("#example1").DataTable({
                fixedHeader:true,
            });

            $("#example2").DataTable({
                fixedHeader:true,
            });

            $("#example3").DataTable({
                fixedHeader:true,
            });
        })
</script>
@endsection