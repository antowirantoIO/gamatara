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
                            <h4 class="mb-0 ml-2"> &nbsp; Progress Pekerjaan</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between">
                        <ul class="nav nav-tabs gap-3" id="myTab" role="tablist">
                            @foreach ($kategori as $key => $item)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $loop->first ? 'active' : '' }} rounded-pill" id="{{ $item->id }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $item->id }}" type="button" role="tab" aria-controls="{{ $item->id }}" aria-selected="true">{{ $item->name }}</button>
                                </li>
                            @endforeach
                        </ul>
                   </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="live-preview">
                                @foreach ($subWorker as $key => $worker)
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $key }}" role="tabpanel" aria-labelledby="{{ $key }}-tab">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="fs-5"><strong>Pekerjaan {{ getNameKategori($key) }}</strong></span>
                                                <div>
                                                    <button class="btn btn-secondary" id="btn-fillter-{{ $key }}">
                                                        <span>
                                                            <i><img src="{{asset('assets/images/filter.svg')}}" style="width: 15px;"></i>
                                                        </span> &nbsp; Filter
                                                    </button>
                                                    <button class="btn btn-danger">
                                                        <span>
                                                            <i><img src="{{asset('assets/images/directbox-send.svg')}}" style="width: 15px;"></i>
                                                        </span> &nbsp; Export
                                                    </button>
                                                </div>
                                            </div>
                                            <table class="table w-100" id="tableData{{ $key }}">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="color:#929EAE;width:600px;">Pekerjaan</th>
                                                        <th style="color:#929EAE">Progres</th>
                                                        <th style="color:#929EAE">Vendor</th>
                                                        <th style="color:#929EAE">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($worker as $value)
                                                        <input type="text" class="d-none id_kategori {{ $loop->first ? 'active' : '' }}" id="id_kategori-{{ $key }}" value="{{ $value->id_kategori }}">
                                                        <input type="text" class="d-none id_project {{ $loop->first ? 'active' : '' }}" id="id_project-{{ $key }}" value="{{ $value->id_project }}">
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
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
                            <label for="sub_kategori" class="form-label">Nama Pekerjaan</label>
                            <select name="sub_kategori" id="sub_kategori" class="form-select">
                                <option value="">Pilih Nama Pekerjaan</option>
                                @foreach($subKategori as $sub)
                                <option value="{{$sub->id}}">{{$sub->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xxl-6 col-md-6">
                        <div>
                            <label for="nama_vendor" class="form-label">Nama Vendor</label>
                            <select name="nama_vendor" id="nama_vendor" class="form-select">
                                <option value="">Pilih Vendor</option>
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

            $('.form-select').select2({
                theme : "bootstrap-5",
                search: true
            });

            @foreach ($subWorker as $key => $worker)

                var id_kategori = $('#id_kategori-{{ $key }}').val();
                var id_project = $('#id_project-{{ $key }}').val();

                var table{{ $key }} = $('#tableData{{ $key }}').DataTable({
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
                        url : '{{ route('ajax.progres-pekerjaan') }}',
                        method : 'GET',
                        data : function(d){
                            d._token = '{{ csrf_token() }}';
                            d.id_kategori = id_kategori;
                            d.id_project = id_project;
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
                                let id_kategori = data.id_kategori;
                                let id_project = data.id_project;
                                let id_subkategori = data.id_subkategori;
                                let url = '{{ route('on_progres.sub-detail',[':id',':project',':subkategori']) }}';
                                let urlReplace = url.replace(':id',id_kategori).replace(':project',id_project).replace(':subkategori',id_subkategori);
                                return `<a href="${urlReplace}" class="btn btn-warning btn-sm">
                                    <span>
                                        <i><img src="{{asset('assets/images/eye.svg')}}" style="width: 15px;"></i>
                                    </span>
                                </a>`
                            }
                        }
                    ]
                });

                $('#btn-fillter-{{ $key }}').click(function(){
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
            @endforeach

        })
    </script>
@endsection
