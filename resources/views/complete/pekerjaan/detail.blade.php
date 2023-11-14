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
                            <h4 class="mb-0 ml-2"> &nbsp; Detail Job</h4>
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
                                                <td>{{ $item->pekerjaan->name }}</td>
                                                <td>{{ $item->id_lokasi }}</td>
                                                <td>{{ $item->detail }}</td>
                                                <td>{{ $item->length }}</td>
                                                <td>{{ $item->width }}</td>
                                                <td>{{ $item->thick }}</td>
                                                <td>{{ $item->qty }}</td>
                                                <td>{{ $item->amount }}</td>
                                                <td>{{ $item->unit }}</td>
                                                <td>{{ $item->vendors->name }}</td>
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
                        <p class="fs-4">Foto Sebelum</p>
                        <div class="d-flex justify-content-around align-items-center">
                            <a href="{{ asset('assets/images/image-example.png') }}" data-lightbox="{{ asset('assets/images/image-example.png') }}">
                                <img  class="img-responsive rounded" src="{{ asset('assets/images/image-example.png') }}" alt="picture">
                            </a>
                            <a href="{{ asset('assets/images/image-example.png') }}" data-lightbox="{{ asset('assets/images/image-example.png') }}">
                                <img  class="img-responsive rounded" src="{{ asset('assets/images/image-example.png') }}" alt="picture">
                            </a>
                            <a href="{{ asset('assets/images/image-example.png') }}" data-lightbox="{{ asset('assets/images/image-example.png') }}">
                                <img  class="img-responsive rounded" src="{{ asset('assets/images/image-example.png') }}" alt="picture">
                            </a>
                            <a href="{{ asset('assets/images/image-example.png') }}" data-lightbox="{{ asset('assets/images/image-example.png') }}">
                                <img  class="img-responsive rounded" src="{{ asset('assets/images/image-example.png') }}" alt="picture">
                            </a>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12 mt-5">
                        <p class="fs-4">Foto Sesudah</p>
                        <div class="d-flex justify-content-around align-items-center">
                            <img src="{{ asset('assets/images/image-example.svg') }}" alt="picture">
                            <img src="{{ asset('assets/images/image-example.svg') }}" alt="picture">
                            <img src="{{ asset('assets/images/image-example.svg') }}" alt="picture">
                            <img src="{{ asset('assets/images/image-example.svg') }}" alt="picture">
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
