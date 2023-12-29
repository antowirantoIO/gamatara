@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('complete.tagihan.all',$id)}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Vendor Bills</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between">
                        <ul class="nav nav-tabs gap-3" id="myTab" role="tablist">
                            @foreach ($workers as $key => $worker)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link btn-filter-category {{ $loop->first ? 'active' : '' }} rounded-pill" data-category_id="{{ $worker->id }}"  data-name="{{ $worker->name }}" data-bs-target="#{{ $key }}" type="button">{{ $worker->name }}</button>
                                </li>
                            @endforeach
                        </ul>
                   </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="live-preview">
                                <div class="col-md-12">
                                    <div>
                                        <div class="tab-pane" role="tabpanel">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="fs-5"><strong>Pekerjaan</strong></span>
                                                <div>
                                                    <button class="btn btn-secondary" id="btn-fillter">
                                                        <span>
                                                            <i><img src="{{asset('assets/images/filter.svg')}}" style="width: 15px;"></i>
                                                        </span> &nbsp; Filter
                                                    </button>
                                                </div>
                                            </div>
                                            <table class="table w-100" id="tableData">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="color:#929EAE;">Kategori</th>
                                                        <th style="color:#929EAE;">Job</th>
                                                        <th style="color:#929EAE">Location</th>
                                                        <th style="color:#929EAE">Detail / Other</th>
                                                        <th style="color:#929EAE">Length (mm)</th>
                                                        <th style="color:#929EAE">Width (mm)</th>
                                                        <th style="color:#929EAE">Thick (mm)</th>
                                                        <th style="color:#929EAE">Qty</th>
                                                        <th style="color:#929EAE">Amount</th>
                                                        <th style="color:#929EAE">Unit</th>
                                                        <th style="color:#929EAE">Unit Price</th>
                                                        <th style="color:#929EAE">Total Bills</th>
                                                        @can('complete-edit-pekerjaan-vendor')
                                                            <th style="color:#929EAE">Action</th>
                                                        @endcan
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                            <div class="d-flex jsutify-content-start align-items-center gap-3 fs-4">
                                                <strong>Total Bills</strong> :
                                                <strong class="tagihan"></strong>
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
                            <label for="id_lokasi" class="form-label">Location Name</label>
                            <input name="id_lokasi" id="id_lokasi" class="form-control"/>
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

