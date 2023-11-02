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
                                        <div class="col-xxl-12">
                                            <label for="pm">Nama Project Manager</label>
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
                                                <label>Nama Project Engineer</label>&nbsp;
                                                <select name="pe[]" id="pe" class="form-control js-example-basic-multiple" multiple="multiple">
                                                    <option value="">Pilih Project Engineer</option>
                                                    @foreach($karyawan as $k)
                                                    <option value="{{$k->id}}">{{$k->name}}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('pe'))
                                                    <span class="text-danger">{{ $errors->first('pe') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-xxl-6 col-md-6">
                                            <div>
                                                <label>Nama Project Admin</label>&nbsp;
                                                <select name="pa[]" id="pa" class="form-control js-example-basic-multiple" multiple="multiple">
                                                    <option value="">Pilih Project Admin</option>
                                                    @foreach($karyawan as $k)
                                                    <option value="{{$k->id}}">{{$k->name}}</option>
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
    <script>   $(function () {
        $("select").select2();
    });

    const pmSelect = $('#pm');
    const peSelect = $('#pe');
    const paSelect = $('#pa');

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

    function handleChangeAllSelect() {
        const selectedPE = peSelect.val();
        const selectedPA = paSelect.val();
        const selectedPM = pmSelect.val();

        let notSelected = {{ json_encode($selected) }};
        notSelected = notSelected.map((item) => item.toString());

        let disabledOption = [...notSelected];

        if (selectedPM.length > 0) {
            disableOptions(peSelect, selectedPM);
            disableOptions(paSelect, selectedPM);
        } else {
            disableOptions(peSelect, []);
            disableOptions(paSelect, []);
        }

        disableOptions(pmSelect, selectedPE.concat(selectedPA,disabledOption));
        disableOptions(peSelect, selectedPM.concat(selectedPA,disabledOption));
        disableOptions(paSelect, selectedPM.concat(selectedPE,disabledOption));
    }

    pmSelect.on('change', function () {
        handleChangeAllSelect();
    });

    peSelect.on('change', function () {
        handleChangeAllSelect();
    });

    paSelect.on('change', function () {
        handleChangeAllSelect();
    });

    handleChangeAllSelect();
    // const pmSelect = $('#pm');
    // const peSelect = $('#pe');
    // const paSelect = $('#pa');

    // function disablePM(selectedPE, selectedPA) {
    //     pmSelect.find('option').each(function () {
    //         const optionValue = $(this).val();
    //         if (selectedPE.includes(optionValue) || selectedPA.includes(optionValue)) {
    //             $(this).prop('disabled', true);
    //         } else {
    //             $(this).prop('disabled', false);
    //         }
    //     });
    // }

    // function disableOptions(selectElement, valuesToDisable) {
    //     selectElement.find('option').each(function () {
    //         const optionValue = $(this).val();
    //         if (valuesToDisable.includes(optionValue)) {
    //             $(this).prop('disabled', true);
    //         } else {
    //             $(this).prop('disabled', false);
    //         }
    //     });
    // }

    // pmSelect.on('change', function () {
    //     const selectedPM = pmSelect.val();
    //     const selectedPE = peSelect.val();
    //     const selectedPA = paSelect.val();

    //     disableOptions(peSelect, selectedPM);
    //     disableOptions(paSelect, selectedPM);

    //     peSelect.select2();
    //     paSelect.select2();
    // });

    // peSelect.on('change', function () {
    //     const selectedPE = peSelect.val();
    //     const selectedPA = paSelect.val();
    //     const selectedPM = pmSelect.val();

    //     disablePM(selectedPE, selectedPA);

    //     paSelect.find('option').each(function () {
    //         if (selectedPE.includes($(this).val())) {
    //             $(this).prop('disabled', true);
    //         } else if(selectedPM.includes($(this).val())){
    //             $(this).prop('disabled', true);
    //         } else {
    //             $(this).prop('disabled', false);
    //         }
    //     });

    //     paSelect.select2();
    // });

    // paSelect.on('change', function () {
    //     const selectedPE = peSelect.val();
    //     const selectedPA = paSelect.val();
    //     const selectedPM = pmSelect.val();

    //     disablePM(selectedPE, selectedPA);

    //     peSelect.find('option').each(function () {
    //         if (selectedPA.includes($(this).val())) {
    //             $(this).prop('disabled', true);
    //         }else if(selectedPM.includes($(this).val())){
    //             $(this).prop('disabled', true);
    //         } else {
    //             $(this).prop('disabled', false);
    //         }
    //     });

    //     peSelect.select2();
    // });

    // peSelect.select2();
    // paSelect.select2();
    // pmSelect.select2();

    // $(function () {
    //     $("select").select2();
    // });
</script>
@endsection