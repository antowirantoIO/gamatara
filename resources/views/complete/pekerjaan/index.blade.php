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
                            <h4 class="mb-0 ml-2"> &nbsp; Job Progress</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between">
                        <ul class="nav nav-tabs gap-3" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link btn-filter-category rounded-pill active"  data-category_id="" type="button">All</button>
                            </li>
                            @foreach ($workers as $key => $worker)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link btn-filter-category rounded-pill" data-category_id="{{ $worker->id }}"  data-name="{{ $worker->name }}" data-bs-target="#{{ $key }}" type="button">{{ $worker->name }}</button>
                                </li>
                            @endforeach
                        </ul>
                   </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="live-preview">
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="fs-5"><strong>Pekerjaan</strong></span>
                                            <div>
                                                <button class="btn btn-secondary" id="btn-fillter">
                                                    <span>
                                                        <i><img src="{{asset('assets/images/filter.svg')}}" style="width: 15px;"></i>
                                                    </span> &nbsp; Filter
                                                </button>
                                                <button class="btn btn-danger export-button" id="export-button">
                                                    <span>
                                                        <i><img src="{{asset('assets/images/directbox-send.svg')}}" style="width: 15px;"></i>
                                                    </span> &nbsp; Export
                                                </button>
                                            </div>
                                        </div>
                                        <table class="table w-100" id="tableData">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="color:#929EAE;width:600px;">Pekerjaan</th>
                                                    <th style="color:#929EAE">Progres</th>
                                                    <th style="color:#929EAE">Vendor</th>
                                                    <th style="color:#929EAE">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
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
</div>

{{-- modal --}}

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
                            <label for="sub_kategori" class="form-label">Job Name</label>
                            <select name="sub_kategori" id="sub_kategori" class="form-select">
                                <option value="">Choose Jobs</option>
                                @foreach($subKategori as $sub)
                                <option value="{{$sub->id}}">{{$sub->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xxl-6 col-md-6">
                        <div>
                            <label for="nama_vendor" class="form-label">Vendor Name</label>
                            <select name="nama_vendor" id="nama_vendor" class="form-select">
                                <option value="">Choose Vendor</option>
                                @foreach($vendor as $v)
                                <option value="{{$v->id}}">{{$v->name}}</option>
                                @endforeach
                            </select>
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
        $(document).ready(function(){
            let modalInput = $('#modalFillter');
            let filterData = {};

            let btnFilterCategory = $('.btn-filter-category');

            $('.form-select').select2({
                theme : "bootstrap-5",
                search: true
            });

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
                ajax : {
                    url : '{{ route('complete.ajax.progres-pekerjaan-vendor') }}',
                    method : 'GET',
                    data : function(d){
                        d._token = '{{ csrf_token() }}';
                        d.id_kategori = filterData.category_id;
                        d.id_project = '{{ $id }}';
                        d.sub_kategori = $('#sub_kategori').val();
                        d.nama_vendor = $('#nama_vendor').val();
                    }
                },
                columns : [
                    { data : 'pekerjaan'},
                    { data : 'progres'},
                    { data : 'vendors.name'},
                    {
                        data : function(data){
                            console.log(data);
                            let id_kategori = data.id_kategori;
                            let id_project = data.id_project;
                            let id_subkategori = data.id_subkategori;
                            let kode_unik = data.kode_unik;
                            let url = '{{ route('complete.sub-detail-pekerjaan',[':id',':project',':subkategori',':kode_unik']) }}';
                            let urlReplace = url.replace(':id',id_kategori).replace(':project',id_project).replace(':subkategori',id_subkategori).replace(':kode_unik',kode_unik);
                            return `<a href="${urlReplace}" class="btn btn-warning btn-sm">
                                <span>
                                    <i><img src="{{asset('assets/images/eye.svg')}}" style="width: 15px;"></i>
                                </span>
                            </a>`
                        }
                    }
                ]
            });

            handleFiltertab($('.btn-filter-category.active'));

            btnFilterCategory.on('click', function(){
                handleFiltertab($(this))
            })

            function handleFiltertab(element){
                const categoryId = element.data('category_id'),
                      name = element.data('name'),
                      idProject = element.data('project_id');
                btnFilterCategory.removeClass('active')
                element.addClass('active');
                filterData = {
                    category_id : categoryId,
                    name_project: name,
                    id_project : idProject
                }
                table.draw();
            }

            $('#btn-fillter').click(function(){
                modalInput.modal('show');
            })
            $('#btn-search').on('click',function(e){
                e.preventDefault();
                modalInput.modal('hide');
                var active = $('#myTabContent .tab-pane.active');
                id_kategori = active.find('.id_kategori.active').val();
                id_project = active.find('.id_project.active').val();

                var activeTabId = $('#myTab .nav-link.active').attr('id');
                if (activeTabId === '{{ $key }}-tab') {
                    table{{ $key }}.draw();
                }
            });


            $('#btn-reset').click(function(e){
                e.preventDefault();
                $('.form-control').val('');
                $('.form-select').val(null).trigger('change');
                var activeTabId = $('#myTab .nav-link.active').attr('id');
                if (activeTabId === '{{ $key }}-tab') {
                    table{{ $key }}.draw();
                }
            })
            function hideOverlay() {
                $('.loading-overlay').fadeOut('slow', function() {
                    $(this).remove();
                });
            }

            $('.export-button').on('click', function(event) {
                event.preventDefault();

                // var sub_kategori    = $('#sub_kategori').val();
                var id_project      = '{{ $id }}';
                // var id_vendor       = $('#nama_vendor').val();

                var url = '{{ route("on_progress.export-pekerjaan") }}?' + $.param({
                    // sub_kategori: sub_kategori,
                    id_project: id_project,
                    // id_vendor: id_vendor,
                });

                $('.loading-overlay').show();

                window.location.href = url;

                setTimeout(hideOverlay, 2000);
            });
        })
    </script>
@endsection
