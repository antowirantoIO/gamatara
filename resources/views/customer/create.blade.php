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
                                <form action="{{route('customer.store')}}" id="npwpForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="customer" class="form-label">Nama Customer</label>
                                                <input type="text" name="name" class="form-control" id="name" placeholder="Masukkan Nama Customer">
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="contact_person" class="form-label">Contact Person</label>
                                                <input type="number" name="contact_person" class="form-control" id="contact_person" placeholder="Masukkan Contact Person">
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="alamat" class="form-label">Alamat</label>
                                                <input type="text" name="alamat" class="form-control" id="alamat" placeholder="Masukkan Nomor Contact Person">
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="nomor_contact_person" class="form-label">Nomor Contact Person</label>
                                                <input type="number" name="nomor_contact_person" class="form-control" id="nomor_contact_person" placeholder="Masukkan Nomor Contact Person">
                                            </div>
                                        </div>                    
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <div>
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" name="email" class="form-control form-control-icon" id="email" placeholder="Masukkan Email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="npwp" class="form-label">NPWP</label>
                                                <input type="text" name="npwp" class="form-control" placeholder="Masukkan NPWP">
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

        function formatNPWP() {
            var input = document.getElementById('npwp');
            var value = input.value.replace(/\D/g, ''); // Hanya mengambil digit angka
            var formattedValue = '';

            if (value.length >= 2) {
                formattedValue += value.substr(0, 2) + '.';
            }

            if (value.length >= 5) {
                formattedValue += value.substr(2, 3) + '.';
            }

            if (value.length >= 8) {
                formattedValue += value.substr(5, 3) + '.';
            }

            if (value.length >= 12) {
                formattedValue += value.substr(8, 1) + '-';
                formattedValue += value.substr(9, 3) + '.';
            }

            if (value.length >= 15) {
                formattedValue += value.substr(12, 3);
            }

            input.value = formattedValue;
        }

        var npwpInput = document.getElementById('npwp');
        npwpInput.addEventListener('input', formatNPWP);
    </script>

</script>
@endsection
