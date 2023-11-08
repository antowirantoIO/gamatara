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
                            @can('on_request-add')
                            <a href="{{ route('on_request.create') }}" class="btn btn-secondary">
                                <span><i class="mdi mdi-plus"></i></span> &nbsp; Add
                            </a>
                            @endcan
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
                                        <th style="color:#929EAE">Code Project</th>
                                        <th style="color:#929EAE">Status Survey</th>
                                        <th style="color:#929EAE">Project Name</th>
                                        <th style="color:#929EAE">Customer Name</th>
                                        <th style="color:#929EAE">Request Date</th>
                                        <th style="color:#929EAE">Displacement Ship</th>
                                        <th style="color:#929EAE">Ship Type</th>
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
                                <label for="code">Code Project</label>
                                <input type="text" name="code" class="form-control" id="code">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="Status Survey">Status Survey</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Choose Status Survey</option>
                                    @foreach($status as $k)
                                    <option value="{{ $k->id }}">{{ $k->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="nama_project">Project Name</label>
                                <input type="text" name="nama_project" id="nama_project" class="form-control">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="nama_customer">Customer Name</label>
                                <select name="nama_customer" id="nama_customer" class="form-control">
                                    <option value="">Choose Customer Name</option>
                                    @foreach($customer as $k)
                                    <option value="{{$k->id}}">{{$k->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <div>
                                    <label for="displacement">Displacement Ship</label>
                                    <input name="displacement" id="displacement" class="form-control form-control-icon">
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="npwp">Ship Type</label>
                                <select name="jenis_kapal" id="jenis_kapal" class="form-control">
                                    <option value="">Choose Ship Type</option>
                                    @foreach($jenis_kapal as $k)
                                    <option value="{{ $k->id }}">{{ $k->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <label for="Tanggal Request">Request Date</label>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="start_date">From </label>
                                <input type="date" name="start_date" id="start_date" class="form-control" >
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="to_date">To</label>
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
        let filterSearch = '';
        var table = $('#tableData').DataTable({
            ordering: false,
            fixedHeader:true,
            scrollX: false,
            processing: true,
            serverSide: true,
            searching: true,
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
                    filterSearch        = d.search?.value;
                    d.code              = $('#code').val();
                    d.survey            = $('#survey').val();
                    d.nama_project      = $('#nama_project').val();
                    d.nama_customer     = $('#nama_customer').val();
                    d.tanggal_request   = $('#tanggal_request').val();
                    d.start_date        = $('#start_date').val();
                    d.to_date           = $('#to_date').val();
                    d.jenis_kapal       = $('#jenis_kapal').val();
                }
            },
            columns: [
                {data: 'code', name: 'code'},
                {data: 'survey',name: 'survey'},
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
            var survey          = $('#survey').val();
            var nama_project    = $('#nama_project').val();
            var nama_customer   = $('#nama_customer').val();
            var displacement    = $('#displacement').val();
            var start_date      = $('#start_date').val();
            var to_date         = $('#to_date').val();
            var jenis_kapal     = $('#jenis_kapal').val();

            var url = '{{ route("on_request.export") }}?' + $.param({
                code            : code,
                survey          : survey,
                nama_project    : nama_project,
                nama_customer   : nama_customer,
                displacement    : displacement,
                start_date      : start_date,
                to_date         : to_date,
                jenis_kapal     : jenis_kapal,
                keyword         : filterSearch
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

