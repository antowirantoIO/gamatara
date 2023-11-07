@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('on_progress.pekerjaan-vendor.all',[$id,$idProject])}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Vendor Work Detail</h4>
                        </div>
                        <div class="d-flex justify-content-center align-items-center gap-3">
                            <button class="btn btn-secondary" id="btn-fillter">
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
                <div class="col-lg-12">
                    <div class="card mt-3 rounded-4 py-4 px-3">
                        <div class="card-body">
                            <div class="live-preview">
                                <span class="fs-5"><strong>{{ $nama_vendor }} ( {{ $nama_project }} )</strong></span>
                                <table class="table mt-3 w-100" id="dataTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="color:#929EAE">Work</th>
                                            <th style="color:#929EAE">Description</th>
                                            <th style="color:#929EAE">Location</th>
                                            <th style="color:#929EAE">Detail / Other</th>
                                            <th style="color:#929EAE">Length (mm)</th>
                                            <th style="color:#929EAE">Width (mm)</th>
                                            <th style="color:#929EAE">Thick (mm)</th>
                                            <th style="color:#929EAE">Qty</th>
                                            <th style="color:#929EAE">Amount</th>
                                            <th style="color:#929EAE">Unit</th>
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
<div id="modalFillter" class="modal fade zoomIn" aria-labelledby="zoomInModalLabel" aria-hidden="true">
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
                            <label for="id_pekerjaan" class="form-label">Pekerjaan</label>
                            <select type="text" name="id_pekerjaan" class="form-select form-select-fillter" id="id_pekerjaan">
                                <option value="">Pilih Pekerjaan</option>
                                @foreach ($pekerjaan as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xxl-6 col-md-6">
                        <div>
                            <label for="id_lokasi" class="form-label">Nama Project</label>
                            <input type="text" name="id_lokasi" id="id_lokasi" class="form-control"/>
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
                                <label for="pekerjaan_id" class="form-label">Work</label>
                                <select type="text" name="pekerjaan_id" class="form-select form-select-edit" id="pekerjaan_id">
                                    <option selected disabled>Choose Work</option>
                                    @foreach ($pekerjaan as $work)
                                        <option value="{{ $work->id }}">{{ $work->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="description" class="form-label">Description</label>
                                <input type="text" name="description" id="description" class="form-control">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="location" class="form-label">Location</label>
                                <input type="text" name="location" id="location" class="form-control">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="detail" class="form-label">Detail / Other</label>
                                <input type="text" name="detail" id="detail" class="form-control">
                            </div>
                        </div>
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

            $('.form-select-fillter').select2({
                theme : "bootstrap-5",
                dropdownParent: $("#modalFillter")
            });
            $('.form-select-edit').select2({
                theme : "bootstrap-5",
                dropdownParent: $("#modalEdit")
            });

            $('#btn-fillter').click(function(){
                modalInput.modal('show');
            });

            let table = $('#dataTable').DataTable({
                fixedHeader:true,
                scrollX: false,
                processing: true,
                ordering:false,
                serverSide: true,
                searching: true,
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
                ajax : {
                    url : '{{ route('ajax.vendor') }}',
                    methdo : 'GET',
                    data : function(d){
                        d._token = '{{ csrf_token() }}';
                        d.id_project = '{{ $idProject }}';
                        d.id_vendor = '{{ $id }}';
                        d.id_lokasi = $('#id_lokasi').val();
                        d.id_subkategori = '{{ $subkategori }}'
                        d.id_pekerjaan = $('#id_pekerjaan').val();
                    }
                },

                columns : [
                    { data : function(data){
                        let reff = data.pekerjaan || '-';
                        let name = reff.name || '-';
                        return name;
                    } },
                    { data : 'deskripsi_pekerjaan' },
                    { data : 'id_lokasi' },
                    { data : 'detail' },
                    { data : 'length' },
                    { data : 'width' },
                    { data : 'thick' },
                    { data : 'qty' },
                    { data : 'amount' },
                    { data : 'unit' },
                    {
                        data : function (data) {
                            let id = data.id;
                            return   `<button data-id="${id}" class="btn btn-info btn-sm btn-edit">
                                <span>
                                    <i><img src="{{asset('assets/images/edit.svg')}}" style="width: 15px;"></i>
                                </span>
                            </button>`
                        }
                    }
                ]

            });

            table.on('click','.btn-edit',function(){
                let id =$(this).data('id');

                let url = '{{ route('on_progres.request.edit',':id') }}';
                let urlReplace = url.replace(':id',id);
                $.ajax({
                    url : urlReplace,
                    method : 'GET'
                }).then(ress => {
                    console.log(ress.data.id_pekerjaan);
                    $('#id').val(id);
                    $('.form-select-edit').val(ress.data.id_pekerjaan).trigger('change');
                    $("#pekerjaan_id").val(ress.data.id_pekerjaan);
                    $('#description').val(ress.data.deskripsi_pekerjaan);
                    $('#location').val(ress.data.id_lokasi);
                    $('#detail').val(ress.data.detail);
                    $('#length').val(ress.data.length);
                    $('#width').val(ress.data.width);
                    $('#thick').val(ress.data.thick);
                    $('#unit').val(ress.data.unit);
                    $('#qty').val(ress.data.qty);
                    $('#amount').val(ress.data.amount);
                    $('#conversion').val(ress.data.conversion);
                    modalEdit.modal('show');
                })
            })

            $('#btn-search').click(function(e){
                e.preventDefault();
                modalInput.modal('hide');
                table.draw();
            })

            $('#btn-reset').click(function(e){
                e.preventDefault();
                $('.form-control').val('');
                $('.form-select').val(null).trigger('change');
                table.draw();
            })

            function hideOverlay() {
                $('.loading-overlay').fadeOut('slow', function() {
                    $(this).remove();
                });
            }

            $('#export-button').on('click', function(event) {
                event.preventDefault();

                var id_lokasi       = $('#code').val();
                var id_pekerjaan    = $('#id_pekerjaan').val();
                var id_project      = '{{ $idProject }}';
                var id_vendor       = '{{ $id }}';

                var url = '{{ route("on_progres.export-pekrjaan-vendor") }}?' + $.param({
                    id_lokasi: id_lokasi,
                    id_pekerjaan: id_pekerjaan,
                    id_project: id_project,
                    id_vendor: id_vendor,
                });

                $('.loading-overlay').show();

                window.location.href = url;

                setTimeout(hideOverlay, 2000);
            });

            function calculate()
            {
                var length = parseFloat($("#length").val()) || 0;
                var width = parseFloat($("#width").val()) || 0;
                var thick = parseFloat($("#thick").val()) || 0;
                var qty = parseFloat($("#qty").val()) || 0;
                var konversi = $('#conversion').val();
                var parts = konversi.split('/');
                var amountValue = (length * width * thick * qty * parseFloat(parts[0])) / parseInt(parts[1]);

                amountValue = amountValue.toFixed(2);
                console.log(parseInt(parts[0]),length, amountValue);
                $('#amount').val(0);
                $("#amount").val(amountValue);
            }

            $("#length, #width, #thick, #qty, #conversion").on("input", calculate);

        })
    </script>
@endsection
