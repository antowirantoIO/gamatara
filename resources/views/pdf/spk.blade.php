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
        <table>
            <tr>
                <!-- <td>
                    <img src="{{asset('assets/images/logo.png')}}" style="width: 100px;" alt="" class="logo">
                </td> -->
                <td style="text-transform: uppercase;padding-left: 200px;font-size: 20px; font-weight: bold;" colspan="8">Rekapan Surat Perintah Kerja</td>
                <br><br>
            </tr>
        </table>
        <table class="font-size-11" style="border-collapse: collapse; width: 100%;" cellpadding="2" cellspacing="0">
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
        </table>
        <br>
        <table class="table-body font-size-11">
            <tbody>
                @foreach($keluhan as $item)
                <tr>
                    <td>Nama Vendor: <br> 
                        <b>{{ $item->vendors->name }} 
                            <br>
                                Request:
                            <br> 
                            {!! nl2br(str_replace('<br>', "\n", $item->keluhan)) !!}
                    </b>
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
