@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('on_request')}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Detail Pekerjaan</h4>
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
                                            <th style="color:#929EAE">Pekerjaan</th>
                                            <th style="color:#929EAE">Lokasi</th>
                                            <th style="color:#929EAE">Detail / Other</th>
                                            <th style="color:#929EAE">Length (mm)</th>
                                            <th style="color:#929EAE">Width (mm)</th>
                                            <th style="color:#929EAE">Thick (mm)</th>
                                            <th style="color:#929EAE">Qty</th>
                                            <th style="color:#929EAE">Amount</th>
                                            <th style="color:#929EAE">Unit</th>
                                            <th style="color:#929EAE">Vendor</th>
                                            <th style="color:#929EAE">Status</th>
                                            <th style="color:#929EAE">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Diver service naik / turun</td>
                                            <td>1 / 1</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>CV DUA PUTRA</td>
                                            <td>
                                                <span class="text-success">Complete</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('on_progres.sub-detail') }}" class="btn btn-warning btn-sm">
                                                    <span>
                                                        <i><img src="{{asset('assets/images/eye.svg')}}" style="width: 15px;"></i>
                                                    </span>
                                                </a>
                                            </td>
                                        </tr>
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

<!--modal -->
<div class="modal fade" id="modalInput" tabindex="-1" aria-labelledby="exampleModalgridLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{route('customer.store')}}" id="npwpForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">Tambah Vendor</h5>
                        <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                            <button class="btn btn-primary" style="margin-right: 10px;" id="saveCustomerButton">Simpan</button>
                            <a class="btn btn-danger" type="button" data-bs-dismiss="modal" aria-label="Close">Cancel</a>
                        </div>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="row gy-4">
                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <label for="customer" class="form-label">Nama Vendor</label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Masukkan Nama Vendor">
                                </div>
                            </div>
                            <div class="col-xxl-12 col-md-12">
                                <table class="table">
                                    <thead  style="background-color:#194BFB;color:#FFFFFF;">
                                        <tr>
                                            <th>Nama Vendor</th>
                                        </tr>
                                    </thead>
                                    @php
                                        $vendor = [
                                            0 => "PT Gamatara Trans Ochean Shipyard",
                                            1 => "CV Zafran Haddad Teknik",
                                            2 => "CV Hidup Dua Putra",
                                            3 => "CV Muara Fajar Utama",
                                            4 => "CV Angkasa Mandiri"
                                        ];
                                    @endphp
                                    <tbody>
                                        @foreach ($vendor as $item)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            {{ $item }}
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
            $("#btn-setting").click(function(){
                modalInput.modal('show');
            })
        })
    </script>
@endsection
