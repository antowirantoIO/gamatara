@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('setting_pekerjaan')}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Setting Pekerjaaan</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{route('setting_pekerjaan.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="name" class="form-label">Kategori</label>
                                                <select name="kategori" id="kategori" class="form-control">
                                                    <option value="">Pilih Kategori</option>
                                                    @foreach($kategori as $r)
                                                        <option value="{{$r->id}}" {{ $r->id == old('kategori') ? 'selected' : '' }}>{{ $r->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('kategori'))
                                                    <span class="text-danger">{{ $errors->first('kategori') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="name" class="form-label">Nama Sub Kategori</label>
                                                <select name="subkategori" id="sub_kategori" class="form-control">
                                                    <option value="">Pilih subkategori</option>
                                                    @foreach($subkategori as $r)
                                                        <option value="{{$r->id}}" {{ $r->id == old('subkategori') ? 'selected' : '' }}>{{ $r->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('subkategori'))
                                                    <span class="text-danger">{{ $errors->first('subkategori') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="name" class="form-label">Pekerjaan</label>
                                                <select name="pekerjaan" id="pekerjaan" class="form-control">
                                                    <option value="">Pilih pekerjaan</option>
                                                    @foreach($pekerjaan as $r)
                                                        <option value="{{$r->id}}" {{ $r->id == old('pekerjaan') ? 'selected' : '' }}>{{ $r->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('pekerjaan'))
                                                    <span class="text-danger">{{ $errors->first('pekerjaan') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div>
                                            <table id="tabelList" class="table table-bordered">
                                                <thead style="background-color:#194BFB;color:#FFFFFF">
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Kategori</th>
                                                        <th>Sub Kategori</th>
                                                        <th>Pekerjaaan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                                            <button class="btn btn-primary" style="margin-right: 10px;">Save</button>
                                            <a href="{{route('setting_pekerjaan')}}" class="btn btn-danger">Cancel</a>
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

    $(document).ready(function() {
        $('#pekerjaan').on('change', function() {
            let sub_kategori = $('#sub_kategori').val(); 
            let pekerjaan = $('#pekerjaan').val();

            let url = '{{ route('setting_pekerjaan.kategori') }}';
            url = url + '?&sub_kategori=' + sub_kategori + '&pekerjaan=' + pekerjaan;

            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    populateTable(data);
                },
                error: function() {
                }
            });
        });

        function populateTable(data) {
            var tableBody = $('#tabelList tbody');
            tableBody.empty();

            for (var i = 0; i < data.data.length; i++) {
               
                var row = '<tr>' +
                    '<td>' + (i + 1) + '</td>' +
                    '<td>' + data.data[i].kategori + '</td>' +
                    '<td>' + data.data[i].sub_kategori + '</td>' +
                    '<td>' + data.data[i].pekerjaan.name + '</td>' +
                    '</tr>';

                tableBody.append(row);
            }
        }
    });
</script>
@endsection