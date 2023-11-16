@extends('index')

@section('content')

<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <h4 class="mb-0 ml-2"> &nbsp; Sub Category</h4>
                        </div>
                        <div class="mt-3 mt-lg-0 ml-lg-auto">
                            @can('sub_kategori-add')
                                <a href="{{ route('sub_kategori.create') }}" class="btn btn-secondary">
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
                            <h4 class="card-title mb-0 flex-grow-1">Sub Category</h4>
                            <div>
                          
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="row">
                                    <table class="table" id="tableDataLight">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="color:#929EAE">Category</th>
                                                <th style="color:#929EAE">Sub Category</th>
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
                                <label for="nama" class="form-label">Category</label>
                                <select name="kategori" id="kategori" class="form-control">
                                    <option value="">Choose Category</option>
                                    @foreach($kategori as $k)
                                    <option value="{{ $k->id }}">{{ $k->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="nama" class="form-label">Sub Category Name</label>
                                <input type="text" name="name" id="name" class="form-control">
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
        var table = $('#tableDataLight').DataTable({
            ordering: false,
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
                url: "{{ route('sub_kategori') }}",
                data: function (d) {
                    d.name      = $('#name').val();
                    d.kategori  = $('#kategori').val();
                }
            },
            columns: [
                {data: 'kategori', name: 'kategori'},
                {data: 'name', name: 'name'},
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

            var name        = $('#name').val();
            var kategori    = $('#kategori').val();

            var url = '{{ route("sub_kategori.export") }}?' + $.param({
                name: name,
                kategori: kategori,
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