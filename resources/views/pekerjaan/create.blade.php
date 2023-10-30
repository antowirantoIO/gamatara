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
                                <form action="{{route('pekerjaan.store')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="nama_pekerjaan" class="form-label">Nama Pekerjaan</label>
                                                <input type="text" name="name" class="form-control" id="name" placeholder="Masukkan Nama Pekerjaan">
                                                @if ($errors->has('name'))
                                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                                @endif
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
                                                <label for="harga_vendor" class="form-label">Harga Vendor</label>
                                                <input type="text" name="harga_vendor" class="form-control" id="harga_vendor" placeholder="1.000.000">
                                            </div>
                                        </div>  
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="harga_customer" class="form-label">Harga Customer</label>
                                                <input type="text" name="harga_customer" class="form-control" id="harga_customer" placeholder="1.000.000">
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

@section('scripts')
<script>
    var hargaCustomer = document.getElementById('harga_customer');
        hargaCustomer.addEventListener('keyup', function(e){
            hargaCustomer.value = formatRupiah(this.value, 'Rp. ');
        });

    var hargaVendor = document.getElementById('harga_vendor');
        hargaVendor.addEventListener('keyup', function(e) {
            hargaVendor.value = formatRupiah(this.value, 'Rp. ');
        });

		function formatRupiah(angka, prefix){
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
		}
</script>
@endsection