<div id="modalHistory" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-top-right modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Recent Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" class="project_pekerjaan_id">
                <table class="table w-100" id="tableHistory">
                    <thead style="background-color:#194BFB; color: white;">
                        <tr>
                            <th style="width: 200px">Job</th>
                            <th style="width: 200px">Date</th>
                            <th style="width: 200px">Status</th>
                            <th style="width: 90px">Length (mm)</th>
                            <th style="width: 90px">Width (mm)</th>
                            <th style="width: 90px">Thick (mm)</th>
                            <th style="width: 90px">Unit</th>
                            <th style="width: 90px">Qty</th>
                            <th style="width: 90px">Amount</th>
                            <th style="width: 90px">Vendor Price</th>
                            <th style="width: 90px">Customer Price</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
                                <input type="text" name="length" id="length" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="width" class="form-label">Width</label>
                                <input type="text" name="width" id="width" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="thick" class="form-label">Thick</label>
                                <input type="text" name="thick" id="thick" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="unit" class="form-label">Unit</label>
                                <input type="text" name="unit" id="unit" class="form-control" >
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
                                <input type="text" name="amount" id="amount" class="form-control">
                            </div>
                        </div>
                        @can('complete-edit-harga-vendor')
                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <label for="harga_vendor" class="form-label">Vendor Price</label>
                                    <input type="text" name="harga_vendor" id="harga_vendor" class="form-control">
                                </div>
                            </div>
                        @endcan
                        @can('complete-edit-harga-customer')
                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <label for="harga_customer" class="form-label">Customer Price</label>
                                    <input type="text" name="harga_customer" id="harga_customer" class="form-control">
                                </div>
                            </div>
                        @endcan
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
            let filterData = {};
            let modalInput = $('#modalFillter');
            let btnFilterCategory = $('.btn-filter-category');
            let modalEdit = $('#modalEdit');
            let modalHistory = $('#modalHistory');

            var table = $('#tableData').DataTable({
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
                    url : '{{ route('ajax.tagihan-vendor') }}',
                    method : 'GET',
                    data : function(d){
                        d._token = '{{ csrf_token() }}';
                        d.id_project = '{{ $id }}';
                        d.id_kategori = filterData.category_id;
                        d.sub_kategori = $('#sub_kategori').val();
                        d.id_lokasi = $('#id_lokasi').val();
                        d.id_vendor = '{{ $vendor }}'
                    },
                    complete : function(d){
                        let data = d.responseJSON.data;
                        let amount = data.reduce((accumulator, currentValue) => {
                            return accumulator + (currentValue.harga_vendor * currentValue.amount);
                        }, 0);
                        console.log(amount,data);
                        $('.tagihan').text(rupiah(amount))
                    }
                },
                columns : [
                    { data : 'subKategori', name : 'subKategori'},
                    { data : 'pekerjaan', name : 'pekerjaan'},
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
                            let amount = data.harga_vendor || 0;
                            var status = false;
                            var recent = data.activitys.map(item =>{
                                status = data.harga_vendor != item.harga_vendor ? 'bg-danger text-white' : '';
                            })
                            return `<div class="${status} p-3 rounded-3">${rupiah(amount)}</div>`;
                        }
                    },
                    {
                        data : function(data){
                            if(data.harga_vendor !== null){
                                let harga = data.harga_vendor || 0;
                                let amount = data.amount || 0;
                                let total = harga * amount;
                                return `<div class="p-3">${rupiah(total)}</div>`
                            }else{
                                return `<div class="p-3">0</div>`;
                            }
                        }
                    },
                    @can('complete-edit-pekerjaan-vendor')
                        {
                            data : function(data){
                                let id = data.id;
                                return `<div class="d-flex justify-content-around gap-3">
                                    <button data-id="${id}" class="btn btn-info btn-sm btn-edit">
                                        <span>
                                            <i><img src="{{asset('assets/images/edit.svg')}}" style="width: 15px;"></i>
                                        </span>
                                    </button>
                                    <button class="btn btn-warning btn-history btn-sm" data-id=${id}>
                                        <span>
                                            <i><img src="{{asset('assets/images/history.svg')}}" style="width: 20px; color:white;"></i>
                                        </span>
                                    </button>
                                </div>`
                            }
                        }
                    @endcan
                ]
            })

            $(document).delegate('.btn-edit','click',function(){
                let id = $(this).data('id');
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
                    $('#harga_customer').val(ress.data.pekerjaan ? rupiah(ress.data.pekerjaan.harga_customer) : 0);
                    modalEdit.modal('show');
                })
            });


            handleFiltertab($('.btn-filter-category.active'));

            btnFilterCategory.on('click', function(){
                handleFiltertab($(this))
            })

            $(document).delegate('.btn-history','click',function(){
                let data = $('.parent-clone');
                let id = $(this).data('id');
                $('.project_pekerjaan_id').val(id);
                modalHistory.modal('show');
                getRecentDetail(id);
            })

            function handleFiltertab(element){
                const categoryId = element.data('category_id'),
                      name = element.data('name');
                btnFilterCategory.removeClass('active')
                element.addClass('active');
                filterData = {
                    category_id : categoryId,
                    name_project: name
                }
                table.draw();
            }

            $('.form-select').select2({
                theme : "bootstrap-5",
                search: true,
                dropdownParent: $("#modalFillter")
            });
            $('#btn-fillter').click(function(){
                modalInput.modal('show');
            })
            $('#btn-search').on('click',function(e){
                e.preventDefault();
                table.draw();
            });

            modalHistory.on('hidden.bs.modal',function(){
                $('#tableHistory').DataTable().destroy();
            })

            $('#btn-reset').click(function(e){
                e.preventDefault();
                $('.form-control').val('');
                $('.form-select').val(null).trigger('change');
                table.draw();
            })
            const rupiah = (number)=>{
                var	reverse = number.toString().split('').reverse().join(''),
                ribuan 	= reverse.match(/\d{1,3}/g);
                ribuan	= ribuan.join('.').split('').reverse().join('');
                return ribuan;
            }

            function getRecentDetail (id) {
                var tableRecent = $('#tableHistory').DataTable({
                    fixedHeader:true,
                    scrollX: false,
                    ordering : false,
                    processing: true,
                    serverSide: true,
                    searching: false,
                    bLengthChange: false,
                    autoWidth : true,
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
                        url : '{{ route('ajax.recent-activity-detail') }}',
                        method : 'POST',
                        data : function (d) {
                            d._token    = '{{ csrf_token() }}';
                            d.id        =  id
                        }
                    },
                    columns : [
                        { data : 'pekerjaan.name', name : 'id_pekerjaan'},
                        {
                            data : function(data) {
                                let status = data.status || '-';
                                if(status === 1) {
                                    let date = moment(data.created_at);
                                    let formated = date.format('DD MMMM YYYY');
                                    return formated
                                }else if ( status === 2 ){
                                    let date = moment(data.updated_at);
                                    let formated = date.format('DD MMMM YYYY');
                                    return formated
                                }else{
                                    let date = moment(data.deleted_at);
                                    let formated = date.format('DD MMMM YYYY');
                                    return formated
                                }
                            }
                        },
                        {
                            data : function(data) {
                                let status = data.status;
                                if(status === 1) {
                                    return `<div class="text-success">${data.description} </div>`
                                }else if(status === 2){
                                    return `<div class="text-info">${data.description} </div>`
                                }else{
                                    return `<div class="text-danger">${data.description} </div>`
                                }
                            }
                        },
                        { data : 'length', name : 'length' },
                        { data : 'width', name : 'width' },
                        { data : 'thick', name : 'thick' },
                        { data : 'unit', name : 'unit' },
                        { data : 'qty', name : 'qty' },
                        {
                            data : function(data) {
                                let amount = data.amount || 0;
                                return parseFloat(amount);
                            },
                            name : 'amount'
                        },
                        {
                            data : function(data) {
                                let harga_vendor = data.harga_vendor || 0;
                                return rupiah(harga_vendor);
                            },
                            name : 'harga_vendor'
                        },
                        {
                            data : function(data) {
                                let harga_customer = data.harga_customer || 0 ;
                                return rupiah(harga_customer);
                            },
                            name : 'harga_customer'
                        },
                    ]
                });

                return tableRecent;
            }
        })
    </script>
@endsection
