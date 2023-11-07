@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('customer')}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Customer</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{route('customer.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="customer" class="form-label">Customer Name</label>
                                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="Enter Customer Name">
                                                @if ($errors->has('name'))
                                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="contact_person" class="form-label">Contact Person</label>
                                                <input type="text" name="contact_person" class="form-control" id="contact_person" value="{{ old('contact_person') }}" placeholder="Enter Contact Person">
                                                @if ($errors->has('contact_person'))
                                                    <span class="text-danger">{{ $errors->first('contact_person') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="alamat" class="form-label">Address</label>
                                                <input type="text" name="alamat" id="alamat" value="{{ old('alamat') }}" class="form-control" placeholder="Enter Address">
                                                @if ($errors->has('alamat'))
                                                    <span class="text-danger">{{ $errors->first('alamat') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="nomor_contact_person" class="form-label">Contact Person Phone</label>
                                                <input type="number" name="nomor_contact_person" class="form-control" id="nomor_contact_person" value="{{ old('nomor_contact_person') }}" maxlength="13" placeholder="Enter Contact Person Phone" oninput="this.value=this.value.slice(0,this.maxLength)">
                                                @if ($errors->has('nomor_contact_person'))
                                                    <span class="text-danger">{{ $errors->first('nomor_contact_person') }}</span>
                                                @endif
                                            </div>
                                        </div>                    
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <div>
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control form-control-icon" placeholder="Enter Email">
                                                    @if ($errors->has('email'))
                                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="npwp" class="form-label">NPWP</label>
                                                <input type="text" name="npwp" id="npwp" value="{{ old('npwp') }}" maxlength="16" class="form-control" placeholder="Enter NPWP">
                                                @if ($errors->has('npwp'))
                                                    <span class="text-danger">{{ $errors->first('npwp') }}</span>
                                                @endif
                                            </div>
                                        </div> 
                                        
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                                            <button class="btn btn-primary" style="margin-right: 10px;">Save</button>
                                            <a href="{{route('customer')}}" class="btn btn-danger">Cancel</a>
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

@section('scripts')
<script>
const NPWP = document.getElementById("npwp")
    NPWP.oninput = (e) => {
        e.target.value = autoFormatNPWP(e.target.value);
    }

    function autoFormatNPWP(NPWPString) {
        try {
            var cleaned = ("" + NPWPString).replace(/\D/g, "");
            var match = cleaned.match(/(\d{0,2})?(\d{0,3})?(\d{0,3})?(\d{0,1})?(\d{0,3})?(\d{0,3})$/);
            return [      
                    match[1], 
                    match[2] ? ".": "",
                    match[2], 
                    match[3] ? ".": "",
                    match[3],
                    match[4] ? ".": "",
                    match[4],
                    match[5] ? "-": "",
                    match[5],
                    match[6] ? ".": "",
                    match[6]].join("")
            
        } catch(err) {
            return "";
        }
 }
</script>
@endsection
