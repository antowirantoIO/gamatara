@extends('index')

@section('content')

<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <h4 class="mb-0 ml-2"> &nbsp; Project Manager Report</h4>
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
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Project Manager Report</h4>
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
                                                @foreach($projectManagers as $pm)
                                                <th style="color:#929EAE" class="text-center">{{ $pm->karyawan->name }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-info">
                                                <th><strong>Total Progress</strong></th>
                                                @foreach($projectManagers as $pm)
                                                <th class="text-center" id="total-progress-{{ $pm->id }}">0</th>
                                                @endforeach
                                            </tr>
                                            <tr class="table-success">
                                                <th><strong>Total Completed</strong></th>
                                                @foreach($projectManagers as $pm)
                                                <th class="text-center" id="total-completed-{{ $pm->id }}">0</th>
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
            <form id="formProjectManager" method="get" enctype="multipart/form-data">
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

<div class="loading-overlay" style="display: none;">
    <div class="loading-content">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Exporting data...</p>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
    // PHP data for JavaScript
    window.pmData = {};
</script>

<script type="text/javascript">
    // PHP data for JavaScript
    window.pmData = {
        ids: @json($projectManagers->pluck('id')),
        route: @json(route('report.project_manager')),
        exportRoute: @json(route('report.project_manager.export'))
    };
</script>

<script type="text/javascript">
     $(document).ready(function () {
        let filterSearch = '';
        var pmIds = window.pmData.ids;
        
        // Build columns dynamically
        var columns = [
            { data: 'project_name', name: 'project_name' }
        ];
        
        // Add PM columns
        for (var i = 0; i < pmIds.length; i++) {
            columns.push({
                data: 'pm_' + pmIds[i],
                name: 'pm_' + pmIds[i],
                orderable: false,
                searchable: false,
                className: 'text-center'
            });
        }

        var table = $('#tableData').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: window.pmData.route,
                data: function (d) {
                    d.project_name = $('#project_name').val();
                    d.status = $('#status').val();
                    d.keyword = filterSearch;
                }
            },
            columns: columns,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            drawCallback: function(settings) {
                updateTotals();
            }
        });

        function updateTotals() {
            var api = table;
            var data = api.rows({ page: 'current' }).data();
            
            // Reset counters
            var progressCounts = {};
            var completedCounts = {};
            
            pmIds.forEach(function(pmId) {
                progressCounts[pmId] = 0;
                completedCounts[pmId] = 0;
            });
            
            // Count symbols in current page
            data.each(function(row) {
                pmIds.forEach(function(pmId) {
                    var cellValue = row['pm_' + pmId];
                    if (cellValue === '●') {
                        progressCounts[pmId]++;
                    } else if (cellValue === '✓') {
                        completedCounts[pmId]++;
                    }
                });
            });
            
            // Update footer
            pmIds.forEach(function(pmId) {
                $('#total-progress-' + pmId).text(progressCounts[pmId]);
                $('#total-completed-' + pmId).text(completedCounts[pmId]);
            });
        }

        $('#tableData_filter input').on('keyup', function() {
            filterSearch = this.value;
            table.ajax.reload();
        });

        $('#export-button').on('click', function() {
            var project_name = $('#project_name').val();
            var status = $('#status').val();

            var url = window.pmData.exportRoute + '?' + $.param({
                project_name    : project_name,
                status          : status,
                keyword         : filterSearch
            });

            window.location.href = url;
        });

        // Make functions available globally
        window.updateTotals = updateTotals;
        window.projectManagerTable = table;

        $(document).ready(function() {
            $('.loading-overlay').hide();
        });
    });

    function applyFilter() {
        window.projectManagerTable.ajax.reload();
        $('#advance').modal('hide');
    }

    function resetFilter() {
        $('#formProjectManager')[0].reset();
        window.projectManagerTable.ajax.reload();
        $('#advance').modal('hide');
    }
</script>
@endsection