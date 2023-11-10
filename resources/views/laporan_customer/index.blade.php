@extends('index')

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-center flex-lg-row flex-column">
                        <div class="flex-grow-1 d-flex align-items-center">
                            <h4 class="mb-0 ml-2"> &nbsp; Report Customer</h4>
                        </div>
                        <input type="hidden" id="tot" value="{{$totalHargaData}}">
                        <div class="mt-3 mt-lg-0 ml-lg-auto">
                            <!-- <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#advance">
                                <span>
                                    <i><img src="{{asset('assets/images/filter.svg')}}" style="width: 15px;"></i>
                                </span> &nbsp; Filter
                            </button> -->
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
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Customer</h4>
                            <div>
                          
                            </div>
                        </div>

                        <div class="card-body" style="height: 670px;">
                            <table class="table" id="tableData">
                                <thead class="table-light">
                                    <tr>
                                        <th style="color:#929EAE">Customer Name</th>
                                        <th style="color:#929EAE">Project Total</th>
                                        <th style="color:#929EAE">Project Value</th>
                                        <th style="color:#929EAE">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">
                                <span style="width: 15px;height: 15px;background-color:#194BFB; display: inline-block;"></span>
                                &nbsp; Nominal Project
                            </h4>
                            <div class="mt-3 mt-lg-0 ml-lg-auto">
                                <div class="dropdown" role="group">
                                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="yearDropdownButton">
                                        {{ $tahun }}
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" id="yearDropdown">
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="card-body" style="height: 660px;">
                            <div id="bar" data-colors='["--vz-success"]' class="apex-charts" dir="ltr"></div>
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
            <form  id="formOnRequest" method="get" enctype="multipart/form-data">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="zoomInModalLabel">Filter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row gy-4">
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="customer" class="form-label">Nama Customer</label>
                                <input type="text" name="nama_customer" class="form-control" id="nama_customer">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="nilai_project" class="form-label">Nilai Project</label>
                                <input type="text" name="nilai_project" id="nilai_project" class="form-control">
                            </div>
                        </div>
                        <div class="col-xxl-6 col-md-6">
                            <div>
                                <label for="jumlah_project" class="form-label">Jumlah Project</label>
                                <input type="text" name="jumlah_project" id="jumlah_project" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end modal-->
@endsection

