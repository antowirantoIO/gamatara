@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <h4 class="mb-0 ml-2"> &nbsp; SPK Summary Report</h4>
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
                            <h4 class="card-title mb-0 flex-grow-1">SPK Summary Report</h4>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tableData">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="color:#929EAE; text-align: center;">No</th>
                                            <th style="color:#929EAE; text-align: center;">NAMA SUBCONTRACTOR</th>
                                            <th style="color:#929EAE; text-align: center;">SUB KATEGORI</th>
                                            <th style="color:#929EAE; text-align: center;">ON PROGRESS</th>
                                            <th style="color:#929EAE; text-align: center;">COMPLETE</th>

                                            @foreach($projects as $project)
                                                <th style="color:#929EAE; text-align: center;">{{ strtoupper($project->nama_project) }}</th>
                                            @endforeach
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
            <form id="formSPKSummary" method="get" enctype="multipart/form-data">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="zoomInModalLabel">Filter SPK Summary Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div>
                                <label for="vendor">Vendor</label>
                                <select name="vendor" id="vendor" class="form-control">
                                    <option value="">All Vendors</option>
                                    @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->name }}">{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div>
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->name }}">{{ $category->name }}</option>
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
    
    // Get projects data via AJAX to build columns dynamically
    $.get('{{ route("report.summary.spk") }}', { get_projects: true }, function(response) {
        // This will be handled by the controller to return projects data
    });
    
    // For now, we'll build columns based on what we know exists
    // The backend will handle the dynamic column creation
    var columns = [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'nama_subcontractor', name: 'nama_subcontractor' },
        { data: 'sub_kategori', name: 'sub_kategori' },
        { data: 'on_progress', name: 'on_progress', className: 'text-center' },
        { data: 'complete', name: 'complete', className: 'text-center' }
    ];
    
    // Add dynamic project columns - these will be handled by the backend
    @foreach($projects as $project)
    columns.push({
        data: 'project_{{ $project->id }}',
        name: 'project_{{ $project->id }}',
        className: 'text-center',
        orderable: false
    });
    @endforeach
    
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
            url: '{{ route("report.summary.spk") }}',
            data: function (d) {
                filterSearch = d.search ? d.search.value : '';
                d.vendor_filter = $('#vendor').val();
                d.category_filter = $('#category').val();
            }
        },
        columns: columns
    });
    
    // Filter functionality
    $('#vendor, #category').on('change', function() {
        table.ajax.reload();
    });

    // Export functionality
});

function applyFilter() {
    $('#tableData').DataTable().ajax.reload();
    $('#advance').modal('hide');
}

function resetFilter() {
    $('#vendor').val('');
    $('#category').val('');
    $('#tableData').DataTable().ajax.reload();
    $('#advance').modal('hide');
}

// Export Excel functionality
$('#export-button').on('click', function() {
    var vendorFilter = $('#vendor').val();
    var categoryFilter = $('#category').val();

    var exportUrl = '{{ route("report.summary.spk.export") }}';
    var params = new URLSearchParams();
    
    if (vendorFilter) {
        params.append('vendor_filter', vendorFilter);
    }
    if (categoryFilter) {
        params.append('category_filter', categoryFilter);
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