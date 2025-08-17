@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <h4 class="mb-0 ml-2"> &nbsp; Annual Area Report</h4>
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
                            <h4 class="card-title mb-0 flex-grow-1">Annual Area Report - Blasting & Painting Category</h4>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tableData">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="color:#929EAE; text-align: center;">No</th>
                                            <th style="color:#929EAE; text-align: center;">NAMA SUBCONTRACTOR</th>
                                            <th style="color:#929EAE; text-align: center;">SUB KATEGORI</th>
                                            <th style="color:#929EAE; text-align: center;">TOTAL PROJECT</th>
                                            <th style="color:#929EAE; text-align: center;">LUASAN (M)</th>
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

<!-- Filter Modal -->
<div class="modal fade" id="advance" tabindex="-1" aria-labelledby="advanceLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="advanceLabel">Filter Annual Area Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="vendor" class="form-label">Vendor</label>
                        <select class="form-select" id="vendor">
                            <option value="">All Vendors</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="year" class="form-label">Year</label>
                        <select class="form-select" id="year">
                            <option value="">All Years</option>
                            @for($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="resetFilter()">Reset</button>
                <button type="button" class="btn btn-primary" onclick="applyFilter()">Apply Filter</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    var table = $('#tableData').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
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
            url: '{{ route("report.summary.annual.area") }}',
            data: function (d) {
                filterSearch = d.search ? d.search.value : '';
                d.vendor_filter = $('#vendor').val();
                d.year_filter = $('#year').val();
            }
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
            {
                data: 'nama_subcontractor',
                name: 'nama_subcontractor',
                className: 'text-left'
            },
            {
                data: 'sub_kategori',
                name: 'sub_kategori',
                className: 'text-center'
            },
            {
                data: 'total_project',
                name: 'total_project',
                className: 'text-center'
            },
            {
                data: 'total_area',
                name: 'total_area',
                className: 'text-right',
                render: function(data, type, row) {
                    return data;
                }
            }
        ]
    });
    
    // Filter functionality
    $('#vendor, #year').on('change', function() {
        table.ajax.reload();
    });
});

function applyFilter() {
    $('#tableData').DataTable().ajax.reload();
    $('#advance').modal('hide');
}

function resetFilter() {
    $('#vendor').val('');
    $('#year').val('');
    $('#tableData').DataTable().ajax.reload();
    $('#advance').modal('hide');
}

// Export Excel functionality
$('#export-button').on('click', function() {
    var vendorFilter = $('#vendor').val();
    var yearFilter = $('#year').val();

    var exportUrl = '{{ route("report.summary.annual.area.export") }}';
    var params = new URLSearchParams();
    
    if (vendorFilter) {
        params.append('vendor_filter', vendorFilter);
    }
    if (yearFilter) {
        params.append('year_filter', yearFilter);
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