@section('scripts')
<script>
    //datatable
     $(document).ready(function () {
        let filterSearch = '';
        var table = $('#tableData').DataTable({
            fixedHeader:true,
            lengthChange: false,
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
            },
            ajax: {
                url: "{{ route('laporan_customer') }}",
                data: function (d) {
                    filterSearch        = d.search?.value;
                    d.name              = $('#name').val();
                    d.jumlah_project    = $('#jumlah_project').val();
                    d.nilai_project     = $('#nilai_project').val();
                    d.year              = '';
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'jumlah_project', name: 'jumlah_project'},
                {data: 'nilai_project', name: 'nilai_project'},
                {data: 'action', name: 'action'}
            ]
        });

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

            var name            = $('#name').val();
            var jumlah_project  = $('#jumlah_project').val();
            var nilai_project   = $('#nilai_project').val();
          

            var url = '{{ route("laporan_customer.export") }}?' + $.param({
                name            : name,
                nilai_project   : nilai_project,
                jumlah_project  : jumlah_project,
                keyword         : filterSearch
            });

            $('.loading-overlay').show();

            window.location.href = url;

            setTimeout(hideOverlay, 2000);
        });

        $(document).ready(function() {
            $('.loading-overlay').hide();
        });
    });

    $(function() {
        const yearDropdown = $('#yearDropdown');
        const yearDropdownButton = $('#yearDropdownButton');

        let chartData = JSON.parse('{{$totalHargaData}}');
        var options = {
        chart: {
            type: 'bar',
            height: 600,
        },
        plotOptions: {
            bar: {
            horizontal: true,
            borderRadius: 5,
            },
        },
        dataLabels: {
            enabled: false,
        },
        series: [
            {
                name: 'Nominal Project',
                data: chartData
            }
        ],
        xaxis: {
                labels:{
                    show:false,
                },
                categories: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            },
            colors: ['#194BFB'],
        };

        let chart = new ApexCharts(document.querySelector("#bar"), options);
        chart.render();

        const currentYear = (new Date()).getFullYear();
        const years = [];
        for (let i = currentYear; i >= currentYear - 20; i--) {
            years.push(i);
        }

        let dropdownList = [];
        years.forEach(function (year) {
            dropdownList += `<li class="dropdown-item" data-year="${year}">
                                ${year}
                            </li>`
            // const listItem = document.createElement('li');
            // const anchor = document.createElement('a');
            // anchor.classList.add('dropdown-item');
            // anchor.href = '#';
            // anchor.textContent = year;
        
            // anchor.addEventListener('click', function () {
            //     yearDropdownButton.textContent = year;
            //     $.ajax({
            //         url: '{{ route('laporan_customer.chart') }}',
            //         method: 'get',
            //         data: {
            //             year: year
            //         },
            //         success: function(response) {
            //             console.log(response.totalHargaData);
            //             const chartData = JSON.parse(response.totalHargaData);
                        
            //             if (chart) {
            //                 chart.updateOptions({
            //                     series: [{
            //                         data: chartData
            //                     }]
            //                 });
            //             } else {
            //                 const options = {
            //                     chart: {
            //                         type: 'bar',
            //                         height: 600,
            //                     },
            //                     plotOptions: {
            //                         bar: {
            //                             horizontal: true,
            //                             borderRadius: 5,
            //                         },
            //                     },
            //                     dataLabels: {
            //                         enabled: false,
            //                     },
            //                     series: [{
            //                         name: 'Nominal Project',
            //                         data: chartData
            //                     }],
            //                     xaxis: {
            //                         labels: {
            //                             show: false,
            //                         },
            //                         categories: [
            //                             'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            //                             'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            //                         ],
            //                     },
            //                     colors: ['#194BFB'],
            //                 };
                            
            //                 chart = new ApexCharts(document.querySelector("#bar"), options);
            //                 chart.render();
            //             }
            //         },
            //         error: function(error) {
            //             console.error(error);
            //         }
            //     });
            // });
        
            // listItem.appendChild(anchor);
        });
        yearDropdown.html(dropdownList);

        yearDropdown.on('click', '.dropdown-item', function() {
            const year = $(this).data('year');
            yearDropdownButton.text(year);
            $.ajax({
                url: '{{ route('laporan_customer.chart') }}',
                method: 'get',
                data: {
                    year: year
                },
                success: function(response) {
                    chartData = JSON.parse(response.totalHargaData);
                    chart.updateSeries([{
                        data: chartData
                    }]);
                    chart.update();
                },
                error: function(error) {
                    console.error(error);
                }
            });
        })
    })

 
//     document.addEventListener('DOMContentLoaded', function () {
//         const yearDropdown = document.getElementById('yearDropdown');
//         const yearDropdownButton = document.getElementById('yearDropdownButton');
        
//         let chart; // Inisialisasi objek grafik di sini

//         const years = [2021, 2022, 2023, 2024, 2025];

    
//         years.forEach(function (year) {
//             const listItem = document.createElement('li');
//             const anchor = document.createElement('a');
//             anchor.classList.add('dropdown-item');
//             anchor.href = '#';
//             anchor.textContent = year;
        
//             anchor.addEventListener('click', function () {
//                 yearDropdownButton.textContent = year;
//                 $.ajax({
//                     url: '{{ route('laporan_customer.chart') }}',
//                     method: 'get',
//                     data: {
//                         year: year
//                     },
//                     success: function(response) {
//                         console.log(response.totalHargaData);
//                         const chartData = JSON.parse(response.totalHargaData); // Konversi ke array
//                         $('#tot').val(response.totalHargaData);
                
//                     },
//                     error: function(error) {
//                         console.error(error);
//                     }
//                 });
//             });
        
//             listItem.appendChild(anchor);
//             yearDropdown.appendChild(listItem);
//     });
// });

</script>
@endsection
