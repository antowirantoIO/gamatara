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
                            <h4 class="mb-0 ml-2"> &nbsp; Details Progress</h4>
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
                                    <input type="hidden" class="id" id="id" value="{{$data->id}}">
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
                                        <label for="nama_customer" class="form-label">Project Location</label>
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
                                            <label for="nomor_contact_person" class="form-label">Contact Person Number</label>
                                            <input type="text" name="nomor_contact_person" class="form-control" id="nomor_contact_person" placeholder="Masukkan Nomor Contact Person" value="{{ $data->nomor_contact_person }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="alamat" class="form-label">Project Manager</label>
                                            <input type="text" class="form-control" id="alamat" value="{{ $data->pm->karyawan->name ?? '-' }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="estimasi" class="form-label">Estimation</label>
                                            <input type="date" class="form-control" id="estimasi" value="{{ $data->target_selesai ? \Carbon\Carbon::parse($data->target_selesai)->toDateString() : '' }}">
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
                                            <th>Progress</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($projects as $keys => $project)
                                            {{-- @php
                                                dd(getTotalProgressVendor($data->id, $project->id_vendor,3));
                                            @endphp --}}
                                            <tr>
                                                <td>{{ $project->vendors->name }}</td>

                                                <td>{{ getTotalProgressVendor($data->id, $project->id_vendor,3) }} / {{ getTotalProgressVendor($data->id, $project->id_vendor,null) }}</td>
                                                <td>
                                                    <div class="d-flex justify-contetn-center gap-3">
                                                        <a href="{{ route('on_progress.pekerjaan-vendor.all',[$project->id_vendor,$data->id]) }}" class="btn btn-warning btn-sm">
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
                                        <div class="d-flex gap-3">
                                            <a href="{{ route('on_progres.detail-worker',$data->id) }}" class="btn btn-primary btn-block w-100 rounded-3 border-0">
                                                <div class="d-flex justify-content-between align-items-end">
                                                    <div class="fs-5 text-start">
                                                        Job Progress<br>
                                                        @if (!getTotalProgressPekerjaan($data->id))
                                                            0 / 0
                                                        @else
                                                            {{ getTotalProgressPekerjaan($data->id,3) }} / {{ getTotalProgressPekerjaan($data->id) }}
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <i><img src="{{asset('assets/images/login.svg')}}" style="width: 30px;"></i>
                                                    </div>
                                                </div>
                                            </a>
                                            @hasrole('BOD')
                                                @if ($status > 0)
                                                    <button class="btn btn-block w-100 rounded-3 border-0 text-white" style="background: grey;" disabled>
                                                        <div class="d-flex justify-content-between align-items-end">
                                                            <div class="fs-5 text-start">
                                                                Approval Complete<br>
                                                            </div>
                                                            <div>
                                                                <i><img src="{{asset('assets/images/like.svg')}}" style="width: 30px;"></i>
                                                            </div>
                                                        </div>
                                                    </button>
                                                @else
                                                    <button class="btn btn-success btn-block w-100 rounded-3 border-0" id="btn-approval">
                                                        <div class="d-flex justify-content-between align-items-end">
                                                            <div class="fs-5 text-start">
                                                                Approval Complete<br>
                                                            </div>
                                                            <div>
                                                                <i><img src="{{asset('assets/images/like.svg')}}" style="width: 30px;"></i>
                                                            </div>
                                                        </div>
                                                    </button>
                                                @endif
                                            @endhasrole
                                        </div>
                                        <div class="d-flex gap-3">
                                            <a href="{{ route('on_progres.tagihan.all',$data->id) }}" class="btn btn-primary flex-fill btn-block  rounded-3 border-0" style="background: #FFBC39;">
                                                <div class="d-flex justify-content-between align-items-end">
                                                    <div class="fs-5 text-start">
                                                        Vendor <br>
                                                       <strong>Bills</strong>
                                                    </div>
                                                    <div>
                                                        <i><img src="{{asset('assets/images/login.svg')}}" style="width: 30px;"></i>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="{{ route('on_progres.tagihan-customer',$data->id) }}" class="btn btn-primary flex-fill btn-block rounded-3 border-0" style="background: #FFBC39;">
                                                <div class="d-flex justify-content-between align-items-end">
                                                    <div class="fs-5 text-start">
                                                        Customer <br>
                                                        <strong>Bills</strong>
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

            $('#btn-approval').on('click',function(){
                let id = '{{ $data->id }}';
                let url = '{{ route('on_progres.approval-project',':id') }}';
                let urlReplace = url.replace(':id',id);
                Swal.fire({
                    title: "Are you sure?",
                    // text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, approved it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                       $.ajax({
                            url : urlReplace,
                            method : 'GET'
                       }).then(ress => {
                            if(ress.status === 200){
                                alertToast('success',ress.msg)
                                location.href = '{{ route('complete') }}'
                            }
                       })
                    }
                });
            });

            $('#estimasi').on('change',function(){
                let id = $('#id').val();
                let tanggal = $(this).val();
                $.ajax({
                    url : '{{ route('ajax.update-estimasi-project') }}',
                    method : 'POST',
                    data : {
                        _token : '{{ csrf_token() }}',
                        id : id,
                        tanggal : tanggal
                    }
                }).then(ress => {
                    if(ress.status === 200){
                        alertToast('success',ress.msg);
                    }
                }).catch(err => {
                    alertToast('error', err.message);
                })
            })

            const alertToast = (icon,message) => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                Toast.fire({
                    icon: icon,
                    title: message
                })
            }
        })
    </script>
@endsection
