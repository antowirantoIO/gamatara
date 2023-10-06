@extends('index')

@section('content')

<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <h4 class="mb-0 ml-2"> &nbsp; Pekerjaan</h4>
                        </div>
                        <div class="mt-3 mt-lg-0 ml-lg-auto">
                            <a href="{{ route('pekerjaan.create') }}" class="btn btn-secondary">
                                <span><i class="mdi mdi-plus"></i></span> &nbsp; Add
                            </a>
                            <button class="btn btn-secondary">
                                <span>
                                    <i><img src="{{asset('assets/images/filter.svg')}}" style="width: 15px;"></i>
                                </span> &nbsp; Filter
                            </button>
                            <button class="btn btn-danger">
                                <span>
                                    <i><img src="{{asset('assets/images/directbox-send.svg')}}" style="width: 15px;"></i>
                                </span> &nbsp; Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Pekerjaan</h4>
                            <div>
                          
                            </div>
                        </div>

                        <div class="card-body">
                            <div class=" table-responsive">
                                <table class="table" id="example1">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="color:#929EAE">Nama Pekerjaan</th>
                                            <th style="color:#929EAE">Length (mm)</th>
                                            <th style="color:#929EAE">Width (mm)</th>
                                            <th style="color:#929EAE">Thick (mm)</th>
                                            <th style="color:#929EAE">Unit</th>
                                            <th style="color:#929EAE">Conversion</th>
                                            <th style="color:#929EAE">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $v)
                                        <tr>
                                            <td>{{$v->name}}</td>
                                            <td>{{$v->length}}</td>
                                            <td>{{$v->width}}</td>
                                            <td>{{$v->thick}}</td>
                                            <td>{{$v->unit}}</td>
                                            <td>{{$v->conversion}}</td>
                                            <td>
                                                <a href="{{ route('pekerjaan.edit',$v->id) }}" class="btn btn-success btn-sm">
                                                    <span>
                                                        <i><img src="{{asset('assets/images/edit.svg')}}" style="width: 15px;"></i>
                                                    </span>
                                                </a>
                                                &nbsp;
                                                <a data-id="{{ $v->id }}" data-name="pekerjaan" data-form="form-pekerjaan" class="btn btn-danger btn-sm deleteData">
                                                    <span>
                                                        <i><img src="{{asset('assets/images/trash.svg')}}" style="width: 15px;"></i>
                                                    </span>
                                                </a>
                                                <form method="get" id="form-pekerjaan{{ $v->id }}" action="{{ route('pekerjaan.delete', $v->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
    $(function() {
            $("#example1").DataTable({
                fixedHeader:true
            });
        })
</script>
@endsection
