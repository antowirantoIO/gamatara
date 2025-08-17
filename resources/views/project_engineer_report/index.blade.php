@extends('index')

@section('content')

<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <h4 class="mb-0 ml-2"> &nbsp; Project Engineer Report</h4>
                        </div>
                        <div class="mt-3 mt-lg-0 ml-lg-auto">
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
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="row">
                                    <table class="table" id="tableData">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="color:#929EAE">Project Name</th>
                                            @foreach($projectEngineers as $pe)
                                                <th style="color:#929EAE" class="text-center">{{ $pe->karyawan->name }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded via AJAX -->
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-info">
                                            <th><strong>Total Progress</strong></th>
                                            @foreach($projectEngineers as $pe)
                                                <th class="text-center" id="total-progress-{{ $pe->id }}">0</th>
                                            @endforeach
                                        </tr>
                                        <tr class="table-success">
                                            <th><strong>Total Completed</strong></th>
                                            @foreach($projectEngineers as $pe)
                                                <th class="text-center" id="total-completed-{{ $pe->id }}">0</th>
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

<!-- Filter Modal -->
<div class="modal fade zoomIn" id="advance" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formProjectEngineer" method="get" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">                <div class="modal-header">
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

@endsection

@section('scripts')
<script type="text/javascript">
    // PHP data for JavaScript
    window.peData = {
        ids: @json($projectEngineers->pluck('id')),
        route: @json(route('report.project_engineer')),
        exportRoute: @json(route('report.project_engineer.export'))
    };
</script>

<script type="text/javascript">
     $(document).ready(function () {
        let filterSearch = '';
        var peIds = window.peData.ids;
        
        // Build columns dynamically
        var columns = [
            { data: 'project_name', name: 'project_name' }
        ];
        
        // Add PE columns
        for (var i = 0; i < peIds.length; i++) {
            columns.push({
                data: 'pe_' + peIds[i],
                name: 'pe_' + peIds[i],
                orderable: false,
                searchable: false,
                className: 'text-center'
            });
        }

        window.projectEngineerTable = $('#tableData').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: window.peData.route,
                data: function (d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.status = $('#status').val();
                    d.project_engineer_id = $('#project_engineer_id').val();
                }
            },
            columns: columns,
            pageLength: 25,
            responsive: true,
            drawCallback: function(settings) {
                // Calculate totals
                var api = this.api();
                var data = api.rows().data();
                
                // Reset totals
                for (var i = 0; i < peIds.length; i++) {
                    $('#total-progress-' + peIds[i]).text('0');
                    $('#total-completed-' + peIds[i]).text('0');
                }
                
                // Calculate new totals
                for (var i = 0; i < data.length; i++) {
                    var row = data[i];
                    for (var j = 0; j < peIds.length; j++) {
                        var peId = peIds[j];
                        var cellValue = row['pe_' + peId];
                        
                        if (cellValue && cellValue.includes('●')) {
                            var current = parseInt($('#total-progress-' + peId).text());
                            $('#total-progress-' + peId).text(current + 1);
                        } else if (cellValue && cellValue.includes('✓')) {
                            var current = parseInt($('#total-completed-' + peId).text());
                            $('#total-completed-' + peId).text(current + 1);
                        }
                    }
                }
            }
        });

        // Export button functionality
        $('#export-button').on('click', function() {
            var url = window.peData.exportRoute;
            var params = [];
            
            if ($('#start_date').val()) {
                params.push('start_date=' + encodeURIComponent($('#start_date').val()));
            }
            if ($('#end_date').val()) {
                params.push('end_date=' + encodeURIComponent($('#end_date').val()));
            }
            if ($('#status').val()) {
                params.push('status=' + encodeURIComponent($('#status').val()));
            }
            if ($('#project_engineer_id').val()) {
                params.push('project_engineer_id=' + encodeURIComponent($('#project_engineer_id').val()));
            }
            
            if (params.length > 0) {
                url += '?' + params.join('&');
            }
            
            // Redirect to export URL
            window.location.href = url;
        });
    });

    function applyFilter() {
        window.projectEngineerTable.ajax.reload();
        $('#advance').modal('hide');
    }

    function resetFilter() {
        $('#formProjectEngineer')[0].reset();
        window.projectEngineerTable.ajax.reload();
        $('#advance').modal('hide');
    }
</script>
@endsection