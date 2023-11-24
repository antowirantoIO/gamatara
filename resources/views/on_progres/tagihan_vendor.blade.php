@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('on_progres.tagihan.all',$id)}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Vendor Bills</h4>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between">
                        <ul class="nav nav-tabs gap-3" id="myTab" role="tablist">
                            @foreach ($workers as $key => $worker)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link btn-filter-category {{ $loop->first ? 'active' : '' }} rounded-pill" data-category_id="{{ $worker->id }}"  data-name="{{ $worker->name }}" data-bs-target="#{{ $key }}" type="button">{{ $worker->name }}</button>
                                </li>
                            @endforeach
                        </ul>
                   </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="live-preview">
                                <div class="col-md-12">
                                    <div>
                                        <div class="tab-pane" role="tabpanel">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="fs-5"><strong>Pekerjaan</strong></span>
                                                <div>
                                                    <button class="btn btn-secondary" id="btn-fillter">
                                                        <span>
                                                            <i><img src="{{asset('assets/images/filter.svg')}}" style="width: 15px;"></i>
                                                        </span> &nbsp; Filter
                                                    </button>
                                                </div>
                                            </div>
                                            <table class="table w-100" id="tableData">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="color:#929EAE;">Job</th>
                                                        <th style="color:#929EAE;">Kategori</th>
                                                        <th style="color:#929EAE">Location</th>
                                                        <th style="color:#929EAE">Detail / Other</th>
                                                        <th style="color:#929EAE">Length (mm)</th>
                                                        <th style="color:#929EAE">Width (mm)</th>
                                                        <th style="color:#929EAE">Thick (mm)</th>
                                                        <th style="color:#929EAE">Qty</th>
                                                        <th style="color:#929EAE">Amount</th>
                                                        <th style="color:#929EAE">Unit</th>
                                                        <th style="color:#929EAE">Unit Price</th>
                                                        <th style="color:#929EAE">Total Bills</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                            <div class="d-flex jsutify-content-start align-items-center gap-3 fs-4">
                                                <strong>Total Bills</strong> :
                                                <strong class="tagihan"></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div id="modalFillter" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-top-right">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gy-4">
                    <div class="col-xxl-6 col-md-6">
                        <div>
                            <label for="sub_kategori" class="form-label">Job Name</label>
                            <select name="sub_kategori" id="sub_kategori" class="form-select">
                                <option value="">Choose Job Name</option>
                                @foreach($subKategori as $sub)
                                <option value="{{$sub->id}}">{{$sub->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xxl-6 col-md-6">
                        <div>
                            <label for="id_lokasi" class="form-label">Location Name</label>
                            <input name="id_lokasi" id="id_lokasi" class="form-control"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn btn-danger" id="btn-reset" style="margin-right: 10px;">Reset</div>
                <button class="btn btn-primary" id="btn-search">Search</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            let filterData = {};
            let modalInput = $('#modalFillter');
            let btnFilterCategory = $('.btn-filter-category');

            var table = $('#tableData').DataTable({
                fixedHeader:true,
                scrollX: true,
                processing: true,
                serverSide: true,
                searching: false,
                bLengthChange: false,
                language: {
                    processing:
                        '<div class="spinner-border text-info" role="status">' +
                        '<span class="sr-only">Loading...</span>' +
                        "</div>",
                    paginate: {
                        Search: '<i class="icon-search"></i>',
                        first: "<i class='fas fa-angle-double-left'></i>",
                        next: "Next <span class='mdi mdi-chevron-right'></span>",
                        last: "<i class='fas fa-angle-double-right'></i>",
                    },
                    "info": "Displaying _START_ - _END_ of _TOTAL_ result",
                },
                drawCallback: function() {
                    var previousButton = $('.paginate_button.previous');
                    previousButton.css('display', 'none');
                },
                ajax : {
                    url : '{{ route('ajax.tagihan-vendor') }}',
                    method : 'GET',
                    data : function(d){
                        d._token = '{{ csrf_token() }}';
                        d.id_project = '{{ $id }}';
                        d.id_kategori = filterData.category_id;
                        d.sub_kategori = $('#sub_kategori').val();
                        d.id_lokasi = $('#id_lokasi').val();
                        d.id_vendor = '{{ $vendor }}'
                    },
                    complete : function(d){
                        let data = d.responseJSON.data;
                        let amount = data.reduce((accumulator, currentValue) => {
                            return accumulator + (currentValue.harga_vendor * currentValue.amount);
                        }, 0);
                        console.log(amount,data);
                        $('.tagihan').text(rupiah(amount))
                    }
                },
                columns : [
                    { data : 'pekerjaan', name : 'pekerjaan'},
                    { data : 'subKategori', name : 'subKategori'},
                    { data : 'id_lokasi', name : 'id_lokasi'},
                    {data : 'detail', name : 'detail'},
                    {data : 'length', name : 'length'},
                    {data : 'width', name : 'width'},
                    {data : 'thick', name : 'thick'},
                    {data : 'qty', name : 'qty'},
                    {data : 'amount', name : 'amount'},
                    {data : 'unit', name : 'unit'},
                    {
                        data : function(data){
                            if(data.harga_vendor !== null){
                                let amount = data.harga_vendor || '-';
                                return rupiah(amount);
                            }else{
                                return 0;
                            }
                        }
                    },
                    {
                        data : function(data){
                            if(data.harga_vendor !== null){
                                let harga = data.harga_vendor || '-';
                                let amount = data.amount;
                                let total = amount * harga;
                                return rupiah(total);
                            }else{
                                return 0;
                            }
                        }
                    }
                ]
            })
            handleFiltertab($('.btn-filter-category.active'));

            btnFilterCategory.on('click', function(){
                handleFiltertab($(this))
            })

            function handleFiltertab(element){
                const categoryId = element.data('category_id'),
                      name = element.data('name');
                btnFilterCategory.removeClass('active')
                element.addClass('active');
                filterData = {
                    category_id : categoryId,
                    name_project: name
                }
                table.draw();
            }

            $('.form-select').select2({
                theme : "bootstrap-5",
                search: true,
                dropdownParent: $("#modalFillter")
            });
            $('#btn-fillter').click(function(){
                modalInput.modal('show');
            })
            $('#btn-search').on('click',function(e){
                e.preventDefault();
                table.draw();
            });


            $('#btn-reset').click(function(e){
                e.preventDefault();
                $('.form-control').val('');
                $('.form-select').val(null).trigger('change');
                table.draw();
            })
            const rupiah = (number)=>{
                var	reverse = number.toString().split('').reverse().join(''),
                ribuan 	= reverse.match(/\d{1,3}/g);
                ribuan	= ribuan.join('.').split('').reverse().join('');
                return ribuan;
            }
        })
    </script>
@endsection
