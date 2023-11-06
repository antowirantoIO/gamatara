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
                                <form action="{{route('vendor.store')}}" id="npwpForm" method="POST" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="customer">Vendor Name</label>
                                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="Enter Vendor Name">
                                                @if ($errors->has('name'))
                                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="contact_person">Contact Person</label>
                                                <input type="text" name="contact_person" class="form-control" id="contact_person" value="{{ old('contact_person') }}" placeholder="Enter Contact Person">
                                                @if ($errors->has('contact_person'))
                                                    <span class="text-danger">{{ $errors->first('contact_person') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="alamat">Address</label>
                                                <input type="text" name="alamat" id="alamat" value="{{ old('alamat') }}" class="form-control" placeholder="Enter Address">
                                                @if ($errors->has('alamat'))
                                                    <span class="text-danger">{{ $errors->first('alamat') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="nomor_contact_person">Contact Person Phone</label>
                                                <input type="number" name="nomor_contact_person" class="form-control" id="nomor_contact_person" value="{{ old('nomor_contact_person') }}" maxlength="13" placeholder="Enter Contact Person Phone" oninput="this.value=this.value.slice(0,this.maxLength)">
                                                @if ($errors->has('nomor_contact_person'))
                                                    <span class="text-danger">{{ $errors->first('nomor_contact_person') }}</span>
                                                @endif
                                            </div>
                                        </div>                    
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <div>
                                                    <label for="email">Email</label>
                                                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control form-control-icon" placeholder="Enter Email">
                                                    @if ($errors->has('email'))
                                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="npwp">NPWP</label>
                                                <input type="text" name="npwp" id="npwp" value="{{ old('npwp') }}" maxlength="16" class="form-control" placeholder="Enter NPWP">
                                                @if ($errors->has('npwp'))
                                                    <span class="text-danger">{{ $errors->first('npwp') }}</span>
                                                @endif
                                            </div>
                                        </div> 
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="kategori_vendor">Category Vendor</label>
                                                <select name="kategori_vendor" id="kategori_vendor" class="form-control">
                                                    <option value="">Pilih Vendor</option>
                                                    @foreach($kategori_vendor as $k)
                                                        <option value="{{ $k->id }}"  {{ $k->id == old('kategori_vendor') ? 'selected' : '' }}>{{ $k->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('kategori_vendor'))
                                                    <span class="text-danger">{{ $errors->first('kategori_vendor') }}</span>
                                                @endif
                                            </div>
                                        </div> 
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="ttd">Signature <span style='font-size:10px'>(PNG format only Max 1Mb)</span></label>
                                                <br>
                                                    <img src="{{ asset('assets/images/nophoto.jpg') }}" alt="Tanda Tangan Preview" class="img-thumbnail" id="ttd_preview" style="max-width: 150px;">
                                                <br><br>
                                                <input type="file" name="ttd" id="ttd" value="{{ old('ttd') }}" class="form-control">
                                                <input type="hidden" name="ttd_base64" id="ttd_base64">
                                                @if ($errors->has('ttd'))
                                                    <span class="text-danger">{{ $errors->first('ttd') }}</span>
                                                @endif
                                            </div>
                                        </div> 
                                        
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                                            <button class="btn btn-primary" style="margin-right: 10px;">Save</button>
                                            <a href="{{route('vendor')}}" class="btn btn-danger">Cancel</a>
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

    document.getElementById('ttd').addEventListener('change', function (e) {
        const fileInput = e.target;
        const ttdPreview = document.getElementById('ttd_preview');
        const ttdBase64Input = document.getElementById('ttd_base64');

        if (fileInput.files && fileInput.files[0]) {
            const file = fileInput.files[0];
            const allowedTypes = ['image/png'];
            const maxSize = 1024 * 1024; // 1MB

            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran file melebihi batas maksimum (1MB).',
                });
                fileInput.value = ""; // Reset input jika file melebihi ukuran maksimum
                return;
            }

            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format File Tidak Valid',
                    text: 'Hanya file PNG yang diizinkan.',
                });
                fileInput.value = ""; // Reset input jika file tidak valid
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {
                ttdPreview.src = e.target.result;
                ttdBase64Input.value = e.target.result.split(',')[1]; // Simpan base64 dalam input tersembunyi
            };

            reader.readAsDataURL(file);
        } else {
            // Jika tidak ada gambar yang dipilih, tampilkan gambar default
            ttdPreview.src = "{{ asset('assets/nophoto.jpg') }}";
            ttdBase64Input.value = ""; // Hapus base64 jika tidak ada gambar yang dipilih
        }
    });

    //untuk semua select menggunakan select2
    $(function () {
        $("select").select2();
    });
</script>
@endsection

