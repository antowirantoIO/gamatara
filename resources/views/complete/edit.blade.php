@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('complete')}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Complete</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card rounded-4 p-3">
                        <div class="card-body">
                            <div class="live-preview">
                                <div class="row gy-4">

                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="nama_project" class="form-label">Project Name</label>
                                            <input type="text" name="nama_project" class="form-control" id="nama_project" placeholder="Masukkan Nama Project" value="{{ $data->nama_project }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="nama_project" class="form-label">Project Code</label>
                                            <input type="text" name="nama_project" class="form-control" id="nama_project" placeholder="Masukkan Nama Project" value="{{ $data->code }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <label for="nama_customer" class="form-label">Customer Name</label>
                                        <input type="text" id="customer_name" name="id_customer" placeholder="Nama Customer" class="form-control" value="{{ $data->customer->name }}" disabled/>
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="contact_person" class="form-label">Contact Person</label>
                                            <input type="text" name="contact_person" class="form-control" id="contact_person" placeholder="Masukkan Contact Person" value="{{ $data->contact_person }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="nomor_contact_person" class="form-label">Number Contact Person</label>
                                            <input type="text" name="nomor_contact_person" class="form-control" id="nomor_contact_person" placeholder="Masukkan Nomor Contact Person" value="{{ $data->nomor_contact_person }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="alamat" class="form-label">Project Manager</label>
                                            <input type="text" class="form-control" id="alamat" value="{{ $data->pm->karyawan->name }}" disabled>
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
                                            <th>Vendor Name</th>
                                            <th>progress</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($projects as $project)
                                            <tr>
                                                <td>{{ $project->vendors->name }}</td>
                                                <td>{{ $project->total_status_2 }} / {{ $project->total_status_1 }}</td>
                                                <td>
                                                    <a href="{{ route('complete.pekerjaan-vendor.all',[$project->id_vendor,$data->id]) }}" class="btn btn-warning btn-sm">
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
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                    <div class="d-flex flex-column gap-3">
                                        <a href="{{ route('complete.pekerjaan',$data->id) }}" class="btn btn-primary btn-block w-100 rounded-3 border-0">
                                            <div class="d-flex justify-content-between align-items-end">
                                                <div class="fs-4 text-start">
                                                    Job Progress<br>
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
                                            <a href="{{ route('complete.tagihan.all',$data->id) }}" class="btn btn-primary flex-fill btn-block  rounded-3 border-0" style="background: #FFBC39;">
                                                <div class="d-flex justify-content-between align-items-end">
                                                    <div class="fs-5 text-start">
                                                        Bills <br>
                                                       <strong>Vendor</strong>
                                                    </div>
                                                    <div>
                                                        <i><img src="{{asset('assets/images/login.svg')}}" style="width: 30px;"></i>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="{{ route('complete.tagihan-customer',$data->id) }}" class="btn btn-primary flex-fill btn-block rounded-3 border-0" style="background: #FFBC39;">
                                                <div class="d-flex justify-content-between align-items-end">
                                                    <div class="fs-5 text-start">
                                                        Bills <br>
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
                                <div id="tabelKeluhanWrapper">

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

            let idData = "{{$data->id}}";
            console.log(idData);
            function getTableData(id) {
                let url = "{{route('on_progres.table-data', ':id')}}";
                url = url.replace(':id', id);
                $.ajax({
                    url: url,
                    success: function(data) {
                        $('#tabelKeluhanWrapper').html(data)
                    }
                })
            }
            getTableData(idData);
        })
    </script>
@endsection
