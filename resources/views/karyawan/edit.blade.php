@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('karyawan')}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Employee</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{route('karyawan.updated', $data->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="name" class="form-label">Employee Name</label>
                                                <input type="text" name="name" id="name" value="{{ $data->name }}" class="form-control" placeholder="Masukkan Nama Karyawan">
                                                @if ($errors->has('name'))
                                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="jabatan">Job Title</label>
                                                <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan') }}" class="form-control" placeholder="Masukkan Jabatan">
                                            </div>
                                            @if ($errors->has('jabatan'))
                                                <span class="text-danger">{{ $errors->first('jabatan') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="nomor_telpon" class="form-label">Phone</label>
                                                <input type="number" name="nomor_telpon" id="nomor_telpon" value="{{ $data->nomor_telpon }}" class="form-control" maxlength="13" placeholder="Masukkan Nomor Telpon" oninput="this.value=this.value.slice(0,this.maxLength)">
                                                @if ($errors->has('nomor_telpon'))
                                                    <span class="text-danger">{{ $errors->first('nomor_telpon') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="alamat" class="form-label">Address</label>
                                                <input type="text" name="alamat" id="alamat" value="{{ $data->alamat }}" class="form-control" placeholder="Masukkan Alamat">
                                                @if ($errors->has('alamat'))
                                                    <span class="text-danger">{{ $errors->first('alamat') }}</span>
                                                @endif
                                            </div>
                                        </div>                 
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <div>
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" name="email" id="email" value="{{ $data->email }}" class="form-control form-control-icon" placeholder="Masukkan Email">
                                                    @if ($errors->has('email'))
                                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                                            <button class="btn btn-primary" style="margin-right: 10px;">Save</button>
                                            <a href="{{route('karyawan')}}" class="btn btn-danger">Cancel</a>
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