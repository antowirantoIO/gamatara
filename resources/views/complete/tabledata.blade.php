@if($pmAuth == 'Project Admin' || $pmAuth == 'BOD')
<div class="flex-grow-1 d-flex align-items-center justify-content-end">
    <button type="button" id="printSPK" data-id-keluhan="" class="btn btn-danger" onclick="openNewTab()">
        <span>
            <i><img src="{{asset('assets/images/directbox.svg')}}" style="width: 15px;"></i>
        </span>
        Rekap SPK
    </button>
</div>
@endif

<table id="tabelKeluhan" class="table table-bordered">
    <thead style="background-color:#194BFB;color:#FFFFFF">
        <tr>
            <th>No.</th>
            <th>Request</th>
            <th>Vendor</th>
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
