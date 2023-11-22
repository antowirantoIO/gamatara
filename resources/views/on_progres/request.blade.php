@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('on_progres.request.tambah-kategori',[$id,$vendor])}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Input Job</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{ route('on_progres.work',$id) }}" method="post">
                                    @csrf
                                    <input type="hidden" id="id_project" name="id_project" value="{{ $id }}">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="kategori" class="form-label">Job Category</label>
                                            <select name="kategori" id="kategori" class="form-select">
                                                <option selected disabled>Choose Work Category</option>
                                                @foreach ($works as $work)
                                                    <option {{ $kategori_id ? ($kategori_id === $work->id ? 'selected' : '') : '' }} value="{{ $work->id }}">{{ $work->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="vendor" class="form-label">Vendor</label>
                                            <input class="form-control" value="{{ $vendor->name }}" disabled></input>
                                            <input type="hidden" name="vendor" value="{{ $vendor->id }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="sub_kategori" class="form-label">Job Subcategory</label>
                                            <select name="sub_kategori" id="sub_kategori" class="form-select">
                                                <option selected disabled>Sub Kategori</option>
                                                @foreach ($subkategori as $s)
                                                    <option {{ $subkategori_id ? ($subkategori_id === $s->id ? 'selected' : '') : '' }} value="{{ $s->id }}">{{ $s->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="nama_pekerjaan" class="form-label">Job Name</label>
                                            <input type="text" class="form-control" placeholder="Masukan Nama Pekerjaan" id="nama_pekerjaan" name="nama_pekerjaan" value="{{ $desc }}">
                                        </div>
                                        <div class="d-flex justify-content-end mb-3">
                                            <div class="btn btn-primary btn-add">Add</div>
                                        </div>
                                        <div class="col-md-12">
                                           <div class="table-container">
                                            <table class="table" id="tablePekerjaan">
                                                <thead style="background-color:#194BFB; color: white;">
                                                    <tr>
                                                        <th style="width: 200px">Job</th>
                                                        <th style="width: 200px">Description</th>
                                                        <th style="width: 200px">Location</th>
                                                        <th style="width: 200px">Detail / Other</th>
                                                        <th style="width: 90px">Length (mm)</th>
                                                        <th style="width: 90px">Width (mm)</th>
                                                        <th style="width: 90px">Thick (mm)</th>
                                                        <th style="width: 90px">Qty</th>
                                                        <th style="width: 90px">Amount</th>
                                                        <th style="width: 90px">Unit</th>
                                                        <th style="width: 90px">Unit Prices</th>
                                                        <th style="width: 90px">Total Prices</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="clone">
                                                    <input type="text" class="d-none" name="kode_unik" value="{{ $kode_unik ?? null }}">

                                                    @foreach ($pekerjaan as $keys => $p)
                                                    <input type="text" class="d-none" name="id[]" value="{{ $p->id }}" class="id-{{ $p->id }}">

{{--
                                                    @if ($p->activity())
                                                        <tr class="draggable-row parent-clone">
                                                            <input type="hidden" id="convertion-{{ $keys }}" value="{{ $p->conversion }}" name="convertion[]">
                                                            <td>
                                                                <select name="pekerjaan[]" id="pekerjaan-{{ $keys }}" class="form-select pekerjaan-{{ $keys }}">
                                                                    <option selected disabled>Pilih Pekerjaan</option>
                                                                    @foreach ($pekerjaans as $sp)
                                                                        <option {{ $p->id_pekerjaan ? ($p->id_pekerjaan === $sp->id ? 'selected' : '') : '' }} value="{{ $sp->id }}">{{ $sp->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control {{ $p->deskripsi_pekerjaan !== $p->activity()->deskripsi_pekerjaan ? 'bg-danger text-white opacity-50' : '' }}" name="deskripsi[]" style="width: 150px;" value="{{ $p->deskripsi_pekerjaan }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="lokasi[]" style="width: 100px;" value="{{ $p->id_lokasi }}">
                                                            </td>

                                                            <td>
                                                                <input type="text" class="form-control" name="detail[]" style="width: 100px;" value="{{ $p->detail }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control {{ $p->length !== $p->activity()->length ? 'bg-danger text-white opacity-50' : '' }}" name="length[]" style="width: 70px" value="{{ $p->length }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control {{ $p->width !== $p->activity()->width ? 'bg-danger text-white opacity-50' : '' }}" name="width[]"style="width: 70px" value="{{ $p->width }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control {{ $p->thick !== $p->activity()->thick ? 'bg-danger text-white opacity-50' : '' }}" name="thick[]" style="width: 70px" value="{{ $p->thick }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control {{ $p->qty !== $p->activity()->qty ? 'bg-danger text-white opacity-50' : '' }}" name="qty[]" style="width: 50px" value="{{ $p->qty }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control {{ $p->amount !== $p->activity()->amount ? 'bg-danger text-white opacity-50' : '' }}" name="amount[]" style="width: 70px" value="{{ $p->amount }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control {{ $p->unit !== $p->activity()->unit ? 'bg-danger text-white opacity-50' : '' }}" name="unit[]" style="width: 60px" id="unit" value="{{ $p->unit }}">
                                                            </td>
                                                            @hasrole('Project Admin')
                                                                <td>
                                                                    <input type="text" class="form-control harga_vendor {{ intval($p->harga_vendor) !== intval($p->activity()->harga_vendor) ? 'bg-danger text-white opacity-50' : '' }}" name="harga_vendor[]" id="harga_vendor" style="width: 100px" value="{{ number_format($p->harga_vendor , 0, '.', ',') }}" readonly>
                                                                </td>
                                                            @endhasrole
                                                            @hasrole(['Staff Finance','SPV Finance'])
                                                                <td>
                                                                    <input type="text" class="form-control harga_vendor {{ intval($p->harga_vendor) !== intval($p->activity()->harga_vendor) ? 'bg-danger text-white opacity-50' : '' }}" name="harga_vendor[]" id="harga_vendor" style="width: 100px" value="{{ number_format($p->harga_vendor , 0, '.', ',') }}">
                                                                </td>
                                                            @endhasrole
                                                            <td>
                                                                <input type="text" class="form-control"style="width: 100px" >
                                                            </td>
                                                            <td>
                                                                <div class="btn btn-danger btn-trash" data-id="{{ $p->id }}">
                                                                    <i><img src="{{asset('assets/images/trash2.svg')}}" style="width: 20px;"></i>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @else
                                                        <tr class="draggable-row parent-clone">
                                                            <input type="hidden" id="convertion-{{ $keys }}" value="{{ $p->conversion }}" name="convertion[]">
                                                            <td>
                                                                <select name="pekerjaan[]" id="pekerjaan-{{ $keys }}" class="form-select pekerjaan-{{ $keys }}">
                                                                    <option selected disabled>Pilih Pekerjaan</option>
                                                                    @foreach ($pekerjaans as $sp)
                                                                        <option {{ $p->id_pekerjaan ? ($p->id_pekerjaan === $sp->id ? 'selected' : '') : '' }} value="{{ $sp->id }}">{{ $sp->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="deskripsi[]" style="width: 150px;" value="{{ $p->deskripsi_pekerjaan }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="lokasi[]" style="width: 100px;" value="{{ $p->id_lokasi }}">
                                                            </td>

                                                            <td>
                                                                <input type="text" class="form-control" name="detail[]" style="width: 100px;" value="{{ $p->detail }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control" name="length[]" style="width: 70px" value="{{ $p->length }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control" name="width[]"style="width: 70px" value="{{ $p->width }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control" name="thick[]" style="width: 70px" value="{{ $p->thick }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="qty[]" style="width: 50px" value="{{ $p->qty }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="amount[]" style="width: 70px" value="{{ $p->amount }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="unit[]" style="width: 60px" id="unit" value="{{ $p->unit }}">
                                                            </td>
                                                            @hasrole('Project Admin')
                                                                <td>
                                                                    <input type="text" class="form-control harga_vendor" name="harga_vendor[]" id="harga_vendor" style="width: 100px" value="{{ number_format($p->harga_vendor , 0, '.', ',') }}" readonly>
                                                                </td>
                                                            @endhasrole
                                                            @hasrole(['Staff Finance','SPV Finance'])
                                                                <td>
                                                                    <input type="text" class="form-control harga_vendor" name="harga_vendor[]" id="harga_vendor" style="width: 100px" value="{{ number_format($p->harga_vendor , 0, '.', ',') }}">
                                                                </td>
                                                            @endhasrole
                                                            <td>
                                                                <input type="text" class="form-control total" style="width: 100px">
                                                            </td>
                                                            <td>
                                                                <div class="btn btn-danger btn-trash" data-id="{{ $p->id }}">
                                                                    <i><img src="{{asset('assets/images/trash2.svg')}}" style="width: 20px;"></i>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif --}}
                                                    @endforeach
                                                </tbody>
                                            </table>
                                           </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center gap-3 mt-4">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <h4>Recent Activity</h4>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <table class="table w-100" id="tableActivity">
                                    <thead style="background-color:#194BFB;color:#FFFFFF;">
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
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            let select = $('#tablePekerjaan').find('#pekerjaan');
            $('#sub_kategori').trigger('change');
            let id_kategori = $('#kategori').val();
            let id_subkategori = '{{ $subkategori_id }}';
            let id_project = '{{ $id }}';
            let id_vendor = '{{ $vendor->id }}';


            $('#sub_kategori').trigger('change');

            function pekerjaan(id, keys) {
                var select = `<select name="pekerjaan[]" id="pekerjaan-${keys}" class="form-select pekerjaan pekerjaan-${keys}">
                <option selected disabled>Pilih Pekerjaan</option>`;

                @foreach ($pekerjaans as $sp)
                    var pe = {!! json_encode($sp->id) !!};
                    var selected = `${id ? (id === pe ? 'selected' : '') : ''}`;
                    select += `<option ${selected} value="${pe}">{{ $sp->name }}</option>`;
                @endforeach

                select += `</select>`;

                $('.form-select').select2({
                    theme : "bootstrap-5",
                    search: true
                });

                $(`#pekerjaan-${keys}`).on('change',function(){
                    let id = $(this).val();
                    let url = '{{ route('ajax.unit-pekerjaan',':id') }}'
                    let urlReplace = url.replace(':id',id);
                    $.ajax({
                        url : urlReplace,
                    }).then(ress => {
                        $('#harga_vendor').val(formatRupiah(ress.data.harga_vendor));
                        $('#unit').val(ress.data.unit);
                        $(`#convertion-${keys}`).val(ress.data.konversi);
                    })
                });

                return select;
            }

            $('.form-select').select2({
                theme : "bootstrap-5",
                search: true
            });

            $("#tablePekerjaan tbody").sortable({
                items: '.draggable-row',
                axis: 'y',
            });

            $('#tablePekerjaan').DataTable({
                fixedHeader:true,
                ordering : false,
                scrollX: true,
                paging : false,
                processing: true,
                serverSide: true,
                searching: false,
                bLengthChange: false,
                autoWidth : true,
                rowCallback : function(row, data, index) {
                    $(row).addClass('draggable-row');
                },
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
                ajax : {
                    url : '{{ route('ajax.data-pekerjaan') }}',
                    method : 'POST',
                    data : {
                        _token : '{{ csrf_token() }}',
                        id_project,
                        id_kategori,
                        id_subkategori,
                        id_vendor
                    }
                },
                columns : [
                    {
                        data : function (data) {
                            let id_pekerjaan = data.id_pekerjaan || '';
                            var keys = data.DT_RowIndex;
                            return pekerjaan(id_pekerjaan, keys);
                        },
                        width : '200px'
                    },
                    {
                        data : function (data) {
                            let desc = data.deskripsi_pekerjaan || '';
                            let konversi = data.conversion || 0;
                            var keys = data.DT_RowIndex;
                            let status = false;
                            let recent = data.activitys.map(item =>{
                                status = data.deskripsi_pekerjaan !== item.deskripsi_pekerjaan ? 'bg-danger text-white' : '';
                            })
                            return ` <input type="text" class="form-control ${status}" name="deskripsi[]" value="${desc}">
                            <input type="text" class="d-none" value="${konversi}" id="convertion-${keys}" name="convertion[]">`;
                        },
                        width : '100px'
                    },
                    {
                        data : function (data) {
                            let location = data.id_lokasi || '';
                            var status = false;
                            let recent = data.activitys.map(item =>{
                                status = data.id_lokasi !== item.id_lokasi ? 'bg-danger text-white' : '';
                            })
                            return ` <input type="text" class="form-control ${status}" name="lokasi[]" value="${location}">`;
                        },
                        width : '100px'
                    },
                    {
                        data : function (data) {
                            let detail = data.detail || '';
                            var status = false;
                            let recent = data.activitys.map(item =>{
                                status = data.detail !== item.detail ? 'bg-danger text-white' : '';
                            })
                            return ` <input type="text" class="form-control ${status ? 'bg-danger text-white' : ''}" name="detail[]" value="${detail}">`;
                        },
                        width : '100px'
                    },
                    {
                        data : function (data) {
                            let length = data.length || '';
                            var status = false;
                            var items = '';
                            let recent = data.activitys.map(item =>{
                                items = item.length ;
                            })
                            status = items !== length ? 'bg-danger text-white' : '';
                            return ` <input type="text" class="form-control ${status}" name="length[]"  value="${length}">`;
                        },
                        width : '70px'
                    },
                    {
                        data : function (data) {
                            let width = data.width || '';
                            var status = false;
                            let recent = data.activitys.map(item =>{
                                status = data.width !== item.width ? 'bg-danger text-white' : '';
                            })
                            return ` <input type="text" class="form-control ${status}" name="width[]" value="${width}">`;
                        },
                        width : '70px'
                    },
                    {
                        data : function (data) {
                            let thick = data.thick || '';
                            var status = false;
                            let recent = data.activitys.map(item =>{
                                status = data.thick !== item.thick ? 'bg-danger text-white' : '';
                            })
                            return ` <input type="text" class="form-control ${status}" name="thick[]" value="${thick}">`;
                        },
                        width : '70px'
                    },
                    {
                        data : function (data) {
                            let qty = data.qty || '';
                            var status = false;
                            let recent = data.activitys.map(item =>{
                                status = data.qty !== item.qty ? 'bg-danger text-white' : '';
                            })
                            return ` <input type="text" class="form-control ${status}" name="qty[]" value="${qty}">`;
                        },
                        width : '50px'
                    },
                    {
                        data : function (data) {
                            let amount = data.amount || '';
                            var status = false;
                            let recent = data.activitys.map(item =>{
                                status = data.amount !== item.amount ? 'bg-danger text-white' : '';
                            })
                            return ` <input type="text" class="form-control ${status}" name="amount[]" value="${amount}" readonly>`;
                        },
                        width : '70px'
                    },
                    {
                        data : function (data) {
                            let unit = data.unit || '';
                            var status = false;
                            data.activitys.forEach(item => {
                                if (item.unit !== unit) {
                                    status = true;
                                }
                            });
                            return ` <input type="text" class="form-control  ${status ? 'bg-danger text-white' : ''}" id="unit" name="unit[]" style="width: 60px;" value="${unit}">`;
                        },
                        width : '50px'
                    },
                    {
                        data : function (data) {
                            var harga_vendor = data.harga_vendor || '';
                            var status = false;
                            var items = '';
                            let recent = data.activitys.map(item =>{
                                items = item.harga_vendor ;
                            })
                            status = items !== harga_vendor ? 'bg-danger text-white' : '';
                            return ` <input type="text" class="form-control ${status}" name="harga_vendor[]" id="harga_vendor" value="${formatRupiah(harga_vendor)}" readonly>`;
                        },
                        width : '150px'
                    },
                    {
                        data : function (data) {
                            let harga_vendor = parseFloat(data.harga_vendor) || '';
                            let amount = data.amount || '';
                            let total = Math.ceil(harga_vendor * amount);
                            return ` <input type="text" class="form-control ${status}" name="total[]" value="${formatRupiah(parseInt(total))}" readonly>`;
                        },
                        width : '150px'
                    },
                    {
                        data : function (data) {
                            let id = data.id;
                            return `<div class="btn btn-danger btn-trash" data-id=${id}>
                                <i><img src="{{asset('assets/images/trash2.svg')}}" style="width: 20px;"></i>
                            </div>`;
                        }
                    }
                ]

            })

            let count = 1;
            $('.btn-add').click(function(){
                var userRole = '{{ auth()->user()->hasRole("Project Admin") ? "admin" : "non-admin" }}';

                $('#clone').append(`<tr class="draggable-row">
                    <input type="hidden" name="convertion[]" id="convertion${count}">
                    <input type="hidden" name="id[]">
                    <td>
                        <select name="pekerjaan[]" id="pekerjaan${count}" class="form-select pekerjaan">
                            <option selected disabled>Pilih Pekerjaan</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="deskripsi[]">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="lokasi[]">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="detail[]">
                    </td>
                    <td>
                        <input type="number" class="form-control" name="length[]">
                    </td>
                    <td>
                        <input type="number" class="form-control" name="width[]">
                    </td>
                    <td>
                        <input type="number" class="form-control" name="thick[]">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="qty[]" >
                    </td>
                    <td>
                        <input type="text" class="form-control" name="amount[]" >
                    </td>
                    <td>
                        <input type="text" class="form-control" id="unit${count}" name="unit[]">
                    </td>

                    <td>
                        <input type="text" class="form-control harga_vendor" id="harga_vendor${count}" name="harga_vendor[]"  ${userRole === 'admin' ? 'readonly' : ''}>
                    </td>
                    <td>
                        <input type="text" class="form-control total" id="total${count}" name="total[]"  ${userRole === 'admin' ? 'readonly' : ''}>
                    </td>

                    <td>
                        <div class="btn btn-danger btn-trash">
                            <i><img src="{{asset('assets/images/trash2.svg')}}" style="width: 20px;"></i>
                        </div>
                    </td>
                </tr>`)
                let select = $(`#pekerjaan${count}`).select2({
                    theme : "bootstrap-5",
                    search: true
                })

                let id = $('#sub_kategori').val();
                var unit = $(`#unit${count}`);
                var harga_vendor = $(`#harga_vendor${count}`);
                var konversi = $(`#convertion${count}`);

                getSelect(select);

                $(`#pekerjaan${count}`).on('change',function(){
                    let id = $(this).val();
                    let url = '{{ route('ajax.unit-pekerjaan',':id') }}'
                    let urlReplace = url.replace(':id',id);
                    $.ajax({
                        url : urlReplace,
                    }).then(ress => {
                        harga_vendor.val(formatRupiah(ress.data.harga_vendor));
                        konversi.val(ress.data.konversi)
                        unit.val(ress.data.unit)
                    })
                });

                count++;

                $('.harga_customer').on('input', function() {
                    var inputValue = $(this).val();
                    var formattedValue = formatRupiah(inputValue);
                    $(this).val(formattedValue);
                });

                $('.harga_vendor').on('input', function() {
                    var inputValue = $(this).val();
                    var formattedValue = formatRupiah(inputValue);
                    $(this).val(formattedValue);
                });

            })


            $('#clone').on('change', '.draggable-row input[name="length[]"], input[name="width[]"], input[name="thick[]"],input[name="qty[]"]', function() {
                var lengthValue = parseFloat($(this).closest('tr').find('input[name="length[]"]').val());
                var widthValue = parseFloat($(this).closest('tr').find('input[name="width[]"]').val());
                var thickValue = parseFloat($(this).closest('tr').find('input[name="thick[]"]').val());
                var qtyValue = parseFloat($(this).closest('tr').find('input[name="qty[]"]').val());
                var konversi = $(this).closest('tr').find('input[name="convertion[]"]').val();
                var parts = konversi.split('/');
                var amountValue = (lengthValue * widthValue * thickValue * qtyValue * parseFloat(parts[0])) / parseInt(parts[1]);
                var harga_vendor = $(this).closest('tr').find('input[name="harga_vendor[]"]').val();
                harga_vendor = harga_vendor.replace(",", "");

                // Mengonversi string menjadi integer
                parseInt(harga_vendor, 10);

                var total = harga_vendor * amountValue;
                amountValue = amountValue.toFixed(2);

                $(this).closest('tr').find('input[name="amount[]"]').val(amountValue);
                $(this).closest('tr').find('input[name="total[]"]').val(formatRupiah(total));

            });

            $(document).delegate('.btn-trash','click',function(){
                let data = $('.parent-clone');
                let id = $(this).data('id');
                if(typeof id !== 'undefined' && id !== null && id !== '' ){
                    Swal.fire({
                        title: 'Are You Sure Deleted Data?',
                        text: "Data will be deleted permanently!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Deleted!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url : '{{ route('on_progres.request-delete') }}',
                                method : 'POST',
                                data : {
                                    _token : '{{ csrf_token() }}',
                                    id,
                                    id_kategori
                                }
                            }).then(ress => {
                                if(ress.status === 200) {
                                    $(this).closest('tr').remove();
                                    $(`.id-${id}`).remove();
                                    table.draw();
                                    alertToast('success',ress.msg);
                                }
                            })
                        }
                    })
                }else {

                    if(data.length != 1){
                        $(this).closest('tr').remove();
                    }
                }
            })

            let table = $('#tableActivity').DataTable({
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
                    url : '{{ route('ajax.recent-activity') }}',
                    data : function (d) {
                        d.id =  id_project,
                        d.id_kategori = '{{ $kategori }}',
                        d.id_subkategori = '{{ $subKategori }}',
                        d.id_vendor = '{{ $vendor->id }}'
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
                    { data : 'amount', name : 'amount' },
                    { data : 'harga_vendor', name : 'harga_vendor' },
                    { data : 'harga_customer', name : 'harga_customer' },
                ]
            })

            $('#kategori').on('change',function(){
                let id = $(this).val();
                let url = '{{ route('on_progres.sub-kategori',':id') }}'
                let urlReplace = url.replace(':id',id);
                $.ajax({
                    url : urlReplace,
                    method : 'GET'
                }).then(ress => {
                    let select = $('#sub_kategori');
                    if(ress.data.length != null){
                        select.empty();
                        select.append(`
                            <option selected disabled>Sub Kategori</option>
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

            $('#sub_kategori').on('change',function(){
                let id = $(this).val();
                let select = $('#pekerjaan');
                getSelect(id,select);
            })

            const getSelect = (select) => {
                $.ajax({
                    url : '{{ route('on_progres.pekerjaan') }}',
                    method : 'GET'
                }).then(ress => {
                    if(ress.data.length != null){
                        select.empty();
                        select.append(`
                            <option selected disabled>Pilih Pekerjaan</option>
                        `)
                        ress.data.forEach(item => {
                            select.append(`
                                <option value="${item.id}">${item.name}</option>
                            `)
                        })
                    }else{
                        select.append(`
                            <option selected disabled>Pilih Pekerjaan</option>
                        `)
                    }
                })
            }

            $('.harga_customer').on('input', function() {
                var inputValue = $(this).val();
                var formattedValue = formatRupiah(inputValue);
                $(this).val(formattedValue);
            });

            $('.harga_vendor').on('input', function() {
                var inputValue = $(this).val();
                var formattedValue = formatRupiah(inputValue);
                $(this).val(formattedValue);
            });

            @foreach ($pekerjaan as $keys => $p)
                $(`#pekerjaan-{{ $keys }}`).on('change',function(){
                    let id = $(this).val();
                    let url = '{{ route('ajax.unit-pekerjaan',':id') }}'
                    let urlReplace = url.replace(':id',id);
                    $.ajax({
                        url : urlReplace,
                    }).then(ress => {
                        $('#harga_vendor').val(formatRupiah(ress.data.harga_vendor));
                        $('#harga_customer').val(formatRupiah(ress.data.harga_customer));
                        $('#unit').val(ress.data.unit);
                        $('#convertion-{{ $keys }}').val(ress.data.konversi);
                    })
                });
            @endforeach

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

            const alertToast = (icon, message) => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                Toast.fire({
                    icon: icon,
                    title: message
                })
            }

        })
    </script>
@endsection
