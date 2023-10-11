@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('project_manager')}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Project Manager</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{route('project_manager.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="row gy-4">
                                        <input type="hidden" name="selectedPE" id="selectedPE">
                                        <input type="hidden" name="selectedPA" id="selectedPA">
                                        <div class="col-xxl-12">
                                            <label for="name" class="form-label">Nama Project Manager</label>
                                            <select name="pm" id="pm" class="form-control">
                                                <option value="">Pilih Project Manager</option>
                                                @foreach($karyawan as $k)
                                                <option value="{{$k->id}}">{{$k->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('pm'))
                                                <span class="text-danger">{{ $errors->first('pm') }}</span>
                                            @endif
                                        </div>

                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="name" class="form-label">Nama Project Engineer</label>&nbsp;
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#selectPeModal">Pilih</button>
                                                <ol id="ListPE"></ol>
                                            </div>
                                        </div>

                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="name" class="form-label">Nama Project Admin</label>&nbsp;
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#selectPaModal">Pilih</button>
                                                <ol id="ListPA"></ol>
                                            </div>
                                        </div>

                                        <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                                            <button class="btn btn-primary" style="margin-right: 10px;">Save</button>
                                            <a href="{{route('project_manager')}}" class="btn btn-danger">Cancel</a>
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

<!-- PA Modal -->
<div class="modal fade zoomIn" id="selectPaModal" tabindex="-1" role="dialog" aria-labelledby="paModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paModalLabel">Select Project Admins</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <table class="table table-bordered" id="pa">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th><center>Action</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($karyawan as $key=> $k)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $k->name }}</td>
                                <td><center><input type="checkbox"></center>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="savePaSelections">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- PE Modal -->
<div class="modal fade zoomIn" id="selectPeModal" tabindex="-1" role="dialog" aria-labelledby="peModalLabel" aria-hidden="true" style="z-index: 1051;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="peModalLabel">Select Project Engineers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <table class="table table-bordered" id="pe">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th><center>Action</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($karyawan as $key=> $k)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $k->name }}</td>
                                <td><center><input type="checkbox"></center></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="savePeSelections">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var selectedPE = [];
    var selectedPA = [];

    var selectedPE = $('#selectedPE').val();
    var selectedPA = $('#selectedPA').val();

    $('#savePeSelections').click(function () {
        selectedPE = [];
        selectedIDPE = [];
        $('#pe tbody input:checked').each(function () {
            var namaPE = $(this).closest('tr').find('td:eq(1)').text();
            selectedPE.push(namaPE);

            var idPE = $(this).closest('tr').find('td:first').text();
            selectedIDPE.push(idPE);
        });
        var ListPE = $('#ListPE');
        ListPE.empty(); 
        
        selectedPE.forEach(function (nama) {
            var listItem = $('<li>').text(nama);
            ListPE.append(listItem);
        });

        $('#selectedPE').val(selectedIDPE.join(','));
 
        $('#selectPeModal').modal('hide');

        $(".modal-backdrop").remove();
    });

    // Tangani pemilihan PA
    $('#savePaSelections').click(function () {
        selectedPA = [];
        selectedIDPA = [];
        $('#pa tbody input:checked').each(function () {
            var namaPA = $(this).closest('tr').find('td:eq(1)').text();
            selectedPA.push(namaPA);

            var idPA = $(this).closest('tr').find('td:first').text();
            selectedIDPA.push(idPA);
        });
        
        var listPA = $('#ListPA');
        listPA.empty(); 
        
        selectedPA.forEach(function (nama) {
            var listItem = $('<li>').text(nama);
            listPA.append(listItem);
        });

        $('#selectedPA').val(selectedIDPA.join(','));

        $('#selectPaModal').modal('hide');

        $(".modal-backdrop").remove();
    });

    $(function () {
        $("select").select2();
    });

    $('#pm').change(function () {
        var selectedPM = $(this).val();
        if (selectedPM === '') {
            return; // Tidak ada PM yang dipilih, tidak perlu validasi
        }

        // Periksa apakah PM yang dipilih sudah ada dalam PE
        var isPMEmployee = $.inArray(selectedPM, selectedPE) !== -1;
        
        // Periksa apakah PM yang dipilih sudah ada dalam PA
        var isPAEmployee = $.inArray(selectedPM, selectedPA) !== -1;

        // Jika PM adalah PE atau PA, tampilkan pesan kesalahan dan kembalikan pemilihan PM ke opsi default
        if (isPMEmployee || isPAEmployee) {
            alert("Project Manager tidak boleh sama dengan Project Engineer atau Project Admin.");
            $(this).val(''); // Kosongkan pemilihan PM
        }
    });
</script>



@endsection