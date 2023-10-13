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
                                <form action="{{route('pekerjaan.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="nama_pekerjaan" class="form-label">Nama Pekerjaan Pekerjaan</label>
                                                <input type="text" name="name" class="form-control" id="name" placeholder="Masukkan Nama Pekerjaan">
                                                @if ($errors->has('name'))
                                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>    
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="length" class="form-label">Length (mm)</label>
                                                <input type="text" name="length" class="form-control" id="length" value="1.00" placeholder="1.00">
                                            </div>
                                        </div>                 
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="width" class="form-label">Width (mm)</label>
                                                <input type="text" name="width" class="form-control" id="width" value="1.00" placeholder="1.00">
                                            </div>
                                        </div>   
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="thick" class="form-label">Thick (mm)</label>
                                                <input type="text" name="thick" class="form-control" id="thick" value="1.00" placeholder="1.00">
                                            </div>
                                        </div>   
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="unit" class="form-label">Unit</label>
                                                <input type="text" name="unit" class="form-control" id="unit" placeholder="m2">
                                            </div>
                                        </div>    
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="conversion" class="form-label">Conversion</label>
                                                <input type="text" name="conversion" class="form-control" id="conversion" value="1.00" placeholder="100/100">
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
