@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('pekerjaan')}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Pekerjaan</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{route('pekerjaan.updated', $data->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="kategori_perkerjaan" class="form-label">Kategori Pekerjaan</label>
                                                <input type="text" name="kategori_pekerjaan" value="{{$data->kategori_pekerjaan}}" class="form-control" id="kategori_pekerjaan" placeholder="Masukkan Kategori Pekerjaan">
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="sub_kategori_pekerjaan" class="form-label">Sub Kategori Pekerjaan</label>
                                                <input type="text" name="sub_kategori_pekerjaan" value="{{$data->sub_kategori_pekerjaan}}" class="form-control" id="sub_kategori_pekerjaan" placeholder="Masukkan Sub Kategori Pekerjaan">
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="jenis_pekerjaan" class="form-label">Jenis Pekerjaan</label>
                                                <input type="text" name="jenis_pekerjaan" value="{{$data->jenis_pekerjaan}}" class="form-control" id="jenis_pekerjaan" placeholder="Masukkan Jenis Pekerjaan">
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="detailother" class="form-label">Detail / Other</label>
                                                <input type="text" name="detailother" value="{{$data->detailother}}" class="form-control" id="detailother" placeholder="Masukkan Detail / Other">
                                            </div>
                                        </div>      
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="length" class="form-label">Length (mm)</label>
                                                <input type="text" name="length" value="{{$data->length}}" class="form-control" id="length" placeholder="1.00">
                                            </div>
                                        </div>                 
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="width" class="form-label">Width (mm)</label>
                                                <input type="text" name="width" value="{{$data->width}}" class="form-control" id="width" placeholder="1.00">
                                            </div>
                                        </div>   
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="thick" class="form-label">Thick (mm)</label>
                                                <input type="text" name="thick" value="{{$data->thick}}" class="form-control" id="thick" placeholder="1.00">
                                            </div>
                                        </div>   
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="unit" class="form-label">Unit</label>
                                                <input type="text" name="unit" value="{{$data->unit}}" class="form-control" id="unit" placeholder="m2">
                                            </div>
                                        </div>   
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="harga_vendor" class="form-label">Harga Vendor</label>
                                                <input type="text" name="harga_vendor" value="{{$data->harga_vendor}}" class="form-control" id="harga_vendor" placeholder="Rp.000.000">
                                            </div>
                                        </div>  
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="harga_customer" class="form-label">Harga Customer</label>
                                                <input type="text" name="harga_customer" value="{{$data->harga_customer}}" class="form-control" id="harga_customer" placeholder="Rp.000.000">
                                            </div>
                                        </div>  
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="convert" class="form-label">Convert</label>
                                                <input type="text" name="convert" value="{{$data->convert}}" class="form-control" id="convert" placeholder="100/100">
                                            </div>
                                        </div>  
                                        
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                                            <button class="btn btn-primary" style="margin-right: 10px;">Save</button>
                                            <a href="{{route('pekerjaan')}}" class="btn btn-danger">Cancel</a>
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
