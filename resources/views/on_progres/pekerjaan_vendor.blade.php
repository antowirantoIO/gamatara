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
                            <h4 class="mb-0 ml-2"> &nbsp; Progress Pekerjaan</h4>
                        </div>
                        <div class="d-flex justify-content-center align-items-center gap-3">
                            <button class="btn btn-secondary">
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
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card mt-3 rounded-4 py-4 px-3">
                        <div class="card-body">
                            <div class="live-preview">
                                <span class="fs-5"><strong>{{ $nama_vendor }} ( {{ $nama_project }} )</strong></span>
                                <table class="table mt-3" id="example1">
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
                                            <th style="color:#929EAE">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $item->pekerjaan->name }}</td>
                                                <td>{{ $item->projects->lokasi->name }}</td>
                                                <td>{{$item->detail}}</td>
                                                <td>{{ $item->length }}</td>
                                                <td>{{ $item->width }}</td>
                                                <td>{{ $item->thick }}</td>
                                                <td>{{ $item->qty }}</td>
                                                <td>{{ $item->amount }}</td>
                                                <td>{{ $item->unit }}</td>
                                                <td>
                                                    <a href="{{ route('on_progres.detail-vendor-worker',$id) }}" class="btn btn-warning btn-sm">
                                                        <span>
                                                            <i><img src="{{asset('assets/images/eye.svg')}}" style="width: 15px;"></i>
                                                        </span>
                                                    </a>
                                                </td>
                                            </tr>
                                       @endforeach
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

@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            let modalInput = $('#modalInput');
            $("#btn-setting").click(function(){
                modalInput.modal('show');
            })
        })
    </script>
@endsection
