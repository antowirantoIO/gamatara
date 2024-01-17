@extends('index')

@section('content')

<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <h4 class="mb-0 ml-2"> &nbsp; On Progress</h4>
                        </div>
                        <div class="mt-3 mt-lg-0 ml-lg-auto">
                            <button class="btn btn-secondary" id="btn-fillter">
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
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">On Progress</h4>
                            <div>

                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="row">
                                    <table class="table w-100" id="example1">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="color:#929EAE">Project Code</th>
                                                <th style="color:#929EAE">Project Name</th>
                                                <th style="color:#929EAE">Customer Name</th>
                                                <th style="color:#929EAE">Project Manager</th>
                                                <th style="color:#929EAE">Start Date</th>
                                                <th style="color:#929EAE">End Date</th>
                                                <th style="color:#929EAE">Progres</th>
                                                <th style="color:#929EAE">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
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
</div>
<div id="modalFillter" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-top-right">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gy-4">
                    <div class="col-xxl-6 col-md-6">
                        <div>
                            <label for="code" class="form-label">Project Code</label>
                            <input type="text" name="code" class="form-control" id="code">
                        </div>
                    </div>
                    <div class="col-xxl-6 col-md-6">
                        <div>
                            <label for="nama_project" class="form-label">Project Name</label>
                            <input type="text" name="nama_project" id="nama_project" class="form-control">
                        </div>
                    </div>
                    <div class="col-xxl-6 col-md-6">
                        <div>
                            <label for="nama_customer" class="form-label">Customer Name</label>
                            <select name="nama_customer" id="nama_customer" class="form-select">
                                <option value="">Choose Customer Name</option>
                                @foreach($customer as $k)
                                <option value="{{$k->id}}">{{$k->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xxl-6 col-md-6">
                        <div>
                            <label for="nama_pm" class="form-label">PM Name</label>
                            <select name="nama_pm" id="nama_pm" class="form-select">
                                <option value="">Choose PM Name</option>
                                @foreach($pm as $p)
                                <option value="{{$p->id}}">{{$p->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div>
                            <label for="date" class="form-label">Date </label>
                            <input type="text" name="date" id="date" class="form-control" >
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn btn-danger" id="btn-reset" style="margin-right: 10px;">Reset</div>
                <button class="btn btn-primary" id="btn-search">Search</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(function() {
            let modalInput = $('#modalFillter');

            $('.form-select').select2({
                theme : "bootstrap-5",
                dropdownParent: $("#modalFillter"),
                search: true
            });

            $('#date').daterangepicker({
                opens: 'right',
                autoUpdateInput: false,
                showDropdowns: true,
                linkedCalendars: false,
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear'

                },
            });

            $('#date').on('apply.daterangepicker',function(e,picker){
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            })

            $('#btn-fillter').click(function(){
                modalInput.modal('show');
            })

            $('#btn-reset').click(function(e){
                e.preventDefault();
                $('.form-control').val('');
                $('.form-select').val(null).trigger('change');
                $('#date').val('');
                table.draw()
            })

            let table = $("#example1").DataTable({
                fixedHeader:true,
                ordering : false,
                scrollX: false,
                processing: true,
                serverSide: true,
                searching: false,
                bLengthChange: false,
                autoWidth : true,
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
                ajax : {
                    url : '{{ route('on_progress') }}',
                    data : function (d) {
                        d.code = $('#code').val();
                        d.nama_project = $('#nama_project').val();
                        d.nama_customer = $('#nama_customer').val();
                        d.nama_pm = $('#nama_pm').val();
                        d.date =  $('#date').val();
                    }
                },
                columns : [
                    { data : 'code', name : 'code'},
                    { data : 'nama_project', name : 'nama_project'},
                    { data : 'customer.name', name : 'customer'},
                    { data : function(data) {
                           let pm = data.pm || '-';
                           let karyawan = pm.karyawan || '-';
                           let name = karyawan.name || '-';
                           return name;
                        }, name : 'pm'
                    },
                    { data : 'start', name : 'start'},
                    { data : 'end', name : 'end'},
                    {
                        data : function(data) {
                            return data.progres
                        }
                    },
                    {
                        data : function(data) {
                            let id = data.id;
                            let url = '{{ route('on_progress.edit',':id') }}';
                            let urlReplace = url.replace(':id',id);
                            return ` <a href="${urlReplace}" class="btn btn-warning btn-sm">
                                <span>
                                    <i><img src="{{asset('assets/images/eye.svg')}}" style="width: 15px;"></i>
                                </span>
                            </a>`
                        }
                    }
                ]
            });

            $('#btn-search').click(function(e){
                e.preventDefault();
                modalInput.modal('hide');
                table.draw();
            })

            function hideOverlay() {
                $('.loading-overlay').fadeOut('slow', function() {
                    $(this).remove();
                });
            }

            $('#export-button').on('click', function(event) {
                event.preventDefault();

                var code            = $('#code').val();
                var nama_project    = $('#nama_project').val();
                var nama_customer   = $('#nama_customer').val();
                var nama_pm         = $('#nama_pm').val();
                var date            = $('#date').val();

                var url = '{{ route("on_progres.export-data") }}?' + $.param({
                    code: code,
                    nama_project: nama_project,
                    nama_customer: nama_customer,
                    nama_pm: nama_pm,
                    date: date
                });

                $('.loading-overlay').show();

                window.location.href = url;

                setTimeout(hideOverlay, 2000);
            });
        })
</script>
@endsection
