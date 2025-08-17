@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <h4 class="mb-0 ml-2"> &nbsp; Sandblast Report</h4>
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
                            <h4 class="card-title mb-0 flex-grow-1">Work Type Report - Sandblast</h4>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tableData">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="color:#929EAE; text-align: center;">NAMA SUBCONTRACTOR</th>
                                            <th style="color:#929EAE; text-align: center;">NAMA PROJECT</th>
                                            <th style="color:#929EAE; text-align: center;">LUASAN SUBCONT (M)</th>
                                            <th style="color:#929EAE; text-align: center;">BAGIAN KAPAL</th>
                                            <th style="color:#929EAE; text-align: center;">DURASI PROJECT (DAY)</th>
                                            <th style="color:#929EAE; text-align: center;">ON PROGRESS</th>
                                            <th style="color:#929EAE; text-align: center;">COMPLETE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be populated by DataTables -->
                                    </tbody>
                                </table>
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
            <form id="formSandblast" method="get" enctype="multipart/form-data">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="zoomInModalLabel">Filter Sandblast Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div>
                                <label for="subcontractor">Subcontractor</label>
                                <select name="subcontractor" id="subcontractor" class="form-control">
                                    <option value="">All Subcontractors</option>
                                    @foreach($subcontractors as $subcontractor)
                                    <option value="{{ $subcontractor->name }}">{{ $subcontractor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div>
                                <label for="project">Project</label>
                                <select name="project" id="project" class="form-control">
                                    <option value="">All Projects</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->nama_project }}">{{ $project->nama_project }}</option>
                                    @endforeach
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
<script>
$(document).ready(function () {
    let filterSearch = '';
    
    var table = $('#tableData').DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        searching: true,
        autoWidth: true,
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
        ajax: {
            url: '{{ route("report.work_type.sandblast") }}',
            data: function (d) {
                filterSearch = d.search ? d.search.value : '';
                d.subcontractor_filter = $('#subcontractor').val();
                d.project_filter = $('#project').val();
            }
        },
        columns: [
            { data: 'nama_subcontractor', name: 'nama_subcontractor' },
            { data: 'nama_project', name: 'nama_project' },
            { data: 'luasan_subcont_m', name: 'luasan_subcont_m', className: 'text-center' },
            { data: 'bagian_kapal', name: 'bagian_kapal', className: 'text-center' },
            { data: 'durasi_project_day', name: 'durasi_project_day', className: 'text-center' },
            { data: 'durasi_project_on_progress', name: 'durasi_project_on_progress', className: 'text-center' },
            { data: 'status_project', name: 'status_project', className: 'text-center' }
        ]
    });
    
    // Filter functionality
    $('#subcontractor, #project').on('change', function() {
        table.ajax.reload();
    });

    // Export functionality
});

function applyFilter() {
    $('#tableData').DataTable().ajax.reload();
    $('#advance').modal('hide');
}

function resetFilter() {
    $('#subcontractor').val('');
    $('#project').val('');
    $('#tableData').DataTable().ajax.reload();
    $('#advance').modal('hide');
}

// Export Excel functionality
        $('#export-button').on('click', function() {
            var subcontractorFilter = $('#subcontractor_filter').val();
            var projectFilter = $('#project_filter').val();
    
    var exportUrl = '{{ route("report.work_type.sandblast.export") }}';
    var params = new URLSearchParams();
    
    if (subcontractorFilter) {
        params.append('subcontractor_filter', subcontractorFilter);
    }
    if (projectFilter) {
        params.append('project_filter', projectFilter);
    }
    
    if (params.toString()) {
        exportUrl += '?' + params.toString();
    }
    
    // Create a temporary link to trigger download
    var link = document.createElement('a');
    link.href = exportUrl;
    link.download = '';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});
</script>
@endsection