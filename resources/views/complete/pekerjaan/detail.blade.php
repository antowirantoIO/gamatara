@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('complete.pekerjaan',$idProject)}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Job Detail</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="live-preview">
                                <table class="table" id="example1" style="font-size: 10px;">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="color:#929EAE">Job</th>
                                            <th style="color:#929EAE">Location</th>
                                            <th style="color:#929EAE">Detail / Other</th>
                                            <th style="color:#929EAE">Length (mm)</th>
                                            <th style="color:#929EAE">Width (mm)</th>
                                            <th style="color:#929EAE">Thick (mm)</th>
                                            <th style="color:#929EAE">Qty</th>
                                            <th style="color:#929EAE">Amount</th>
                                            <th style="color:#929EAE">Unit</th>
                                            <th style="color:#929EAE">Vendor</th>
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

            <div class="row g-3">
                <div class="row gy-4">
                    <div class="col-xxl-12 col-md-12">
                        <p class="fs-4">Before</p>
                            @if ($before->count() > 0)
                                <div class="d-flex justify-content-start align-items-center bg-white p-3 flex-wrap gap-3">
                                    @foreach ($before as $b)
                                    <a href="{{ asset($b->photo) }}" data-lightbox="{{ asset($b->photo) }}">
                                        <img  class="img-responsive rounded" src="{{ asset($b->photo) }}" alt="picture" height="200" width="200">
                                    </a>
                                   @endforeach
                                </div>
                            @else
                                <div class="d-flex justify-content-center align-items-center w-100 bg-white">
                                    <img src="{{ asset('assets/images/notfound.svg') }}" alt="notfound" height="150" class="img-responsive">
                                </div>
                            @endif
                    </div>
                    <div class="col-xxl-12 col-md-12 mt-5">
                        <p class="fs-4">After</p>
                        @if ($after->count())
                            <div class="d-flex justify-content-start align-items-center bg-white p-3 flex-wrap gap-3">
                                @foreach ($after as $a)
                                    <a href="{{ asset($a->photo) }}" data-lightbox="{{ asset($a->photo) }}">
                                        <img  class="img-responsive rounded" src="{{ asset($a->photo) }}" alt="picture" height="200" width="200">
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="d-flex justify-content-center align-items-center w-100 bg-white">
                                <img src="{{ asset('assets/images/notfound.svg') }}" alt="notfound" height="150" class="img-responsive">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!--modal -->
<div class="modal fade" id="modalInput" tabindex="-1" aria-labelledby="exampleModalgridLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{route('customer.store')}}" id="npwpForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                        <button type="button" class="btn-close fs-3" aria-label="Close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">

                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            let modalInput = $('#modalInput');
            $(".btn-modal").click(function(){
                modalInput.modal('show');
            })

            lightbox.option({
                'resizeDuration': 200,
                'fitImagesInViewport' : true,
                'wrapAround': true
            })

            let id_kategori = '{{ $id }}';
            let id_project = '{{ $idProject }}';
            let id_subkategori = '{{ $subKategori }}';
            let kode_unik = '{{ $kodeUnik }}';
            let url = '{{ route('on_progres.sub-detail',[':id',':project',':subkategori',':kode_unik']) }}';
            let urlReplace = url.replace(':id',id_kategori).replace(':project',id_project).replace(':subkategori',id_subkategori).replace(':kode_unik',kode_unik);

            let table = $("#example1").DataTable({
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
                    url : urlReplace,
                    method : 'GET',
                },
                columns : [
                    { data : 'pekerjaan', name : 'pekerjaan'},
                    { data : 'id_lokasi', name : 'id_lokasi'},
                    { data : 'detail', name : 'detail'},
                    { data : 'length', name : 'length'},
                    { data : 'width', name : 'width'},
                    { data : 'thick', name : 'thick'},
                    { data : 'qty', name : 'qty'},
                    { data : 'amount', name : 'amount'},
                    { data : 'unit', name : 'unit'},
                    { data : 'vendor', name : 'vendor'}
                ]
            })
        })
    </script>
@endsection
