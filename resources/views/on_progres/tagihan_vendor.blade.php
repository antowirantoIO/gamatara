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
                            <h4 class="mb-0 ml-2"> &nbsp; Tagihan Vendor</h4>
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
                                <div class="col-md-12">
                                    @foreach ($workers as $key => $worker)
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
                                                            <th style="color:#929EAE;">Pekerjaan</th>
                                                            <th style="color:#929EAE">Lokasi</th>
                                                            <th style="color:#929EAE">Detail / Other</th>
                                                            <th style="color:#929EAE">Length (mm)</th>
                                                            <th style="color:#929EAE">Width (mm)</th>
                                                            <th style="color:#929EAE">Thick (mm)</th>
                                                            <th style="color:#929EAE">Qty</th>
                                                            <th style="color:#929EAE">Amount</th>
                                                            <th style="color:#929EAE">Unit</th>
                                                            <th style="color:#929EAE">Total Harga</th>
                                                            <th style="color:#929EAE">Total Tagihan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($worker as $value)
                                                        <input type="text" class="d-none id_kategori {{ $loop->first ? 'active' : '' }}" id="id_kategori-{{ $key }}" value="{{ $value->id_kategori }}">
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="d-flex jsutify-content-start align-items-center gap-3 fs-4">
                                                    <strong>Total Tagihan</strong> :
                                                    <strong class="tagihan-{{ $key }} {{ $loop->first ? 'active' : '' }}"></strong>
                                                </div>
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
</div>

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
                            <label for="id_lokasi" class="form-label">Nama Vendor</label>
                            <select name="id_lokasi" id="id_lokasi" class="form-select">
                                <option value="">Pilih Lokasi</option>
                                @foreach($lokasi as $l)
                                <option value="{{$l->id}}">{{$l->name}}</option>
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

            @foreach ( $workers as $key => $worker )
                var id_kategori = $('#id_kategori-{{ $key }}').val();
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
                        url : '{{ route('ajax.tagihan') }}',
                        method : 'GET',
                        data : function(d){
                            d._token = '{{ csrf_token() }}';
                            d.id_project = '{{ $id }}';
                            d.id_kategori = id_kategori;
                            d.sub_kategori = $('#sub_kategori').val();
                            d.id_lokasi = $('#id_lokasi').val();
                        },
                        complete : function(d){
                            let data = d.responseJSON.data;
                            let amount = data.reduce((accumulator, currentValue) => {
                                return accumulator + currentValue.pekerjaan.harga_vendor;
                            }, 0);
                            $('.tagihan-{{ $key }}').text(rupiah(amount))
                        }
                    },
                    columns : [
                        { data : 'subKategori', name : 'pekerjaan'},
                        { data : 'id_lokasi', name : 'id_lokasi'},
                        {data : 'detail', name : 'detail'},
                        {data : 'length', name : 'length'},
                        {data : 'width', name : 'width'},
                        {data : 'thick', name : 'thick'},
                        {data : 'qty', name : 'qty'},
                        {data : 'amount', name : 'amount'},
                        {data : 'unit', name : 'unit'},
                        {
                            data : function(data){
                                let amount = data.pekerjaan.harga_vendor || '-';
                                return rupiah(amount);
                            }
                        },
                        {
                            data : function(data){
                                let amount = data.pekerjaan.harga_vendor || '-';
                                return rupiah(amount);
                            }
                        }
                    ]
                })
                $('#btn-fillter-{{ $key }}').click(function(){
                    modalInput.modal('show');
                })
                $('#btn-search').on('click',function(e){
                    e.preventDefault();
                    modalInput.modal('hide');
                    var active = $('#myTabContent .tab-pane.active');
                    id_kategori = active.find('.id_kategori.active').val();
                    var activeTabId = $('#myTab .nav-link.active').attr('id');
                    console.log(id_kategori,activeTabId);
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
            const rupiah = (number)=>{
                var	reverse = number.toString().split('').reverse().join(''),
                ribuan 	= reverse.match(/\d{1,3}/g);
                ribuan	= ribuan.join('.').split('').reverse().join('');
                return ribuan;
            }
        })
    </script>
@endsection
