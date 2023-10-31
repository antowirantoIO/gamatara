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

            .table-body th{
                /* background-color: #4F71BE; */
                /* color: white; */
                font-size: 12px
            }

            .table-body td, .table-body th{
                border: 1px solid black;
                padding: 5px;
                font-size: 11px
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
        <table width="100%" style="margin-bottom: 10px;" >
            <tr>
            <th colspan="1">
                <!-- <img src="{{asset('assets/images/logo.png')}}" style="width: 100px;" alt="" class="logo"> -->
            </th>
            <th colspan="5" style="font-size: 16px;">Surat Perintah Kerja <br> No: SPK/GTS/2023-09</th>
                  
            </tr>
        </table>
        <table width="100%" class="font-size-11" style="border: 1px solid black;">
            <tr>
                <td width="100px"></td>
                <td width="10px"></td>
                <td width="300px"></td>
                <td width="100px">PO Number</td>
                <td width="10px"> : </td>
                <td>{{ $data->created_at }}</td>
            </tr>
            <tr>
                <td width="150px">Nama Proyek</td>
                <td width="10px"> : </td>
                <td width="300px">{{ $data->nama_project }}</td>
                <td width="100px">Tanggal SPK</td>
                <td width="10px"> : </td>
                <td>{{ $data->created_at }}</td>
            </tr>

            <tr>
                <td width="150px">Sales Order</td>
                <td width="10px"> : </td>
                <td width="300px"></td>
                <td width="100px">Project Manager</td>
                <td width="10px"> : </td>
                <td>{{ $data->pm->karyawan->name ?? ''}}</td>
            </tr>

            <tr>
                <td width="150px">Penerima SPK/ Subkon</td>
                <td width="10px">:</td>
                <td width="300px">{{ $keluhan->vendors->name ?? '' }}</td>
                <td width="100px">PEngineer - PAdmin</td>
                <td width="10px"> : </td>
                <td>{{ $data->pe_name }} - {{ $data->pa_name }}</td>
            </tr>
            <br><br>
        </table>
        <table width="90%" class="font-size-11 table-body">
            <thead>
                <tr>
                    <th>Uraian Detail Pekerjaan</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Harga/Satuan</th>
                    <th>East</th>
                    <th>Project</th>
                    <th>Estimasi Hari</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{!! nl2br(str_replace('<br>', "\n", $keluhan->keluhan)) !!}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ $data->nama_project }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <table width="100%" border="1" cellpadding="2" cellspacing="0" style="margin-top: 10px;">
            <thead>
                <tr>
                    <th colspan="2">Catatan</th>
                    <th colspan="2">Project Admin</th>
                    <th colspan="2">Project Manager</th>
                    <th colspan="2">Direktur</th>
                    <th colspan="2">Penerima SPK</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2" class="text-center"><br> </td>
                    <td colspan="2" class="text-center"><img src="data:image/png;base64,{{ $data->ttdPM }}" alt="" width="50px"> <br></td>
                    <td colspan="2" class="text-center"><img src="data:image/png;base64,{{ $data->ttdBOD }}" alt="" width="50px"> <br></td>
                    <td colspan="2" class="text-center"><br> </td>
                </tr>

                <tr>
                    <td colspan="2"></td>
                    <td colspan="2"></td>
                    <td colspan="2"><center>{{ $data->approvalPM}}</center></td>
                    <td colspan="2"><center>{{ $data->approvalBOD}}</center></td>
                    <td colspan="2"></td>
                </tr>

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
