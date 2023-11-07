@extends('index')

@section('content')

<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <h4 class="mb-0 ml-2"> &nbsp; Vendor</h4>
                        </div>
                        <div class="mt-3 mt-lg-0 ml-lg-auto">   
                            @can('vendor-add')
                                <a href="{{ route('vendor.create') }}" class="btn btn-secondary">
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
                            <h4 class="card-title mb-0 flex-grow-1">Vendor</h4>
                            <div>
                          
                            </div>
                        </div>

                        <div class="card-body">
                            <table class="table" id="tableData">
                                <thead class="table-light">
                                    <tr>
                                        <th style="color:#929EAE">vendor Name</th>
                                        <th style="color:#929EAE">Address</th>
                                        <th style="color:#929EAE">Contact Person</th>
                                        <th style="color:#929EAE">Contact Person Phone</th>
                                        <th style="color:#929EAE">Email</th>
                                        <th style="color:#929EAE">NPWP</th>
                                        <th style="color:#929EAE">Category Vendor</th>
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
                                <label for="customer">Name</label>
                                <input type="text" name="name" class="form-control" id="name">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="contact_person">Contact Person</label>
                                <input type="text" name="contact_person" id="contact_person" class="form-control">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="alamat">Address</label>
                                <input type="text" name="alamat" id="alamat" class="form-control">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="nomor_contact_person">Contact Person Phone</label>
                                <input type="number" name="nomor_contact_person" id="nomor_contact_person" class="form-control" >
                            </div>
                        </div>                    
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <div>
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control form-control-icon">
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="npwp">NPWP</label>
                                <input type="text" name="npwp" id="npwp" class="form-control">
                            </div>
                        </div> 
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="kategori_vendor">Category Vendor</label>
                                <select name="kategori_vendor" id="kategori_vendor" class="form-control">
                                    <option value="">Choose Category Vendor</option>
                                    @foreach($kategori_vendor as $k)
                                        <option value="{{ $k->id }}">{{ $k->name }}</option>
                                    @endforeach
                                </select>
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
                url: "{{ route('vendor') }}",
                data: function (d) {
                    filterSearch            = d.search?.value;
                    d.name                  = $('#name').val();
                    d.alamat                = $('#alamat').val();
                    d.contact_person        = $('#contact_person').val();
                    d.nomor_contact_person  = $('#nomor_contact_person').val();
                    d.email                 = $('#email').val();
                    d.npwp                  = $('#npwp').val();
                    d.kategori_vendor       = $('#kategori_vendor').val();
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'alamat', name: 'alamat'},
                {data: 'contact_person', name: 'contact_person'},
                {data: 'nomor_contact_person', name: 'nomor_contact_person'},
                {data: 'email', name: 'email'},
                {data: 'npwp', name: 'npwp'},
                {data: 'kategori_vendor', name: 'kategori_vendor'},
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

            var name                    = $('#name').val();
            var alamat                  = $('#alamat').val();
            var contact_person          = $('#contact_person').val();
            var nomor_contact_person    = $('#nomor_contact_person').val();
            var email                   = $('#email').val();
            var npwp                    = $('#npwp').val();
            var kategori_vendor         = $('#kategori_vendor').val();

            var url = '{{ route("vendor.export") }}?' + $.param({
                name                : name,
                alamat              : alamat,
                contact_person      : contact_person,
                nomor_contact_person: nomor_contact_person,
                email               : email,
                npwp                : npwp,
                kategori_vendor     : kategori_vendor,
                keyword             : filterSearch
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
                title: "Are you sure?",
                text: `Data ${name} will be deleted`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#6492b8da",
                cancelButtonColor: "#d33",
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`#${form}${id}`).submit();
                }
            });
        })
    });
</script>
@endsection
