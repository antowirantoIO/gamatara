    @can('on_request-request-printrekapan')
        @if($keluhan == 0)
            <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                <button type="button" id="printSPK" data-id-keluhan="" class="btn" style="background-color:grey;" disabled>
                    <span>
                        <i><img src="{{asset('assets/images/directbox.svg')}}" style="width: 15px;"></i>
                    </span>
                    Rekap SPK
                </button>
            </div>
        @else
            @if($keluhan != null && $count == $keluhan) 
            <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                <button type="button" id="printSPK" data-id-keluhan="" class="btn btn-danger" onclick="openNewTab()">
                    <span>
                        <i><img src="{{asset('assets/images/directbox.svg')}}" style="width: 15px;"></i>
                    </span>
                    Rekap SPK
                </button>
            </div>
            @elseif($count != $keluhan)
            <div class="flex-grow-1 d-flex align-items-center justify-content-end">
                <button type="button" id="printSPK" data-id-keluhan="" class="btn" style="background-color:grey;" disabled>
                    <span>
                        <i><img src="{{asset('assets/images/directbox.svg')}}" style="width: 15px;"></i>
                    </span>
                    Rekap SPK
                </button>
            </div>
            @endif
        @endif
    @endcan

    <table id="tabelKeluhan" class="table table-bordered">
        <thead style="background-color:#194BFB;color:#FFFFFF">
            <tr>
                <th width="10px">No.</th>
                <th>Request</th>
                <th>Vendor</th>
                <th>PM</th>
                <th>BOD</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($keluhans as $key => $complaint)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td> 
                        <a type="button" data-bs-toggle="modal" data-bs-target="#opo-{{$complaint->id}}"> 
                            {{ explode('<br>', $complaint->keluhan)[0] ?? '' }}
                        </a>
                    </td>
                    <td>{{ $complaint->vendors->name ?? ''}}</td>
                    <td>
                        @can('on_request-request-approval-pm')
                            @if($complaint->id_pm_approval == null && $complaint->id_bod_approval == null)
                                <button type="button" class="btn btn-primary btn-sm" onclick="approve({{$complaint->id}}, 'PM')">
                                    Approve
                                </button>
                            @else
                                <button type="button" class="btn" style="background-color:grey;" disabled data-keluhan-id="{{ $complaint->id }}">
                                    Approved
                                </button>
                            @endif
                        @endcan
                        @can('on_request-request-approval-bod')
                            @if($complaint->id_pm_approval != null)
                                <button type="button" class="btn" style="background-color:grey;" disabled data-keluhan-id="{{ $complaint->id }}">
                                    Approved
                                </button>
                            @endif
                        @endcan
                        @can('on_request-request-view-approval')
                            @if($complaint->id_pm_approval != null)
                                Approved
                            @endif
                        @endcan
                    </td>
                    <td> 
                        @can('on_request-request-approval-bod')
                            @if($complaint->id_pm_approval != null && $complaint->id_bod_approval == null)
                                <button type="button" class="btn btn-primary btn-sm" data-keluhan-id="{{ $complaint->id }}" onclick="approve({{$complaint->id}}, 'BOD')">
                                    Approve
                                </button>
                            @elseif($complaint->id_bod_approval != null && $complaint->id_pm_approval != null)
                                <button type="button" class="btn" style="background-color:grey;" disabled data-keluhan-id="{{ $complaint->id }}">
                                    Approved
                                </button>
                            @endif
                        @endcan
                        @can('on_request-request-approval-pm')
                            @if($complaint->id_bod_approval != null)
                                <button type="button" class="btn" style="background-color:grey;" disabled data-keluhan-id="{{ $complaint->id }}">
                                    Approved
                                </button>
                            @endif
                        @endcan
                        @can('on_request-request-view-approval')
                            @if($complaint->id_bod_approval != null)
                                Approved
                            @endif
                        @endcan
                    </td>
                    <td>
                        @can('on_request-request-edit')
                            <button type="button" class="btn btn-sm btnEdit" 
                                @if($complaint->id_pm_approval != null && $complaint->id_bod_approval != null) 
                                    style="background-color:grey; 
                                    disabled 
                                @else 
                                    style="background-color:#FFBC39;" 
                                @endif 
                                onclick="setEditData({{$complaint->id}}, {{$complaint->id_vendor}})"
                                >
                                <span>
                                    <i><img src="{{asset('assets/images/edit-2.svg')}}" style="width: 15px;"></i>
                                </span>
                            </button>
                        @endcan
                        @can('on_request-request-printspk-pa')
                            <a type="button" class="btn btn-sm btnPrint"
                                @if($complaint->id_pm_approval != null && $complaint->id_bod_approval != null) 
                                    style="background-color:blue;" 
                                    id="printSPKsatuan"
                                    target="_blank"
                                    href="{{route('keluhan.satuan',$complaint->id)}}"
                                @else 
                                    style="background-color:grey; 
                                    disabled 
                                @endif 
                                data-keluhan-id="{{ $complaint->id }}"
                            >
                                <span>
                                    <i><img src="{{asset('assets/images/directbox.svg')}}" style="width: 15px;"></i>
                                </span>
                            </a>
                        @endcan
                        @can('on_request-request-printspk-bod')
                            <a type="button" class="btn btn-sm btnPrint" id="printSPKsatuan" target="_blank" href="{{route('keluhan.satuan',$complaint->id)}}"" style="background-color:blue;" data-keluhan-id="{{ $complaint->id }}">
                                <span>
                                    <i><img src="{{asset('assets/images/directbox.svg')}}" style="width: 15px;"></i>
                                </span>
                            </a>
                        @endcan
                        @can('on_request-request-delete-pm-bod')
                            <button type="button" class="btn btn-danger btn-sm btnHapus" data-keluhan-id="{{ $complaint->id }}" onclick="hapusKeluhan({{$complaint->id}})">
                                <span>
                                    <i><img src="{{asset('assets/images/trash.svg')}}" style="width: 15px;"></i>
                                </span>
                            </button>
                        @endcan
                        @can('on_request-request-delete-pa')
                            <button type="button" class="btn btn-sm" 
                                @if($complaint->id_pm_approval != null && $complaint->id_bod_approval != null) 
                                    style="background-color:grey; 
                                    disabled 
                                @else 
                                    style="background-color:#FF6666;" 
                                    onclick="hapusKeluhan({{$complaint->id}})"
                                @endif 
                                data-keluhan-id="{{ $complaint->id }}"
                                >
                                <span>
                                    <i><img src="{{asset('assets/images/trash.svg')}}" style="width: 15px;"></i>
                                </span>
                            </button>
                        @endcan
                    </td>
                </tr>

                <!--modal -->
                <div class="modal fade" id="opo-{{ $complaint->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalgridLabel">Detail Request</h5>
                            </div>
                            <hr>
                            <div class="modal-body">
                                {!! nl2br(str_replace('<br>', "\n", $complaint->keluhan)) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <!--end modal-->
                @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function () {
        var table = $('#tabelKeluhan').DataTable({
            fixedHeader:true,
            scrollX: false,
            searching: false,
            lengthMenu: [5, 10, 15],
            pageLength: 5,
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
        });
    });
    </script>