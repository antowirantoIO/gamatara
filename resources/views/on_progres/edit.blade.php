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
                            <h4 class="mb-0 ml-2"> &nbsp; On Progres</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <div class="row gy-4">
                                    <div class="flex-grow-1 d-flex align-items-center justify-content-end gap-3">
                                        <a href="{{ route('on_progres.work',$data->id) }}" class="btn btn-request btn-primary border-0">Request Form</a>
                                        <div class="btn btn-primary border-0" id="btn-setting"><i><img src="{{asset('assets/images/setting-2.svg')}}" style="width: 15px;margin-right: 5px;"></i>Setting</div>
                                    </div>

                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="nama_project" class="form-label">Nama Project</label>
                                            <input type="text" name="nama_project" class="form-control" id="nama_project" placeholder="Masukkan Nama Project" value="{{ $data->nama_project }}">
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="nama_project" class="form-label">Kode Project</label>
                                            <input type="text" name="nama_project" class="form-control" id="nama_project" placeholder="Masukkan Nama Project" value="{{ $data->code }}">
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <label for="nama_customer" class="form-label">Nama Customer</label>
                                        <input type="text" id="customer_name" name="id_customer" placeholder="Nama Customer" class="form-control" value="{{ $data->customer->name }}" />
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="contact_person" class="form-label">Contact Person</label>
                                            <input type="text" name="contact_person" class="form-control" id="contact_person" placeholder="Masukkan Contact Person" value="{{ $data->contact_person }}">
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="nomor_contact_person" class="form-label">Nomor Contact Person</label>
                                            <input type="text" name="nomor_contact_person" class="form-control" id="nomor_contact_person" placeholder="Masukkan Nomor Contact Person" value="{{ $data->nomor_contact_person }}">
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="alamat" class="form-label">Project Manager</label>
                                            <input type="text" class="form-control" id="alamat" value="Dodi Setiawan">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <table id="tableVendor" class="table">
                                    <thead style="background-color:#194BFB;color:#FFFFFF">
                                        <tr>
                                            <th>Nama Vendor</th>
                                            <th>progres</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>CV Muara Fajar Utama</td>
                                            <td>57 / 100</td>
                                            <td>
                                                <a href="{{ route('on_progres.detail-worker') }}" class="btn btn-warning btn-sm">
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
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                    <div class="d-flex flex-column gap-3">
                                        <a href="#" class="btn btn-primary btn-block w-100 rounded-3 border-0">
                                            <div class="d-flex justify-content-between align-items-end">
                                                <div class="fs-4">
                                                    Pekerjaan <br>
                                                    110 / 1002
                                                </div>
                                                <div>
                                                    <i><img src="{{asset('assets/images/login.svg')}}" style="width: 30px;"></i>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="d-flex gap-3">
                                            <a href="#" class="btn btn-primary flex-fill btn-block  rounded-3 border-0" style="background: #FFBC39;">
                                                <div class="d-flex justify-content-between align-items-end">
                                                    <div class="fs-5">
                                                        Tagihan <br>
                                                       <strong>Vendor</strong>
                                                    </div>
                                                    <div>
                                                        <i><img src="{{asset('assets/images/login.svg')}}" style="width: 30px;"></i>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="#" class="btn btn-primary flex-fill btn-block rounded-3 border-0" style="background: #FFBC39;">
                                                <div class="d-flex justify-content-between align-items-end">
                                                    <div class="fs-5">
                                                        Tagihan <br>
                                                        <strong>Customer</strong>
                                                    </div>
                                                    <div>
                                                        <i><img src="{{asset('assets/images/login.svg')}}" style="width: 30px;"></i>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <table id="tabelKeluhan" class="table table-bordered">
                                    <thead style="background-color:#194BFB;color:#FFFFFF">
                                        <tr>
                                            <th>Keluhan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data->complaint as $item)
                                            <tr>
                                                <td>{{ $item->keluhan }}</td>
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
