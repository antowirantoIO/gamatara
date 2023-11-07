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
                                <form action="{{route('project_manager.updated',$data->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-xxl-12">
                                            <label for="pm">Project Manager Name</label>
                                            <select name="pm" id="pm" class="form-control">
                                                <option value="">Choose Project Manager</option>
                                                @foreach($karyawan as $k)
                                                <option value="{{$k->id}}" {{ in_array($k->id, $notSelected) ? 'disabled' : '' }}>{{$k->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('pm'))
                                                <span class="text-danger">{{ $errors->first('pm') }}</span>
                                            @endif
                                        </div>

                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label>Project Engineer Name</label>&nbsp;
                                                <select name="pe[]" id="pe" class="form-control js-example-basic-multiple" multiple="multiple">
                                                    <option value="">Choose Project Engineer</option>
                                                    @foreach($karyawan as $k)
                                                    <option value="{{$k->id}}" {{ in_array($k->id, $notSelected) ? 'disabled' : '' }}>{{$k->name}}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('pe'))
                                                    <span class="text-danger">{{ $errors->first('pe') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label>Project Admin Name</label>&nbsp;
                                                <select name="pa[]" id="pa" class="form-control js-example-basic-multiple" multiple="multiple">
                                                    <option value="">Choose Project Admin</option>
                                                    @foreach($karyawan as $k)
                                                    <option value="{{$k->id}}" {{ in_array($k->id, $notSelected) ? 'disabled' : '' }}>{{$k->name}}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('pa'))
                                                    <span class="text-danger">{{ $errors->first('pa') }}</span>
                                                @endif
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
@endsection

@section('scripts')
<script>
    $(function () {
        $("select").select2();
    });

    const pmSelect = $('select[name="pm"]');
    const peSelect = $('select[name="pe[]"]');
    const paSelect = $('select[name="pa[]"]');

    function disableOptions(selectElement, valuesToDisable) {
        selectElement.find('option').each(function () {
            const optionValue = $(this).val();
            if (valuesToDisable.includes(optionValue)) {
                $(this).prop('disabled', true);
            } else {
                $(this).prop('disabled', false);
            }
        });
    }

    pmSelect.val({{$data->id_karyawan}}).trigger('change')
    peSelect.val({{ json_encode($selectedPE) }}).trigger('change')
    paSelect.val({{ json_encode($selectedPA) }}).trigger('change')
    
    function handleChangeAllSelect() {
        const selectedPE = peSelect.val();
        const selectedPA = paSelect.val();
        const selectedPM = pmSelect.val();

        console.log(selectedPE, selectedPA, selectedPM)

        let notSelected = {{json_encode($notSelected)}}
        notSelected = notSelected.map((item) => item.toString());
        
        let disabledOption = [
            ...notSelected,
            ...selectedPE,
            ...selectedPA,            
            selectedPM,
        ];

        // console.log(disabledOption?.filter((item) => {
        //     return selectedPE?.includes(item)
        // }))

        disableOptions(peSelect, disabledOption?.filter((item) => {
            return !selectedPE?.includes(item)
        }));
        disableOptions(paSelect,  disabledOption?.filter((item) => {
            return !selectedPA?.includes(item)
        }));
        disableOptions(pmSelect, disabledOption?.filter((item) => {
            return selectedPM !== item
        }));
    }
    handleChangeAllSelect();

    pmSelect.on('change', function () {
        handleChangeAllSelect();
    });

    peSelect.on('change', function () {
        handleChangeAllSelect();
    });

    paSelect.on('change', function () {
        handleChangeAllSelect();
    });
</script>
@endsection