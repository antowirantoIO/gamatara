@extends('index')

@section('content')

<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('laporan_customer')}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Laporan Customer Detail</h4>
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
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">{{ $name->customer->name ?? ''}}</h4>
                            <div>
                          
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-container">
                                <table class="table" id="tableData">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="color:#929EAE">Kode Project</th>
                                            <th style="color:#929EAE">Nama Project</th>
                                            <th style="color:#929EAE">Tanggal Mulai</th>
                                            <th style="color:#929EAE">Tanggal Selesai</th>
                                            <th style="color:#929EAE">Nilai Project</th>
                                            <th style="color:#929EAE">Status Project</th>
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
                                <label for="customer" class="form-label">Nama Project</label>
                                <input type="text" name="nama_project" class="form-control" id="nama_project">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="nilai_project" class="form-label">Nilai Project</label>
                                <input type="text" name="nilai_project" id="nilai_project" class="form-control">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="jumlah_project" class="form-label">Jumlah Project</label>
                                <input type="text" name="jumlah_project" id="jumlah_project" class="form-control">
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
                url: "{{ route('laporan_customer.detail', 5) }}",
                data: function (d) {
                    d.code              = $('#code').val();
                    d.nama_project      = $('#nama_project').val();
                    d.tanggal_request   = $('#tanggal_request').val();
                    d.tanggal_selesai   = $('#tanggal_selesai').val();
                    d.nilai_project     = $('#nilai_project').val();
                    d.status_project    = $('#status_project').val();
                }
            },
            columns: [
                {data: 'code', code: 'name'},
                {data: 'nama_project', name: 'nama_project'},
                {data: 'tanggal_request', name: 'tanggal_request'},
                {data: 'tanggal_request', name: 'tanggal_request'},
                {data: 'nilai_project', name: 'nilai_project'},
                {data: 'status_project', name: 'status_project'},
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

            var code            = $('#code').val();
            var nama_project    = $('#nama_project').val();
            var tanggal_request = $('#tanggal_request').val();
            var tanggal_selesai = $('#tanggal_selesai').val();
            var nilai_project   = $('#nilai_project').val();
            var status_project  = $('#status_project').val();

            var url = '{{ route("laporan_detail_customer.export") }}?' + $.param({
                code: code,
                nama_project: nama_project,
                tanggal_request: tanggal_request,
                tanggal_selesai: tanggal_selesai,
                nilai_project: nilai_project,
                status_project: status_project
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
