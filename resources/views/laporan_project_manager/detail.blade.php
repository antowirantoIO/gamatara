@extends('index')

@section('content')

<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <!-- <a href="{{route('laporan_project_manager')}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a> -->
                            <a href="javascript:void(0);" onclick="history.back();">
                                <i><img src="{{ asset('assets/images/arrow-left.svg') }}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Report Project Manager Detail</h4>
                        </div>
                        <div class="mt-3 mt-lg-0 ml-lg-auto">
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
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">{{ $pm->karyawan->name }}</h4>
                            <div>

                            </div>
                        </div>

                        <div class="card-body">
                            <table class="table w-100" id="example1">
                                <thead class="table-light">
                                    <tr>
                                        <th style="color:#929EAE">Project Code</th>
                                        <th style="color:#929EAE">Project Name</th>
                                        <th style="color:#929EAE">Start Date</th>
                                        <th style="color:#929EAE">End Date</th>
                                        <th style="color:#929EAE">Project Value</th>
                                        <th style="color:#929EAE">Status Project</th>
                                        {{-- <th style="color:#929EAE">Action</th> --}}
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

<!--modal -->
<div id="modalFillter" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-top-right">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gy-4">
                    <!-- <div class="col-xxl-6 col-md-6">
                        <div>
                            <label for="code" class="form-label">Code Project</label>
                            <input type="text" name="code" class="form-control" id="code">
                        </div>
                    </div> -->
                    <div class="col-xxl-6 col-md-6">
                        <div>
                            <label for="nama_project" class="form-label">Project Name</label>
                            <input type="text" name="nama_project" id="nama_project" class="form-control">
                        </div>
                    </div>
                    <div class="col-xxl-6 col-md-6">
                        <div>
                            <label for="status_project">Project Status</label>
                            <select name="status_project" id="status_project" class="form-control form-select select2">
                                <option value="">Choose Project Status</option>
                                <option value="1">Progress</option>
                                <option value="2">Complete</option>
                            </select>
                        </div>
                    </div>
                    <!-- <div class="col-xxl-12 col-md-12">
                        <div>
                            <label for="dates" class="form-label">Start Date</label>
                            <input type="text" name="dates" id="dates" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div>
                            <label for="enddates" class="form-label">End Date</label>
                            <input type="text" name="enddates" id="enddates" class="form-control" autocomplete="off">
                        </div>
                    </div> -->
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
    $(function() {
            let modalInput = $('#modalFillter');

            $('.form-select').select2({
                theme : "bootstrap-5",
                search: true
            });

            $('#dates').daterangepicker({
                opens: 'right',
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear'

                },
            });

            $('#dates').on('apply.daterangepicker',function(e,picker){
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            })

            $('#enddates').daterangepicker({
                opens: 'right',
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear'

                },
            });

            $('#enddates').on('apply.daterangepicker',function(e,picker){
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            })

            $('#btn-fillter').click(function(){
                modalInput.modal('show');
            })

            $('#btn-reset').click(function(e){
                e.preventDefault();
                $('.form-control').val('');
                $('.form-select').val(null).trigger('change');
                $('#dates').val('');
                $('#enddates').val('');
                table.draw()
            })

            $('#btn-search').click(function(e){
                e.preventDefault();
                table.draw()
            })

            let id = '{{ $id }}';
            let url = '{{ route('laporan_project_manager.detail',':id') }}';
            let urlReplace = url.replace(':id',id);
            let filterSearch = '';

            let table = $("#example1").DataTable({
                ordering: false,
                fixedHeader:true,
                scrollX: false,
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
                    url : urlReplace,
                    data : function (d) {
                        filterSearch    = d.search?.value;
                        d.code              = $('#code').val();
                        d.nama_project      = $('#nama_project').val();
                        d.dates             = $('#dates').val();
                        d.enddates          = $('#enddates').val();
                        d.status_project    = $('#status_project').val();
                    }
                },
                columns : [
                    { data : 'code', name : 'code'},
                    { data : 'nama_project', name : 'nama_project'},
                    {
                        data : function(data) {
                            let start = data.created_at || '';

                            if (start) {
                                let startDate = moment(start);

                                let formattedStartDate = startDate.format('DD MMMM YYYY');

                                return formattedStartDate ;
                            }

                            return '';
                        }
                        , name : 'created_at'},
                    {
                        data : function(data) {
                            let end = data.actual_selesai || '';

                            if (end) {
                                let endDate = moment(end);

                                let formattedEndDate = endDate.format('DD MMMM YYYY');

                                return formattedEndDate ;
                            }

                            return '';
                        },
                        name : 'actual_selesai'},
                    {
                        data : function(data) {
                            let harga = data.progress.reduce((accumulator, currentValue) => {
                                return accumulator + (currentValue.harga_customer * currentValue.amount);
                            }, 0);

                            return rupiah(harga);
                        }

                    },
                    { data : function(data) {
                            if(data.status === 1) {
                                return '<div style="color:blue;">On Progres</div>'
                            }else if(data.status === 2){
                                return '<div style="color:green;">Complete</div>'
                            }else {
                                return ''
                            }
                        }, name : 'status'
                    },
                ]
            });

            const rupiah = (number)=>{
                var	reverse = number.toString().split('').reverse().join(''),
                ribuan 	= reverse.match(/\d{1,3}/g);
                ribuan	= ribuan.join('.').split('').reverse().join('');
                return ribuan;
            }

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

                var code            = $('#code').val();
                var nama_project    = $('#nama_project').val();
                var tanggal_mulai   = $('#tanggal_mulai').val();
                var tanggal_selesai = $('#tanggal_selesai').val();
                var nilai_project   = $('#nilai_project').val();
                var status_project  = $('#status_project').val();
                var dates           = $('#dates').val();
                var enddates        = $('#enddates').val();
                var status_project  = $('#status_project').val();

                var url = '{{ route("laporan_project_manager.exportDetail") }}?' + $.param({
                    id              : {{$pm->id}},
                    code            : code,
                    nama_project    : nama_project,
                    tanggal_mulai   : tanggal_mulai,
                    tanggal_selesai : tanggal_selesai,
                    nilai_project   : nilai_project,
                    status_project  : status_project,
                    dates           : dates,
                    enddates        : enddates,
                    status_project  : status_project,
                    keyword         : filterSearch
                });

                $('.loading-overlay').show();

                window.location.href = url;

                setTimeout(hideOverlay, 2000);
            });

            $(document).ready(function() {
                $('.loading-overlay').hide();
            });
        })
</script>
@endsection
