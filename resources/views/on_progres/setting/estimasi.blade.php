@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <a href="{{route('on_request')}}">
                                <i><img src="{{asset('assets/images/arrow-left.svg')}}" style="width: 20px;"></i>
                            </a>
                            <h4 class="mb-0 ml-2"> &nbsp; Estimasi Pekerjaan</h4>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between">
                        <ul class="nav nav-tabs gap-3" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Umum</button>
                            </li>
                            <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Perawatan Badan Kapal</button>
                            </li>
                            <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Kontruksi Kapal</button>
                            </li>
                            <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Pipa - Pipa</button>
                            </li>
                            <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Permesinan</button>
                            </li>
                            <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Interior Kapal</button>
                            </li>
                            <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Lain - Lain</button>
                            </li>
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
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                            <span class="fs-5"><strong>Pekerjaan Umum</strong></span>
                                            <table class="table" id="example1">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="color:#929EAE;width:600px;">Pekerjaan</th>
                                                        <th style="color:#929EAE">Progres</th>
                                                        <th style="color:#929EAE">Vendor</th>
                                                        <th style="color:#929EAE">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Diver service naik / turun</td>
                                                        <td>1 / 1</td>
                                                        <td>CV DUA PUTRA</td>
                                                        <td>
                                                            <a href="{{ route('setting.estimasi.detail') }}" class="btn btn-warning btn-sm">
                                                                <span>
                                                                    <i><img src="{{asset('assets/images/eye.svg')}}" style="width: 15px;"></i>
                                                                </span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
                                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
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
