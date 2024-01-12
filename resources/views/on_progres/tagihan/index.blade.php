@extends('index')

@section('content')

<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('on_progress.edit',$id)}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Vendor Bills</h4>
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
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="container">
                                <table class="table w-100" id="example1">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="color:#929EAE">Project Code</th>
                                            <th style="color:#929EAE">Project Name</th>
                                            <th style="color:#929EAE">Customer Name</th>
                                            <th style="color:#929EAE">Vendor Name</th>
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
{{-- <div id="modalFillter" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
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
                            <label for="code" class="form-label">Code Project</label>
                            <input type="text" name="code" class="form-control" id="code">
                        </div>
                    </div>
                    <div class="col-xxl-6 col-md-6">
                        <div>
                            <label for="nama_project" class="form-label">Nama Project</label>
                            <input type="text" name="nama_project" id="nama_project" class="form-control">
                        </div>
                    </div>
                    <div class="col-xxl-6 col-md-6">
                        <div>
                            <label for="nama_customer" class="form-label">Nama Customer</label>
                            <select name="nama_customer" id="nama_customer" class="form-select">
                                <option value="">Pilih Nama Customer</option>
                                @foreach($customer as $k)
                                <option value="{{$k->id}}">{{$k->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xxl-6 col-md-6">
                        <div>
                            <label for="nama_pm" class="form-label">Nama PM</label>
                            <select name="nama_pm" id="nama_pm" class="form-select">
                                <option value="">Pilih Nama PM</option>
                                @foreach($pm as $p)
                                <option value="{{$p->id}}">{{$p->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div>
                            <label for="date" class="form-label">Dari </label>
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
</div> --}}
@endsection
@section('scripts')
<script>
    $(function() {
            let modalInput = $('#modalFillter');
            $('.form-select').select2({
                theme : "bootstrap-5",
                search: true
            });

            $('#date').daterangepicker({
                opens: 'right',
                autoUpdateInput: false,
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
                scrollX: false,
                processing: true,
                serverSide: true,
                searching: true,
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
                    url : '{{ route('ajax.tagiham-all') }}',
                    data : function (d) {
                        filterSearch    = d.search?.value;
                        d.code          = $('#code').val();
                        d.id            = '{{ $id }}';
                        d.nama_project  = $('#nama_project').val();
                        d.nama_customer = $('#nama_customer').val();
                        d.nama_pm       = $('#nama_pm').val();
                        d.date          =  $('#date').val();
                    },
                    complete : function (data) {
                        console.log(data.responseJSON);
                    }
                },
                columns : [
                    { data : 'code', name : 'code'},
                    { data : 'nama_project', name : 'nama_project'},
                    { data : 'customer', name : 'customer'},
                    { data : 'vendor', name : 'vendor'},
                    {
                        data : function(data) {
                            let id = data.id_project;
                            let vendor = data.id_vendor;
                            let url = '{{ route('on_progres.tagihan-vendor',[':id',':vendor']) }}';
                            let urlReplace = url.replace(':id',id).replace(':vendor',vendor);
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

                var id_project      = '{{ $id }}';

                var url = '{{ route("on_progres.export.all-tagihan-vendor") }}?' + $.param({
                    id_project: id_project,
                });

                $('.loading-overlay').show();

                window.location.href = url;

                setTimeout(hideOverlay, 2000);
            });
        })
</script>
@endsection
