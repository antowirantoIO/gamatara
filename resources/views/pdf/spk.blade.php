<html>
    <head>
        <title>Rekapan Surat Perintah Kerja</title>
        <style>
            * {
                font-family: 'Arial', sans-serif;
            }

            .container {
                /* display: flex; */
                align-items: center;
            }

            .table-body {
                border-collapse: collapse;
                width: 100%;
            }

            /* Contoh CSS untuk Tabel Horizontal */
            .table-horizontal {
                border-collapse: collapse;
                width: 100%;
            }

            .table-horizontal th, .table-horizontal td {
                border-top: 1px solid #000000; /* Garis horizontal di atas setiap sel */
                border-bottom: 1px solid #000000; /* Garis horizontal di bawah setiap sel */
                padding: 8px;
                text-align: left;
            }

            .table-horizontal th:first-child, .table-horizontal td:first-child {
                border-left: none; /* Hapus garis vertikal di kiri untuk sel pertama */
            }

            .table-horizontal th:last-child, .table-horizontal td:last-child {
                border-right: none; /* Hapus garis vertikal di kanan untuk sel terakhir */
            }

            .table-horizontal th {
                background-color: #fff; /* Atur warna latar belakang header sesuai kebutuhan */
                border-top: none; /* Hapus garis horizontal di atas header */
            }

            .table-horizontal thead th:first-child {
                border-top: 1px solid #fff; /* Sisakan garis horizontal di atas header pertama */
            }

            .font-size-11{
                font-size: 11px;
            }

            .logo {
                width: 100px;
            }

            .text-container {
                flex-grow: 1;
                text-align: center;
                text-transform: uppercase;
                padding-left: 100px; /* Sesuaikan sesuai kebutuhan */
            }

            .logo-container {
                margin-right: 100px; /* Tambahkan margin kanan sesuai kebutuhan */
            }

            .header {
                font-size: 15px;
                font-weight: bold;
            }

            .project-name {
                font-size: 18px;
                font-weight: bold;
                color: maroon;
            }

            .date-range {
                font-size: 12px;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <table class="container">
            <tr>
                <td class="logo-container">
                    <img src="{{asset('assets/images/logo.png')}}" alt="" class="logo">
                </td>
                <td class="text-container">
                    <div class="header">PT. Gamatara Trans Ocean Shipyard</div>
                    <div class="project-name">Rekap SPK {{$data->nama_project}}</div>
                    <div class="date-range">From {{$data->created_ats}} To {{$data->target_selesais ?? '-'}}</div>
                </td>
            </tr>
        </table>
        <!-- <table class="font-size-11" style="border-collapse: collapse; width: 100%;" cellpadding="2" cellspacing="0">
            <tr>
                <td>Kode Project</td>
                <td class="w-10">:</td>
                <td colspan="2">{{ $data->code }}</td>

                <td class="text-left">Nama Project</td>
                <td class="text-left">:</td>
                <td class="text-left" colspan="2">{{ $data->nama_project }}</td>
            </tr>
            <tr>
                <td>Project Manajer</td>
                <td class="w-10">:</td>
                <td colspan="2">{{ $data->pm->karyawan->name ?? '' }}</td>

                <td class="text-left">Displacement</td>
                <td class="text-left">:</td>
                <td class="text-left" colspan="2">{{ $data->displacement }}</td>
            </tr>
        </table> -->
        <br>
        <table class="table-body font-size-11 table-horizontal">
            <thead>
                <th>Vendor</th>
                <th>No SPK</th>
                <th>SPK Date</th>
                <th>Description</th>
            </thead>
            <tbody>
                @foreach($keluhan as $item)
                <tr>
                    <td>&nbsp;{{ $item->vendors->name }}</td>
                    <td>&nbsp;{{ $item->no_spk }} </td>
                    <td>&nbsp;{{ $item->created_ats }} </td>
                    <td>  
                        &nbsp;{!! nl2br(str_replace('<br>', "\n", $item->keluhan)) !!}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <script type="text/php">
            if(isset($pdf)) {
                $font = $fontMetrics->getFont("Segoe UI, Trebuchet MS, Tahoma, Verdana, sans-serif", "normal");
                $pdf->page_text(520, 800, "<?php echo 'Page {PAGE_NUM} of {PAGE_COUNT}'; ?>", $font, 10, array(0,0,0));
            }
        </script>
    </body>
</html>
