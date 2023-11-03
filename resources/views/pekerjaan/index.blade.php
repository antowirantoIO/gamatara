@extends('index')

@section('content')

<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <h4 class="mb-0 ml-2"> &nbsp; Pekerjaan</h4>
                        </div>
                        <div class="mt-3 mt-lg-0 ml-lg-auto">
                            <a href="{{ route('pekerjaan.create') }}" class="btn btn-secondary">
                                <span><i class="mdi mdi-plus"></i></span> &nbsp; Add
                            </a>
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
                            <h4 class="card-title mb-0 flex-grow-1">Pekerjaan</h4>
                            <div>
                          
                            </div>
                        </div>

                        <div class="card-body">
                            <table class="table" id="tableData">
                                <thead class="table-light">
                                    <tr>
                                        <th style="color:#929EAE">Nama Pekerjaan</th>
                                        <th style="color:#929EAE">Unit</th>
                                        <th style="color:#929EAE">Konversi</th>
                                        <th style="color:#929EAE">Harga Vendor</th>
                                        <th style="color:#929EAE">Harga Customer</th>
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
                                <label for="nama">Nama Pekerjaan</label>
                                <input type="text" name="name" class="form-control" id="name">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6"></div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="unit">Unit</label>
                                <input type="text" name="unit" class="form-control" id="unit">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="konversi">Konversi</label>
                                <input type="text" name="konversi" class="form-control" id="konversi">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="harga_vendor">Harga Vendor</label>
                                <input type="text" name="harga_vendor" class="form-control" id="harga_vendor">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="harga_customer">Harga Customer</label>
                                <input type="text" name="harga_customer" class="form-control" id="harga_customer">
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
    $(function() {
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
                url: "{{ route('pekerjaan') }}",
                data: function (d) {
                    filterSearch        = d.search?.value;
                    d.name              = $('#name').val();
                    d.unit              = $('#unit').val();
                    d.konversi          = $('#konversi').val();
                    d.harga_vendor      = $('#harga_vendor').val();
                    d.harga_customer    = $('#harga_customer').val();
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'unit', name: 'unit'},
                {data: 'konversi', name: 'konversi'},
                {data: 'harga_vendor', name: 'harga_vendor'},
                {data: 'harga_customer', name: 'harga_customer'},
                {data: 'action', name: 'action'},
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

            var name                = $('#name').val();
            var unit                = $('#unit').val();
            var konversi                = $('#konversi').val();
            var harga_customer      = $('#harga_customer').val();
            var harga_vendor        = $('#harga_vendor').val();

            var url = '{{ route("pekerjaan.export") }}?' + $.param({
                name            : name,
                unit            : unit,
                konversi        : konversi,
                harga_customer  : harga_customer,
                harga_vendor    : harga_vendor,
                keyword         : filterSearch
            });

            $('.loading-overlay').show();

            window.location.href = url;

            setTimeout(hideOverlay, 2000);
        });

        $(document).ready(function() {
            $('.loading-overlay').hide();
        });

        table.on('click', '.deleteData', function() {
            let name = $(this).data('name');
            let id = $(this).data('id');
            let form = $(this).data('form');

            Swal.fire({
                title: "Apakah yakin?",
                text: `Data ${name} akan Dihapus`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#6492b8da",
                cancelButtonColor: "#d33",
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`#${form}${id}`).submit();
                }
            });
        })
    });
</script>
@endsection
