@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('role')}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Peran</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{route('role.store')}}" id="npwpForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-xxl-12">
                                            <div>
                                                <label for="role" class="form-label">Nama Peran</label>
                                                <input type="text" name="name" class="form-control" id="name" placeholder="Masukkan Nama Customer">
                                            </div>
                                        </div>

                                        <label for="permission" class="form-label">Permission</label>
                                        <div class="row">
                                            @foreach($permission as $p)
                                            @if($loop->iteration % 2 == 1)
                                            <div class="row">
                                            @endif
                                            <div class="col-md-3">
                                                <div class="form-check bg-checkbox">
                                                    <input type="checkbox" name="permission[]" value="{{$p->id}}" class="cust-checkbox">
                                                    <label class="form-check-label label-color">&nbsp;{{ ucfirst($p->name) }}</label>
                                                </div>
                                            </div>
                                            @if($loop->iteration % 2 == 0 || $loop->last)
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                    
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                                            <button class="btn btn-primary" style="margin-right: 10px;">Save</button>
                                            <a href="{{route('customer')}}" class="btn btn-danger">Cancel</a>
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
