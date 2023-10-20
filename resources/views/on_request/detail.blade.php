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
                            <h4 class="mb-0 ml-2"> &nbsp; Detail Request</h4>
                        </div>
                        <div class="mt-3 mt-lg-0 ml-lg-auto">
                            <button class="btn btn-danger" id="export-button">
                                <span>
                                    <i><img src="{{asset('assets/images/directbox-send.svg')}}" style="width: 15px;"></i>
                                </span> &nbsp; Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">

                                <form action="{{route('on_request.updated',$data->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="nama_project" class="form-label">Nama Project</label>
                                                <input type="hidden" name="on_request_id" value="{{$data->id}}" id="on_request_id">
                                                <input type="text" name="nama_project" value="{{$data->nama_project}}" class="form-control" id="nama_project" placeholder="Masukkan Nama Project">
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="nama_project" class="form-label">Nama Project Manajer</label>
                                                <select name="pm_id" id="pm_id" class="form-control">
                                                    <option value="">Pilih Project Manager</option>
                                                    @foreach($pm as $p)
                                                    <option value="{{$p->id}}" {{ $p->id == $data->pm_id ? 'selected' : '' }}>{{$p->karyawan->name ?? ''}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <label for="nama_customer" class="form-label">Nama Customer</label>
                                            <div class="input-group">
                                                <input type="text" id="customer_name" name="id_customer" value="{{$getCustomer->name}}" placeholder="Nama Customer" class="form-control" />
                                                <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalgrid">+</button>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="lokasi_project" class="form-label">Lokasi Project</label>
                                                <select name="lokasi_project" id="lokasi_project" class="form-control">
                                                    <option value="">Pilih Lokasi Project</option>
                                                    @foreach($lokasi as $l)
                                                    <option value="{{$l->id}}" {{ $l->id == $data->id_lokasi_project ? 'selected' : '' }}>{{$l->name}}</option>
                                                    @endforeach
                                                </select>
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
                                                <label for="nomor_contact_person" class="form-label">Nomor Contact Person</label>
                                                <input type="text" name="nomor_contact_person" value="{{$data->nomor_contact_person}}"  class="form-control" id="nomor_contact_person" placeholder="Masukkan Nomor Contact Person" maxlength="13" placeholder="Masukkan Nomor Contact Person" oninput="this.value=this.value.slice(0,this.maxLength)">
                                            </div>
                                        </div>          
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="alamat" class="form-label">Alamat Customer</label>
                                                <input type="text" class="form-control" value="{{$getCustomer->alamat}}" id="alamat" readonly>
                                            </div>
                                        </div>   
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="npwp" class="form-label">NPWP</label>
                                                <input type="text" class="form-control" id="npwps" value="{{$getCustomer->npwp}}" readonly>
                                            </div>
                                        </div>   
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="displacement" class="form-label">Displacement Kapal</label>
                                                <input type="text" name="displacement" value="{{$data->displacement}}" class="form-control" id="displacement" placeholder="Masukkan Displacement Kapal">
                                            </div>
                                        </div>   
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="Jenis Kapal" class="form-label">Jenis Kapal</label>
                                                    <select name="jenis_kapal" name="jenis_kapal" id="jenis_kapal" class="form-control">
                                                        <option value="">Pilih Jenis Kapal</option>
                                                        @foreach($jenis_kapal as $l)
                                                        <option value="{{$l->id}}" {{ $l->id == $data->id_jenis_kapal ? 'selected' : '' }}>{{$l->name}}</option>
                                                        @endforeach
                                                    </select>
                                            </div>
                                        </div> 

                                        <div class="col-xxl-6 col-md-6">
                                            <label for="keluhan">Request</label>
                                            <input type="hidden" name="keluhan" id="keluhanInput" value="">
                                            <textarea id="keluhan" rows="4" cols="50" class="form-control"></textarea>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label style="color:white;"><br><br><br></label>
                                                <button type="button" id="tambahKeluhan" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                        <div id="tabelKeluhanWrapper">
                                            <table id="tabelKeluhan" class="table table-bordered">
                                                <thead style="background-color:#194BFB;color:#FFFFFF">
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Keluhan</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                        @foreach($keluhan as $key => $complaint)
                                                        <tr>
                                                            <td><?php echo $key + 1; ?></td>
                                                            <td><?php echo $complaint->keluhan; ?></td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-sm btnHapus" data-keluhan-id="{{ $complaint->id }}">
                                                                    <span>
                                                                        <i><img src="{{asset('assets/images/trash.svg')}}" style="width: 15px;"></i>
                                                                    </span>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                </tbody>
                                            </table>
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

<!--modal -->
<div class="modal fade" id="exampleModalgrid" tabindex="-1" aria-labelledby="exampleModalgridLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{route('customer.store')}}" id="npwpForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">Tambah Customer</h5>
                        <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                            <button class="btn btn-primary" style="margin-right: 10px;" id="saveCustomerButton">Save</button>
                            <a class="btn btn-danger" type="button" data-bs-dismiss="modal" aria-label="Close">Cancel</a>
                        </div>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="row gy-4">
                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <label for="customer" class="form-label">Nama Customer</label>
                                    <input type="text" name="name" class="form-control" placeholder="Masukkan Nama Customer">
                                </div>
                            </div>
                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <label for="contact_person" class="form-label">Contact Person</label>
                                    <input type="text" name="contact_person" class="form-control" placeholder="Masukkan Contact Person">
                                </div>
                            </div>
                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <input type="text" name="alamat" class="form-control" placeholder="Masukkan Nomor Contact Person">
                                </div>
                            </div>
                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <label for="nomor_contact_person" class="form-label">Nomor Contact Person</label>
                                    <input type="number" name="nomor_contact_person" class="form-control" placeholder="Masukkan Nomor Contact Person" maxlength="13" placeholder="Masukkan Nomor Contact Person" oninput="this.value=this.value.slice(0,this.maxLength)">
                                </div>
                            </div>                    
                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <div>
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control form-control-icon" placeholder="Masukkan Email">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <label for="npwp" class="form-label">NPWP</label>
                                    <input type="text" name="npwp" id="npwp" class="form-control" placeholder="Masukkan NPWP">
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
            </form>
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

    // Fungsi untuk menyimpan data formulir utama
    function simpanDataFormUtama() {
        var dataForm = {
            nama_project: document.getElementById('nama_project').value,
            id_customer: document.getElementById('customer_name').value,
            lokasi_project: $('#lokasi_project').find(":selected").val(),
            contact_person: document.getElementById('contact_person').value,
            nomor_contact_person: document.getElementById('nomor_contact_person').value,
            displacement: document.getElementById('displacement').value,
            jenis_kapal: document.getElementById('jenis_kapal').value,
            pm_id: document.getElementById('pm_id').value,
        };

        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch('{{ route('on_request.updated', $data->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(dataForm),
        })
        .then(function (response) {
            // Handle respons Anda di sini
        })
        .catch(function (error) {
            console.error(error);
        });
    }

    // Simpan data formulir utama secara otomatis saat ada perubahan pada input
    document.querySelectorAll('.form-control').forEach(function (input) {
        input.addEventListener('input', function () {
            simpanDataFormUtama();
        });
    });

    $('.form-control').on('change', function () {
        simpanDataFormUtama();
    });

    //hapus keluhan
    document.querySelectorAll('.btnHapus').forEach(function (button) {
        button.addEventListener('click', function () {
            var keluhanId = this.getAttribute('data-keluhan-id');

            fetch('{{ url('keluhan') }}/' + keluhanId, {
                method: 'get',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            .then(function (response) {
                if (response.status === 200) {
                    var row = button.closest('tr');
                    row.remove();
                    refreshNomorUrut();
                    Swal.fire(
                        '',
                        'Keluhan Berhasil Dihapus',
                        'success'
                    )
                } else {
                    console.error('Gagal menghapus keluhan');
                }
            })
            .catch(function (error) {
                console.error('Terjadi kesalahan:', error);
            });
        });
    });

    // Fungsi untuk menghapus keluhan sehabis taambah data
    function hapusKeluhan(keluhanId) {
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('{{ url('keluhan') }}/' + keluhanId, {
            method: 'get',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
        .then(function (response) {
            if (response.status === 200) {
                var row = document.querySelector('[data-keluhan-id="' + keluhanId + '"]').closest('tr');
                row.remove();
                refreshNomorUrut();
                Swal.fire(
                    '',
                    'Keluhan Berhasil Dihapus',
                    'success'
                )
            } else {
                console.error('Gagal menghapus keluhan');
            }
        })
        .catch(function (error) {
            console.error('Terjadi kesalahan:', error);
        });
    }

    //tambah customer
    $(document).ready(function () {
        $("#saveCustomerButton").click(function (e) {
            e.preventDefault();

            var form = $("#npwpForm");
            var formData = form.serialize();

            $.ajax({
                type: "POST",
                url: form.attr("action"),
                data: formData,
                success: function (response) {
                    if (response) {
                        $("#exampleModalgrid").modal("hide");
                    
                        form[0].reset();
               
                        Swal.fire(
                            '',
                            'Customer Telah Berhasil Ditambahkan',
                            'success'
                        )
                    } else {
                        alert("Validation error: " + response.message);
                    }
                },
                error: function (error) {
                    alert("An error occurred while saving the customer.");
                }
            });
        });
    });

    //get nama customer dan set ke inputan masing2
    var route = "{{ url('customer') }}";
    $('#customer_name').typeahead({
        source: function (query, process) {
            return $.get(route, { query: query }, function (data) {
                return process(data.map(function(customer) {
                    return customer.name;
                }));
            });
        },
        updater: function (item) {
            $.get(route, { query: item }, function (customerData) {
                if (customerData.length > 0) {
                    var selectedCustomer = customerData[0];
                    $('#contact_person').val(selectedCustomer.contact_person);
                    $('#nomor_contact_person').val(selectedCustomer.nomor_contact_person);
                    $('#alamat').val(selectedCustomer.alamat);
                    $('#npwps').val(selectedCustomer.npwp);
                }
            });
            return item;
        }
    });

    //button tambah request keluhan
    var addButton = document.getElementById("tambahKeluhan");
    addButton.addEventListener("click", tambahKeluhan);

    function refreshNomorUrut() {
        var tabel = document.getElementById("tabelKeluhan");
        var rows = tabel.getElementsByTagName("tr");

        for (var i = 1; i < rows.length; i++) {
            rows[i].getElementsByTagName("td")[0].textContent = i;
        }
    }

    function tambahKeluhan() {
        var keluhanInput = document.getElementById("keluhan").value;
        var id = document.getElementById("on_request_id").value;
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (keluhanInput.trim() !== "") {
            fetch('{{ route('keluhan.store', '') }}/' + {{ $data->id }}, {
                method: 'post',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ keluhan: keluhanInput }),
            })

            .then(function (response) {
                if (response.status === 200) {
                    Swal.fire(
                        '',
                        'Keluhan Berhasil Ditambahkan',
                        'success'
                    )
                    return response.json();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Gagal menambahkan keluhan!',
                    });
                    throw new Error('Gagal menambahkan keluhan');
                }
            })
            .then(function (data) {
                var tabel = document.getElementById("tabelKeluhan").getElementsByTagName('tbody')[0];
                var newRow = tabel.insertRow(tabel.rows.length);
                var cell1 = newRow.insertCell(0);
                var cell2 = newRow.insertCell(1);
                var cell3 = newRow.insertCell(2);

                cell1.innerHTML = tabel.rows.length;
                cell2.innerHTML = keluhanInput;
                cell3.innerHTML = '<button type="button" class="btn btn-danger btn-sm btnHapus" data-keluhan-id="' + data.id + '"><span><i><img src="{{asset("assets/images/trash.svg")}}" style="width: 15px;"></i></span></button>';

                document.getElementById("keluhan").value = "";

                var btnHapus = newRow.querySelector(".btnHapus");
                btnHapus.addEventListener("click", function () {
                    hapusKeluhan(data.id);
                });

                refreshNomorUrut();
            })
            .catch(function (error) {
                console.error(error);
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Keluhan Tidak Boleh Kosong!',
            });
        }
    }

    //hapus data yg sudah ada sebelumnya 
    var btnHapus = document.querySelectorAll(".btnHapus");
    btnHapus.forEach(function (button) {
        button.addEventListener("click", function () {
            var complaintId = this.getAttribute("data-complaint-id");
            var row = this.parentNode.parentNode;
            row.parentNode.removeChild(row);
        });
    });

    //simpan data keluhan ke $keluhan
    function simpanData() {
        var keluhanRows = document.getElementById("tabelKeluhan").getElementsByTagName('tbody')[0].rows;
        var keluhanData = [];

        for (var i = 0; i < keluhanRows.length; i++) {
            var keluhan = keluhanRows[i].cells[1].innerText;
            keluhanData.push(keluhan);
        }
        var keluhanInput = document.getElementById("keluhanInput");
        keluhanInput.value = JSON.stringify(keluhanData);
    }

    //export detail
    $('#export-button').on('click', function(event) {
            event.preventDefault(); 

            var url = '{{ route("on_request.exportDetail", $data->id) }}?';

            $('.loading-overlay').show();

            window.location.href = url;

            setTimeout(hideOverlay, 2000);
        });

    //untuk semua select menggunakan select2
    $(function () {
        $("select").select2();
    });
    </script>
@endsection
