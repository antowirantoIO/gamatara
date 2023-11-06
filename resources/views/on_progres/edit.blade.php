@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('on_progress')}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Detail Progres</h4>
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
                                        {{-- <a href="{{ route('on_progres.work',$data->id) }}" class="btn btn-request btn-primary border-0">Input Pekerjaan</a> --}}
                                        {{-- <a href="{{ route('on_progres.setting',$data->id) }}"class="btn btn-primary border-0" id="btn-setting"><i><img src="{{asset('assets/images/setting-2.svg')}}" style="width: 15px;margin-right: 5px;"></i>Setting</a> --}}
                                    </div>

                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="nama_project" class="form-label">Nama Project</label>
                                            <input type="text" name="nama_project" class="form-control" id="nama_project" placeholder="Masukkan Nama Project" value="{{ $data->nama_project }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="nama_project" class="form-label">Kode Project</label>
                                            <input type="text" name="nama_project" class="form-control" id="nama_project" placeholder="Masukkan Nama Project" value="{{ $data->code }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <label for="nama_customer" class="form-label">Nama Customer</label>
                                        <input type="text" id="customer_name" name="id_customer" placeholder="Nama Customer" class="form-control" value="{{ $data->customer->name }}" disabled/>
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <label for="nama_customer" class="form-label">Lokasi Project</label>
                                        <input type="text" id="customer_name" name="id_customer" placeholder="Nama Customer" class="form-control" value="{{ $data->lokasi->name }}" disabled/>
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="contact_person" class="form-label">Contact Person</label>
                                            <input type="text" name="contact_person" class="form-control" id="contact_person" placeholder="Masukkan Contact Person" value="{{ $data->contact_person }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="nomor_contact_person" class="form-label">Nomor Contact Person</label>
                                            <input type="text" name="nomor_contact_person" class="form-control" id="nomor_contact_person" placeholder="Masukkan Nomor Contact Person" value="{{ $data->nomor_contact_person }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="alamat" class="form-label">Project Manager</label>
                                            <input type="text" class="form-control" id="alamat" value="{{ $data->pm->karyawan->name ?? '-' }}" disabled>
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
                                            <th>Progress</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($projects as $keys => $project)
                                            <tr>
                                                <td>{{ $project->vendors->name }}</td>

                                                @isset($progress[$keys])
                                                    <td>{{ $progress[$keys]->total_status_2 }} / {{ $progress[$keys]->total_status_1 }}</td>
                                                @else
                                                    <td>
                                                        0 / 0
                                                    </td>
                                                @endisset
                                                <td>
                                                    <div class="d-flex justify-contetn-center gap-3">
                                                        <a href="{{ route('on_progres.vendor-worker',[$project->id_vendor,$data->id]) }}" class="btn btn-warning btn-sm">
                                                            <span>
                                                                <i><img src="{{asset('assets/images/eye.svg')}}" style="width: 15px;"></i>
                                                            </span>
                                                        </a>
                                                        @hasrole(['Staff Finance','Project Admin','SPV Finance'])
                                                        <a href="{{ route('on_progres.request.tambah-kategori',[$data->id,$project->id_vendor]) }}" class="btn btn-info btn-sm">
                                                            <span>
                                                                <i><img src="{{asset('assets/images/edit.svg')}}" style="width: 15px;"></i>
                                                            </span>
                                                        </a>
                                                        @endhasrole
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
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                    <div class="d-flex flex-column gap-3">
                                        <a href="{{ route('on_progres.detail-worker',$data->id) }}" class="btn btn-primary btn-block w-100 rounded-3 border-0">
                                            <div class="d-flex justify-content-between align-items-end">
                                                <div class="fs-4 text-start">
                                                    Pekerjaan<br>
                                                    @if (!$pekerjaan->total_status_1)
                                                        0 / 0
                                                    @else
                                                        {{ $pekerjaan->total_status_2 }} / {{ $pekerjaan->total_status_1 }}
                                                    @endif
                                                </div>
                                                <div>
                                                    <i><img src="{{asset('assets/images/login.svg')}}" style="width: 30px;"></i>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="d-flex gap-3">
                                            <a href="{{ route('on_progres.tagihan.all',$data->id) }}" class="btn btn-primary flex-fill btn-block  rounded-3 border-0" style="background: #FFBC39;">
                                                <div class="d-flex justify-content-between align-items-end">
                                                    <div class="fs-5 text-start">
                                                        Tagihan <br>
                                                       <strong>Vendor</strong>
                                                    </div>
                                                    <div>
                                                        <i><img src="{{asset('assets/images/login.svg')}}" style="width: 30px;"></i>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="{{ route('on_progres.tagihan-customer',$data->id) }}" class="btn btn-primary flex-fill btn-block rounded-3 border-0" style="background: #FFBC39;">
                                                <div class="d-flex justify-content-between align-items-end">
                                                    <div class="fs-5 text-start">
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
                                        @foreach ($data->keluhan as $item)
                                            <tr>
                                                <td>{!! $item->keluhan !!}</td>
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
        })
    </script>
@endsection
