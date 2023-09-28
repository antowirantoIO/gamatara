@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('user')}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; edit User</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{route('user.updated', $data->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="User" class="form-label">Nama</label>
                                                <input type="text" name="name" value="{{$data->name}}" class="form-control" id="name" placeholder="Masukkan Nama User">
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="nomor_telpon" class="form-label">Nomor Telpon</label>
                                                <input type="number" name="nomor_telpon" value="{{$data->nomor_telpon}}" class="form-control" id="nomor_telpon" placeholder="Masukkan Nomor Telpon">
                                            </div>
                                        </div>   
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <div>
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" name="email" value="{{$data->email}}" class="form-control form-control-icon" id="email" placeholder="Masukkan Email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="jabatan" class="form-label">Jabatan</label>
                                                <input type="text" name="jabatan" value="{{$data->jabatan}}" class="form-control" id="jabatan" placeholder="Jabatan">
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="password" class="form-label">Password <span style='font-size:10px'>(Only For Change)</span></label>
                                                <input type="password" name="password" class="form-control" id="password" placeholder="Masukkan Password">
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="konfirmasi_password" class="form-label">Konfirmasi Password</label>
                                                <input type="password" name="konfirmasi_password" class="form-control" id="konfirmasi_password" placeholder="Masukkan Konfirmasi Password">
                                            </div>
                                        </div>
                                        
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                                            <button class="btn btn-primary" style="margin-right: 10px;">Save</button>
                                            <a href="{{route('user')}}" class="btn btn-danger">Cancel</a>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> 
    </div>
</div>

@endsection

