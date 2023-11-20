<html>
    <head>
        <title>Rekapan Surat Perintah Kerja</title>
        <style>
            * {
                font-family: 'Arial', sans-serif;
            }
            .table-header{
                border-collapse: collapse;
                width: 100%;
            }

            .table-header td, .table-header th{
                /* padding: 10px; */
            }

            .table-body {
                border-collapse: collapse;
                width: 100%;
            }

            table {
                border-collapse: collapse;
            }

            table thead th {
                border-bottom: 1px solid black;
            }

            table tbody td {
                border: none;
            }

            .table-bottom {
                border-collapse: collapse;
                width: 100%;
            }

            .table-bottom th {
                background-color: #B2D2E9;
                color: black;
                padding: 5px;
                width: 20%;
                font-size: 12px;
                font-weight: 400
            }

            .table-bottom td {
                font-size: 11px;
                font-weight: 400
            }

            .table-bottom td, .table-bottom th{
                border: 1px solid black;
            }

            .font-size-12{
                font-size: 12px;
            }

            .font-size-11{
                font-size: 11px;
            }

            .font-size-10{
                font-size: 10px;
            }

            .text-blue{
                color: #4F71BE;
            }

            .p-20 {
                padding: 15px;
            }

            .text-center {
                text-align: center;
            }

            .text-right {
                text-align: right;
            }

            .text-left {
                text-align: left;
            }
        </style>
    </head>
    <body>
        <table>
            <tr>
                <td>
                    <img src="{{asset('assets/images/logo.png')}}" style="width: 100px;" alt="" class="logo">
                </td>
                <td style="text-transform: uppercase;padding-left: 100px;">
                    <span style="font-size: 15px; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PT. Gamatara Trans Ocean Shipyard </span><br> 
                    <span style="font-size: 18px; font-weight: bold;color:maroon;">Rekap SPK {{$data->nama_project}}</span><br>
                    <span style="font-size: 12px; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;From {{$data->created_ats}} To {{$data->target_selesai ?? '-'}}</span>
                </td>
                <br><br>
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
        <table class="table-body font-size-11">
            <thead>
                <th>Vendor</th>
                <th>No SPK</th>
                <th>Start Project</th>
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
