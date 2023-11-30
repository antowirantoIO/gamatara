@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <h4 class="mb-0 ml-2"> &nbsp; Report Project Manager</h4>
                        </div>
                        <div class="mt-3 mt-lg-0 ml-lg-auto">
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
                <div class="col-12">
                    <div class="card card-outline">
                        <div class="card-body">
                            <form id="form_filter">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label>Report By</label>
                                        <select class="form-control" name="report_by" id="report_by">
                                            <option value="tanggal">Days</option>
                                            <option value="bulan">Month</option>
                                            <option value="tahun">Year</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Customer Name</label>
                                        <select class="form-control" name="nama_project_manager" id="nama_project_manager">
                                            <option value="">-- Select Project Manager --</option>
                                            @foreach($project_manager as $pm)
                                                <option value="{{ $pm->id }}">{{ $pm->karyawan->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Date</label>
                                        <input type="text" class="form-control" name="daterange" id="daterange" autocomplete="off" placeholder="--Select Date--">
                                    </div>
                                    <div class="form-group col-md-3 mt-3">
                                        <div>
                                            <button class="btn btn-primary" id="btn-search">Show Data</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table" id="tableData">
                                <thead class="table-light">
                                    <tr>
                                        <th style="color:#929EAE">Project Manager Name</th>
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
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">
                                <span style="width: 15px;height: 15px;background-color:#90BDFF; display: inline-block;"></span>
                                &nbsp; On Progress
                                &nbsp;
                                <span style="width: 15px;height: 15px;background-color:#194BFB; display: inline-block;"></span>
                                &nbsp; Complete
                            </h4>
                        </div>

                        <div class="card-body">
                            <div id="chartContent"></div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>

    //datatable
     $(document).ready(function () {
        $('#daterange').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        });

        $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });


        $.ajax({
            url : '{{ route('laporan_project_manager.charts') }}',
            success : function(data){
                charts(data);
            }

        })

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
                    d.name          = $('#nama_project_manager').val();
                    d.report_by = $('#report_by').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'on_progress', name: 'on_progress'},
                {data: 'complete', name: 'complete'},
                {data: 'action', name: 'action'}
            ]
        });

        $('#btn-search').on('click', function(e) {
            e.preventDefault();
            table.draw();
            $.ajax({
                url : '{{ route('laporan_project_manager.charts') }}',
                data : function(d){
                    d.name          = $('#nama_project_manager').val();
                    d.report_by     = $('#report_by').val();
                    d.start_date    = $('#start_date').val();
                    d.end_date      = $('#end_date').val();
                },
                success : function(data){
                    charts(data, true)
                }

            })
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
        var chart = null;
        var charts = (data, isUpdate=false) => {
            var chartData = Object.values(data).map(item => ({
                name: item.Employee,
                data: [item['On Progress'], item['Complete']],
            }));

            const chart = new ApexCharts(document.querySelector("#chartContent"),{
                chart: {
                    type: 'bar',
                    height: 600,
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "55%",
                        endingShape: "rounded",
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ["transparent"],
                },
                series: [
                    {
                        name: 'On Progress',
                        data: chartData ? chartData.map(item => parseInt(item.data[0])) : [0],
                    },
                    {
                        name: 'Complete',
                        data: chartData ? chartData.map(item => parseInt(item.data[1])) : [0],
                    }
                ],
                xaxis: {
                    categories: chartData ? chartData.map(item => item.name) : [],
                },
                colors: ['#90BDFF','#194BFB'],
                legend: {
                    show: false,
                }
            });
            if(isUpdate){
                chart.updateOptions({
                    chart: {
                        type: 'bar',
                        height: 600,
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: "55%",
                            endingShape: "rounded",
                        },
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ["transparent"],
                    },
                    series: [
                        {
                            name: 'On Progress',
                            data: chartData ? chartData.map(item => parseInt(item.data[0])) : [0],
                        },
                        {
                            name: 'Complete',
                            data: chartData ? chartData.map(item => parseInt(item.data[1])) : [0],
                        }
                    ],
                    xaxis: {
                        categories: chartData ? chartData.map(item => item.name) : [],
                    },
                    colors: ['#90BDFF','#194BFB'],
                    legend: {
                        show: false,
                    }
                })
            }
            chart.render();

        }

        $('#export-button').on('click', function(event) {
            event.preventDefault();

            var url = '{{ route("laporan_project_manager.export") }}'

            $('.loading-overlay').show();

            window.location.href = url;

            setTimeout(hideOverlay, 2000);
        });
    });
</script>
@endsection
