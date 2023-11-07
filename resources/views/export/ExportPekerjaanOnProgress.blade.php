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
        <td height="30" colspan="3">Tanggal 03 Juli 2023</td>
        <td>Project</td>
        <td height="30" colspan="6"> &nbsp;TK. PULAU TIGA 3017 - 2023</td>
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
        $prevSub = '';
    @endphp
    @foreach ($data as $index => $item)
        <tr style="font-size: 8px; border:20px medium black;">
            <td style="font-weight: bold; border:20px medium black;" align="center"  height="20">{{ $letters[$count] }}.</td>
            <td colspan="9" style="font-weight: bold; border:20px medium black;"  height="20">&nbsp;{{ $index }}</td>
        </tr>
        @foreach ($item as $indexs => $value)
            @php
                $subkategori = $value->subKategori->name;
            @endphp
            @if ($letters[$count] !== 'A')
                @if ($subkategori !== $prevSub)
                    <tr style="font-size: 8px;">
                        <td class="text-center" height="20" style="border-right: 20px medium black;border-left: 20px medium black;">{{ $letters[$count] }}.{{ $indexs + 1 }}.</td>
                        <td height="20">&nbsp;
                            @if ($subkategori === 'Telah dilaksanakan pekerjaan')
                                {{ $value->subKategori->name }} {{ $value->deskripsi_subkategori }}
                            @else
                                {{ $value->subKategori->name }}
                            @endif
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
                @endif
                <tr style="font-size: 8px; border:20px medium black;">
                    <td height="20" style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td height="20"> &nbsp;{{ $value->pekerjaan->name }}</td>
                    <td height="20" align="center">{{ $value->id_lokasi }}</td>
                    <td height="20" align="center">{{ $value->detail }}</td>
                    <td height="20" align="center">{{ $value->length }}</td>
                    <td height="20" align="center">{{ $value->width }}</td>
                    <td height="20" align="center">{{ $value->thick }}</td>
                    <td height="20" align="center">{{ $value->qty }}</td>
                    <td height="20" align="center" style="border-right: 20px medium black;border-left: 20px medium black;">{{ $value->amount }}</td>
                    <td height="20" align="center" style="border-right: 20px medium black;border-left: 20px medium black;">{{ $value->unit }}</td>
                </tr>
            @endif
            @php
                $prevSub = $subkategori;
            @endphp
        @endforeach
        @php
            $count++
        @endphp
    @endforeach
    {!! str_repeat('<tr></tr>', 1) !!}
    <tr align="center" style="font-size: 8px;">
        <td colspan="11" align="center">Cirebon, {{ formatTanggal() }}</td>
    </tr>
    {!! str_repeat('<tr></tr>', 1) !!}
    <tr>
        <td colspan="9" align="right" style="font-size: 8px;">HORMAT KAMI</td>
        <td colspan="2"></td>
    </tr>
    {!! str_repeat('<tr></tr>', 6) !!}
    <tr>
        <td style="font-size: 8px;" colspan="5" align="center"><span class="text-decoration-underline"><strong style="text-decoration: underline;">WIDJI LAKSANA</strong></span></td>
        <td style="font-size: 8px;" colspan="5" align="center"><span class="text-decoration-underline"><strong>{{ $project->pm->karyawan->name }}</strong></span></td>
    </tr>
    <tr align="center">
        <td style="font-size: 8px;" colspan="5" align="center"><span class="">DIREKTUR</span></td>
        <td style="font-size: 8px;" colspan="5" align="center"><span class="">KEPALA PROYEK</span></td>
    </tr>
    <tr>
        <td style="font-size: 8px;" colspan="5" align="center"><span class="">PT. GAMATARA TRANS OCEAN SHIPYARD</span></td>
        <td></td>
        <td colspan="5"></td>
    </tr>
    {!! str_repeat('<tr></tr>', 6) !!}
    <tr>
        <td style="font-size: 8px;" colspan="5" align="center"><span class="text-decoration-underline"><strong>HANAFI SANTOSO, ST</strong></span></td>
        <td style="font-size: 8px;" colspan="5" align="center"><span class="text-decoration-underline"><strong>SUGIARTO SANTOSO, S.KOM</strong></span></td>
    </tr>
    <tr align="center">
        <td style="font-size: 8px;" colspan="5" align="center"><span class="">DIREKTUR</span></td>
        <td style="font-size: 8px;" colspan="5" align="center"><span class="">DIREKTUR</span></td>
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
