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
                            <h4 class="mb-0 ml-2"> &nbsp; Input Pekerjaan</h4>
                        </div>
                    </div>
                </div>
            </div>
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
                                                    <option {{ $kategori_id ? ($kategori_id === $work->id ? 'selected' : '') : '' }} value="{{ $work->id }}">{{ $work->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="vendor" class="form-label">Vendor</label>
                                            <input class="form-control" value="{{ $vendor->name }}" disabled></input>
                                            <input type="hidden" name="vendor" value="{{ $vendor->id }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="sub_kategori" class="form-label">Sub Kategori Pekerjaan</label>
                                            <select name="sub_kategori" id="sub_kategori" class="form-select">
                                                <option selected disabled>Sub Kategori</option>
                                                @foreach ($subkategori as $s)
                                                    <option {{ $subkategori_id ? ($subkategori_id === $s->id ? 'selected' : '') : '' }} value="{{ $s->id }}">{{ $s->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="nama_pekerjaan" class="form-label">Nama Pekerjaan</label>
                                            <input type="text" class="form-control" placeholder="Masukan Nama Pekerjaan" id="nama_pekerjaan" name="nama_pekerjaan" value="{{ $desc }}">
                                        </div>
                                        <div class="d-flex justify-content-end mb-3">
                                            <div class="btn btn-primary btn-add">Add</div>
                                        </div>
                                        <div class="col-md-12">
                                           <div class="table-container">
                                            <table class="table" id="tablePekerjaan">
                                                <thead style="background-color:#194BFB;color:#FFFFFF;">
                                                    <tr>
                                                        <th style="width: 200px">Jenis Pekerjaan</th>
                                                        <th style="width: 200px">Deskripsi</th>
                                                        <th style="width: 200px">Lokasi</th>
                                                        <th style="width: 200px">Dertail / Other</th>
                                                        <th style="width: 90px">Length (mm)</th>
                                                        <th style="width: 90px">Width (mm)</th>
                                                        <th style="width: 90px">Thick (mm)</th>
                                                        <th style="width: 90px">Unit</th>
                                                        <th style="width: 90px">Qty</th>
                                                        <th style="width: 90px">Amount</th>
                                                        <th style="width: 90px">Harga Vendor</th>
                                                        <th style="width: 90px">Harga Customer</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="clone">
                                                    @foreach ($pekerjaan as $keys => $p)
                                                    <input type="hidden" name="id[]" value="{{ $p->id }}">
                                                    <tr class="draggable-row">
                                                        <td>
                                                            <select name="pekerjaan[]" id="pekerjaan-{{ $keys }}" class="form-select pekerjaan-{{ $keys }}">
                                                                <option selected disabled>Pilih Pekerjaan</option>
                                                                @foreach ($settingPekerjaan as $sp)
                                                                    <option {{ $p->id_pekerjaan ? ($p->id_pekerjaan === $sp->id_pekerjaan ? 'selected' : '') : '' }} value="{{ $sp->id_pekerjaan }}">{{ $sp->pekerjaan->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="deskripsi[]" style="width: 150px;" value="{{ $p->deskripsi_pekerjaan }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="lokasi[]" style="width: 100px;" value="{{ $p->id_lokasi }}">
                                                        </td>

                                                        <td>
                                                            <input type="text" class="form-control" name="detail[]" style="width: 100px;" value="{{ $p->detail }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" name="length[]" style="width: 70px" value="{{ $p->length }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" name="width[]"style="width: 70px" value="{{ $p->width }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" name="thick[]" style="width: 70px" value="{{ $p->thick }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="unit[]" style="width: 70px" value="{{ $p->unit }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="qty[]" style="width: 70px" value="{{ $p->qty }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" name="amount[]" style="width: 70px" value="{{ $p->amount }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="harga_vendor[]" id="harga_vendor" style="width: 100px" value="{{ $p->harga_vendor }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="harga_customer[]" id="harga_customer" style="width: 100px" value="{{ $p->harga_customer }}">
                                                        </td>
                                                        <td>
                                                            <div class="btn btn-danger btn-trash">
                                                                <i><img src="{{asset('assets/images/trash2.svg')}}" style="width: 20px;"></i>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
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
            let select = $('#tablePekerjaan').find('#pekerjaan');
            $('#sub_kategori').trigger('change');
            let id_kategori = $('#kategori').val();
            let id_subkategori = '{{ $subkategori_id }}';

            $('#sub_kategori').trigger('change');
            $('.form-select').select2({
                theme : "bootstrap-5",
                search: true
            });

            $("#tablePekerjaan tbody").sortable({
                items: '.draggable-row',
                axis: 'y',
            });

            let count = 1;
            $('.btn-add').click(function(){
                $('#clone').append(`<tr class="draggable-row">
                    <input type="hidden" name="id[]">
                    <td>
                        <select name="pekerjaan[]" id="pekerjaan${count}" class="form-select pekerjaan">
                            <option selected disabled>Pilih Pekerjaan</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="deskripsi[]">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="lokasi[]" style="width: 100px;">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="detail[]">
                    </td>
                    <td>
                        <input type="number" class="form-control" name="length[]" style="width: 70px">
                    </td>
                    <td>
                        <input type="number" class="form-control" name="width[]"style="width: 70px">
                    </td>
                    <td>
                        <input type="number" class="form-control" name="thick[]" style="width: 70px">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="unit[]" style="width: 70px">
                    </td>
                    <td>
                        <input type="number" class="form-control" name="harga_vendor[]" style="width: 100px">
                    </td>
                    <td>
                        <input type="number" class="form-control" name="harga_customer[]" style="width: 100px">
                    </td>

                    <td>
                        <div class="btn btn-danger btn-trash">
                            <i><img src="{{asset('assets/images/trash2.svg')}}" style="width: 20px;"></i>
                        </div>
                    </td>
                </tr>`)
                let select = $(`#pekerjaan${count}`).select2({
                    theme : "bootstrap-5",
                    search: true
                })


                let id = $('#sub_kategori').val();
                getSelect(id,select);
                count++;
            })

            $('#clone').on('change', '.draggable-row input[name="length[]"], input[name="width[]"], input[name="thick[]"],input[name="qty[]"]', function() {
                // Ambil nilai dari input length, width, dan thick
                var lengthValue = parseFloat($(this).closest('tr').find('input[name="length[]"]').val());
                var widthValue = parseFloat($(this).closest('tr').find('input[name="width[]"]').val());
                var thickValue = parseFloat($(this).closest('tr').find('input[name="thick[]"]').val());
                var qtyValue = parseFloat($(this).closest('tr').find('input[name="qty[]"]').val());

                // Hitung amount
                var amountValue = (lengthValue * widthValue * thickValue * qtyValue * 0.64) / 1000;

                // Perbarui input amount dengan hasil perhitungan
                $(this).closest('tr').find('input[name="amount[]"]').val(amountValue);
            });

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

            $('#harga_customer').on('input', function() {
                var inputValue = $(this).val();
                var formattedValue = formatRupiah(inputValue);
                $(this).val(formattedValue);
            });

            $('#harga_vendor').on('input', function() {
                var inputValue = $(this).val();
                var formattedValue = formatRupiah(inputValue);
                $(this).val(formattedValue);
            });

            function formatRupiah(angka) {
                var numberString = angka.toString().replace(/[^0-9]/g, '');
                var rupiah = '';
                var ribuan = 0;

                for (var i = numberString.length - 1; i >= 0; i--) {
                    rupiah = numberString[i] + rupiah;
                    ribuan++;
                    if (ribuan == 3 && i > 0) {
                        rupiah = ',' + rupiah;
                        ribuan = 0;
                    }
                }

                return rupiah;
            }

        })
    </script>
@endsection
