@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <h4 class="mb-0 ml-2"> &nbsp; Laporan Project Manager</h4>
                        </div>
                        <div class="mt-3 mt-lg-0 ml-lg-auto">
                            <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#advance">
                                <span>
                                    <i><img src="{{asset('assets/images/filter.svg')}}" style="width: 15px;"></i>
                                </span> &nbsp; Filter
                            </button>
                            <button class="btn btn-danger" id="export-button">
                                <span>
                                    <i><img src="{{asset('assets/images/directbox-send.svg')}}" style="width: 15px;"></i>
                                </span> &nbsp; Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Project Manager</h4>
                            <div>
                          
                            </div>
                        </div>

                        <div class="card-body" style="height: 640px;">
                            <table class="table" id="tableData">
                                <thead class="table-light">
                                    <tr>
                                        <th style="color:#929EAE">Nama Project Manager</th>
                                        <th style="color:#929EAE">On Progress</th>
                                        <th style="color:#929EAE">Complete</th>
                                        <th style="color:#929EAE">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">
                                <span style="width: 15px;height: 15px;background-color:#90BDFF; display: inline-block;"></span>
                                &nbsp; On Progress
                                &nbsp;
                                <span style="width: 15px;height: 15px;background-color:#194BFB; display: inline-block;"></span>
                                &nbsp; Complete
                            </h4>
                            <div class="mt-3 mt-lg-0 ml-lg-auto">
                                <div class="dropdown" role="group">
                                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        2023
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <li><a class="dropdown-item" href="#">Dropdown link</a></li>
                                        <li><a class="dropdown-item" href="#">Dropdown link</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div id="bar" data-colors='["--vz-success"]' class="apex-charts" dir="ltr"></div>
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
                    <h5 class="modal-title" id="zoomInModalLabel">Filter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row gy-4">
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="customer" class="form-label">Nama Project Manager</label>
                                <input type="text" name="nama_project_manager" class="form-control" id="nama_project_manager">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="on_progress" class="form-label">On Progress</label>
                                <input type="text" name="on_progress" id="on_progress" class="form-control">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="complete" class="form-label">Complete</label>
                                <input type="text" name="complete" id="complete" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end modal-->
@endsection

@section('scripts')
<script>
    //chart
    var options = {
    chart: {
        type: 'bar',
        height: 600,
    },
    plotOptions: {
        bar: {
        horizontal: true,
        borderRadius: 5,
        },
    },
    dataLabels: {
        enabled: false,
    },
    series: [
        {
            name: 'On Progress',
            data: [150, 220, 350, 280, 420, 310, 260, 380, 420, 330, 280, 200],
        },
        {
            name: 'Complete',
            data: [200, 270, 380, 310, 450, 350, 280, 420, 460, 350, 300, 220],
        }
    ],
    xaxis: {
            labels:{
                show:false,
            },
            categories: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        },
        colors: ['#90BDFF','#194BFB'],
        legend: {
        show: false,
    }
    };

    var chart = new ApexCharts(document.querySelector("#bar"), options);
    chart.render();

    //datatable
     $(document).ready(function () {
        var table = $('#tableData').DataTable({
            fixedHeader:true,
            lengthChange: false,
            scrollX: false,
            processing: true,
            serverSide: true,
            searching: false,
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
            ajax: {
                url: "{{ route('laporan_project_manager') }}",
                data: function (d) {
                    d.name          = $('#name').val();
                    d.on_progress   = $('#on_progress').val();
                    d.complete      = $('#complete').val();
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'on_progress', name: 'on_progress'},
                {data: 'complete', name: 'complete'},
                {data: 'action', name: 'action'}
            ]
        });

        $('.form-control').on('change', function() {
            table.draw();
        });

        function hideOverlay() {
            $('.loading-overlay').fadeOut('slow', function() {
                $(this).remove();
            });
        }

        $('#export-button').on('click', function(event) {
            event.preventDefault(); 

            var name        = $('#name').val();
            var on_progress = $('#on_progress').val();
            var complete    = $('#complete').val();

            var url = '{{ route("laporan_project_manager.export") }}?' + $.param({
                name: name,
                on_progress: on_progress,
                complete: complete,
            });

            $('.loading-overlay').show();

            window.location.href = url;

            setTimeout(hideOverlay, 2000);
        });

        $(document).ready(function() {
            $('.loading-overlay').hide();
        });
    });
</script>
@endsection
