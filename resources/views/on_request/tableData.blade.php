<table id="tabelKeluhan" class="table table-bordered">
                                                <thead style="background-color:#194BFB;color:#FFFFFF">
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Request</th>
                                                        <th>Vendor</th>
                                                        <th>PM</th>
                                                        <th>BOD</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($keluhan as $key => $complaint)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ explode('<br>', $complaint->keluhan)[0] ?? '' }}</td>
                                                            <td>{{ $complaint->vendor->name ?? ''}}</td>
                                                            <td>
                                                                @if($pmAuth == 'Project Manager' || $pmAuth == 'PM')
                                                                    @if($complaint->id_pm_approval == null)
                                                                        <button type="button" class="btn btn-primary btn-sm" onclick="approve({{$complaint->id}}, 'PM')">
                                                                            Approve
                                                                        </button>
                                                                    @else
                                                                        <button type="button" class="btn btn-sm" style="background-color:grey;" disabled data-keluhan-id="{{ $complaint->id }}">
                                                                            Approved
                                                                        </button>
                                                                    @endif
                                                                @elseif($pmAuth == 'BOD')
                                                                    <button type="button" class="btn btn-sm" style="background-color:grey;" disabled data-keluhan-id="{{ $complaint->id }}">
                                                                        Approved
                                                                    </button>
                                                                @elseif($pmAuth == 'Project Admin' || $pmAuth == 'PA')
                                                                    @if($complaint->id_pm_approval != null)
                                                                        Approved
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td> 
                                                                @if($pmAuth == 'BOD')
                                                                    @if($complaint->id_bod_approval == null && $complaint->id_pm_approval != null)
                                                                        <button type="button" class="btn btn-primary btn-sm" data-keluhan-id="{{ $complaint->id }}" onclick="approve({{$complaint->id}}, 'BOD')">
                                                                            Approve
                                                                        </button>
                                                                    @elseif($complaint->id_bod_approval == null && $complaint->id_pm_approval == null)
                                                                        
                                                                    @else
                                                                    <button type="button" class="btn btn-sm" style="background-color:grey;" disabled data-keluhan-id="{{ $complaint->id }}">
                                                                            Approved
                                                                        </button>
                                                                    @endif
                                                                @elseif($pmAuth == 'Project Manager')
                                                                <button type="button" class="btn btn-sm" style="background-color:grey;" disabled data-keluhan-id="{{ $complaint->id }}">
                                                                    Approved
                                                                </button>
                                                                @elseif($pmAuth == 'Project Admin' || $pmAuth == 'PA')
                                                                    @if($complaint->id_bod_approval != null)
                                                                        Approved  
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($pmAuth == 'Project Admin' || $pmAuth == 'PA' || $pmAuth == 'BOD')
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
                                                                @endif
                                                                @if($pmAuth == 'Project Admin' || $pmAuth == 'PA')
                                                                    <button type="button" class="btn btn-sm btnPrint" 
                                                                        @if($complaint->id_pm_approval != null && $complaint->id_bod_approval != null) 
                                                                            style="background-color:blue;" 
                                                                        @else 
                                                                            style="background-color:grey; 
                                                                            disabled 
                                                                        @endif 
                                                                        data-keluhan-id="{{ $complaint->id }}"
                                                                    >
                                                                        <span>
                                                                            <i><img src="{{asset('assets/images/directbox.svg')}}" style="width: 15px;"></i>
                                                                        </span>
                                                                    </button>
                                                                @endif
                                                                @if($pmAuth == 'BOD')
                                                                    <button type="button" class="btn btn-sm btnPrint" style="background-color:blue;" data-keluhan-id="{{ $complaint->id }}">
                                                                        <span>
                                                                            <i><img src="{{asset('assets/images/directbox.svg')}}" style="width: 15px;"></i>
                                                                        </span>
                                                                    </button>
                                                                @endif
                                                                @if($pmAuth == 'Project Manager' || $pmAuth == 'PM' || $pmAuth == 'BOD')
                                                                    <button type="button" class="btn btn-danger btn-sm btnHapus" data-keluhan-id="{{ $complaint->id }}">
                                                                        <span>
                                                                            <i><img src="{{asset('assets/images/trash.svg')}}" style="width: 15px;"></i>
                                                                        </span>
                                                                    </button>
                                                                @else
                                                                    <button type="button" class="btn btn-sm btnHapus" 
                                                                        @if($complaint->id_pm_approval != null && $complaint->id_bod_approval != null) 
                                                                            style="background-color:grey; 
                                                                            disabled 
                                                                        @else 
                                                                            style="background-color:#FF6666;" 
                                                                        @endif 
                                                                        data-keluhan-id="{{ $complaint->id }}"
                                                                        >
                                                                        <span>
                                                                            <i><img src="{{asset('assets/images/trash.svg')}}" style="width: 15px;"></i>
                                                                        </span>
                                                                    </button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                </tbody>
                                            </table>