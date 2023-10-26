@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <h4 class="mb-0 ml-2"> &nbsp; On Request</h4>
                        </div>
                        <div class="mt-3 mt-lg-0 ml-lg-auto">
                            @if($auth == 'Project Admin')
                            <a href="{{ route('on_request.create') }}" class="btn btn-secondary">
                                <span><i class="mdi mdi-plus"></i></span> &nbsp; Tambah Project
                            </a>
                            @endif
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
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Request</h4>
                            <div>

                            </div>
                        </div>

                        <div class="card-body">
                            <table class="table" id="tableData">
                                <thead class="table-light">
                                    <tr>
                                        <th style="color:#929EAE">Kode Project</th>
                                        <th style="color:#929EAE">Nama Project</th>
                                        <th style="color:#929EAE">Nama Customer</th>
                                        <th style="color:#929EAE">Tanggal Request</th>
                                        <th style="color:#929EAE">Displacement Kapal</th>
                                        <th style="color:#929EAE">Jenis Kapal</th>
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
                                <select name="nama_customer" id="nama_customer" class="form-control">
                                    <option value="">Pilih Nama Customer</option>
                                    @foreach($customer as $k)
                                    <option value="{{$k->id}}">{{$k->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <div>
                                    <label for="displacement" class="form-label">Displacement Kapal</label>
                                    <input name="displacement" id="displacement" class="form-control form-control-icon">
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="npwp" class="form-label">Jenis Kapal</label>
                                <select name="jenis_kapal" id="jenis_kapal" class="form-control">
                                    <option value="">Pilih Jenis Kapal</option>
                                    @foreach($jenis_kapal as $k)
                                    <option value="{{ $k->id }}">{{ $k->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <label for="Tanggal Request">Tanggal Request</label>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="start_date" class="form-label">Dari </label>
                                <input type="date" name="start_date" id="start_date" class="form-control" >
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="to_date" class="form-label">Sampai</label>
                                <input type="date" name="to_date" id="to_date" class="form-control" >
                            </div>
                        </div>
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
        var table = $('#tableData').DataTable({
            fixedHeader:true,
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
                url: "{{ route('on_request') }}",
                data: function (d) {
                    d.code              = $('#code').val();
                    d.nama_project      = $('#nama_project').val();
                    d.nama_customer     = $('#nama_customer').val();
                    d.tanggal_request   = $('#tanggal_request').val();
                    d.start_date        = $('#start_date').val();
                    d.to_date           = $('#to_date').val();
                    d.jenis_kapal       = $('#jenis_kapal').val();
                }
            },
            columns: [
                {data: 'code', code: 'name'},
                {data: 'nama_project', name: 'nama_project'},
                {data: 'nama_customer', name: 'nama_customer'},
                {data: 'tanggal_request', name: 'tanggal_request'},
                {data: 'displacement', name: 'displacement'},
                {data: 'jenis_kapal', name: 'jenis_kapal'},
                {data: 'action', name: 'action'}
            ]
        });

        $('.form-control').on('change', function() {
            table.draw();
        });

        $('#clear-filter').on('click', function() {
            event.preventDefault();
            $('#search-form')[0].reset();
            table.search('').draw();
        });

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
            var displacement    = $('#displacement').val();
            var start_date      = $('#start_date').val();
            var to_date         = $('#to_date').val();
            var jenis_kapal     = $('#jenis_kapal').val();

            var url = '{{ route("on_request.export") }}?' + $.param({
                code: code,
                nama_project: nama_project,
                nama_customer: nama_customer,
                displacement: displacement,
                start_date: start_date,
                to_date: to_date,
                jenis_kapal: jenis_kapal
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

