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

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{ route('on_progres.work',$id) }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="kategori" class="form-label">Kategori Pekerjaan</label>
                                            <select name="kategori" id="kategori" class="form-select">
                                                <option selected disabled>Masukan Kategori Pekerjaan</option>
                                                @foreach ($works as $work)
                                                    <option value="{{ $work->id }}">{{ $work->kategori_pekerjaan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="sub_kategori" class="form-label">Sub Kategori Pekerjaan</label>
                                            <select name="sub_kategori" id="sub_kategori" class="form-select">
                                                <option selected disabled>Sub Kategori</option>
                                                @foreach ($works as $work)
                                                    <option value="{{ $work->id }}">{{ $work->sub_kategori_pekerjaan }} - ({{ $work->kategori_pekerjaan }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <table class="table">
                                                <thead style="background-color:#194BFB;color:#FFFFFF;">
                                                    <tr>
                                                        <th style="font-size: 11px;">Jenis Pekerjaan</th>
                                                        <th style="font-size: 11px;">Dertail / Other</th>
                                                        <th style="font-size: 11px;">Length (mm)</th>
                                                        <th style="font-size: 11px;">Width (mm)</th>
                                                        <th style="font-size: 11px;">Thick (mm)</th>
                                                        <th style="font-size: 11px;">Unit</th>
                                                        <th style="font-size: 11px;">Volume</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr >
                                                        <td>
                                                            Docking / Undocking
                                                            <input type="hidden" value="Docking / Undocking">
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
                                                            <input type="text" class="form-control" name="vloume[]">
                                                        </td>
                                                    </tr>
                                                    <tr >
                                                        <td>
                                                            Diver service naik / turun
                                                            <input type="hidden" value="Diver service naik / turun">
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
                                                            <input type="text" class="form-control" name="vloume[]">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center gap-3">
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
            })
        })
    </script>
@endsection
