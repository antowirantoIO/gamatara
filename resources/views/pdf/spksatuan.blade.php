<html>
    <head>
        <title>Surat Perintah Kerja</title>
        <style>
            * {
                font-family: 'Arial', sans-serif;
            }
            .table-header{
                border-collapse: collapse;
                width: 100%;
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
        <table width="100%" style="margin-bottom: 10px;">
            <tr>
                <td>
                    <img src="{{asset('assets/images/logo.png')}}" style="width: 100px;" alt="" class="logo">
                </td>
                <td style="text-transform: uppercase;padding-left: 155px;font-size: 20px; font-weight: bold;" colspan="8">Surat Perintah Kerja <br> No: {{ $data->po_no }}
                </td>
            </tr>
        </table>
        <table class="font-size-11" style="border: 1px solid black;" width="100%">
            <tr>
                <td>
                    <table style="border-collapse: collapse; width: 90%;" border="0">
                        <tbody>
                            <tr style="width:50%">
                                <td style="width:30%;border-style: none;">Nama Project</td>
                                <td style="width:30%;border-style: none;">:</td>
                                <td style="width:30%;border-style: none;">{{ $data->nama_project }}</td>
                                <td style="width:30%;border-style: none;">Tanggal SPK</td>
                                <td style="width:30%;border-style: none;">:</td>
                                <td style="width:30%;border-style: none;">{{ $data->created_at }}</td>
                            </tr>
                            <tr>
                                <td style="width:30%;border-style: none;">Project Manager</td>
                                <td style="width:30%;border-style: none;">:</td>
                                <td style="width:30%;border-style: none;">{{ $data->pm->karyawan->name ?? ''}}</td>
                                <td style="width:30%;border-style: none;">Penerima SPK/ Subkon</td>
                                <td style="width:30%;border-style: none;">:</td>
                                <td style="width:30%;border-style: none;">{{ $keluhan->vendors->name ?? '' }}</td>
                                
                               
                            </tr>
                            <tr >
                                <td style="width:30%;border-style: none;">PEngineer - PAdmin</td>
                                <td style="width:30%;border-style: none;">:</td>
                                <td style="width:30%;border-style: none;">{{ $data->pe->karyawan->name ??'' }} - {{ $data->pa->karyawan->name ?? '' }}</td>
                                <td style="width:30%;border-style: none;"></td>
                                <td style="width:30%;border-style: none;"></td>
                                <td style="width:30%;border-style: none;"></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <br><br>
            <tr>
                <td>
                    <table class="font-size-10 table-body">
                        <thead>
                            <tr>
                                <th>Uraian Detail Pekerjaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{!! nl2br(str_replace('<br>', "\n", $keluhan->keluhan)) !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <br><br><br><br><br><br>
            <tr>
                <td>
                    <table class="table-body text-center">
                        <tbody>
                            <tr>
                                <td colspan="9">Pemberi SPK</td>
                                <td colspan="5">Penerima SPK</td>
                            </tr>
                            <tr>
                                <td colspan="3">Project Admin</td>
                                <td colspan="3">Project Manager</td>
                                <td colspan="3">Direktur</td>
                                <td colspan="5" rowspan="2"><img src="data:image/png;base64,{{ $data->ttdVendor }}" alt="" width="50px"><br></td>
                            </tr>
                            <tr>
                                <td colspan="3"><img src="data:image/png;base64,{{ $data->ttdPA }}" alt="" width="50px"><br></td>
                                <td colspan="3"><img src="data:image/png;base64,{{ $data->ttdPM }}" alt="" width="50px"><br></td>
                                <td colspan="3"><img src="data:image/png;base64,{{ $data->ttdBOD }}" alt="" width="50px"><br></td>
                            </tr>
                            <tr>
                                <td colspan="3">{{ $data->approvalPA}}</td>
                                <td colspan="3">{{ $data->approvalPM}}</td>
                                <td colspan="3">{{ $data->approvalBOD}}</td>
                                <td colspan="5">{{ $keluhan->vendors->name ?? '' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        
        <script type="text/php">
            if(isset($pdf)) {
                $font = $fontMetrics->getFont("Segoe UI, Trebuchet MS, Tahoma, Verdana, sans-serif", "normal");
                $pdf->page_text(520, 800, "<?php echo 'Page {PAGE_NUM} of {PAGE_COUNT}'; ?>", $font, 10, array(0,0,0));
            }
        </script>
    </body>
</html>
