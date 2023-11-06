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
                                <form action="{{route('user.updated', $data->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="karyawan" class="form-label">Employee</label>
                                                <select name="karyawan" id="karyawan" class="form-control">
                                                    <option value="">Choose Employee</option>
                                                    @foreach($karyawan as $r)
                                                        <option value="{{$r->id}}" {{ $r->id == $data->id_karyawan ? 'selected' : '' }}>{{ $r->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if ($errors->has('karyawan'))
                                                <span class="text-danger">{{ $errors->first('karyawan') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="nomor_telpon" class="form-label">Phone</label>
                                                <input type="number" name="nomor_telpon" value="{{$data->nomor_telpon}}" id="nomor_telpon" maxlength="13" class="form-control" placeholder="Masukkan Nomor Telpon" oninput="this.value=this.value.slice(0,this.maxLength)">
                                            </div>
                                        </div>   
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <div>
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" name="email" value="{{$data->email}}" id="email" class="form-control form-control-icon" placeholder="Masukkan Email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="role" class="form-label">Role</label>
                                                <select name="role" id="role" class="form-control">
                                                    <option value="">Choose Role</option>
                                                    @foreach($role as $r)
                                                        <option value="{{$r->id}}" {{ $r->id == $data->id_role ? 'selected' : '' }}>{{ $r->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if ($errors->has('role'))
                                                <span class="text-danger">{{ $errors->first('role') }}</span>
                                            @endif
                                        </div> 
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="ttd">Signature <span style='font-size:10px'>(PNG format only Max 1Mb)</span></label>
                                                <br>
                                                    <img src="data:image/png;base64,{{ $data->ttd }}" alt="Signature Preview" class="img-thumbnail" id="ttd_preview" style="max-width: 150px;">
                                                <br><br>
                                                <input type="file" name="ttd" id="ttd" class="form-control">
                                                <input type="hidden" name="ttd_base64" id="ttd_base64" value="{{ $data->ttd }}">
                                                @if ($errors->has('ttd'))
                                                    <span class="text-danger">{{ $errors->first('ttd') }}</span>
                                                @endif
                                            </div>
                                        </div> 
                                        <div class="col-xxl-6 col-md-6"></div> 
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="password" class="form-label">Password <span style='font-size:10px'>(Only For Change)</span></label>
                                                <input type="password" name="password" id="password" autocomplete="new-password" class="form-control" placeholder="Enter Password">
                                                <span id="passwordLengthError" class="text-danger" style="display:none;">Passwords must have at least 6 characters.</span>
                                            </div>
                                            @if ($errors->has('password'))
                                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <label for="konfirmasi_password" class="form-label">Konfirmasi Password</label>
                                            <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control" placeholder="Enter Confirm Password">
                                            <div class="col-12">
                                                <span id="passwordMismatchError" class="text-danger" style="display:none;">Password does not match.</span>
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
                fileInput.value = "";
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
                ttdBase64Input.value = e.target.result.split(',')[1];
            };

            reader.readAsDataURL(file);
        } else {
            ttdPreview.src = "{{ asset('assets/nophoto.jpg') }}";
            ttdBase64Input.value = "";
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

