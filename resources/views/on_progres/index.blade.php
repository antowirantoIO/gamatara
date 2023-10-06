@extends('index')

@section('content')

<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <h4 class="mb-0 ml-2"> &nbsp; On Progres</h4>
                        </div>
                        <div class="mt-3 mt-lg-0 ml-lg-auto">
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
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Request</h4>
                            <div>

                            </div>
                        </div>

                        <div class="card-body">
                            <div class="container">
                                <table class="table" id="example1">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="color:#929EAE">Kode Project</th>
                                            <th style="color:#929EAE">Nama Project</th>
                                            <th style="color:#929EAE">Nama Customer</th>
                                            <th style="color:#929EAE">Project Manager</th>
                                            <th style="color:#929EAE">Tanggal Mulai</th>
                                            <th style="color:#929EAE">Tanggal Selesai</th>
                                            <th style="color:#929EAE">Progres</th>
                                            <th style="color:#929EAE">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $item->code }}</td>
                                                <td>{{ $item->nama_project }}</td>
                                                <td>PT Bomas Tiga</td>
                                                <td>Bagus Ampito</td>
                                                <td>28 Ags 2023</td>
                                                <td>28 Des 2023</td>
                                                <td>133 / 436</td>
                                                <td>
                                                    <a href="{{ route('on_progress.edit',$item->id) }}" class="btn btn-warning btn-sm">
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
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

<script>
    $(function() {
            $("#example1").DataTable({
                fixedHeader:true,
                scrollX:true
            });
        })
</script>
@endsection
