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
                            <h4 class="mb-0 ml-2"> &nbsp; On Request</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{route('on_request.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="nama_project" class="form-label">Nama Project</label>
                                                <input type="text" name="nama_project" class="form-control" id="nama_project" placeholder="Masukkan Nama Project">
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="code" class="form-label">Kode Project</label>
                                                <input type="text" name="code" class="form-control" id="code" placeholder="Masukkan Kode Project">
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <label for="nama_customer" class="form-label">Nama Customer</label>
                                                <div class="input-group">
                                                    <input id="customer_name" type="text" spellcheck=false autocomplete="off" autocapitalize="off" class="form-control">
                                                <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalgrid">+</button>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="lokasi_project" class="form-label">Lokasi Project</label>
                                                <select name="lokasi_project" id="lokasi_project" class="form-control">
                                                    <option value="">Pilih Lokasi Project</option>
                                                    @foreach($lokasi as $l)
                                                    <option value="{{$l->id}}">{{$l->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>      
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="contact_person" class="form-label">Contact Person</label>
                                                <input type="text" name="contact_person" class="form-control" id="contact_person" placeholder="Masukkan Contact Person">
                                            </div>
                                        </div>         
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="nomor_contact_person" class="form-label">Nomor Contact Person</label>
                                                <input type="text" name="nomor_contact_person" class="form-control" id="nomor_contact_person" placeholder="Masukkan Nomor Contact Person">
                                            </div>
                                        </div>          
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="alamat" class="form-label">Alamat Customer</label>
                                                <input type="text" class="form-control" id="width" readonly>
                                            </div>
                                        </div>   
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="npwp" class="form-label">NPWP</label>
                                                <input type="text" class="form-control" id="npwp" readonly>
                                            </div>
                                        </div>   
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="displacement" class="form-label">Displacement Kapal</label>
                                                <input type="text" name="displacement" class="form-control" id="displacement" placeholder="Masukkan Displacement Kapal">
                                            </div>
                                        </div>   
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="Jenis Kapal" class="form-label">Jenis Kapal</label>
                                                    <select name="jenis_kapal" id="jenis_kapal" class="form-control">
                                                        <option value="">Pilih Jenis Kapal</option>
                                                        @foreach($jenis_kapal as $l)
                                                        <option value="{{$l->id}}">{{$l->name}}</option>
                                                        @endforeach
                                                    </select>
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

    <div class="modal fade" id="exampleModalgrid" tabindex="-1" aria-labelledby="exampleModalgridLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">Grid Modals</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);">
                        <div class="row g-3">
                            <div class="col-xxl-6">
                                <div>
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstName" placeholder="Enter firstname">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-xxl-6">
                                <div>
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastName" placeholder="Enter lastname">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <label class="form-label">Gender</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                        <label class="form-check-label" for="inlineRadio1">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                        <label class="form-check-label" for="inlineRadio2">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3">
                                        <label class="form-check-label" for="inlineRadio3">Others</label>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-xxl-6">
                                <label for="emailInput" class="form-label">Email</label>
                                <input type="email" class="form-control" id="emailInput" placeholder="Enter your email">
                            </div>
                            <!--end col-->
                            <div class="col-xxl-6">
                                <label for="passwordInput" class="form-label">Password</label>
                                <input type="password" class="form-control" id="passwordInput" value="451326546" placeholder="Enter password">
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
            </div>
        </div>
    </div>




@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script async src="//code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
    <script>
        $(function () {
            $("select").select2();
        });

        $(function () {
        // Data customer dari controller
       
        
        $(document).ready(function() {
            var customerData = <?php echo json_encode($customer); ?>;
    $("#customer_name").autocomplete({
        source: customerData,
        minLength: 1
    });
});
    });
    </script>
@endsection
