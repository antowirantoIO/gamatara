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
                                        <div class="col-xxl-6 col-md-6">
                                            <h5>Project Manager</h5>
                                            <div>
                                                <label for="name" class="form-label">Nama Project Manager</label>
                                                <select name="pm" id="pm" class="form-control">
                                                    <option>Pilih Project Manager</option>
                                                    @foreach($karyawan as $k)
                                                    <option value="{{$k->id}}">{{$k->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="name" class="form-label">Nama Project Engineer</label>
                                                <select name="pe" id="pe" class="form-control">
                                                    <option>Pilih Project Engineer</option>
                                                    @foreach($karyawan as $k)
                                                    <option value="{{$k->id}}">{{$k->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                        </div>
                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label for="name" class="form-label">Nama Project Admin</label>
                                                <select name="pa" id="pa" class="form-control">
                                                    <option>Pilih Project Admin</option>
                                                    @foreach($karyawan as $k)
                                                    <option value="{{$k->id}}">{{$k->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                                            <button class="btn btn-primary" style="margin-right: 10px;">Save</button>
                                            <a href="{{route('kategori')}}" class="btn btn-danger">Cancel</a>
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
     $(function () {
        $("select").select2();
    });

    $(document).ready(function() {
        $('#pm, #pe, #pa').on('change', function() {
            var selectedPM = $('#pm').val();
            var selectedPE = $('#pe').val();
            var selectedPI = $('#pa').val();

            $('#pm, #pe, #pa').find('option').prop('disabled', false);

            if (selectedPM != '') {
                $('#pe, #pa').find('option[value="' + selectedPM + '"]').prop('disabled', true);
            }
            if (selectedPE != '') {
                $('#pm, #pa').find('option[value="' + selectedPE + '"]').prop('disabled', true);
            }
            if (selectedPI != '') {
                $('#pm, #pe').find('option[value="' + selectedPI + '"]').prop('disabled', true);
            }
        });
    });
</script>
@endsection