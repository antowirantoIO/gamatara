<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table></table>
<table>
    <tr style="font-size: 8px;">
        <td rowspan="4"></td>
        <td align="right" rowspan="4" height="20">

        </td>
        <td colspan="8">PT. GAMATARA TRANS OCEAN SHIPYARD</td>
    </tr>
    <tr style="font-size: 8px;" height="20">
        <td colspan="8">Kantor : Jl. Tanjung Tengah No. 1B</td>
    </tr>
    <tr style="font-size: 8px;" height="20">
        <td colspan="8">Pelabuhan Cirebon 45112 - Jawa Barat</td>
    </tr>
    <tr style="font-size: 8px;" height="20">
        <td colspan="8">Telp. (0231) 226435 Fax. (0231) 226436</td>
    </tr>
    <tr align="center" >
        <td colspan="10" style="font-weight: 600;font-size: 8px;" align="center" height="20">
            <strong>SATISFACTION NOTE</strong>
        </td>
    </tr>
    <tr style="font-size: 12px;font-weight: bold;">
        <td height="30" colspan="10" align="center"> &nbsp; {{ $project->nama_project }}</td>
    </tr>
    <tr align="center" style="font-size: 8px;font-weight: bold;">
        <td height="30">No</td>
        <td height="30" colspan="9">Uraian Pekerjaan</td>
    </tr>
    <tr style="font-size: 8px;">
        <th></th>
        <th height="30">Pekerjaan</th>
        <th height="30">Location / Area</th>
        <th height="30">Detail / Other</th>
        <th height="30">Length (mm)</th>
        <th height="30">Width (mm)</th>
        <th height="30">Thick (mm)</th>
        <th height="30">Qty/Days/%</th>
        <th height="30">Amount</th>
        <th height="30">Unit</th>
    </tr>
    @php
        $letters = range('A', 'Z');
        $count = 0;
        $subCount = 1;
        $prevSub = '';
        $prevKodeUnik = '';
        $prevIndex = '';
        $currentPhoto = '';
        $photoNow = '';
        $currentIndex = '';
        $indexNow = '';
        $totalIndex = '';
        $before = [];
        $after = [];
    @endphp
    @foreach ($data as $index => $item)

        <tr style="font-size: 8px; border:20px medium black;">
            <td style="font-weight: bold; border:20px medium black;" align="center"  height="20">{{ getLatters($index) }}.</td>
            <td colspan="9" style="font-weight: bold; border:20px medium black;"  height="20">&nbsp;{{ $index }}</td>
        </tr>
       @foreach ($item as $indexs => $items)
                <tr style="font-size: 8px;">
                    <td class="text-center" height="20" style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td height="20"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                </tr>
                <tr style="font-size: 8px;">
                    <td class="text-center" height="20" style="border-right: 20px medium black;border-left: 20px medium black;">{{ getLatters($index) }}.{{ $loop->iteration }}.</td>
                    <td height="20">&nbsp;
                        <strong>{{ $indexs }}</strong>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                </tr>
                @foreach ($items as $keys => $value)
                    <tr style="font-size: 8px; border:20px medium black;">
                        <td height="20" style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                        <td height="20"> &nbsp;{{ $value->pekerjaan_concat ?? ' ' }}</td>
                        <td height="20" align="center">{{ $value->id_lokasi }}</td>
                        <td height="20" align="center">{{ $value->detail }}</td>
                        <td height="20" align="center">{{ number_format($value->length,2, ',','') }}</td>
                        <td height="20" align="center">{{ number_format($value->width,2, ',','') }}</td>
                        <td height="20" align="center">{{ number_format($value->thick,2, ',','') }}</td>
                        <td height="20" align="center">{{ number_format($value->qty,2, ',','') }}</td>
                        <td height="20" align="right" style="border-right: 20px medium black;border-left: 20px medium black;">{{  number_format($value->amount,2, ',','') }}</td>
                        <td height="20" align="right" style="border-right: 20px medium black;border-left: 20px medium black;">{{ $value->unit }}</td>
                        <td height="20" align="left">{{ $value->nama_vendor ?? '-' }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td colspan="9" height="30" align="center" style="border: 20px medium black;border-left: 20px medium black;">
                        <strong>Before</strong>
                    </td>
                </tr>
                @php
                    $beforePhoto = getBeforePhotoSM($value->id_Project, $value->subkategori_concat);
                    $afterPhoto = getAfterPhotoSM($value->id_Project, $value->subkategori_concat);
                @endphp
                <tr>
                    <td height="100" style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    @foreach ($beforePhoto as $photo => $b)
                        @php
                            $indexNow = $photo + 1;
                            $photoNow = $b ? $b->photo : null

                        @endphp
                        @isset ($b->photo)
                            @if ($photoNow !== $currentPhoto)
                                <td height="100">
                                    <img src="{{ public_path($b->photo)  }}" width="100px">
                                </td>
                            @endif
                        @endisset
                        @php
                            $currentPhoto = $photoNow;
                            $currentIndex = $indexNow;
                            $totalIndex = max(7, 7 - $currentIndex);
                        @endphp
                    @endforeach
                    @if ($currentIndex !== 7)
                        @if (intval($totalIndex) === 0)
                        {!! str_repeat('<td></td>', 7) !!}
                        @else
                        {!! str_repeat('<td></td>', intval($totalIndex)) !!}
                        @endif
                    @endif
                    <td height="100" style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td height="100" style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                </tr>
                <tr>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td colspan="9" height="30" align="center" style="border: 20px medium black;border-left: 20px medium black;">
                        <strong>After</strong>
                    </td>
                </tr>
                <tr>
                    <td height="100" style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    @foreach ($afterPhoto as $photo => $a)
                        @php
                            $indexNow = $photo + 1;
                            $photoNow = $a ? $a->photo : null

                        @endphp
                        @isset ($b->photo)
                            @if ($photoNow !== $currentPhoto)
                                <td height="100">
                                    <img src="{{ public_path($a->photo)  }}" width="100px" alt="photo">
                                </td>
                            @endif
                        @endisset
                        @php
                            $currentPhoto = $photoNow;
                            $currentIndex = $indexNow;
                            $totalIndex = max(7, 7 - $currentIndex);
                        @endphp
                    @endforeach
                    @if ($currentIndex !== 7)
                        @if (intval($totalIndex) === 0)
                        {!! str_repeat('<td></td>', 7) !!}
                        @else
                        {!! str_repeat('<td></td>', intval($totalIndex)) !!}
                        @endif
                    @endif
                    <td height="100" style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td height="100" style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                </tr>

                @if($items->count() > 0)
                    <tr style="font-size: 8px;">
                        <td class="text-center" height="20" style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                        <td height="20"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                        <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    </tr>
                @endif
                @php
                    $count++
                @endphp
       @endforeach

    @endforeach
    {!! str_repeat('<tr></tr>', 1) !!}
    <tr align="center" style="font-size: 8px;">
        <td colspan="11" align="center">Cirebon, {{ formatTanggal() }}</td>
    </tr>
    {!! str_repeat('<tr></tr>', 1) !!}
    <tr>
        <td colspan="9" align="right" style="font-size: 8px;">HORMAT KAMI</td>
    </tr>
    {!! str_repeat('<tr></tr>', 6) !!}
    <tr>
        <td style="font-size: 8px;" colspan="5" align="center"><span class="text-decoration-underline"><strong style="text-decoration: underline;">WIDJI LAKSANA</strong></span></td>
        <td style="font-size: 8px;" colspan="5" align="center"><span class="text-decoration-underline"><strong>{{ $project->pm->karyawan->name }}</strong></span></td>
    </tr>
    <tr align="center">
        <td style="font-size: 8px;" colspan="5" align="center"><span class="">DIREKTUR TEKNIK</span></td>
        <td style="font-size: 8px;" colspan="5" align="center"><span class="">MANAGER PROJECT</span></td>
    </tr>
    <tr>
        <td style="font-size: 8px;" colspan="5" align="center"><span class="">PT. GAMATARA TRANS OCEAN SHIPYARD</span></td>
        <td style="font-size: 8px;" colspan="5" align="center">PT. GAMATARA TRANS OCEAN SHIPYARD</td>
    </tr>
    {!! str_repeat('<tr></tr>', 6) !!}
    <tr>
        <td style="font-size: 8px;" colspan="5" align="center"><span class="text-decoration-underline"><strong>HANAFI SANTOSO, ST</strong></span></td>
        <td style="font-size: 8px;" colspan="5" align="center"><span class="text-decoration-underline"><strong>SUGIARTO SANTOSO, S.KOM</strong></span></td>
    </tr>
    <tr align="center">
        <td style="font-size: 8px;" colspan="5" align="center"><span class="">DIREKTUR FINANCE &amp; MARKETING</span></td>
        <td style="font-size: 8px;" colspan="5" align="center"><span class="">DIREKTUR PROJECT</span></td>
    </tr>
    <tr>
        <td style="font-size: 8px;" colspan="5" align="center"><span class="">PT. GAMATARA TRANS OCEAN SHIPYARD</span></td>
        <td style="font-size: 8px;" colspan="5" align="center"><span class="">PT. GAMATARA TRANS OCEAN SHIPYARD</span></td>
    </tr>
    {!! str_repeat('<tr></tr>', 2) !!}
    <tr align="center" style="font-size: 8px;">
        <td colspan="11" align="center"><span class="">MENGETAHUI,</span></td>
    </tr>
    {!! str_repeat('<tr></tr>', 6) !!}
    <tr align="center" style="font-size: 8px;font-weight: bold;text-decoration: underline;">
        <td colspan="11" align="center" style="font-size: 8px;font-weight: bold;">OWNER SURVEYOR</td>
    </tr>
    <tr align="center" style="font-size: 8px;font-weight: bold;">
        <td colspan="11" align="center" style="font-size: 8px;font-weight: bold;">{{ $project->customer->name }}</td>
    </tr>
</table>

</body>
</html>
