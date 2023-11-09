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
                            <h4 class="mb-0 ml-2"> &nbsp; Vendor Job Category</h4>
                        </div>
                        <div class="mt-3 mt-lg-0 ml-lg-auto">
                            <div class="btn btn-secondary" id="btn-plus">
                                <span><i class="mdi mdi-plus"></i></span> &nbsp; Add Category
                            </div>
                            <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#advance">
                                <span>
                                    <i><img src="{{asset('assets/images/filter.svg')}}" style="width: 15px;"></i>
                                </span> &nbsp; Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Category</h4>
                            <div>

                            </div>
                        </div>

                        <div class="card-body">
                            <table class="table w-100" id="tableData">
                                <thead class="table-light">
                                    <tr>
                                        <th style="color:#929EAE">Category</th>
                                        <th style="color:#929EAE">Subcategory</th>
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

<div id="modalFillter" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-top-right">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('on_progres.store-kategori') }}" method="post">
                @csrf
                <input type="hidden" value="{{ $id }}" name="id_project">
                <input type="hidden" value="{{ $vendor }}" name="id_vendor">
                <div class="modal-body">
                    <div class="row gy-4">
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="kategori" class="form-label">Kategori</label>
                                <select name="kategori" id="kategori" class="form-select">
                                    <option value="">Pilih Nama Kategori</option>
                                    @foreach($categories as $categorie)
                                        <option value="{{$categorie->id}}">{{$categorie->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="subkategori" class="form-label">Sub Kategori</label>
                                <select name="subkategori" id="subkategori" class="form-select">
                                    <option value="">Pilih Nama Subkategori</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="btn-search">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end modal-->
@endsection

@section('scripts')
<script>
     $(document).ready(function () {
        let modalInput = $('#modalFillter');

        $('.form-select').select2({
            theme : "bootstrap-5",
            search: true
        });

        $('#btn-plus').on('click',function(){
            modalInput.modal('show');
        })

        $('#kategori').on('change',function(){
            let id = $(this).val();
            console.log(id);
            let url = '{{ route('on_progres.sub-kategori',':id') }}'
            let urlReplace = url.replace(':id',id);
            $.ajax({
                url : urlReplace,
                method : 'GET'
            }).then(ress => {
                let select = $('#subkategori');
                if(ress.data.length != null){
                    select.empty();
                    select.append(`
                        <option selected disabled>Pilih Nama Subkategori</option>
                    `)
                    ress.data.forEach(item => {
                        select.append(`
                            <option value="${item.id}">${item.name}</option>
                        `)
                    })
                }else{
                    select.append(`
                        <option selected disabled>Sub Kategori</option>
                    `)
                }
            })
        })

        let id = "{{ $id }}";
        let vendor = "{{ $vendor }}";

        let url = "{{ route('on_progres.request.tambah-kategori',[':id',':vendor']) }}";
        let urlReplace = url.replace(':id',id).replace(':vendor',vendor);

        var table = $('#tableData').DataTable({
            fixedHeader:true,
            scrollX: false,
            processing: true,
            serverSide: true,
            searching: false,
            bLengthChange: false,
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
                url: urlReplace,
            },
            columns: [
                {data: 'kategori.name', code: 'kategori'},
                {data: 'sub_kategori.name', name: 'subkategori'},
                {data: 'action', name: 'action'}
            ]
        });

        // $('.form-control').on('change', function() {
        //     table.draw();
        // });

        // $('#clear-filter').on('click', function() {
        //     event.preventDefault();
        //     $('#search-form')[0].reset();
        //     table.search('').draw();
        // });

        // function hideOverlay() {
        //     $('.loading-overlay').fadeOut('slow', function() {
        //         $(this).remove();
        //     });
        // }
        // $(document).ready(function() {
        //     $('.loading-overlay').hide();
        // });
    });
</script>
@endsection

{{-- {{ route('on_progres.request-pekerjaan',[$data->id,$project->id_vendor]) }} --}}
