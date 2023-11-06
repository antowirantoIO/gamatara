@extends('index')

@section('content')

<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <h4 class="mb-0 ml-2"> &nbsp; User</h4>
                        </div>
                        <div class="mt-3 mt-lg-0 ml-lg-auto">
                            @can('user-add')
                            <a href="{{ route('user.create') }}" class="btn btn-secondary">
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
                            <h4 class="card-title mb-0 flex-grow-1">User</h4>
                            <div>
                          
                            </div>
                        </div>

                        <div class="card-body">
                            <table class="table" id="tableData">
                                <thead class="table-light">
                                    <tr>
                                        <th style="color:#929EAE">Nama Karyawan</th>
                                        <th style="color:#929EAE">Nomor Telpon</th>
                                        <th style="color:#929EAE">Email</th>
                                        <th style="color:#929EAE">Role</th>
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
                                <label for="karyawan">Nama</label>
                                <select name="karyawan" id="karyawan" class="form-control">
                                    <option value="">Pilih Karyawan</option>
                                    @foreach($karyawan as $r)
                                        <option value="{{$r->id}}">{{ $r->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="nomor_telpom">Nomor Telpon</label>
                                <input type="text" name="nomor_telpom" id="nomor_telpom" class="form-control">
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
                                <label for="role">Role</label>
                                <select name="role" id="role" class="form-control">
                                    <option value="">Pilih Role</option>
                                    @foreach($role as $r)
                                        <option value="{{$r->id}}">{{ $r->name }}</option>
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
                url: "{{ route('user') }}",
                data: function (d) {
                    filterSearch    = d.search?.value;
                    d.karyawan      = $('#karyawan').val();
                    d.role          = $('#role').val();
                    d.nomor_telpon  = $('#nomor_telpon').val();
                    d.email         = $('#email').val();
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'nomor_telpon', name: 'nomor_telpon'},
                {data: 'email', name: 'email'},
                {data: 'role', name: 'role'},
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

            var karyawan        = $('#karyawan').val();
            var role        = $('#role').val();
            var nomor_telpon    = $('#nomor_telpon').val();
            var email           = $('#email').val();

            var url = '{{ route("user.export") }}?' + $.param({
                karyawan        : karyawan,
                role            : role,
                nomor_telpon    : nomor_telpon,
                email           : email,
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
