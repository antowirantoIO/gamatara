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
                            <h4 class="mb-0 ml-2"> &nbsp; User</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{route('user.store')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="karyawan">Karyawan</label>
                                                <select name="karyawan" id="karyawan" class="form-control">
                                                    <option value="">Pilih Karyawan</option>
                                                    @foreach($karyawan as $r)
                                                        <option value="{{$r->id}}" {{ $r->id == old('karyawan') ? 'selected' : '' }} data-email="{{$r->email}}" data-nomor-telepon="{{$r->nomor_telpon}}">{{ $r->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if ($errors->has('karyawan'))
                                                <span class="text-danger">{{ $errors->first('karyawan') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="nomor_telpon">Nomor Telpon</label>
                                                <input type="number" name="nomor_telpon" id="nomor_telpon" value="{{ old('nomor_telpon') }}" maxlength="13" class="form-control" placeholder="Masukkan Nomor Telpon" oninput="this.value=this.value.slice(0,this.maxLength)">
                                            </div>
                                            @if ($errors->has('nomor_telpon'))
                                                <span class="text-danger">{{ $errors->first('nomor_telpon') }}</span>
                                            @endif
                                        </div> 
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="email">Email</label>
                                                <input type="email" name="email" id="email" autocomplete="new-email" value="{{ old('email') }}" class="form-control" placeholder="Masukkan Email">
                                            </div>
                                            @if ($errors->has('email'))
                                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="role" class="form-label">Role</label>
                                                <select name="role" id="role" class="form-control">
                                                    <option value="">Pilih role</option>
                                                    @foreach($role as $r)
                                                        <option value="{{$r->id}}" {{ $r->id == old('role') ? 'selected' : '' }}>{{ $r->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if ($errors->has('role'))
                                                <span class="text-danger">{{ $errors->first('role') }}</span>
                                            @endif
                                        </div>  
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="ttd">Tanda Tangan <span style='font-size:10px'>(Format hanya PNG Max 1Mb)</span></label>
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
                                        <div class="col-xxl-6 col-md-6"></div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="password">Password</label>
                                                <input type="password" name="password" id="password" autocomplete="new-password" class="form-control" placeholder="Masukkan Password">
                                                <span id="passwordLengthError" class="text-danger" style="display:none;">Password harus memiliki setidaknya 6 karakter.</span>
                                            </div>
                                            @if ($errors->has('password'))
                                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <label for="konfirmasi_password">Konfirmasi Password</label>
                                            <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control" placeholder="Masukkan Konfirmasi Password">
                                            <div class="col-12">
                                                <span id="passwordMismatchError" class="text-danger" style="display:none;">Password tidak sesuai.</span>
                                            </div>
                                            @if ($errors->has('konfirmasi_password'))
                                                <span class="text-danger">{{ $errors->first('konfirmasi_password') }}</span>
                                            @endif
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

@section('scripts')
<script>
    $(document).ready(function() {
        var karyawanSelect = $("#karyawan");
        var emailInput = $("#email");
        var nomorTelponInput = $("#nomor_telpon");

        // Inisialisasi Select2
        karyawanSelect.select2();

        karyawanSelect.on("change", function() {
            var selectedOption = karyawanSelect.find(":selected");

            if (selectedOption.length) {
                // Dapatkan atribut data dari option yang dipilih
                var email = selectedOption.data("email");
                var nomorTelepon = selectedOption.data("nomor-telepon");

                // Setel nilai email dan nomor telepon
                emailInput.val(email);
                nomorTelponInput.val(nomorTelepon);
            } else {
                // Jika tidak ada yang dipilih, kosongkan input email dan nomor telepon
                emailInput.val("");
                nomorTelponInput.val("");
            }
        });
    });

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

    //konfirmasi password
    function checkPasswordMatch() {
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('konfirmasi_password').value;
        var konfirmasiPasswordInput = document.getElementById('konfirmasi_password');

        if (password === confirmPassword) {
            document.getElementById('passwordMismatchError').style.display = 'none';
            konfirmasiPasswordInput.classList.remove('is-invalid');
        } else {
            document.getElementById('passwordMismatchError').style.display = 'block';
            konfirmasiPasswordInput.classList.add('is-invalid');
        }
    }
    document.getElementById('konfirmasi_password').addEventListener('input', checkPasswordMatch);

    //password tidak boleh kurang dari 6
    function checkPasswordLength() {
        var passwordInput = document.getElementById('password');
        var passwordLengthError = document.getElementById('passwordLengthError');
        
        if (passwordInput.value.length < 6) {
            passwordLengthError.style.display = 'block';
            passwordInput.classList.add('is-invalid');
        } else {
            passwordLengthError.style.display = 'none';
            passwordInput.classList.remove('is-invalid');
        }
    }
    document.getElementById('password').addEventListener('input', checkPasswordLength);

    //untuk semua select menggunakan select2
    $(function () {
        $("select").select2();
    });
</script>
@endsection

