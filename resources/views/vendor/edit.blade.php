@extends('index')

@section('content')

<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('vendor')}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Vendor</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{route('vendor.updated',$data->id)}}" id="npwpForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row gy-4">
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="customer" class="form-label">Nama Customer</label>
                                                <input type="text" name="name" value="{{$data->name}}" class="form-control" id="name" placeholder="Masukkan Nama Customer">
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="contact_person" class="form-label">Contact Person</label>
                                                <input type="text" name="contact_person" value="{{$data->contact_person}}" class="form-control" id="contact_person" placeholder="Masukkan Contact Person">
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="alamat" class="form-label">Alamat</label>
                                                <input type="text" name="alamat"  value="{{$data->alamat}}" class="form-control" id="alamat" placeholder="Masukkan Nomor Contact Person">
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="nomor_contact_person" class="form-label">Nomor Contact Person</label>
                                                <input type="number" class="form-control" name="nomor_contact_person" value="{{$data->nomor_contact_person}}" id="nomor_contact_person" placeholder="Masukkan Nomor Contact Person">
                                            </div>
                                        </div>                    
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <div>
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" name="email"  value="{{$data->email}}" class="form-control" id="email" placeholder="Masukkan Email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="npwp" class="form-label">NPWP</label>
                                                <input type="text" name="npwp" value="{{$data->npwp}}" class="form-control" id="npwp" placeholder="Masukkan NPWP">
                                            </div>
                                        </div> 
                                        
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                                            <button class="btn btn-primary" style="margin-right: 10px;">Save</button>
                                            <a href="{{route('customer')}}" class="btn btn-danger">Cancel</a>
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
</div>
@endsection