@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('on_progress.edit',$id)}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Request Form</h4>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="loader d-none">
                <x-loader/>
            </div> --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{ route('on_progres.work',$id) }}" method="post">
                                    @csrf
                                    <input type="hidden" id="id_project" name="id_project" value="{{ $id }}">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="kategori" class="form-label">Kategori Pekerjaan</label>
                                            <select name="kategori" id="kategori" class="form-select">
                                                <option selected disabled>Masukan Kategori Pekerjaan</option>
                                                @foreach ($works as $work)
                                                    <option value="{{ $work->id }}">{{ $work->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="vendor" class="form-label">Vendor</label>
                                            <select name="vendor" id="vendor" class="form-select">
                                                <option selected disabled>Pilih Vendor</option>
                                                @foreach ($vendors as $vendor)
                                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="sub_kategori" class="form-label">Sub Kategori Pekerjaan</label>
                                            <select name="sub_kategori" id="sub_kategori" class="form-select">
                                                <option selected disabled>Sub Kategori</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="nama_pekerjaan" class="form-label">Nama Pekerjaan</label>
                                            <input type="text" class="form-control" placeholder="Masukan Nama Pekerjaan" id="nama_pekerjaan" name="nama_pekerjaan">
                                        </div>
                                        <div class="d-flex justify-content-end mb-3">
                                            <div class="btn btn-primary btn-add">Add</div>
                                        </div>
                                        <div class="col-md-12">
                                           <div class="table-container">
                                            <table class="table overflow-x-auto" id="tablePekerjaan">
                                                <thead style="background-color:#194BFB;color:#FFFFFF;">
                                                    <tr>
                                                        <th>Jenis Pekerjaan</th>
                                                        <th>Deskripsi</th>
                                                        <th>Dertail / Other</th>
                                                        <th>Length (mm)</th>
                                                        <th>Width (mm)</th>
                                                        <th>Thick (mm)</th>
                                                        <th>Unit</th>
                                                        <th>Qty</th>
                                                        <th>Ammount</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="clone">
                                                    <tr class="parent-clone">
                                                        <td>
                                                            <select name="pekerjaan[]" id="pekerjaan" class="form-select pekerjaan">
                                                                <option selected disabled>Pilih Pekerjaan</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="deskripsi[]">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="detail[]">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" name="length[]">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" name="width[]">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" name="thick[]">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="unit[]">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="qty[]">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" name="amount[]">
                                                        </td>
                                                        <td>
                                                            <div class="btn btn-danger btn-trash">
                                                                <i><img src="{{asset('assets/images/trash2.svg')}}" style="width: 20px;"></i>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                           </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center gap-3 mt-4">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                        <div class="btn btn-danger">Cancel</div>
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
        $(document).ready(function(){
            let modalInput = $('#modalInput');
            $("#btn-setting").click(function(){
                modalInput.modal('show');
            })

            $('.form-select').select2({
                theme : "bootstrap-5",
                search: true
            });

            let count = 1;
            $('.btn-add').click(function(){
                $('#clone').append(`<tr class="parent-clone">
                    <td>
                        <select name="pekerjaan[]" id="pekerjaan-${count}" class="form-select pekerjaan">
                            <option selected disabled>Pilih Pekerjaan</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="deskripsi[]">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="detail[]">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="length[]">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="width[]">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="thick[]">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="unit[]">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="qty[]">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="amount[]">
                    </td>
                    <td>
                        <div class="btn btn-danger btn-trash">
                            <i><img src="{{asset('assets/images/trash2.svg')}}" style="width: 20px;"></i>
                        </div>
                    </td>
                </tr>`)
                let select = $(`#pekerjaan-${count}`).select2({
                    theme : "bootstrap-5",
                    search: true
                })
                let id = $('#sub_kategori').val();
                getSelect(id,select);
                count++;
            })

            $(document).delegate('.btn-trash','click',function(){
                let data = $('.parent-clone');
                console.log(data);
                if(data.length != 1){
                    $(this).closest('tr').remove();
                }
            })

            $('#kategori').on('change',function(){
                let id = $(this).val();
                let url = '{{ route('on_progres.sub-kategori',':id') }}'
                let urlReplace = url.replace(':id',id);
                // $('#loader').show();
                $.ajax({
                    url : urlReplace,
                    method : 'GET'
                }).then(ress => {
                    let select = $('#sub_kategori');
                    if(ress.data.length != null){
                        select.empty();
                        select.append(`
                            <option selected disabled>Sub Kategori</option>
                        `)
                        ress.data.forEach(item => {
                            select.append(`
                                <option value="${item.id}">${item.name}</option>
                            `)
                        })
                    }else{
                        select.append(`
                            <option selected disabled>Sub Kategori</option>
                        `)
                    }
                })
                // $('#loader').hide();
            })

            $('#sub_kategori').on('change',function(){
                let id = $(this).val();
                let select = $('#pekerjaan');
                getSelect(id,select);

            })

            const getSelect = (id,select) => {
                let url = '{{ route('on_progres.pekerjaan',':id') }}'
                let urlReplace = url.replace(':id',id);

                $.ajax({
                    url : urlReplace,
                    method : 'GET'
                }).then(ress => {
                    console.log(ress.data);
                    if(ress.data.length != null){
                        select.empty();
                        select.append(`
                            <option selected disabled>Pilih Pekerjaan</option>
                        `)
                        ress.data.forEach(item => {
                            select.append(`
                                <option value="${item.pekerjaan.id}">${item.pekerjaan.name}</option>
                            `)
                        })
                    }else{
                        select.append(`
                            <option selected disabled>Pilih Pekerjaan</option>
                        `)
                    }
                })
            }
        })
    </script>
@endsection
