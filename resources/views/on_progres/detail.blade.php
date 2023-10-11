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
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between">
                        <ul class="nav nav-tabs gap-3" id="myTab" role="tablist">
                            @foreach ($kategori as $key => $item)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $key === 0 ? 'active' : '' }} rounded-pill" id="{{ $item->id }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $item->id }}" type="button" role="tab" aria-controls="{{ $item->id }}" aria-selected="true">{{ $item->name }}</button>
                                </li>
                            @endforeach
                        </ul>
                        <div>
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
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="live-preview">
                                <div class="col-md-12">
                                    @foreach ($subWorker as $key => $worker)
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show {{ $key === 0 ? 'active' : '' }}" id="{{ $key }}" role="tabpanel" aria-labelledby="{{ $key }}-tab">
                                                <span class="fs-5"><strong>Pekerjaan {{ getNameKategori($key) }}</strong></span>
                                                <table class="table" id="example1">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th style="color:#929EAE;width:600px;">Pekerjaan</th>
                                                            <th style="color:#929EAE">Progres</th>
                                                            <th style="color:#929EAE">Vendor</th>
                                                            <th style="color:#929EAE">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($worker as $value)
                                                        @php
                                                            $status = getProgress($value->id_project,$value->id_kategori);
                                                        @endphp
                                                            <tr>
                                                                <td>{{ $value->subKategori->name }}</td>
                                                                <td>{{ $status->total_status_2 }} / {{ $status->total_status_1 }}</td>
                                                                <td>{{ $value->vendors->name }}</td>
                                                                <td>
                                                                    <a href="{{ route('on_progres.sub-detail',[$value->kategori,$value->id_project,$value->id_subkategori]) }}" class="btn btn-warning btn-sm">
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
