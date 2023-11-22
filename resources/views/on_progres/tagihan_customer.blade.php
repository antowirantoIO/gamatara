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
                            <h4 class="mb-0 ml-2"> &nbsp; Customer Bills</h4>
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
                                    <button class="nav-link {{ $loop->first ? 'active' : '' }} rounded-pill" id="{{ $item->id }}-tab" data-bs-toggle="tab" data-bs-target="#kategori-{{ $item->id }}" type="button" role="tab" aria-controls="{{ $item->id }}" aria-selected="true">{{ $item->name }}</button>
                                </li>
                            @endforeach
                        </ul>
                   </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="live-preview">
                                <div class="col-md-12">
                                    @foreach ($kategori as $keys => $items)
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane {{ $loop->first ? 'fade show active' : '' }}" id="kategori-{{ $items->id }}" role="tabpanel" aria-labelledby="{{ $items->id }}-tab">
                                                @foreach ($workers as $key => $worker)

                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <span class="fs-5"><strong>Pekerjaan {{ getNameKategori($items->id) }}</strong></span>
                                                        <div>
                                                            <button class="btn btn-secondary" id="btn-fillter-{{ $key }}">
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
                                                    <table class="table w-100" id="tableData{{ $items->id }}">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th style="color:#929EAE;">Job</th>
                                                                <th style="color:#929EAE;">Vendor</th>
                                                                <th style="color:#929EAE;">Description</th>
                                                                <th style="color:#929EAE">Location</th>
                                                                <th style="color:#929EAE">Detail / Other</th>
                                                                <th style="color:#929EAE">Length (mm)</th>
                                                                <th style="color:#929EAE">Width (mm)</th>
                                                                <th style="color:#929EAE">Thick (mm)</th>
                                                                <th style="color:#929EAE">Qty</th>
                                                                <th style="color:#929EAE">Amount</th>
                                                                <th style="color:#929EAE">Unit</th>
                                                                <th style="color:#929EAE">Unit Price</th>
                                                                <th style="color:#929EAE">Total Price</th>
                                                                <th style="color:#929EAE">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($worker as $value)
                                                            <input type="text" class="d-none id_kategori" id="id_kategori-{{ $key }}" value="{{ $value->id_kategori }}">
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <div class="d-flex jsutify-content-start align-items-center gap-3 fs-4">
                                                        <strong>Total Bill</strong> :
                                                        <strong class="tagihan-{{ $items->id }} {{ $loop->first ? 'active' : '' }}"></strong>
                                                    </div>

                                                @endforeach
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
                            <label for="sub_kategori" class="form-label">Job Name</label>
                            <select name="sub_kategori" id="sub_kategori" class="form-select">
                                <option value="">Choose Job Name</option>
                                @foreach($subKategori as $sub)
                                <option value="{{$sub->id}}">{{$sub->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xxl-6 col-md-6">
                        <div>
                            <label for="id_lokasi" class="form-label">Vendor Name</label>
                            <select name="id_lokasi" id="id_lokasi" class="form-select">
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

<div id="modalEdit" class="modal fade zoomIn" aria-labelledby="zoomInModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-top-right">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Edit Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('on_progress.pekerjaan-vendor.update') }}" method="post">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" class="id" id="id">
                    <div class="row gy-4">
                        <input type="hidden" name="conversion" id="conversion" class="conversion">
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="length" class="form-label">Length</label>
                                <input type="text" name="length" id="length" class="form-control">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="width" class="form-label">Width</label>
                                <input type="text" name="width" id="width" class="form-control">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="thick" class="form-label">Thick</label>
                                <input type="text" name="thick" id="thick" class="form-control">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="unit" class="form-label">Unit</label>
                                <input type="text" name="unit" id="unit" class="form-control">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="qty" class="form-label">Qty</label>
                                <input type="text" name="qty" id="qty" class="form-control">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="amount" class="form-label">Amount</label>
                                <input type="text" name="amount" id="amount" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="harga_vendor" class="form-label">Vendor Price</label>
                                <input type="text" name="harga_vendor" id="harga_vendor" class="form-control">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="harga_customer" class="form-label">Customer Price</label>
                                <input type="text" name="harga_customer" id="harga_customer" class="form-control harga_customer">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn-search">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            let modalInput = $('#modalFillter');
            let modalEdit = $('#modalEdit');

            $('.form-select').select2({
                theme : "bootstrap-5",
                search: true
            });

            @foreach ( $kategori as $key => $worker )
                var id_kategori = $('#id_kategori-{{ $key }}').val();
                var table{{ $key }} = $('#tableData{{ $key }}').DataTable({
                    fixedHeader:true,
                    scrollX: true,
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
                        url : '{{ route('ajax.tagihan-customer') }}',
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
                                return accumulator + (currentValue.harga_customer * currentValue.amount);
                            }, 0);
                            $('.tagihan-{{ $key }}').text(rupiah(amount))
                            console.log(data);
                        }
                    },
                    columns : [
                        {
                            data : function(data) {
                                let pekerjaan = data.pekerjaan || '';
                                let name = pekerjaan.name || '' ;
                                return name;
                            }
                        },
                        {
                            data : function(data) {
                                let vendor = data.vendors || '';
                                let name = vendor.name || '';
                                return name;
                            }
                        },
                        {
                            data : function(data) {
                                let desc = data.deskripsi_pekerjaan || '';
                                return desc;
                            }
                        },
                        {
                            data : function(data) {
                                let location = data.id_lokasi || '';
                                var status = false;
                                var recent = data.activitys.map(item =>{
                                    status = data.id_lokasi !== item.id_lokasi ? 'bg-danger text-white' : '';
                                })
                                return `<div class="${status} p-3 rounded-3">${location}</div>`;
                            }
                        },
                        {
                            data : function(data){
                                let detail = data.detail || '';
                                var status = false;
                                var recent = data.activitys.map(item =>{
                                    status = data.detail !== item.detail ? 'bg-danger text-white' : '';
                                })
                                return `<div class="${status} p-3 rounded-3">${detail}</div>`;
                            }
                        },
                        {
                            data : function(data) {
                                let length = data.length || 0;
                                var status = false;
                                var recent = data.activitys.map(item =>{
                                    status = data.length !== item.length ? 'bg-danger text-white' : '';
                                })
                                return `<div class="${status} p-3 rounded-3">${length}</div>`;
                            },
                            name : 'length'
                        },
                        {
                            data : function (data) {
                                let width = data.width || 0;
                                var status = false;
                                var recent = data.activitys.map(item =>{
                                    status = data.width !== item.width ? 'bg-danger text-white' : '';
                                })
                                return `<div class="${status} p-3 rounded-3">${width}</div>`;
                            },
                            name : 'width'
                        },
                        {
                            data : function (data) {
                                let thick = data.thick || 0;
                                var status = false;
                                var recent = data.activitys.map(item =>{
                                    status = data.thick !== item.thick ? 'bg-danger text-white' : '';
                                })
                                return `<div class="${status} p-3 rounded-3">${thick}</div>`;
                            },
                            name : 'thick'
                        },
                        {
                            data : function (data) {
                                let qty = data.qty || 0;
                                var status = false;
                                var recent = data.activitys.map(item =>{
                                    status = data.qty !== item.qty ? 'bg-danger text-white' : '';
                                })
                                return `<div class="${status} p-3 rounded-3">${qty}</div>`;
                            },
                            name : 'qty'
                        },
                        {
                            data : function (data) {
                                let amount = data.amount || 0;
                                var status = false;
                                var recent = data.activitys.map(item =>{
                                    status = data.amount !== item.amount ? 'bg-danger text-white' : '';
                                })
                                return `<div class="${status} p-3 rounded-3">${amount}</div>`;
                            },
                            name : 'amount'
                        },
                        {
                            data : function (data) {
                                let unit = data.unit || 0;
                                var status = false;
                                var recent = data.activitys.map(item =>{
                                    status = data.unit !== item.unit ? 'bg-danger text-white' : '';
                                })
                                return `<div class="${status} p-3 rounded-3">${unit}</div>`;
                            },
                            name : 'unit'
                        },
                        {
                            data : function(data){
                                let amount = data.harga_customer || 0;
                                var status = false;
                                var recent = data.activitys.map(item =>{
                                    console.log(data.harga_customer, item.harga_customer, data);
                                    status = data.harga_customer != item.harga_customer ? 'bg-danger text-white' : '';
                                })
                                return `<div class="${status} p-3 rounded-3">${rupiah(amount)}</div>`;
                            }
                        },
                        {
                            data : function(data){
                                if(data.harga_customer !== null){
                                    let harga = data.harga_customer || 0;
                                    let amount = data.amount || 0;
                                    let total = harga * amount;
                                    return `<div class="p-3">${rupiah(total)}</div>`
                                }else{
                                    return `<div class="p-3">0</div>`;
                                }
                            }
                        },
                        {
                            data : function(data){
                                let id = data.id;
                                return `<button data-id="${id}" class="btn btn-info btn-sm btn-edit">
                                    <span>
                                        <i><img src="{{asset('assets/images/edit.svg')}}" style="width: 15px;"></i>
                                    </span>
                                </button>`
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

                function formatRupiah(angka) {
                    var numberString = angka.toString().replace(/[^0-9]/g, '');
                    var rupiah = '';
                    var ribuan = 0;

                    for (var i = numberString.length - 1; i >= 0; i--) {
                        rupiah = numberString[i] + rupiah;
                        ribuan++;
                        if (ribuan == 3 && i > 0) {
                            rupiah = ',' + rupiah;
                            ribuan = 0;
                        }
                    }

                    return rupiah;
                }
            @endforeach

            $(document).delegate('.btn-edit','click',function(){
                let id = $(this).data('id');
                console.log(id);
                let url = '{{ route('on_progres.request.edit',':id') }}';
                let urlReplace = url.replace(':id',id);
                $.ajax({
                    url : urlReplace,
                    method : 'GET'
                }).then(ress => {
                    $('#id').val(id);
                    $('#length').val(ress.data.length);
                    $('#width').val(ress.data.width);
                    $('#thick').val(ress.data.thick);
                    $('#unit').val(ress.data.unit);
                    $('#qty').val(ress.data.qty);
                    $('#amount').val(ress.data.amount);
                    $('#conversion').val(ress.data.conversion);
                    $('#harga_vendor').val(rupiah(ress.data.harga_vendor));
                    $('#harga_customer').val(ress.data.harga_customer ? rupiah(ress.data.harga_customer) : 0);
                    modalEdit.modal('show');
                })
            });
            $('.harga_customer').on('input', function() {
                var inputValue = $(this).val();
                var formattedValue = formatRupiah(inputValue);
                $(this).val(formattedValue);
            });
            $('.export-button').on('click', function(event) {
                event.preventDefault();

                var id_project      = '{{ $id }}';

                var url = '{{ route("on_progres.export.tagihan_customer") }}?' + $.param({
                    id_project: id_project,
                });

                $('.loading-overlay').show();

                window.location.href = url;

                setTimeout(hideOverlay, 2000);
            });

            const rupiah = (number)=>{
                var	reverse = number.toString().split('').reverse().join(''),
                ribuan 	= reverse.match(/\d{1,3}/g);
                ribuan	= ribuan.join(',').split('').reverse().join('');
                return ribuan;
            }
        })
    </script>
@endsection
