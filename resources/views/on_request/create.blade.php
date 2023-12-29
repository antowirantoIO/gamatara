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
                                <form action="{{route('on_request.store')}}" method="POST" enctype="multipart/form-data"  autocomplete="off">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="nama_project">Project Name</label>
                                                <input type="text" name="nama_project" id="nama_project" value="{{ old('nama_project') }}" class="form-control" placeholder="Enter Project Name">
                                            </div>
                                            @if ($errors->has('nama_project'))
                                                <span class="text-danger">{{ $errors->first('nama_project') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label>Status Survey</label>
                                                <select name="status_survey" id="status_survey" class="form-control select2">
                                                    <option value="">Choose Status Survey</option>
                                                    @foreach($status as $p)
                                                    <option value="{{$p->id}}" {{ $p->id == old('status_survey') ? 'selected' : '' }}>{{$p->name ?? ''}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label>Project Manager</label>
                                                <input type="text" value="{{ $pm->karyawan->name ?? '' }}" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label>Project Engineer</label>
                                                <select name="pe_id_1" id="pe_id_1" class="form-control select2">
                                                    <option value="">Choose Project Engineer</option>
                                                    @foreach($pe as $p)
                                                    <option value="{{$p->id}}">{{ $p->karyawan->name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <label for="customer_name" class="form-label">Customer Name</label>
                                            <div class="position-relative">
                                                <select id="customer_name" name="id_customer" class="form-control select2" aria-label="Customer Name">
                                                    <option value="">Choose Customer</option>
                                                    @foreach($customer as $k)
                                                        <option value="{{$k->id}}" {{ $k->id == old('id_customer') ? 'selected' : '' }}>{{$k->name}}</option>
                                                    @endforeach
                                                </select>
                                                @if($pmAuth == 'Project Admin')
                                                    <button style="width: 7%; height: 110%;" class="btn btn-primary btn-sm position-absolute top-0 end-0" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalgrid">+</button>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="lokasi_project">Project Location</label>
                                                <select name="lokasi_project" id="lokasi_project" class="form-control select2" value="{{ old('lokasi_project') }}" >
                                                    <option value="">Choose Project Location</option>
                                                    @foreach($lokasi as $l)
                                                    <option value="{{$l->id}}" {{ $l->id == old('lokasi_project') ? 'selected' : '' }}>{{$l->name}}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('lokasi_project'))
                                                    <span class="text-danger">{{ $errors->first('lokasi_project') }}</span>
                                                @endif
                                            </div>
                                        </div>      
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="contact_person">Contact Person</label>
                                                <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person') }}" class="form-control" placeholder="Enter Contact Person">
                                            </div>
                                            @if ($errors->has('contact_person'))
                                                <span class="text-danger">{{ $errors->first('contact_person') }}</span>
                                            @endif
                                        </div>         
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="nomor_contact_person">Contact Person</label>
                                                <input type="text" name="nomor_contact_person" id="nomor_contact_person" value="{{ old('nomor_contact_person') }}" class="form-control" placeholder="Enter Nomor Contact Person" maxlength="13" placeholder="Enter Contact Person Phone" oninput="this.value=this.value.slice(0,this.maxLength)">
                                            </div>
                                            @if ($errors->has('nomor_contact_person'))
                                                <span class="text-danger">{{ $errors->first('nomor_contact_person') }}</span>
                                            @endif
                                        </div>          
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="alamat">Customer Address</label>
                                                <input type="text" class="form-control" name="alamat" id="alamat" value="{{ old('alamat') }}" readonly>
                                            </div>
                                        </div>   
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="npwps">NPWP</label>
                                                <input type="text" class="form-control" name="npwps" id="npwps" value="{{ old('npwps') }}" readonly>
                                            </div>
                                        </div>   
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="displacement">Displacement Ship (GT)</label>
                                                <input type="number" name="displacement" id="displacement" value="{{ old('displacement') }}" class="form-control" placeholder="Enter Displacement Kapal">
                                            </div>
                                            @if ($errors->has('displacement'))
                                                <span class="text-danger">{{ $errors->first('displacement') }}</span>
                                            @endif
                                        </div>   
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="Jenis Kapal">Ship Type</label>
                                                <select name="jenis_kapal" id="jenis_kapal" class="form-control select2">
                                                    <option value="">Choose Ship Type</option>
                                                    @foreach($jenis_kapal as $l)
                                                    <option value="{{$l->id}}" {{ $l->id == old('jenis_kapal') ? 'selected' : '' }}>{{$l->name}}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('jenis_kapal'))
                                                    <span class="text-danger">{{ $errors->first('jenis_kapal') }}</span>
                                                @endif
                                            </div>
                                        </div> 

                                        <!-- <div class="col-xxl-6 col-md-12">
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
                                                </tbody>
                                            </table>
                                        </div> -->

                                        <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                                            <!-- <button class="btn btn-primary" style="margin-right: 10px;" onclick="simpanData()">Save</button> -->
                                            <button type="submit" class="btn btn-primary" style="margin-right: 10px;" >Save</button>
                                            <a href="{{route('on_request')}}" class="btn btn-danger">Cancel</a>
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
            <form action="{{route('customer.store')}}" id="formOnRequest" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">Add Customer</h5>
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
                                    <label for="customer">Customer Name</label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter Customer Name">
                                </div>
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <label for="contact_person">Contact Person</label>
                                    <input type="text" name="contact_person" class="form-control" id="contact_person" placeholder="Enter Contact Person">
                                </div>
                                @if ($errors->has('contact_person'))
                                    <span class="text-danger">{{ $errors->first('contact_person') }}</span>
                                @endif
                            </div>
                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <label for="alamat">Address</label>
                                    <input type="text" name="alamat" class="form-control" id="alamat" placeholder="Enter Address">
                                </div>
                                @if ($errors->has('alamat'))
                                    <span class="text-danger">{{ $errors->first('alamat') }}</span>
                                @endif
                            </div>
                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <label for="nomor_contact_person">Contact Person Phone</label>
                                    <input type="number" name="nomor_contact_person" class="form-control" id="nomor_contact_person" placeholder="Enter Contact Person Phone" maxlength="13" placeholder="Enter Nomor Contact Person" oninput="this.value=this.value.slice(0,this.maxLength)">
                                </div>
                                @if ($errors->has('nomor_contact_person'))
                                    <span class="text-danger">{{ $errors->first('nomor_contact_person') }}</span>
                                @endif
                            </div>                    
                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <div>
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="form-control form-control-icon" id="email" placeholder="Enter Email">
                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xxl-6 col-md-6">
                                <div>
                                    <label for="npwp">NPWP</label>
                                    <input type="text" name="npwp" id="npwp" class="form-control" placeholder="Enter NPWP">
                                </div>
                                @if ($errors->has('npwp'))
                                    <span class="text-danger">{{ $errors->first('npwp') }}</span>
                                @endif
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

    //save data utama
    $(document).ready(function () {
        $("#saveCustomerButton").click(function (e) {
            e.preventDefault();

            var form = $("#formOnRequest");
            var formData = form.serialize();

            $.ajax({
                type: "POST",
                url: form.attr("action"),
                data: formData,
                success: function (response) {
                    console.log(response)
                    if (response) {
                        $("#exampleModalgrid").modal("hide");
                        form[0].reset();
                        Swal.fire(
                            '',
                            'Customer has been successfully added',
                            'success'
                        );
                        window.location.reload();
                    } else if (response.errors) {
                        if (response.errors.npwp) {
                            $("#npwp").addClass('is-invalid');
                            $("#npwp-error").text(response.errors.npwp[0]);
                        } else {
                            $("#npwp").removeClass('is-invalid');
                            $("#npwp-error").text('');
                        }
                    } else {
                        alert("An error occurred while saving the customer.");
                    }
                },
                error: function (error) {
                    alert("An error occurred while saving the customer.");
                }
            });
        });
    });

    // Initialize Select2
    $('#customer_name').select2({
        placeholder: 'Choose Customer',
        ajax: {
            url: "{{ url('customer') }}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data.map(function (customer) {
                        return {
                            id: customer.id,
                            text: customer.name
                        };
                    })
                };
            },
            cache: true
        }
    });

    // Listen for the "select2:select" event when an item is selected
    $('#customer_name').on('select2:select', function (e) {
        var selectedCustomer = e.params.data;

        // Use the selected customer data to populate other fields
        $.ajax({
            url: "{{ url('on_request/edits') }}/" + selectedCustomer.id,
            method: 'GET',
            dataType: 'json',
            success: function (customerData) {
                // Populate additional fields with the retrieved data
                $('#contact_person').val(customerData.contact_person);
                $('#nomor_contact_person').val(customerData.nomor_contact_person);
                $('#alamat').val(customerData.alamat);
                $('#npwps').val(customerData.npwp);
            },
            error: function (error) {
                console.error('Error retrieving customer data:', error);
            }
        });
    });
    //show data customer
    // var route = "{{ url('customer') }}";
    // $('#nama_customer').typeahead({
    //     source: function (query, process) {
    //         return $.get(route, { query: query }, function (data) {
    //             return process(data.map(function(customer) {
    //                 return customer.name;
    //             }));
    //         });
    //     },
    //     updater: function (item) {
    //         $.get(route, { query: item }, function (customerData) {
    //             if (customerData.length > 0) {
    //                 var selectedCustomer = customerData[0];
    //                 $('#contact_person').val(selectedCustomer.contact_person);
    //                 $('#nomor_contact_person').val(selectedCustomer.nomor_contact_person);
    //                 $('#alamat').val(selectedCustomer.alamat);
    //                 $('#npwps').val(selectedCustomer.npwp);
    //             }
    //         });

    //         return item;
    //     }
    // });

    //simpan keluhan
    // var addButton = document.getElementById("tambahKeluhan");
    // addButton.addEventListener("click", tambahKeluhan);

    // function refreshNomorUrut() {
    //     var tabel = document.getElementById("tabelKeluhan");
    //     var rows = tabel.getElementsByTagName("tr");

    //     for (var i = 1; i < rows.length; i++) {
    //         rows[i].getElementsByTagName("td")[0].textContent = i;
    //     }
    // }

    // document.getElementById("tambahKeluhan").addEventListener("click", tambahKeluhan);
    // function tambahKeluhan() {
    //     var keluhanInput = document.getElementById("keluhan").value;
        
    //     if (keluhanInput.trim() !== "") {
    //         var tabel = document.getElementById("tabelKeluhan").getElementsByTagName('tbody')[0];
    //         var newRow = tabel.insertRow(tabel.rows.length);
    //         var cell1 = newRow.insertCell(0);
    //         var cell2 = newRow.insertCell(1);
    //         var cell3 = newRow.insertCell(2);

    //         cell1.innerHTML = tabel.rows.length;
    //         cell2.innerHTML = keluhanInput;
    //         cell3.innerHTML = '<button class="btn btn-danger btn-sm btnHapus"> <span><i><img src="{{asset("assets/images/trash.svg")}}" style="width: 15px;"></i></span></button>';

    //         // Mengosongkan textarea
    //         document.getElementById("keluhan").value = "";

    //         // Menambahkan event listener untuk tombol "Hapus"
    //         var btnHapus = newRow.querySelector(".btnHapus");
    //         btnHapus.addEventListener("click", function() {
    //             var row = this.parentNode.parentNode;
    //             row.parentNode.removeChild(row);

    //             refreshNomorUrut();
    //         });
    //     } else {
    //         Swal.fire({
    //         icon: 'error',
    //         title: 'Oops...',
    //         text: 'Keluhan Tidak Boleh Kosong!',
    //         })
    //     }
    // }

    // function simpanData() {
    //     var keluhanRows = document.getElementById("tabelKeluhan").getElementsByTagName('tbody')[0].rows;
    //     var keluhanData = [];

    //     for (var i = 0; i < keluhanRows.length; i++) {
    //         var keluhan = keluhanRows[i].cells[1].innerText;
    //         keluhanData.push(keluhan);
    //     }

    //     var keluhanInput = document.getElementById("keluhanInput");
    //     keluhanInput.value = JSON.stringify(keluhanData);
    // }

    //untuk semua select menggunakan select2
    $(function () {
        $(".select2").select2();
    });
    </script>
@endsection
