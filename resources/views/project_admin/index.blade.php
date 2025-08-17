@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <h4 class="mb-0 ml-2"> &nbsp; Project Admin</h4>
                        </div>
                     
                        <div class="mt-3 mt-lg-0 ml-lg-auto">
                            @can('project_admin-add')
                            <a href="{{ route('project_admin.create') }}" class="btn btn-secondary">
                                <span><i class="mdi mdi-plus"></i></span> &nbsp; Add
                            </a>
                            @endcan
                            <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#advance">
                                <span>
                                    <i><img src="{{asset('assets/images/filter.svg')}}" style="width: 15px;"></i>
                                </span> &nbsp; Filter
                            </button>
                            <button class="btn btn-danger" id="export-button">
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
                            <h4 class="card-title mb-0 flex-grow-1">Project Admin</h4>
                            <div>

                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="row">
                                    <table class="table" id="tableData">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="color:#929EAE">Project Name</th>
                                                @foreach($projectAdmins as $pa)
                                                <th style="color:#929EAE" class="text-center">{{ $pa->karyawan->name }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                            <tr class="table-info">
                                                <th><strong>Total Progress</strong></th>
                                                @foreach($projectAdmins as $pa)
                                                <th class="text-center" id="total-progress-{{ $pa->id }}">0</th>
                                                @endforeach
                                            </tr>
                                            <tr class="table-success">
                                                <th><strong>Total Completed</strong></th>
                                                @foreach($projectAdmins as $pa)
                                                <th class="text-center" id="total-completed-{{ $pa->id }}">0</th>
                                                @endforeach
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!--modal-->
<div id="advance" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form  id="formProjectAdmin" method="get" enctype="multipart/form-data">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="zoomInModalLabel">Filter Projects</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div>
                                <label for="nama_project">Project Name</label>
                                <input type="text" name="nama_project" id="nama_project" class="form-control">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div>
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control" style="width: 52%;">
                                    <option value="">All Status</option>
                                    <option value="1">On Progress</option>
                                    <option value="2">Completed</option>
                                    <option value="0">Pending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="applyFilter()">Apply Filter</button>
                    <button type="button" class="btn btn-warning" onclick="resetFilter()">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end modal-->
@endsection

@section('scripts')
<script type="text/javascript">
    // PHP data for JavaScript
    window.paData = {
        ids: [@foreach($projectAdmins as $index => $pa){{ $pa->id }}@if(!$loop->last),@endif @endforeach],
        route: @json(route('report.project_admin')),
        exportRoute: @json(route('report.project_admin.export'))
    };
</script>

<script>
     $(document).ready(function () {
        let filterSearch = '';
        var paIds = window.paData.ids;
        
        // Build columns dynamically
        var columns = [
            { data: 'project_name', name: 'project_name' }
        ];
        
        // Add PA columns
        for (var i = 0; i < paIds.length; i++) {
            var paId = paIds[i];
            columns.push({
                data: 'pa_' + paId,
                name: 'pa_' + paId,
                className: 'text-center',
                orderable: false
            });
        }
        
        var table = $('#tableData').DataTable({
            ordering: false,
            fixedHeader:true,
            scrollX: false,
            processing: true,
            serverSide: true,
            searching: true,
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
                updateTotals();
            },
            ajax: {
                url: window.paData.route,
                data: function (d) {
                    filterSearch        = d.search?.value;
                    d.project_name      = $('#nama_project').val();
                    d.status            = $('#status').val();
                }
            },
            columns: columns
        });

        function updateTotals() {
            // Reset totals
            for (var i = 0; i < paIds.length; i++) {
                var paId = paIds[i];
                $('#total-progress-' + paId).text('0');
                $('#total-completed-' + paId).text('0');
            }

            // Calculate totals from current page data
            var data = table.rows({page: 'current'}).data();
            var totals = {};
            
            for (var i = 0; i < paIds.length; i++) {
                var paId = paIds[i];
                totals['progress_' + paId] = 0;
                totals['completed_' + paId] = 0;
            }

            for (var i = 0; i < data.length; i++) {
                var row = data[i];
                for (var j = 0; j < paIds.length; j++) {
                    var paId = paIds[j];
                    var cellValue = row['pa_' + paId];
                    if (cellValue === '●') {
                        totals['progress_' + paId]++;
                    } else if (cellValue === '✓') {
                        totals['completed_' + paId]++;
                    }
                }
            }

            // Update footer
            for (var i = 0; i < paIds.length; i++) {
                var paId = paIds[i];
                $('#total-progress-' + paId).text(totals['progress_' + paId]);
                $('#total-completed-' + paId).text(totals['completed_' + paId]);
            }
        }

        $('.form-control').on('change', function() {
            table.draw();
        });

        function hideOverlay() {
            $('.loading-overlay').fadeOut('slow', function() {
                $(this).remove();
            });
        }

        $('#export-button').on('click', function(event) {
            event.preventDefault();

            var nama_project    = $('#nama_project').val();
            var status          = $('#status').val();

            var url = window.paData.exportRoute + '?' + $.param({
                project_name    : nama_project,
                status          : status,
                keyword         : filterSearch
            });

            $('.loading-overlay').show();

            window.location.href = url;

            setTimeout(hideOverlay, 2000);
        });

        // Make functions available globally
        window.updateTotals = updateTotals;
        window.projectAdminTable = table;

        $(document).ready(function() {
            $('.loading-overlay').hide();
        });
    });

    function applyFilter() {
        window.projectAdminTable.ajax.reload();
        $('#advance').modal('hide');
    }

    function resetFilter() {
        $('#formProjectAdmin')[0].reset();
        window.projectAdminTable.ajax.reload();
        $('#advance').modal('hide');
    }
</script>
@endsection

