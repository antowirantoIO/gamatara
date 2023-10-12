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
                            <h4 class="mb-0 ml-2"> &nbsp; Tagihan Customer</h4>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between">
                        <ul class="nav nav-tabs gap-3" id="myTab" role="tablist">
                            @foreach ($kategori as $key => $item)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $key === 0 ? 'active' : '' }} rounded-pill" id="{{ $item->id }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $item->id }}" type="button" role="tab" aria-controls="{{ $item->id }}" aria-selected="true">{{ $item->name }}</button>
                                </li>
                            @endforeach
                        </ul>
                        <div>
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
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="live-preview">
                                <div class="col-md-12">
                                    @foreach ($workers as $key => $worker)
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show {{ $key === 0 ? 'active' : '' }}" id="{{ $key }}" role="tabpanel" aria-labelledby="{{ $key }}-tab">
                                            <span class="fs-5"><strong>Pekerjaan {{ getNameKategori($key) }}</strong></span>
                                            <table class="table" id="example1">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="color:#929EAE;">Pekerjaan</th>
                                                        <th style="color:#929EAE">Lokasi</th>
                                                        <th style="color:#929EAE">Detail / Other</th>
                                                        <th style="color:#929EAE">Length (mm)</th>
                                                        <th style="color:#929EAE">Width (mm)</th>
                                                        <th style="color:#929EAE">Thick (mm)</th>
                                                        <th style="color:#929EAE">Qty</th>
                                                        <th style="color:#929EAE">Amount</th>
                                                        <th style="color:#929EAE">Unit</th>
                                                        <th style="color:#929EAE">Total Harga</th>
                                                        <th style="color:#929EAE">Total Tagihan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $total = 0;
                                                    @endphp
                                                    @foreach ($worker as $value)
                                                        @php
                                                            $harga_customer = $value->pekerjaan->harga_customer;
                                                            $total += $harga_customer;
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $value->subKategori->name }}</td>
                                                            <td>{{ $value->projects->lokasi->name }}</td>
                                                            <td>{{ $value->detail }}</td>
                                                            <td>{{ $value->length }}</td>
                                                            <td>{{ $value->width }}</td>
                                                            <td>{{ $value->thick }}</td>
                                                            <td>{{ $value->qty }}</td>
                                                            <td>{{ $value->amount }}</td>
                                                            <td>{{ $value->unit }}</td>
                                                            <td>Rp. {{ number_format($value->pekerjaan->harga_customer, 0, ',', '.') }}</td>
                                                            <td>Rp. {{ number_format($value->pekerjaan->harga_customer, 0, ',', '.') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="d-flex jsutify-content-start align-items-center gap-3 fs-4">
                                                <strong>Total Tagihan</strong> :
                                                <strong>Rp. {{ number_format($total, 0, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
                                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                                    </div>
                                @endforeach
                                </div>
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
        })
    </script>
@endsection
