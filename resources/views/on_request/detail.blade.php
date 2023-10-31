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
                        <!-- <div class="mt-3 mt-lg-0 ml-lg-auto">
                            <button class="btn btn-danger" id="export-button">
                                <span>
                                    <i><img src="{{asset('assets/images/directbox-send.svg')}}" style="width: 15px;"></i>
                                </span> &nbsp; Export
                            </button>
                        </div> -->
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{route('on_request.updated',$data->id)}}" method="POST" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                    <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                                        <button type="submit" class="btn btn-primary" style="margin-right: 10px;" >Save</button>
                                        <a href="{{route('pekerjaan')}}" class="btn btn-danger">Cancel</a>
                                    </div>

                                    <div class="row gy-4">
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label>Nama Project</label>
                                                <input type="hidden" name="on_request_id" value="{{$data->id}}" id="on_request_id">
                                                <input type="text" name="nama_project" value="{{$data->nama_project}}" class="form-control" id="nama_project" placeholder="Masukkan Nama Project">
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label>Nama Project Manajer</label>
                                                <select name="pm_id" id="pm_id" class="form-control">
                                                    <option value="">Pilih Project Manager</option>
                                                    @foreach($pm as $p)
                                                    <option value="{{$p->id}}" {{ $p->id == $data->pm_id ? 'selected' : '' }}>{{$p->karyawan->name ?? ''}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <label>Nama Customer</label>
                                            <div class="input-group">
                                                <input type="text" id="customer_name" name="id_customer" value="{{$getCustomer->name}}" placeholder="Nama Customer" class="form-control" />
                                                <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalgrid">+</button>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label>Lokasi Project</label>
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
                                                <label>Contact Person</label>
                                                <input type="text" name="contact_person" value="{{$data->contact_person}}" class="form-control" id="contact_person" placeholder="Masukkan Contact Person">
                                            </div>
                                        </div>         
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label>Nomor Contact Person</label>
                                                <input type="text" name="nomor_contact_person" value="{{$data->nomor_contact_person}}"  class="form-control" id="nomor_contact_person" placeholder="Masukkan Nomor Contact Person" maxlength="13" placeholder="Masukkan Nomor Contact Person" oninput="this.value=this.value.slice(0,this.maxLength)">
                                            </div>
                                        </div>          
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label>Alamat Customer</label>
                                                <input type="text" class="form-control" value="{{$getCustomer->alamat}}" id="alamat" readonly>
                                            </div>
                                        </div>   
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label>NPWP</label>
                                                <input type="text" class="form-control" id="npwps" value="{{$getCustomer->npwp}}" readonly>
                                            </div>
                                        </div>   
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label >Displacement Kapal</label>
                                                <input type="text" name="displacement" value="{{$data->displacement}}" class="form-control" id="displacement" placeholder="Masukkan Displacement Kapal">
                                            </div>
                                        </div>   
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label>Jenis Kapal</label>
                                                    <select name="jenis_kapal" name="jenis_kapal" id="jenis_kapal" class="form-control">
                                                        <option value="">Pilih Jenis Kapal</option>
                                                        @foreach($jenis_kapal as $l)
                                                        <option value="{{$l->id}}" {{ $l->id == $data->id_jenis_kapal ? 'selected' : '' }}>{{$l->name}}</option>
                                                        @endforeach
                                                    </select>
                                            </div>
                                        </div> 

                                        <div class="col-xxl-6 col-md-6">
                                            <label>Request</label>
                                            <input type="hidden" name="keluhan" id="keluhanInput" value="">
                                            <textarea id="keluhan" rows="4" cols="50" class="form-control"></textarea>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label>Vendor</label>
                                                <select name="vendor" id="vendor" class="form-control">
                                                    <option value="">Pilih Vendor</option>
                                                    @foreach($vendor as $v)
                                                    <option value="{{$v->id}}">{{ $v->name }}</option>
                                                    @endforeach
                                                </select>
                                                <br><br>
                                                @if($pmAuth == 'Project Admin')
                                                    <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                                                        <button type="button" id="tambahKeluhan" class="btn btn-primary">Save</button>
                                                    </div>
                                                @endif
                                                @if($pmAuth == 'BOD')
                                                    <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                                                        <button type="button" id="tambahKeluhan" data-id-keluhan="" class="btn btn-primary">Save</button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!--tabel-->
                                        <div id="tabelKeluhanWrapper">
                                            
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
                                    <label>Nama Customer</label>
                                    <input type="text" name="name" class="form-control" placeholder="Masukkan Nama Customer">
                                </div>
                            </div>
                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <label>Contact Person</label>
                                    <input type="text" name="contact_person" class="form-control" placeholder="Masukkan Contact Person">
                                </div>
                            </div>
                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <label>Alamat</label>
                                    <input type="text" name="alamat" class="form-control" placeholder="Masukkan Nomor Contact Person">
                                </div>
                            </div>
                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <label>Nomor Contact Person</label>
                                    <input type="number" name="nomor_contact_person" class="form-control" placeholder="Masukkan Nomor Contact Person" maxlength="13" placeholder="Masukkan Nomor Contact Person" oninput="this.value=this.value.slice(0,this.maxLength)">
                                </div>
                            </div>                    
                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <div>
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control form-control-icon" placeholder="Masukkan Email">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <label>NPWP</label>
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
<!--end modal-->
@endsection

@section('scripts')
<script>
    function approve(id, type) {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Anda yakin ingin menyetujui ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                var url = `{{route('keluhan.approve', ':id')}}`;
                url = url.replace(':id', id);

                var requestConfig = {
                    method: 'post',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ id: id, type: type }),
                };

                fetch(url, requestConfig)
                    .then(function(response) {
                        if (response.status === 200) {
                            Swal.fire(
                                '',
                                'Success',
                                'success'
                            )
                            getTableData(idData);       
                            return response.json();
                        } else {
                            throw new Error('Gagal melakukan persetujuan');
                        }
                    })
                    .then(function(data) {
                        console.log('Persetujuan berhasil:', data);
                    })
                    .catch(function(error) {
                        console.error('Kesalahan saat melakukan persetujuan:', error);
                    });
            }
        });
    }

    function openNewTab() {
        var urlToOpen = "{{ route('keluhan.spk',$data->id)}}";
        window.open(urlToOpen, '_blank');
    }

    let idData = "{{$data->id}}";
    function getTableData(id) {
        let url = "{{route('on_request.tableData', ':id')}}";
        url = url.replace(':id', id);
        $.ajax({
            url: url,
            success: function(data) {
               $('#tabelKeluhanWrapper').html(data)
            }
        })
    }
    getTableData(idData);

    function setEditData(id, vendorId) {
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('{{ route('keluhan.getData', '') }}/' + id, {
            method: 'get',
            headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
        })
        .then(function (response) {
            if (response.status === 200) {
                getTableData(idData);
                return response.json();
            } else {
                throw new Error('Gagal mengambil data keluhan');
            }
        })
        .then(function (data) {
            var textarea = document.getElementById("keluhan");
            var vendorSelect = document.getElementById("vendor");

            textarea.value = data.data.keluhan.replace('<br>', '\n');
            vendorSelect.value = data.data.id_vendor;
            $("#vendor").select2("val", vendorSelect.value);
            var saveButton = document.getElementById("tambahKeluhan");
            saveButton.setAttribute("data-id-keluhan", id);
        })
        .catch(function (error) {
            console.error(error);
        });
    }

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
        var vendorSelect = document.getElementById("vendor");
        var selectedVendor = vendorSelect.options[vendorSelect.selectedIndex];
        var vendorId = selectedVendor.value;
        var vendorName = selectedVendor.text;

        var id = document.getElementById("on_request_id").value;
        var keluhan = document.getElementById("tambahKeluhan");
        var keluhanId = keluhan.getAttribute("data-id-keluhan")
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (keluhanInput.trim() !== "") {
            fetch('{{ route('keluhan.store', '') }}/' + {{ $data->id }}, {
                method: 'post',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ keluhan: keluhanInput.replace('\n', '<br\>'), vendor: vendorId, keluhanId : keluhanId}),
            })

            .then(function (response) {
                response.json().then(data => {
                    if (data.status === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                        });
                        getTableData(idData);       
                        // return response.json();
                    } else if(data.status === 500) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.message,
                        });
                        getTableData(idData);     
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Gagal menambahkan keluhan',
                        });
                        throw new Error('Gagal menambahkan keluhan');
                    }
                })
            })
            .then(function (data) {
                var tabel = document.getElementById("tabelKeluhan").getElementsByTagName('tbody')[0];
                var newRow = tabel.insertRow(tabel.rows.length);
                var cell1 = newRow.insertCell(0);
                var cell2 = newRow.insertCell(1);
                var cell3 = newRow.insertCell(2);
                var cell4 = newRow.insertCell(3);
                var cell5 = newRow.insertCell(4);
                var cell6 = newRow.insertCell(5);

                cell1.innerHTML = tabel.rows.length;
                cell2.innerHTML = keluhanInput.split('<br>')?.length > 1 ? keluhanInput.split('<br>')?.[0] : keluhanInput.split('\n')?.[0];
                cell3.innerHTML = vendorName;
                cell4.innerHTML = '';
                cell5.innerHTML = '';
                cell6.innerHTML = 
                    '<div>' +
                        '<button type="button" class="btn btn-warning btn-sm btnEdit" data-keluhan-id="' + data.id + '" data-vendor-id="' + data.id_vendor + '" onclick="setEditData(' + data.id + ', ' + data.id_vendor + ')"><img src="{{asset("assets/images/edit.svg")}}" style="width: 15px;"></button>&nbsp;' +
                        '<button type="button" class="btn btn-success btn-sm btnApprove" data-keluhan-id="' + data.id + '"><img src="{{asset("assets/images/like.svg")}}" style="width: 15px;"></button>&nbsp;' +
                        '<button type="button" class="btn btn-primary btn-sm btnPrint" data-keluhan-id="' + data.id + '"><img src="{{asset("assets/images/directbox.svg")}}" style="width: 15px;"></button>&nbsp;' +
                        '<button type="button" class="btn btn-danger btn-sm" data-keluhan-id="' + data.id + '" onclick="hapusKeluhan(' + data.id + ')"><img src="{{asset("assets/images/trash.svg")}}" style="width: 15px;"></button>' +
                    '</div>';

                document.getElementById("keluhan").value = "";
                $("#vendor").val('').trigger('change');

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

    // Fungsi untuk menghapus keluhan sehabis taambah data
    function hapusKeluhan(keluhanId) {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Anda yakin ingin menghapus Request ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
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
                        getTableData(idData);    
                        Swal.fire(
                            'Sukses!',
                            'Request berhasil dihapus.',
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
        });
    }

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

    //hapus keluhan
    // document.querySelectorAll('.btnHapus').forEach(function (button) {
    //     button.addEventListener('click', function () {
    //         var keluhanId = this.getAttribute('data-keluhan-id');

    //         fetch('{{ url('keluhan') }}/' + keluhanId, {
    //             method: 'get',
    //             headers: {
    //                 'Content-Type': 'application/json',
    //             },
    //         })
    //         .then(function (response) {
    //             if (response.status === 200) {
    //                 var row = button.closest('tr');
    //                 row.remove();
    //                 refreshNomorUrut();
    //                 Swal.fire(
    //                     '',
    //                     'Keluhan Berhasil Dihapus',
    //                     'success'
    //                 )
    //             } else {
    //                 console.error('Gagal menghapus keluhan');
    //             }
    //         })
    //         .catch(function (error) {
    //             console.error('Terjadi kesalahan:', error);
    //         });
    //     });
    // });

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

    //hapus data yg sudah ada sebelumnya 
    var btnHapus = document.querySelectorAll(".btnHapus");
    btnHapus.forEach(function (button) {
        button.addEventListener("click", function () {
            var complaintId = this.getAttribute("data-complaint-id");
            var row = this.parentNode.parentNode;
            row.parentNode.removeChild(row);
        });
    });

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
