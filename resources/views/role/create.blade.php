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
                            <h4 class="mb-0 ml-2"> &nbsp; Role</h4>
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
                                                <label for="role" class="form-label">Role Name</label>
                                                <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" placeholder="Masukkan Nama Customer">
                                                @if ($errors->has('name'))
                                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="card-body">
                                            <label for="permission" class="form-label">Permission</label>
                                                <table class="table table-bordered">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Menu Name</th>
                                                            <th>Feature Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($group_permission as $group)
                                                        <tr>
                                                            <th>{{ ucwords(str_replace("-"," ",$group->menu_name)) }}</th>
                                                            <td>
                                                                @php $feat = 0; @endphp
                                                                @foreach ($feature as $key=>$f)
                                                                    @if($f->menu_name == $group->menu_name)
                                                                        <div class="checkbox checkbox-success pl-2 mb-2">
                                                                            <input type="checkbox" id="customCheck-feature{{ $key }}" name="permission[]" value="{{ $f->id }}">
                                                                            <label class="custom-control-label" for="customCheck-feature{{ $key }}">{{ ucwords($f->feature_name) }}
                                                                                @if ($f->info != null || $f->info != '')
                                                                                    <i class="fe-info" data-toggle="tooltip"
                                                                                        data-placement="top" title="{{ $f->info }}"
                                                                                        data-original-title="{{ $f->info }}"></i>
                                                                                @endif
                                                                            </label>
                                                                        </div>
                                                                        @php $feat++; @endphp

                                                                        @foreach ($sub as $key3=>$s)
                                                                            @if($s->sub_feature == $f->feature)
                                                                                <div class="checkbox checkbox-success pl-2 mb-2 ml-3">
                                                                                    <input type="checkbox" id="customCheck-sub{{ $key3 }}" name="permission[]" value="{{ $s->id }}">
                                                                                    <label class="custom-control-label" for="customCheck-sub{{ $key3 }}">{{ ucwords($s->feature_name) }}</label>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach

                                                                @if($feat == 0)
                                                                    @foreach($permission as $key2=>$per)
                                                                        @if($per->menu_name == $group->menu_name)
                                                                            <div class="checkbox checkbox-success pl-3 mb-2">
                                                                                <input type="checkbox" id="customCheck-permission{{ $key2 }}" name="permission[]" value="{{ $per->id }}">
                                                                                <label class="custom-control-label" for="customCheck-permission{{ $key2 }}">{{ ucwords($per->feature_name) }}
                                                                                    @if ($per->info != null || $per->info != '')
                                                                                        <i class="fe-info" data-toggle="tooltip"
                                                                                            data-placement="top" title="{{ $per->info }}"
                                                                                            data-original-title="{{ $per->info }}"></i>
                                                                                    @endif
                                                                                </label>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    @foreach($otherpermission as $key1=>$other)
                                                                        @if($other->menu_name == $group->menu_name)
                                                                            <div class="checkbox checkbox-success pl-2 mb-2">
                                                                                <input type="checkbox" id="customCheck-other{{ $key1 }}" name="permission[]" value="{{ $other->id }}">
                                                                                <label class="custom-control-label" for="customCheck-other{{ $key1 }}">{{ ucwords($other->feature_name) }}
                                                                                    @if ($other->info != null || $other->info != '')
                                                                                        <i class="fe-info" data-toggle="tooltip"
                                                                                            data-placement="top" title="{{ $other->info }}"
                                                                                            data-original-title="{{ $other->info }}"></i>
                                                                                    @endif
                                                                                </label>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach  
                                                    </tbody>                             
                                                </table>
                                        </div>
                                        <!-- <div class="row">
                                            @foreach($permission as $p)
                                            @if($loop->iteration % 2 == 1)
                                            <div class="row">
                                            @endif
                                            <div class="col-md-3">
                                                <div class="form-check bg-checkbox">
                                                    <input type="checkbox" name="permission[]" value="{{ $p->id }}" {{ $p->id != null && in_array($p->id, old('permission', [])) ? 'checked' : '' }} class="cust-checkbox">
                                                    <label class="form-check-label label-color">&nbsp;{{ ucfirst($p->menu_name) }}</label>
                                                </div>
                                            </div>
                                            @if($loop->iteration % 2 == 0 || $loop->last)
                                            </div>
                                            @endif
                                            @endforeach
                                            @if ($errors->has('permission'))
                                                <span class="text-danger">{{ $errors->first('permission') }}</span>
                                            @endif
                                        </div> -->
                                    
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                                            <button class="btn btn-primary" style="margin-right: 10px;">Save</button>
                                            <a href="{{route('role')}}" class="btn btn-danger">Cancel</a>
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
