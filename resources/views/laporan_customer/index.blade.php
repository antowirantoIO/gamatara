@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <h4 class="mb-0 ml-2"> &nbsp; Report Customer</h4>
                        </div>
                        <div class="mt-3 mt-lg-0 ml-lg-auto">
                            <button class="btn btn-danger" id="export-button">
                                <span>
                                    <i><img src="{{asset('assets/images/directbox-send.svg')}}" style="width: 15px;"></i>
                                </span> &nbsp; Export
                            </button>
                            <!-- <a href="{{ route('laporan_customer.export') }}" class="btn btn-danger">
                                <span>
                                    <i><img src="{{asset('assets/images/directbox-send.svg')}}" style="width: 15px;"></i>
                                </span> &nbsp; Export
                            </a> -->
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
                                            <!-- <option value="tanggal">Days</option>-->
                                            <option value="bulan">Month</option>
                                            <option value="tahun">Year</option> 
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Customer Name</label>
                                        <select class="form-control select2" name="customer_id" id="customer_id">
                                            <option value="">-- Select Customer --</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
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

            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="table-responsive">
                                    <table class="table" id="tableData">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="color:#929EAE">Customer Name</th>
                                                <th style="color:#929EAE">Project Total</th>
                                                <th style="color:#929EAE">Project Value</th>
                                                <th style="color:#929EAE">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($datas as $d)
                                            <tr>
                                                <td>{{$d->name}}</td>
                                                <td>{{$d->total_project}}</td>
                                                <td>{{$d->totalHargaCustomer}}</td>
                                                <td><a href="{{route('laporan_customer.detail', $d->id)}}" class="btn btn-warning btn-sm">
                                                <span>
                                                    <i><img src="{{asset('assets/images/eye.svg')}}" style="width: 15px;"></i>
                                                </span>
                                                </a></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                        <div class="card-body">
                             <div id="chartContent"></div>
                        </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#daterange').daterangepicker({
            autoUpdateInput: false,
            showDropdowns: true,
            linkedCalendars: false,
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

        var table = $('#tableData').DataTable({
            ordering: false,
            fixedHeader:true,
            lengthChange: false,
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
        });

        $('#btn-search').click(function(e){
            var report_by = $('#report_by').val();
            var customer_id = $('#customer_id').val();
            var daterange = $('#daterange').val();

            $.ajax({
                    url: '{{route('laporan_customer')}}',
                    type: 'GET',
                    data: { report_by: report_by, customer_id: customer_id,daterange : daterange},
                    success: function (response) {
                        table.clear().draw();
                        table.rows.add(response.datas.map(function (item) {
                            return [
                                item.name,
                                item.total_project,
                                item.totalHargaCustomer,
                                '<a href="' + item.detail_url + '" class="btn btn-warning btn-sm">' +
                                '<span><i><img src="' + item.eye_image_url + '" style="width: 15px;"></i></span>' +
                                '</a>'
                            ];
                        })).draw();
                    },
                    error: function (error) {
                        console.error(error);
                    }
                    });
                })
        });

        $('#export-button').on('click', function(event) {
            event.preventDefault(); 

            var customer_id = $('#customer_id').val();
            var daterange   = $('#daterange').val();

            var url = '{{ route("laporan_customer.export") }}?' + $.param({
                customer_id     : customer_id,
                daterange       : daterange,
            });

            $('.loading-overlay').show();

            window.location.href = url;

            setTimeout(hideOverlay, 2000);
        });


    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
        },
    });

    const chartTab = new ApexCharts(document.querySelector("#chartContent"), {
        series: [],
        chart: {
            type: "bar",
            height: 350,
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
        xaxis: {
            categories: ["hari"],
        },
        fill: {
            opacity: 1,
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val
                },
            },
        },
    });
    chartTab.render();

    const domString = {
        form_filter: $('#form_filter'),
    }

    $(() => {
        domString.form_filter.on('submit', (e) => {
            e.preventDefault()
            const data = domString.form_filter.serialize()
            chartData(data)
        })
    })

    function chartData(input) {
        $.ajax({
            url: `{{ route('laporan_customer.dataChart') }}`,
            method: "POST",
            data: input,
            success: function (data) {

                chartTab.updateOptions({
                    series: data.data_customer,
                    chart: {
                        type: "bar",
                        height: 350,
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
                    xaxis: {
                        categories: data.date,
                    },
                    yaxis: {
                        labels: {
                            formatter: function (val) {
                                return val.toLocaleString();
                            }
                        }
                    },
                    fill: {
                        opacity: 1,
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                if (val % 1 !== 0) {
                                    return val.toLocaleString('id-ID', { minimumFractionDigits: 3, maximumFractionDigits: 3 });
                                } else {
                                    return val.toLocaleString('id-ID');
                                }
                            },
                        },
                    },
                });
            },
            error: function (err) {
                console.log(err.responseJSON.message);
            },
        });
    }

    $(function () {
        $(".select2").select2();
    });
    
</script>
@endsection
