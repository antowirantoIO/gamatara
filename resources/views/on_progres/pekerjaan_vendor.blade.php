@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('on_progress.edit',$idProject)}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Pekerjaan Vendor</h4>
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
                                <table class="table mt-3" id="dataTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="color:#929EAE">Pekerjaan</th>
                                            <th style="color:#929EAE">Lokasi</th>
                                            <th style="color:#929EAE">Detail / Other</th>
                                            <th style="color:#929EAE">Length (mm)</th>
                                            <th style="color:#929EAE">Width (mm)</th>
                                            <th style="color:#929EAE">Thick (mm)</th>
                                            <th style="color:#929EAE">Qty</th>
                                            <th style="color:#929EAE">Amount</th>
                                            <th style="color:#929EAE">Unit</th>
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
                            <label for="id_pekerjaan" class="form-label">Pekerjaan</label>
                            <select type="text" name="id_pekerjaan" class="form-select" id="id_pekerjaan">
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
                            <select type="text" name="id_lokasi" id="id_lokasi" class="form-select">
                                <option value="">Pilih Lokasi</option>
                                @foreach ($lokasi as $l)
                                    <option value="{{ $l->id }}">{{ $l->name }}</option>
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

            $('#btn-fillter').click(function(){
                modalInput.modal('show');
            });

            let table = $('#dataTable').DataTable({
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
                ajax : {
                    url : '{{ route('ajax.vendor') }}',
                    methdo : 'GET',
                    data : function(d){
                        d._token = '{{ csrf_token() }}';
                        d.id_project = '{{ $idProject }}';
                        d.id_vendor = '{{ $id }}';
                        d.id_lokasi = $('#id_lokasi').val();
                        d.id_pekerjaan = $('#id_pekerjaan').val();
                    }
                },

                columns : [
                    { data : 'pekerjaan.name' },
                    { data : 'lokasi.name' },
                    { data : 'detail' },
                    { data : 'length' },
                    { data : 'width' },
                    { data : 'thick' },
                    { data : 'qty' },
                    { data : 'amount' },
                    { data : 'unit' },
                ]

            });


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

        })
    </script>
@endsection
