@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('on_progres.detail-worker',$idProject)}}">
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
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $item->pekerjaan->name . ' ' . $item->deskripsi_pekerjaan ?? '-' }}</td>
                                                <td>{{ $item->id_lokasi ?? '-' }}</td>
                                                <td>{{ $item->detail ?? '-' }}</td>
                                                <td>{{ $item->length ?? 0 }}</td>
                                                <td>{{ $item->width ?? 0 }}</td>
                                                <td>{{ $item->thick ?? 0 }}</td>
                                                <td>{{ $item->qty ?? 0 }}</td>
                                                <td>{{ $item->amount ?? 0 }}</td>
                                                <td>{{ $item->unit ?? '-' }}</td>
                                                <td>{{ $item->vendors->name ?? '-' }}</td>
                                            </tr>
                                        @endforeach
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
                                <div class="d-flex justify-content-around align-items-center">
                                    @foreach ($before as $b)
                                        <a href="{{ asset($b->photo) }}" data-lightbox="{{ asset($b->photo) }}">
                                            <img  class="img-responsive rounded" src="{{ asset($b->photo) }}" alt="picture">
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
                            <div class="d-flex justify-content-around align-items-center">
                                @foreach ($after as $a)
                                    <a href="{{ asset($a->photo) }}" data-lightbox="{{ asset($a->photo) }}">
                                        <img  class="img-responsive rounded" src="{{ asset($a->photo) }}" alt="picture">
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
        })
    </script>
@endsection
